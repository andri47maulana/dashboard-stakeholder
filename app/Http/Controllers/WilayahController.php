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



}

