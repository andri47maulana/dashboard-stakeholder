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
        return view('home.home',compact('datastakeholderall','datagovernance','datanongovernance','persen_datagovernance','persen_datanongovernance','dataperjanjiankerjasamaexpired','datamouexpired','dataperizinanexpired','datasertifikasiexpired'));
    }
    
    
    
}
