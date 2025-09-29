<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Auth;

class HomeController extends Controller
{
    public function index(){
        
        $datastakeholderall = DB::table('stakeholder')
        
        ->count();

        $datagovernance = DB::table('stakeholder') ->where('kategori','Governance')->count();
        $datanongovernance = DB::table('stakeholder') ->where('kategori','Non Governance')->count();
        $dataperjanjiankerjasamaexpired = DB::table('dokumenkerjasama') ->where('dokumenkerjasama.jenis_dokumen','PKS')->whereRaw('(datediff(masa_berlaku, current_date()) < 31)')->count();
        $datamouexpired = DB::table('dokumenkerjasama') ->where('dokumenkerjasama.jenis_dokumen','MOU')->whereRaw('(datediff(masa_berlaku, current_date()) < 31)')->count();
        $dataperizinanexpired = DB::table('perizinan') ->whereRaw('(datediff(tanggal_end, current_date()) < 31)')->count();
        $datasertifikasiexpired = DB::table('sertifikasi') ->whereRaw('(datediff(tanggal_end, current_date()) < 31)')->count();


        $persen_datagovernance =  round($datagovernance/$datastakeholderall * 100,1) ;
        $persen_datanongovernance =  round($datanongovernance/$datastakeholderall * 100,1) ;

        //dd($dataalluser);
        $status=0;
        //ini pakai compact
        return view('home.home', compact(
            'datastakeholderall',
            'datagovernance',
            'datanongovernance',
            'persen_datagovernance',
            'persen_datanongovernance',
            'dataperjanjiankerjasamaexpired',
            'datamouexpired',
            'dataperizinanexpired',
            'datasertifikasiexpired'
        ));

        // return view('home.home',compact('datastakeholderall','datagovernance','datanongovernance','persen_datagovernance','persen_datanongovernance','dataperjanjiankerjasamaexpired','datamouexpired','dataperizinanexpired','datasertifikasiexpired'));
    }
    public function getRegionalData(Request $request)
    {
        $region = $request->get('region');

        // Stakeholder
        $datastakeholderall = DB::table('stakeholder')
            ->where('region', $region)
            ->count();

        $datagovernance = DB::table('stakeholder')
            ->where('region', $region)
            ->where('kategori', 'Governance')
            ->count();

        $datanongovernance = DB::table('stakeholder')
            ->where('region', $region)
            ->where('kategori', 'Non Governance')
            ->count();

        // Dokumen Kerjasama
        $dataperjanjiankerjasamaexpired = DB::table('dokumenkerjasama')
            ->where('region', $region)
            ->where('jenis_dokumen', 'PKS')
            ->whereRaw('(datediff(masa_berlaku, current_date()) < 31)')
            ->count();

        $datamouexpired = DB::table('dokumenkerjasama')
            ->where('region', $region)
            ->where('jenis_dokumen', 'MOU')
            ->whereRaw('(datediff(masa_berlaku, current_date()) < 31)')
            ->count();

        // Perizinan
        $dataperizinanexpired = DB::table('perizinan')
            ->where('region', $region)
            ->whereRaw('(datediff(tanggal_end, current_date()) < 31)')
            ->count();

        // Sertifikasi
        $datasertifikasiexpired = DB::table('sertifikasi')
            ->where('region', $region)
            ->whereRaw('(datediff(tanggal_end, current_date()) < 31)')
            ->count();

        // Persentase
        $persen_datagovernance = $datastakeholderall > 0
            ? round($datagovernance / $datastakeholderall * 100, 1)
            : 0;

        $persen_datanongovernance = $datastakeholderall > 0
            ? round($datanongovernance / $datastakeholderall * 100, 1)
            : 0;

        return response()->json([
            'region' => $region,
            'datastakeholderall' => $datastakeholderall,
            'datagovernance' => $datagovernance,
            'datanongovernance' => $datanongovernance,
            'persen_datagovernance' => $persen_datagovernance,
            'persen_datanongovernance' => $persen_datanongovernance,
            'dataperjanjiankerjasamaexpired' => $dataperjanjiankerjasamaexpired,
            'datamouexpired' => $datamouexpired,
            'dataperizinanexpired' => $dataperizinanexpired,
            'datasertifikasiexpired' => $datasertifikasiexpired,
        ]);
    }
    // public function getStakeholderByRegion(Request $request)
    // {
    //     $region = $request->get('region');

    //     $stakeholders = DB::table('stakeholder')
    //         ->where('region', $region)
    //         ->select('id','nama_instansi', 'kategori', 'region', 'nama_pic', 'jabatan_pic','nomorkontak_pic','daerah_instansi')
    //         ->get();

    //     // return response()->json($stakeholders);
    //     return response()->json([
    //         'data' => $stakeholders
    //     ]);
    // }

    public function getStakeholderByRegion(Request $request)
    {
        $region = $request->get('region');

        $query = DB::table('stakeholder')
            ->select('id','nama_instansi', 'kategori', 'region', 'nama_pic', 'jabatan_pic','nomorkontak_pic','daerah_instansi');

        if ($region !== 'PTPN I') {
            // Jika region bukan PTPN I, filter berdasarkan region
            $query->where('region', $region);
        }
        // Jika region = PTPN I, ambil semua stakeholder (tanpa filter)

        $stakeholders = $query->get();

        return response()->json([
            'data' => $stakeholders
        ]);
    }


    public function getDetailInstansi($id)
    {
        $instansi = DB::table('stakeholder')->where('id', $id)->first();

        if (!$instansi) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        // Bisa tambah field lain sesuai kebutuhan
        return response()->json($instansi);
    }

    

    
    
    
}
