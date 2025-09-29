<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Auth;

class PetaController extends Controller
{
    
    public function index()
    {
        $user = Auth::user()->region;
        if ($user === "PTPN I HO") {
            // Jika user = PTPN I HO → lihat semua
            $units = DB::table('tb_unit')
                ->select('id', 'unit', 'region')
                ->orderBy('region')
                ->orderBy('unit')
                ->get()
                ->groupBy('region');
        } else {
            // Jika user selain PTPN I HO → lihat sesuai region user
            $units = DB::table('tb_unit')
                ->where('region', $user)
                ->select('id', 'unit', 'region')
                ->orderBy('unit')
                ->get()
                ->groupBy('region');
        }
        
        // Ambil semua json polygon
        $kebunJsons = DB::table('kebun_json')
            ->select('id', 'unit_id', 'json')
            ->get()
            ->map(function ($item) {
                $item->decoded = json_decode($item->json, true);
                return $item;
            });

        // Ambil derajat_hubungan
        $derajatHubungan = DB::table('tb_derajat_hubungan')
            ->select(
                'id', 'id_unit', 'lingkungan', 'ekonomi', 'pendidikan', 'sosial_kesesjahteraan',
                'okupasi', 'skor_socmap', 'prioritas_socmap', 'kepuasan', 'kontribusi',
                'komunikasi', 'kepercayaan', 'keterlibatan', 'indeks_kepuasan',
                'derajat_hubungan', 'deskripsi', 'tahun','derajat_kepuasan'
            )
            ->get()
            ->groupBy('id_unit'); // biar mudah dicari per unit

        return view('peta/peta', compact('units', 'kebunJsons', 'derajatHubungan'));
    }



    public function getPolygons($unitId)
    {
        $polygons = DB::table('kebun_json')
            ->where('unit_id', $unitId)
            ->get(['json']);

        // decode json string ke array
        $data = $polygons->map(function ($row) {
            return json_decode($row->json, true);
        });

        return response()->json($data);
    }


    public function peta_region($region)
    {
        $user = Auth::user()->region;
        // dd($region);
        // Ambil semua json polygon
        $kebunJsons = DB::table('kebun_json as kj')
            ->leftjoin('tb_unit as u', 'u.id', '=', 'kj.unit_id')
            ->where('u.region', $region)
            ->select('kj.*', 'u.unit as nm_unit', 'u.region as nm_region')
            ->get()
            ->map(function ($item) {
                $item->decoded = json_decode($item->json, true);
                return $item;
            });
            // dd($kebunJsons);
        $units = DB::table('tb_unit')
                ->where('region', $region)
                ->select('id', 'unit')
                ->orderBy('unit')
                ->get();

        // Ambil derajat_hubungan
        $derajatHubungan = DB::table('tb_derajat_hubungan')
            ->select(
                'id', 'id_unit', 'lingkungan', 'ekonomi', 'pendidikan', 'sosial_kesesjahteraan',
                'okupasi', 'skor_socmap', 'prioritas_socmap', 'kepuasan', 'kontribusi',
                'komunikasi', 'kepercayaan', 'keterlibatan', 'indeks_kepuasan',
                'derajat_hubungan', 'deskripsi', 'tahun','derajat_kepuasan'
            )
            ->get()
            ->groupBy('id_unit'); // biar mudah dicari per unit

        return view('peta/peta_region', compact('units', 'kebunJsons', 'derajatHubungan'));
    }
}