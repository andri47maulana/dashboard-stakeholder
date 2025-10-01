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

    public function peta_region($region = null)
    {
        $userRegion = Auth::user()->region;
        $region = $region ?? $userRegion;

        // Tahun unik
        $tahun = DB::table('tb_derajat_hubungan as dh')
            ->join('tb_unit as u','u.id','=','dh.id_unit')
            ->where('u.region', $region)
            ->distinct('dh.tahun')
            ->pluck('dh.tahun');

        // Polygon JSON
        $kebunJsons2 = DB::table('kebun_json as kj')
            ->join('tb_unit as u','u.id','=','kj.unit_id')
            ->where('u.region', $region)
            ->select('kj.*','u.unit as nm_unit','u.region as nm_region')
            ->get()
            ->map(fn($item) => (object)[
                'id' => $item->id,
                'unit_id' => $item->unit_id,
                'decoded' => json_decode($item->json, true)
            ]);
        
        // dd($kebunJsons2);
            $kebunJsons = DB::table('kebun_json as kj')
            ->leftJoin('tb_unit as u', 'u.id', '=', 'kj.unit_id')
            ->where('u.region', $region)
            ->select('kj.*', 'u.unit as nm_unit', 'u.region as nm_region')
            ->get()
            ->map(function ($item) {
                $item->decoded = json_decode($item->json, true);
                return $item;
            });
            // dd($kebunJsons,$kebunJsons1);
        // Unit di region
        $units = DB::table('tb_unit')
            ->where('region', $region)
            ->select('id','unit')
            ->orderBy('unit')
            ->get();

        $unitIds = $units->pluck('id');
        $jlhUnit = $unitIds->count();

        // Ambil data default tahun = sekarang-1
        $yearNow = date('Y');
        $defaultYear = $yearNow - 1;

        // Derajat hubungan & rata-rata
        $rataRata = $this->getRataRata($unitIds, $defaultYear);
        $jumlahPrioritas = $this->getJumlahPrioritas($unitIds, $defaultYear);
        $countsPerYear = $this->getCountsPerYear($unitIds);

        return view('peta/peta_region', [
            'units' => $units,
            'kebunJsons' => $kebunJsons,
            'region' => $region,
            'jlhUnit' => $jlhUnit,
            'tahun' => $tahun,
            'yearNow' => $yearNow,
            'rataRata' => $rataRata,
            'jumlahPrioritas' => $jumlahPrioritas,
            'countsPerYear' => $countsPerYear,
        ]);
    }

    // --- ENDPOINT AJAX UNTUK UPDATE DATA BERDASARKAN TAHUN ---
    public function dataByYear($region, $tahun)
{
    $units = DB::table('tb_unit')->where('region',$region)->pluck('id');

    $rataRata = $this->getRataRata($units, $tahun);
    $jumlahPrioritas = $this->getJumlahPrioritas($units, $tahun);
    $countsPerYear = $this->getCountsPerYear($units);

    // ambil kebun json + derajat_hubungan
    $kebunJsons = DB::table('kebun_json as kj')
        ->join('tb_unit as u', 'u.id', '=', 'kj.unit_id')
        ->leftJoin('tb_derajat_hubungan as dh', function($join) use ($tahun) {
            $join->on('dh.id_unit','=','u.id')
                 ->where('dh.tahun',$tahun);
        })
        ->where('u.region', $region)
        ->select(
            'kj.id','kj.unit_id','kj.json',
            'u.unit as nm_unit',
            'dh.derajat_hubungan'
        )
        ->get()
        ->map(function($item){
            return [
                'id' => $item->id,
                'unit_id' => $item->unit_id,
                'decoded' => json_decode($item->json, true),
                'derajat' => $item->derajat_hubungan
            ];
        });

    return response()->json([
        'rataRata' => $rataRata,
        'jumlahPrioritas' => $jumlahPrioritas,
        'countsPerYear' => $countsPerYear,
        'jlhUnit' => count($units),
        'kebunJsons' => $kebunJsons
    ]);
}


    // --- ENDPOINT AJAX UNTUK MODAL UNIT DETAIL ---
    public function unitDetail($unitId, $tahun)
    {
        $unit = DB::table('tb_unit')->where('id',$unitId)->first();
        // dd($tahun);
            $kebunJsons = DB::table('kebun_json')
                ->where('unit_id', $unitId)
                ->get()
                ->map(function($item){
                    return json_decode($item->json, true); // return langsung hasil decode
                });
            // dd($kebunJsons);
        $derajatHubungan = DB::table('tb_derajat_hubungan')
            ->where('id_unit',$unitId)
            ->where('tahun',$tahun)->first();
        if ($derajatHubungan) {
            // dd($derajatHubungan->id);
            $isu=DB::table('tb_isu_detail')->where('derajat_id',$derajatHubungan->id)->get();
            $desa=DB::table('tb_isu_desa')->leftjoin('wilayah as w','w.kode','=','tb_isu_desa.desa_id')
                    ->where('derajat_id',$derajatHubungan->id)->select('tb_isu_desa.*','w.nama as nama')
                    ->get();
            $lembaga=DB::table('tb_isu_instansi')->leftjoin('stakeholder as i','i.id','=','tb_isu_instansi.instansi_id')
                    ->where('derajat_id',$derajatHubungan->id)->select('tb_isu_instansi.*','i.nama_instansi as nama')
                    ->get();
            $okupasi=DB::table('tb_isu_okupasi')
                    ->where('derajat_id',$derajatHubungan->id)
                    ->get();
            // dd($desa);
            // Gabungkan data isu
            $isuDetails = DB::table('tb_derajat_hubungan as derajat')
                ->leftJoin('tb_isu_detail as detail','detail.derajat_id','=','derajat.id')
                ->leftJoin('tb_isu_desa as desa','desa.derajat_id','=','derajat.id')
                ->leftJoin('tb_isu_instansi as inst','inst.derajat_id','=','derajat.id')
                ->leftJoin('tb_isu_okupasi as okup','okup.derajat_id','=','derajat.id')
                ->where('derajat.id_unit',$unitId)
                ->where('derajat.tahun',$tahun)
                ->select(
                    'detail.isu as isu','detail.keterangan as ket_isu',
                    'desa.desa_id as desa','inst.instansi_id as instansi','okup.okupasi as okupasi'
                )
                ->get();
            // dd($unitId);
            return response()->json([
                'unit' => $unit,
                'derajatHubungan' => $derajatHubungan,
                'isuDetails' => $isuDetails,
                'isu' => $isu,
                'desa' => $desa,
                'lembaga' => $lembaga,
                'okupasi' => $okupasi,
                'kebunJsons' => $kebunJsons,
            ]);
        } else {
            return response()->json([
                'unit' => $unit,
                'derajatHubungan' => null,
                'isuDetails' => [],
                'isu' => [],
                'desa' => [],
                'lembaga' => [],
                'okupasi' => [],
                'kebunJsons' => $kebunJsons,
            ]);
        }
            
    }

    // ----------------------- FUNCTION BANTU -----------------------
    private function getRataRata($unitIds, $tahun)
    {
        return DB::table('tb_derajat_hubungan as dh')
            ->whereIn('dh.id_unit',$unitIds)
            ->where('dh.tahun',$tahun)
            ->select(
                DB::raw('AVG(dh.kepuasan) as avg_kepuasan'),
                DB::raw('AVG(dh.kontribusi) as avg_kontribusi'),
                DB::raw('AVG(dh.komunikasi) as avg_komunikasi'),
                DB::raw('AVG(dh.kepercayaan) as avg_kepercayaan'),
                DB::raw('AVG(dh.keterlibatan) as avg_keterlibatan'),
                DB::raw('AVG(dh.indeks_kepuasan) as avg_indeks_kepuasan')
            )->first();
    }

    private function getJumlahPrioritas($unitIds, $tahun)
    {
        return DB::table('tb_derajat_hubungan as dh')
            ->whereIn('dh.id_unit',$unitIds)
            ->where('dh.tahun',$tahun)
            ->select(
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan='P1' THEN 1 ELSE 0 END) as total_p1"),
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan='P2' THEN 1 ELSE 0 END) as total_p2"),
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan='P3' THEN 1 ELSE 0 END) as total_p3"),
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan='P4' THEN 1 ELSE 0 END) as total_p4")
            )->first();
    }

    private function getCountsPerYear($unitIds)
    {
        $yearNow = date('Y');
        $yearStart = $yearNow - 4;

        return DB::table('tb_derajat_hubungan as dh')
            ->whereIn('dh.id_unit',$unitIds)
            ->whereBetween('dh.tahun',[$yearStart,$yearNow])
            ->select(
                'dh.tahun',
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan='P1' THEN 1 ELSE 0 END) as total_p1"),
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan='P2' THEN 1 ELSE 0 END) as total_p2"),
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan='P3' THEN 1 ELSE 0 END) as total_p3"),
                DB::raw("SUM(CASE WHEN dh.derajat_hubungan='P4' THEN 1 ELSE 0 END) as total_p4")
            )
            ->groupBy('dh.tahun')
            ->orderBy('dh.tahun')
            ->get();
    }

    public function petaJsonByYear($region, $tahun)
    {
        $kebunJsons = DB::table('kebun_json as kj')
            ->join('tb_unit as u', 'u.id', '=', 'kj.unit_id')
            ->leftJoin('tb_derajat_hubungan as dh', function($join) use ($tahun) {
                $join->on('dh.id_unit','=','u.id')
                    ->where('dh.tahun',$tahun);
            })
            ->where('u.region', $region)
            ->select(
                'kj.id','kj.unit_id','kj.json',
                'u.unit as nm_unit','u.region as nm_region',
                'dh.derajat_hubungan','dh.tahun'
            )
            ->get()
            ->map(function($item){
                return [
                    'id' => $item->id,
                    'unit_id' => $item->unit_id,
                    'decoded' => json_decode($item->json,true),
                    'derajat' => $item->derajat_hubungan
                ];
            });

        return response()->json($kebunJsons);
    }


}