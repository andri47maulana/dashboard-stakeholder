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
        $lat = (float) $request->input('lat');
        $lng = (float) $request->input('lng');

        if (!$lat || !$lng) {
            return response()->json([
                'error' => 'Koordinat tidak valid',
                'data' => $request->all()
            ], 422);
        }

        $kebunJsons = DB::table('kebun_json')->get()->map(function ($item) {
            $item->decoded = json_decode($item->json, true);
            return $item;
        });

        $inside = null;
        $nearest = null;
        $nearestDist = PHP_INT_MAX;

        foreach ($kebunJsons as $kebun) {
            if (!$kebun->decoded) continue;

            $bounds = $kebun->decoded['bounds'] ?? null;
            $center = $kebun->decoded['center'] ?? null;

            if ($bounds && count($bounds) === 4) {
                $minLng = $bounds[0];
                $minLat = $bounds[1];
                $maxLng = $bounds[2];
                $maxLat = $bounds[3];

                if ($lat >= $minLat && $lat <= $maxLat && $lng >= $minLng && $lng <= $maxLng) {
                    $inside = $kebun;
                    break;
                }
            }

            if ($center && count($center) === 2) {
                $dist = $this->haversine($lat, $lng, $center[1], $center[0]); // KM
                if ($dist < $nearestDist) {
                    $nearestDist = $dist;
                    $nearest = $kebun;
                }
            }
        }

        if ($nearest) {
    $nearest->distance_km = round($nearestDist, 2);
    $nearest->decoded = $nearest->decoded ?? json_decode($nearest->json, true);

    if (isset($nearest->decoded['center'])) {
        $nearest->center = $nearest->decoded['center'];
    }
    if (isset($nearest->decoded['bounds'])) {
        $nearest->bounds = $nearest->decoded['bounds'];
    }
    if (isset($nearest->decoded['geometry'])) {
        $nearest->geometry = $nearest->decoded['geometry']; // ⬅ kirim polygon utuh
    }
}


        return response()->json([
            'inside' => $inside,
            'nearest' => $nearest
        ]);

    } catch (\Throwable $e) {
        \Log::error('CheckPoint error: '.$e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'error' => 'Internal Server Error',
            'message' => $e->getMessage()
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


}
