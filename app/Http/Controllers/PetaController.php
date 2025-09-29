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


    // public function peta_regionx($region)
    // {
    //     $user = Auth::user()->region;
    //     // dd($region);
    //     // Ambil semua json polygon
    //     $kebunJsons = DB::table('kebun_json as kj')
    //         ->leftjoin('tb_unit as u', 'u.id', '=', 'kj.unit_id')
    //         ->where('u.region', $region)
    //         ->select('kj.*', 'u.unit as nm_unit', 'u.region as nm_region')
    //         ->get()
    //         ->map(function ($item) {
    //             $item->decoded = json_decode($item->json, true);
    //             return $item;
    //         });
    //         // dd($kebunJsons);
    //     $units = DB::table('tb_unit')
    //             ->where('region', $region)
    //             ->select('id', 'unit')
    //             ->orderBy('unit')
    //             ->get();

    //     // Ambil derajat_hubungan
    //     $derajatHubungan = DB::table('tb_derajat_hubungan')
    //         ->select(
    //             'id', 'id_unit', 'lingkungan', 'ekonomi', 'pendidikan', 'sosial_kesesjahteraan',
    //             'okupasi', 'skor_socmap', 'prioritas_socmap', 'kepuasan', 'kontribusi',
    //             'komunikasi', 'kepercayaan', 'keterlibatan', 'indeks_kepuasan',
    //             'derajat_hubungan', 'deskripsi', 'tahun','derajat_kepuasan'
    //         )
    //         ->get()
    //         ->groupBy('id_unit'); // biar mudah dicari per unit

    //     return view('peta/peta_region', compact('units', 'kebunJsons', 'derajatHubungan'));
    // }

    // // public function getRegionalData($region)
    //  public function peta_region($region)
    // {
    //     // ambil unit di region tertentu
    //     $units = DB::table('tb_unit')
    //         ->where('region', $region)
    //         ->pluck('id'); // hanya ambil ID unit
    //         $unitsx = DB::table('tb_unit')
    //         ->where('region', $region)
    //         ->count('id'); // hanya ambil ID unit
    //         // ->pluck('id'); // hanya ambil ID unit
    //     dd($unitsx);
    //     if ($units->isEmpty()) {
    //         return response()->json([
    //             'message' => 'Tidak ada unit pada region ' . $region
    //         ], 404);
    //     }

    //     // ambil data derajat_hubungan berdasarkan unit di region tsb
    //     $data = DB::table('tb_derajat_hubungan as dh')
    //         ->join('tb_unit as u', 'u.id', '=', 'dh.id_unit')
    //         ->whereIn('u.id', $units)
    //         ->where('dh.tahun', date('Y')) // filter tahun berjalan
    //         ->select(
    //             DB::raw('AVG(dh.kepuasan) as avg_kepuasan'),
    //             DB::raw('AVG(dh.kontribusi) as avg_kontribusi'),
    //             DB::raw('AVG(dh.komunikasi) as avg_komunikasi'),
    //             DB::raw('AVG(dh.kepercayaan) as avg_kepercayaan'),
    //             DB::raw('AVG(dh.keterlibatan) as avg_keterlibatan'),
    //             DB::raw('AVG(dh.indeks_kepuasan) as avg_indeks_kepuasan')
    //         )
    //         ->first();
    //             // dd($data);
    //     // hitung jumlah P1 - P4 dari derajat_hubungan
    //     $counts = DB::table('tb_derajat_hubungan as dh')
    //         ->join('tb_unit as u', 'u.id', '=', 'dh.id_unit')
    //         ->whereIn('u.id', $units)
    //         ->where('dh.tahun', date('Y'))
    //         ->select(
    //             DB::raw("SUM(CASE WHEN dh.derajat_hubungan = 'P1' THEN 1 ELSE 0 END) as total_p1"),
    //             DB::raw("SUM(CASE WHEN dh.derajat_hubungan = 'P2' THEN 1 ELSE 0 END) as total_p2"),
    //             DB::raw("SUM(CASE WHEN dh.derajat_hubungan = 'P3' THEN 1 ELSE 0 END) as total_p3"),
    //             DB::raw("SUM(CASE WHEN dh.derajat_hubungan = 'P4' THEN 1 ELSE 0 END) as total_p4")
    //         )
    //         ->first();

    //     return response()->json([
    //         'region' => $region,
    //         'rata_rata' => $data,
    //         'jumlah_prioritas' => $counts
    //     ]);
    // }
    public function peta_region($region)
    {
        $userRegion = Auth::user()->region; 
        // kalau mau pakai region user, bisa ganti $region dengan $userRegion

        // --- AMBIL POLYGON JSON ---
        $kebunJsons = DB::table('kebun_json as kj')
            ->leftJoin('tb_unit as u', 'u.id', '=', 'kj.unit_id')
            ->where('u.region', $region)
            ->select('kj.*', 'u.unit as nm_unit', 'u.region as nm_region')
            ->get()
            ->map(function ($item) {
                $item->decoded = json_decode($item->json, true);
                return $item;
            });

        // --- AMBIL UNIT DI REGION ---
        $units = DB::table('tb_unit')
            ->where('region', $region)
            ->select('id', 'unit')
            ->orderBy('unit')
            ->get();

        $unitIds = $units->pluck('id');
        $jlhUnit = $unitIds->count();

        // --- DERAJAT HUBUNGAN PER UNIT ---
        $derajatHubungan = DB::table('tb_derajat_hubungan')
            ->whereIn('id_unit', $unitIds)
            ->where('tahun', date('Y'))
            ->select(
                'id', 'id_unit', 'lingkungan', 'ekonomi', 'pendidikan', 'sosial_kesesjahteraan',
                'okupasi', 'skor_socmap', 'prioritas_socmap', 'kepuasan', 'kontribusi',
                'komunikasi', 'kepercayaan', 'keterlibatan', 'indeks_kepuasan',
                'derajat_hubungan', 'deskripsi', 'tahun','derajat_kepuasan'
            )
            ->get()
            ->groupBy('id_unit');

        // --- RATA-RATA NILAI (UNTUK CHART GARIS) ---
        $dataRata = DB::table('tb_derajat_hubungan as dh')
            ->join('tb_unit as u', 'u.id', '=', 'dh.id_unit')
            ->whereIn('u.id', $unitIds)
            ->where('dh.tahun', date('Y'))
            ->select(
                DB::raw('AVG(dh.kepuasan) as avg_kepuasan'),
                DB::raw('AVG(dh.kontribusi) as avg_kontribusi'),
                DB::raw('AVG(dh.komunikasi) as avg_komunikasi'),
                DB::raw('AVG(dh.kepercayaan) as avg_kepercayaan'),
                DB::raw('AVG(dh.keterlibatan) as avg_keterlibatan'),
                DB::raw('AVG(dh.indeks_kepuasan) as avg_indeks_kepuasan')
            )
            ->first();

        // --- JUMLAH P1 - P4 (UNTUK DONUT CHART) ---
        $counts = DB::table('tb_derajat_hubungan as dh')
            ->join('tb_unit as u', 'u.id', '=', 'dh.id_unit')
            ->whereIn('u.id', $unitIds)
            ->where('dh.tahun', date('Y'))
            ->select(
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan = 'P1' THEN 1 ELSE 0 END) as total_p1"),
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan = 'P2' THEN 1 ELSE 0 END) as total_p2"),
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan = 'P3' THEN 1 ELSE 0 END) as total_p3"),
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan = 'P4' THEN 1 ELSE 0 END) as total_p4")
            )
            ->first();
        
        $yearNow = date('Y');
        $yearStart = $yearNow - 4; // 5 tahun ke belakang termasuk tahun sekarang

        $countsPerYear = DB::table('tb_derajat_hubungan as dh')
            ->join('tb_unit as u', 'u.id', '=', 'dh.id_unit')
            ->whereIn('u.id', $unitIds)
            ->whereBetween('dh.tahun', [$yearStart, $yearNow])
            ->select(
                'dh.tahun',
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan = 'P1' THEN 1 ELSE 0 END) as total_p1"),
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan = 'P2' THEN 1 ELSE 0 END) as total_p2"),
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan = 'P3' THEN 1 ELSE 0 END) as total_p3"),
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan = 'P4' THEN 1 ELSE 0 END) as total_p4")
            )
            ->groupBy('dh.tahun')
            ->orderBy('dh.tahun')
            ->get();

            // dd($dataRata);
        // --- KEMBALIKAN KE VIEW ---
        return view('peta/peta_region', [
            'units' => $units,
            'kebunJsons' => $kebunJsons,
            'derajatHubungan' => $derajatHubungan,
            'rataRata' => $dataRata,
            'jumlahPrioritas' => $counts,
            'region' => $region,
            'jlhUnit' => $jlhUnit,
            'countsPerYear' => $countsPerYear,
            'yearNow' => $yearNow,
        ]);
    }

}