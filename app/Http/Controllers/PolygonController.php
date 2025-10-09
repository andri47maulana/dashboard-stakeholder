<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolygonController extends Controller
{
    public function index()
    {
        $kebunJsons = DB::table('kebun_json as kj')
            ->leftJoin('tb_unit as u', 'u.id', '=', 'kj.unit_id')
            ->select('kj.*', 'u.unit as nm_unit', 'u.region as nm_region')
            ->get()
            ->map(function ($item) {
                $item->decoded = json_decode($item->json, true);
                return $item;
            });
        return view('polygon.index',compact('kebunJsons'));
    }

//     public function checkPoint(Request $request)
// {
//     try {
//         // Debug input dulu
//         \Log::info('CheckPoint input:', $request->all());

//         $lat = (float) $request->input('lat');
//         $lng = (float) $request->input('lng');

//         // Kalau kosong langsung balikin error
//         if (!$lat || !$lng) {
//             return response()->json([
//                 'error' => 'Latitude dan Longitude wajib diisi',
//                 'data' => $request->all()
//             ], 422);
//         }

//         $kebunJsons = DB::table('kebun_json')->get()->map(function ($item) {
//             $item->decoded = json_decode($item->json, true);
//             return $item;
//         });

//         $inside = null;
//         $nearest = null;
//         $nearestDist = PHP_INT_MAX;

//         foreach ($kebunJsons as $kebun) {
//             if (!$kebun->decoded) {
//                 continue;
//             }

//             $bounds = $kebun->decoded['bounds'] ?? null;
//             $center = $kebun->decoded['center'] ?? null;

//             // --- cek bounding box
//             if ($bounds && count($bounds) === 4) {
//                 $minLng = $bounds[0];
//                 $minLat = $bounds[1];
//                 $maxLng = $bounds[2];
//                 $maxLat = $bounds[3];

//                 if ($lat >= $minLat && $lat <= $maxLat && $lng >= $minLng && $lng <= $maxLng) {
//                     $inside = $kebun;
//                     break;
//                 }
//             }

//             // --- hitung jarak ke center
//             if ($center && count($center) === 2) {
//                 $dist = sqrt(pow($lat - $center[1], 2) + pow($lng - $center[0], 2));
//                 if ($dist < $nearestDist) {
//                     $nearestDist = $dist;
//                     $nearest = $kebun;
//                 }
//             }
//         }

//         return response()->json([
//             'inside' => $inside,
//             'nearest' => $nearest
//         ]);

//     } catch (\Throwable $e) {
//         \Log::error('CheckPoint error: '.$e->getMessage(), [
//             'trace' => $e->getTraceAsString()
//         ]);
//         return response()->json([
//             'error' => 'Internal Server Error',
//             'message' => $e->getMessage()
//         ], 500);
//     }
// }
public function checkPoint(Request $request)
{
        try {
            // Validasi input (0 tetap dianggap valid)
            $validated = $request->validate([
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
                'radius_km' => 'nullable|numeric|min:0'
            ]);

            $lat = (float) $validated['lat'];
            $lng = (float) $validated['lng'];
            $radiusKm = isset($validated['radius_km']) ? (float)$validated['radius_km'] : null;

            // Ambil data kebun dengan unit & region
            $kebunJsons = DB::table('kebun_json')
                ->leftJoin('tb_unit','tb_unit.id','=','kebun_json.unit_id')
                ->select('kebun_json.*','tb_unit.unit','tb_unit.region')
                ->get()
                ->map(function ($item) {
                    $item->decoded = json_decode($item->json, true);
                    return $item;
                });

            $inside = null;              // kebun yang benar-benar berisi titik (point-in-polygon)
            $nearest = null;             // kebun terdekat (berdasar center)
            $nearestDist = PHP_FLOAT_MAX;
            $withinRadius = [];          // daftar kebun dalam radius (jika radius_km dikirim)

            foreach ($kebunJsons as $kebun) {
                if (!$kebun->decoded) continue;

                $decoded  = $kebun->decoded;
                $bounds   = $decoded['bounds']   ?? null; // [minLng,minLat,maxLng,maxLat]
                $center   = $decoded['center']   ?? null; // [lng,lat]
                $geometry = $decoded['geometry'] ?? null; // GeoJSON geometry

                // Normalisasi geometry bila diperlukan (swap lat/lng jika terdeteksi kebalik)
                if ($geometry) {
                    $geometry = $this->normalizeGeometry($geometry);
                }

                // 1. Cek bounding box cepat
                $maybeIn = false;
                if ($bounds && count($bounds) === 4) {
                    [$minLng,$minLat,$maxLng,$maxLat] = $bounds;
                    if ($lat >= $minLat && $lat <= $maxLat && $lng >= $minLng && $lng <= $maxLng) {
                        $maybeIn = true;
                    }
                }

                // 2. Jika lolos bounding box dan ada geometry, lakukan point-in-polygon
                if ($maybeIn && $geometry && !$inside) { // hanya set pertama kali
                    if ($this->pointInGeometry($lat, $lng, $geometry)) {
                        $inside = $this->formatKebun($kebun);
                        $inside['method'] = 'geometry';
                    } else {
                        // Logging bantuan untuk debug
                        \Log::debug('Point failed geometry test despite bbox', [
                            'kebun_id' => $kebun->id ?? null,
                            'lat' => $lat,
                            'lng' => $lng,
                            'sample_coord' => $geometry['coordinates'][0][0][0] ?? null
                        ]);
                    }
                }

                // 2b. Fallback: jika tidak ada geometry tapi bounding box match, anggap approximate
                if ($maybeIn && !$geometry && !$inside) {
                    $inside = $this->formatKebun($kebun);
                    $inside['method'] = 'bbox-only';
                    $inside['approximate'] = true;
                }

                // 3. Hitung jarak ke center untuk nearest & within radius
                if ($center && count($center) === 2) {
                    $dist = $this->haversine($lat, $lng, $center[1], $center[0]); // km
                    if ($dist < $nearestDist) {
                        $nearestDist = $dist;
                        $nearest = $this->formatKebun($kebun);
                        $nearest['distance_km'] = round($nearestDist, 3);
                    }
                    if ($radiusKm !== null && $dist <= $radiusKm) {
                        $row = $this->formatKebun($kebun);
                        $row['distance_km'] = round($dist, 3);
                        $withinRadius[] = $row;
                    }
                }
            }

            // Urutkan dalam radius berdasarkan jarak
            if ($radiusKm !== null && $withinRadius) {
                usort($withinRadius, fn($a,$b) => $a['distance_km'] <=> $b['distance_km']);
            }

            return response()->json([
                'query_point' => ['lat'=>$lat,'lng'=>$lng],
                'search_radius_km' => $radiusKm,
                'inside' => $inside,
                'nearest' => $nearest,
                'within_radius' => $radiusKm !== null ? $withinRadius : null,
                'circle' => $radiusKm !== null ? [
                    'center' => ['lng'=>$lng,'lat'=>$lat],
                    'radius_km' => $radiusKm
                ] : null
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'error' => 'VALIDATION_ERROR',
                'messages' => $ve->errors()
            ], 422);
        } catch (\Throwable $e) {
            \Log::error('CheckPoint error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'INTERNAL_ERROR',
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
}

private function haversine($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371; // km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);

    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $earthRadius * $c;
}

    /**
     * Cek apakah titik berada di dalam geometry (Polygon / MultiPolygon GeoJSON).
     */
    private function pointInGeometry(float $lat, float $lng, array $geometry): bool
    {
        $type = $geometry['type'] ?? null;
        $coords = $geometry['coordinates'] ?? null;
        if (!$type || !$coords) return false;

        if ($type === 'Polygon') {
            return $this->pointInPolygonRings($lat, $lng, $coords);
        }
        if ($type === 'MultiPolygon') {
            foreach ($coords as $poly) {
                if ($this->pointInPolygonRings($lat, $lng, $poly)) return true;
            }
            return false;
        }
        return false;
    }

    /**
     * GeoJSON Polygon: array of linear rings (outer + holes)
     */
    private function pointInPolygonRings(float $lat, float $lng, array $rings): bool
    {
        if (empty($rings)) return false;
        $outerInside = $this->pointInSingleRing($lat, $lng, $rings[0]);
        if (!$outerInside) return false;
        // Jika masuk hole -> di luar
        $count = count($rings);
        for ($i = 1; $i < $count; $i++) {
            if ($this->pointInSingleRing($lat, $lng, $rings[$i])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Ray casting di satu ring (array koordinat [lng,lat])
     */
    private function pointInSingleRing(float $lat, float $lng, array $ring): bool
    {
        $inside = false;
        $n = count($ring);
        if ($n < 3) return false; // bukan polygon
        for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
            $xi = (float)$ring[$i][0]; $yi = (float)$ring[$i][1];
            $xj = (float)$ring[$j][0]; $yj = (float)$ring[$j][1];
            // Jika titik tepat di segment tepi, langsung true (edge-inclusive)
            if ($this->pointOnSegment($lng, $lat, $xi, $yi, $xj, $yj)) {
                return true;
            }
            $intersect = (($yi > $lat) !== ($yj > $lat)) &&
                ($lng < ($xj - $xi) * ($lat - $yi) / (($yj - $yi) ?: 1e-12) + $xi);
            if ($intersect) $inside = !$inside;
        }
        return $inside;
    }

    /**
     * Cek apakah titik berada di segmen (x1,y1)-(x2,y2) (koordinat dalam pasangan (lng,lat)).
     */
    private function pointOnSegment(float $x, float $y, float $x1, float $y1, float $x2, float $y2, float $eps = 1e-9): bool
    {
        $cross = ($y - $y1) * ($x2 - $x1) - ($x - $x1) * ($y2 - $y1);
        if (abs($cross) > $eps) return false;
        $dot = ($x - $x1) * ($x2 - $x1) + ($y - $y1) * ($y2 - $y1);
        if ($dot < 0) return false;
        $lenSq = ($x2 - $x1)**2 + ($y2 - $y1)**2;
        if ($dot > $lenSq) return false;
        return true;
    }

    /**
     * Normalisasi koordinat GeoJSON: deteksi jika pasangan disimpan sebagai [lat,lng] lalu swap ke [lng,lat].
     * Deteksi berbasis rentang Indonesia (lat -12..7, lng 95..142)
     */
    private function normalizeGeometry(array $geometry): array
    {
        $type = $geometry['type'] ?? null;
        $coords = $geometry['coordinates'] ?? null;
        if (!$type || !$coords) return $geometry;

        $needsSwap = function($a, $b) {
            // a seharusnya lng (95..142), b seharusnya lat (-12..7)
            $aLooksLat = ($a >= -12 && $a <= 7);
            $bLooksLng = ($b >= 95 && $b <= 142);
            return $aLooksLat && $bLooksLng; // indikasi [lat,lng]
        };

        $swapPair = fn($p) => [ $p[1], $p[0] ];

        if ($type === 'Polygon') {
            foreach ($coords as $ri => $ring) {
                foreach ($ring as $pi => $pt) {
                    if (is_array($pt) && count($pt) >= 2 && $needsSwap($pt[0], $pt[1])) {
                        $coords[$ri][$pi] = $swapPair($pt);
                    }
                }
            }
            $geometry['coordinates'] = $coords;
        } elseif ($type === 'MultiPolygon') {
            foreach ($coords as $pi => $poly) {
                foreach ($poly as $ri => $ring) {
                    foreach ($ring as $ci => $pt) {
                        if (is_array($pt) && count($pt) >= 2 && $needsSwap($pt[0], $pt[1])) {
                            $coords[$pi][$ri][$ci] = $swapPair($pt);
                        }
                    }
                }
            }
            $geometry['coordinates'] = $coords;
        }
        return $geometry;
    }

    /**
     * Format standar data kebun (array) untuk response JSON.
     */
    private function formatKebun($kebun): array
    {
        $decoded = $kebun->decoded ?? json_decode($kebun->json, true) ?? [];
        return [
            'id' => $kebun->id ?? null,
            'unit_id' => $kebun->unit_id ?? null,
            'unit' => $kebun->unit ?? null,
            'region' => $kebun->region ?? null,
            'center' => $decoded['center'] ?? null,
            'bounds' => $decoded['bounds'] ?? null,
            'geometry' => $decoded['geometry'] ?? null,
        ];
    }


}
