<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{
    public function getWilayah(Request $request)
{
    $parent = $request->get('parent');
    $level = $request->get('level');
    $q = $request->get('q'); // pencarian teks

    switch ($level) {
        case 'provinsi':
            $query = DB::table('wilayah')
                ->whereRaw('LENGTH(kode) = 2');
            break;

        case 'kabupaten':
            $query = DB::table('wilayah')
                ->whereRaw('LENGTH(kode) = 5')
                ->where('kode', 'like', $parent . '%');
            break;

        case 'kecamatan':
            $query = DB::table('wilayah')
                ->whereRaw('LENGTH(kode) = 8')
                ->where('kode', 'like', $parent . '%');
            break;

        case 'desa':
            $query = DB::table('wilayah')
                ->whereRaw('LENGTH(kode) = 13')
                ->where('kode', 'like', $parent . '%');
            break;

        default:
            return response()->json([]);
    }

    if ($q) {
        $query->where('nama', 'like', '%' . $q . '%');
    }

    $data = $query->get();
    return response()->json($data);
}

    public function getWilayahByCode(Request $request)
    {
        $kode = $request->get('kode');
        
        if (!$kode) {
            return response()->json(['error' => 'Kode wilayah tidak ditemukan'], 400);
        }

        // Database menyimpan kode dalam format dengan titik (32.73.09.1003)
        // Jadi kita gunakan kode asli tanpa konversi
        $wilayah = DB::table('wilayah')
            ->where('kode', $kode)
            ->first();

        if ($wilayah) {
            return response()->json([
                'kode' => $kode,
                'nama' => $wilayah->nama
            ]);
        }

        return response()->json(['error' => 'Wilayah tidak ditemukan'], 404);
    }

    private function convertKodeFormat($kode)
    {
        // Convert dari format 11.01.01.2002 ke format database
        $parts = explode('.', $kode);
        
        if (count($parts) == 1) {
            // Provinsi: 11 -> 11
            return $kode;
        } elseif (count($parts) == 2) {
            // Kabupaten: 11.01 -> 1101
            return $parts[0] . $parts[1];
        } elseif (count($parts) == 3) {
            // Kecamatan: 11.01.01 -> 11010100 (tambah 00 di akhir)
            return $parts[0] . $parts[1] . $parts[2] . '00';
        } elseif (count($parts) == 4) {
            // Desa: 11.01.01.2002 -> 1101012002
            return $parts[0] . $parts[1] . $parts[2] . $parts[3];
        }
        
        return $kode;
    }
}

