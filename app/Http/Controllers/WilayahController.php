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
        $kode = trim($request->get('kode'));

        if (!$kode) {
            return response()->json(['error' => 'Kode wilayah tidak ditemukan'], 400);
        }

        // Preserve dotted format; normalize only if input has no dots
        $normalizedKode = $this->convertKodeFormat($kode);

        $wilayah = DB::table('wilayah')
            ->where('kode', $normalizedKode)
            ->first();

        if ($wilayah) {
            return response()->json([
                'kode' => $normalizedKode,
                'nama' => $wilayah->nama
            ]);
        }

        return response()->json(['error' => 'Wilayah tidak ditemukan'], 404);
    }

    private function convertKodeFormat($kode)
    {
        $kode = trim($kode);

        // If already dotted (e.g., 32.73 or 32.73.09.1003), use as-is
        if (strpos($kode, '.') !== false) {
            return $kode;
        }

        // Normalize dense codes to dotted format expected by DB
        $len = strlen($kode);
        if ($len === 2) { // provinsi: 11
            return $kode;
        }
        if ($len === 4) { // kabupaten: 11.01
            return substr($kode, 0, 2) . '.' . substr($kode, 2, 2);
        }
        if ($len === 6) { // kecamatan: 11.01.01
            return substr($kode, 0, 2) . '.' . substr($kode, 2, 2) . '.' . substr($kode, 4, 2);
        }
        if ($len === 10) { // desa: 11.01.01.2002
            return substr($kode, 0, 2) . '.' . substr($kode, 2, 2) . '.' . substr($kode, 4, 2) . '.' . substr($kode, 6, 4);
        }

        // Fallback: return as-is
        return $kode;
    }

    // API methods for TJSL dropdown
    public function getProvinsi()
    {
        $provinsi = DB::table('wilayah')
            ->whereRaw('LENGTH(kode) = 2')
            ->select('kode as id', 'nama as name')
            ->orderBy('nama')
            ->get();

        return response()->json($provinsi);
    }

    public function getKabupaten($provinsi_id)
    {
        $kabupaten = DB::table('wilayah')
            ->whereRaw('LENGTH(kode) = 5')
            ->where('kode', 'like', $provinsi_id . '.%')
            ->select('kode as id', 'nama as name')
            ->orderBy('nama')
            ->get();

        return response()->json($kabupaten);
    }

    public function getKecamatan($kabupaten_id)
    {
        $kecamatan = DB::table('wilayah')
            ->whereRaw('LENGTH(kode) = 8')
            ->where('kode', 'like', $kabupaten_id . '.%')
            ->select('kode as id', 'nama as name')
            ->orderBy('nama')
            ->get();

        return response()->json($kecamatan);
    }

    public function getDesa($kecamatan_id)
    {
        $desa = DB::table('wilayah')
            ->whereRaw('LENGTH(kode) = 13')
            ->where('kode', 'like', $kecamatan_id . '.%')
            ->select('kode as id', 'nama as name')
            ->orderBy('nama')
            ->get();

        return response()->json($desa);
    }
}

