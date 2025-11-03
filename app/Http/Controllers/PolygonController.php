<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SearchLog;

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
        // Daftar regional unik untuk kontrol checkbox
        $regions = $kebunJsons->pluck('nm_region')
            ->filter(function($r){ return !is_null($r) && $r !== ''; })
            ->unique()
            ->sort()
            ->values();
        // Ambil derajat hubungan terbaru per unit (berdasarkan id terbesar)
        $latestDerajat = DB::table('tb_derajat_hubungan as d')
            ->join(DB::raw('(select id_unit, max(id) as max_id from tb_derajat_hubungan group by id_unit) m'), function($join){
                $join->on('m.id_unit','=','d.id_unit')->on('m.max_id','=','d.id');
            })
            ->select(
                'd.id_unit','d.derajat_hubungan','d.indeks_kepuasan','d.prioritas_socmap',
                'd.kepuasan','d.kontribusi','d.komunikasi','d.kepercayaan','d.keterlibatan',
                // Social Mapping breakdown and score
                'd.lingkungan','d.ekonomi','d.pendidikan','d.sosial_kesesjahteraan','d.okupasi','d.skor_socmap',
                // Optionally include derajat_kepuasan if needed by other modules
                'd.derajat_kepuasan',
                'd.tahun','d.deskripsi'
            )
            ->get();
        $derajatMap = [];
        foreach ($latestDerajat as $row) {
            $derajatMap[$row->id_unit] = $row;
        }
        $logs = SearchLog::query()
            ->leftJoin('stakeholder as s','s.id','=','search_logs.stakeholder_id')
            ->leftJoin('tb_tjsl as t','t.id','=','search_logs.tjsl_id')
            ->select('search_logs.*','s.nama_instansi as stakeholder_nama','t.nama_program as tjsl_nama')
            ->orderBy('search_logs.created_at','desc')
            ->limit(50)
            ->get();
        // Ambil data TJSL yang memiliki koordinat dengan informasi regional dari unit
        $tjslLocations = DB::table('tb_tjsl')
            ->leftJoin('tb_unit', 'tb_unit.id', '=', 'tb_tjsl.unit_id')
            ->whereNotNull('tb_tjsl.latitude')
            ->whereNotNull('tb_tjsl.longitude')
            ->select('tb_tjsl.id', 'tb_tjsl.nama_program', 'tb_tjsl.lokasi_program', 'tb_tjsl.latitude', 'tb_tjsl.longitude',
                     'tb_tjsl.tanggal_mulai', 'tb_tjsl.tanggal_akhir', 'tb_tjsl.status', 'tb_tjsl.unit_id', 'tb_unit.region as nm_region')
            ->get();
        return view('polygon.index',compact('kebunJsons','logs','derajatMap','regions','tjslLocations'));
    }

    public function viewLog($id)
    {
        $kebunJsons = DB::table('kebun_json as kj')
            ->leftJoin('tb_unit as u', 'u.id', '=', 'kj.unit_id')
            ->select('kj.*', 'u.unit as nm_unit', 'u.region as nm_region')
            ->get()
            ->map(function ($item) {
                $item->decoded = json_decode($item->json, true);
                return $item;
            });
        // Daftar regional unik untuk kontrol checkbox
        $regions = $kebunJsons->pluck('nm_region')
            ->filter(function($r){ return !is_null($r) && $r !== ''; })
            ->unique()
            ->sort()
            ->values();
        // Ambil derajat hubungan terbaru per unit juga untuk halaman ini
        $latestDerajat = DB::table('tb_derajat_hubungan as d')
            ->join(DB::raw('(select id_unit, max(id) as max_id from tb_derajat_hubungan group by id_unit) m'), function($join){
                $join->on('m.id_unit','=','d.id_unit')->on('m.max_id','=','d.id');
            })
            ->select(
                'd.id_unit','d.derajat_hubungan','d.indeks_kepuasan','d.prioritas_socmap',
                'd.kepuasan','d.kontribusi','d.komunikasi','d.kepercayaan','d.keterlibatan',
                'd.lingkungan','d.ekonomi','d.pendidikan','d.sosial_kesesjahteraan','d.okupasi','d.skor_socmap',
                'd.derajat_kepuasan',
                'd.tahun','d.deskripsi'
            )
            ->get();
        $derajatMap = [];
        foreach ($latestDerajat as $row) {
            $derajatMap[$row->id_unit] = $row;
        }
        $logs = SearchLog::query()
            ->leftJoin('stakeholder as s','s.id','=','search_logs.stakeholder_id')
            ->leftJoin('tb_tjsl as t','t.id','=','search_logs.tjsl_id')
            ->select('search_logs.*','s.nama_instansi as stakeholder_nama','t.nama_program as tjsl_nama')
            ->orderBy('search_logs.created_at','desc')
            ->limit(50)
            ->get();
        $activeLog = SearchLog::query()
            ->leftJoin('stakeholder as s','s.id','=','search_logs.stakeholder_id')
            ->leftJoin('tb_tjsl as t','t.id','=','search_logs.tjsl_id')
            ->select('search_logs.*','s.nama_instansi as stakeholder_nama','t.nama_program as tjsl_nama')
            ->where('search_logs.id',$id)
            ->firstOrFail();
        // Ambil data TJSL yang memiliki koordinat dengan informasi regional dari unit
        $tjslLocations = DB::table('tb_tjsl')
            ->leftJoin('tb_unit', 'tb_unit.id', '=', 'tb_tjsl.unit_id')
            ->whereNotNull('tb_tjsl.latitude')
            ->whereNotNull('tb_tjsl.longitude')
            ->select('tb_tjsl.id', 'tb_tjsl.nama_program', 'tb_tjsl.lokasi_program', 'tb_tjsl.latitude', 'tb_tjsl.longitude',
                     'tb_tjsl.tanggal_mulai', 'tb_tjsl.tanggal_akhir', 'tb_tjsl.status', 'tb_tjsl.unit_id', 'tb_unit.region as nm_region')
            ->get();
        return view('polygon.index', compact('kebunJsons','logs','activeLog','derajatMap','regions','tjslLocations'));
    }

    public function deleteLog($id)
    {
        $deleted = SearchLog::where('id',$id)->delete();
        if ($deleted) {
            return response()->json(['ok'=>true]);
        }
        return response()->json(['ok'=>false], 404);
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

                // 3. Hitung jarak ke center (untuk nearest dan sebagai fallback)
                $centerDist = null;
                if ($center && count($center) === 2) {
                    $centerDist = $this->haversine($lat, $lng, $center[1], $center[0]); // km
                    if ($centerDist < $nearestDist) {
                        $nearestDist = $centerDist;
                        $nearest = $this->formatKebun($kebun);
                        $nearest['distance_km'] = round($nearestDist, 3);
                        $nearest['distance_type'] = 'center';
                    }
                }

                // 4. Jika radius diminta: gunakan jarak ke tepi polygon (edge) jika geometry tersedia
                if ($radiusKm !== null) {
                    $edgeDist = null;

                    if ($geometry) {
                        // Jarak minimal ke sisi polygon; 0 jika di dalam
                        $edgeDist = $this->minDistanceToGeometry($lat, $lng, $geometry, 0.25); // early-stop 250 m
                    } elseif ($bounds && count($bounds) === 4) {
                        // Jika tidak ada geometry tapi ada bounds, gunakan jarak ke bounding box
                        $edgeDist = $this->minDistanceToBounds($lat, $lng, $bounds);
                    } elseif ($centerDist !== null) {
                        // Fallback terakhir: jarak ke center
                        $edgeDist = $centerDist;
                    }

                    if ($edgeDist !== null && $edgeDist <= $radiusKm) {
                        $row = $this->formatKebun($kebun);
                        $row['distance_km'] = round($edgeDist, 3);
                        if ($geometry && $edgeDist !== $centerDist) {
                            $row['distance_type'] = 'edge';
                        } elseif ($bounds && !$geometry) {
                            $row['distance_type'] = 'bbox';
                        } else {
                            $row['distance_type'] = 'center';
                        }
                        if ($centerDist !== null) {
                            $row['center_distance_km'] = round($centerDist, 3);
                        }
                        $withinRadius[] = $row;
                    }
                }
            }

            // Urutkan dalam radius berdasarkan jarak
            if ($radiusKm !== null && $withinRadius) {
                usort($withinRadius, fn($a,$b) => $a['distance_km'] <=> $b['distance_km']);
            }

            $response = [
                'query_point' => ['lat'=>$lat,'lng'=>$lng],
                'search_radius_km' => $radiusKm,
                'inside' => $inside,
                'nearest' => $nearest,
                'within_radius' => $radiusKm !== null ? $withinRadius : null,
                'circle' => $radiusKm !== null ? [
                    'center' => ['lng'=>$lng,'lat'=>$lat],
                    'radius_km' => $radiusKm
                ] : null
            ];

            // Optional: log the search if client passes a title or associations
            $logTitle = $request->input('title');
            $stakeholderId = $request->input('stakeholder_id');
            $tjslId = $request->input('tjsl_id');
            $noLog = filter_var($request->input('no_log'), FILTER_VALIDATE_BOOLEAN);
            $shouldLog = !$noLog && ($logTitle || $stakeholderId || $tjslId);
            if ($shouldLog) {
                SearchLog::create([
                    'user_id' => Auth::id(),
                    'title' => $logTitle,
                    'lat' => $lat,
                    'lng' => $lng,
                    'radius_km' => $radiusKm,
                    'is_inside' => $inside ? 1 : 0,
                    'inside_unit' => $inside['unit'] ?? $inside['id'] ?? null,
                    'inside_unit_id' => $inside['unit_id'] ?? null,
                    'nearest_unit' => $nearest['unit'] ?? $nearest['id'] ?? null,
                    'nearest_unit_id' => $nearest['unit_id'] ?? null,
                    'nearest_distance_km' => $nearest['distance_km'] ?? null,
                    'stakeholder_id' => $stakeholderId ?: null,
                    'tjsl_id' => $tjslId ?: null,
                ]);
            }

            return response()->json($response);
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
     * Hitung jarak minimum (km) dari titik ke geometry (Polygon/MultiPolygon). 0 jika di dalam.
     * Menggunakan proyeksi lokal equirectangular untuk aproksimasi cepat dan akurat untuk jarak pendek.
     * earlyStopKm: jika jarak sudah di bawah threshold, hentikan awal untuk performa.
     */
    private function minDistanceToGeometry(float $lat, float $lng, array $geometry, ?float $earlyStopKm = null): ?float
    {
        $type = $geometry['type'] ?? null;
        $coords = $geometry['coordinates'] ?? null;
        if (!$type || !$coords) return null;

        if ($type === 'Polygon') {
            return $this->minDistanceToPolygon($lat, $lng, $coords, $earlyStopKm);
        } elseif ($type === 'MultiPolygon') {
            $min = PHP_FLOAT_MAX;
            foreach ($coords as $poly) {
                $d = $this->minDistanceToPolygon($lat, $lng, $poly, $earlyStopKm);
                if ($d === 0.0) return 0.0;
                if ($d !== null && $d < $min) $min = $d;
                if ($earlyStopKm !== null && $min <= $earlyStopKm) return $min;
            }
            return $min === PHP_FLOAT_MAX ? null : $min;
        }
        return null;
    }

    private function minDistanceToPolygon(float $lat, float $lng, array $rings, ?float $earlyStopKm = null): ?float
    {
        // Jika di dalam outer dan tidak di dalam hole → jarak 0
        if ($this->pointInPolygonRings($lat, $lng, $rings)) return 0.0;

        $min = PHP_FLOAT_MAX;
        // Gunakan proyeksi lokal sekitar titik kueri untuk konversi derajat->meter
        $lat0 = deg2rad($lat);
        $mPerDegLat = 111132.0; // kira-kira
        $mPerDegLon = 111320.0 * cos($lat0);

        foreach ($rings as $ring) {
            $n = count($ring);
            if ($n < 2) continue;
            for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
                $x1 = ($ring[$j][0] - $lng) * $mPerDegLon; // lon
                $y1 = ($ring[$j][1] - $lat) * $mPerDegLat; // lat
                $x2 = ($ring[$i][0] - $lng) * $mPerDegLon;
                $y2 = ($ring[$i][1] - $lat) * $mPerDegLat;

                // Proyeksi P(0,0) ke segmen A(x1,y1)-B(x2,y2)
                $dx = $x2 - $x1; $dy = $y2 - $y1;
                $len2 = $dx*$dx + $dy*$dy;
                if ($len2 == 0) {
                    $distM = sqrt($x1*$x1 + $y1*$y1);
                } else {
                    $t = -($x1*$dx + $y1*$dy) / $len2; // karena P di (0,0)
                    if ($t < 0) $t = 0; elseif ($t > 1) $t = 1;
                    $cx = $x1 + $t*$dx; $cy = $y1 + $t*$dy;
                    $distM = sqrt($cx*$cx + $cy*$cy);
                }
                $distKm = $distM / 1000.0;
                if ($distKm < $min) $min = $distKm;
                if ($earlyStopKm !== null && $min <= $earlyStopKm) return $min;
            }
        }
        return $min === PHP_FLOAT_MAX ? null : $min;
    }

    /**
     * Jarak minimum (km) dari titik ke bounding box [minLng,minLat,maxLng,maxLat]. 0 jika di dalam bbox.
     */
    private function minDistanceToBounds(float $lat, float $lng, array $bounds): float
    {
        if (count($bounds) !== 4) return PHP_FLOAT_MAX;
        [$minLng,$minLat,$maxLng,$maxLat] = $bounds;

        // Konversi derajat ke meter di sekitar latitude query
        $lat0 = deg2rad($lat);
        $mPerDegLat = 111132.0;
        $mPerDegLon = 111320.0 * cos($lat0);

        // Hitung delta pada sumbu lon/lat
        $dx = 0.0; $dy = 0.0;
        if ($lng < $minLng) {
            $dx = ($minLng - $lng) * $mPerDegLon;
        } elseif ($lng > $maxLng) {
            $dx = ($lng - $maxLng) * $mPerDegLon;
        }

        if ($lat < $minLat) {
            $dy = ($minLat - $lat) * $mPerDegLat;
        } elseif ($lat > $maxLat) {
            $dy = ($lat - $maxLat) * $mPerDegLat;
        }

        // Jika di dalam rentang kedua sumbu, dx=dy=0 → jarak 0
        $distM = sqrt($dx*$dx + $dy*$dy);
        return $distM / 1000.0;
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
