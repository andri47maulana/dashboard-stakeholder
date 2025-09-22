<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

use DB;

class DokumenController extends Controller
{
    public function dashperjanjiankerjasama()
    {
        $dataallusers = DB::connection('mysql')->table('dokumenkerjasama')
        ->where('dokumenkerjasama.jenis_dokumen','PKS');
            // ->whereRaw('(name like "Febri%" or password="1111")')
        $searchregion = "";
        $searchkebun = "";
        $searchdesa = "";
        $searchkategori = "";

        $datakebun = DB::table('stakeholder')->select('kebun')->groupBy('kebun')->get();
        $datadesa = DB::table('stakeholder')->select('desa')->groupBy('desa')->get();
        
        if(isset($_COOKIE['region']) and $_COOKIE['region']!=""){
            $searchregion = $_COOKIE['region'];
            $dataallusers = $dataallusers->where('region','like',$_COOKIE['region']);
        }
        if(isset($_COOKIE['kebun']) and $_COOKIE['kebun']!=""){
            $searchkebun = $_COOKIE['kebun'];
            $dataallusers = $dataallusers->where('kebun','like',$_COOKIE['kebun']);
        }
        if(isset($_COOKIE['desa']) and $_COOKIE['desa']!=""){
            $searchdesa = $_COOKIE['desa'];
            $dataallusers = $dataallusers->where('desa','like',$_COOKIE['desa']);
        }
        if(isset($_COOKIE['kategori']) and $_COOKIE['kategori']!=""){
            $searchkategori = $_COOKIE['kategori'];
            $dataallusers = $dataallusers->where('kategori','like',$_COOKIE['kategori']);
        }
        if(Auth::user()->hakakses =='Admin')
        {
            $dataallusers = $dataallusers;
        }
        else
        {
            $datakebun = DB::table('stakeholder')->select('kebun')->groupBy('kebun')->where('region',Auth::user()->region)->get();
            $datadesa = DB::table('stakeholder')->select('desa')->where('region',Auth::user()->region)->groupBy('desa')->get();
            $dataallusers = $dataallusers->where('region',Auth::user()->region);
        }

        $dataallusers = $dataallusers->get();

        

        $dataalluser= $dataallusers;
            // dd($dataalluser);
            // $status = 0;
            // return view('import.importproduksikaret', compact('data','status'));
        return view('dokumen.perjanjiankerjasama',compact('dataalluser','searchregion','searchkebun','searchdesa','searchkategori','datakebun','datadesa'));
    }

    public function dashperjanjiankerjasama_expired()
    {
        $dataallusers = DB::connection('mysql')->table('dokumenkerjasama')
        ->where('dokumenkerjasama.jenis_dokumen','PKS')
        ->whereRaw('(datediff(masa_berlaku, current_date()) < 31)');
            // ->whereRaw('(name like "Febri%" or password="1111")')
        $searchregion = "";
        $searchkebun = "";
        $searchdesa = "";
        $searchkategori = "";

        $datakebun = DB::table('stakeholder')->select('kebun')->groupBy('kebun')->get();
        $datadesa = DB::table('stakeholder')->select('desa')->groupBy('desa')->get();
        
        if(isset($_COOKIE['region']) and $_COOKIE['region']!=""){
            $searchregion = $_COOKIE['region'];
            $dataallusers = $dataallusers->where('region','like',$_COOKIE['region']);
        }
        if(isset($_COOKIE['kebun']) and $_COOKIE['kebun']!=""){
            $searchkebun = $_COOKIE['kebun'];
            $dataallusers = $dataallusers->where('kebun','like',$_COOKIE['kebun']);
        }
        if(isset($_COOKIE['desa']) and $_COOKIE['desa']!=""){
            $searchdesa = $_COOKIE['desa'];
            $dataallusers = $dataallusers->where('desa','like',$_COOKIE['desa']);
        }
        if(isset($_COOKIE['kategori']) and $_COOKIE['kategori']!=""){
            $searchkategori = $_COOKIE['kategori'];
            $dataallusers = $dataallusers->where('kategori','like',$_COOKIE['kategori']);
        }
        if(Auth::user()->hakakses =='Admin')
        {
            $dataallusers = $dataallusers;
        }
        else
        {
            $datakebun = DB::table('stakeholder')->select('kebun')->groupBy('kebun')->where('region',Auth::user()->region)->get();
            $datadesa = DB::table('stakeholder')->select('desa')->where('region',Auth::user()->region)->groupBy('desa')->get();
            $dataallusers = $dataallusers->where('region',Auth::user()->region);
        }

        $dataallusers = $dataallusers->get();

        

        $dataalluser= $dataallusers;
            // dd($dataalluser);
            // $status = 0;
            // return view('import.importproduksikaret', compact('data','status'));
        return view('dokumen.perjanjiankerjasama_expired',compact('dataalluser','searchregion','searchkebun','searchdesa','searchkategori','datakebun','datadesa'));
    }

    public function exportperjanjiankerjasama()
    {
        $dataallusers = DB::connection('mysql')->table('stakeholder');
            // ->whereIn('tbl_bagian.kode_bagian',$bagian_yang_komoditasnya_karet)
            // ->whereRaw('(name like "Febri%" or password="1111")')
        $searchregion = "";
        $searchkebun = "";
        $searchdesa = "";
        $searchkategori = "";
        
        if(isset($_COOKIE['region']) and $_COOKIE['region']!=""){
            $searchregion = $_COOKIE['region'];
            $dataallusers = $dataallusers->where('region','like',$_COOKIE['region']);
        }
        if(isset($_COOKIE['kebun']) and $_COOKIE['kebun']!=""){
            $searchkebun = $_COOKIE['kebun'];
            $dataallusers = $dataallusers->where('kebun','like',$_COOKIE['kebun']);
        }
        if(isset($_COOKIE['desa']) and $_COOKIE['desa']!=""){
            $searchdesa = $_COOKIE['desa'];
            $dataallusers = $dataallusers->where('desa','like',$_COOKIE['desa']);
        }
        if(isset($_COOKIE['kategori']) and $_COOKIE['kategori']!=""){
            $searchkategori = $_COOKIE['kategori'];
            $dataallusers = $dataallusers->where('kategori','like',$_COOKIE['kategori']);
        }
        if(Auth::user()->hakakses =='Admin')
        {
            $dataallusers = $dataallusers;
        }
        else
        {
            $dataallusers = $dataallusers->where('region',Auth::user()->region);
        }
        $dataallusers = $dataallusers->get();

        // $products = Product::all();
        $csvFileName = 'dokumen '.$searchregion.''.$searchkebun.''.$searchdesa.''.$searchkategori.''.date('Ymd').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment;  filename="' . $csvFileName . '"',
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, [
            'No',
            'Nama Instansi', 
            'Region',
            'Kebun',
            'Desa',
            'Jenis Dokumen',
            'Nomor PKS',
            'Tanggal PKS',
            'Kategori',
            'Masa Berlaku',
            'Satuan Kerja',
            'Perihal',
            'Ekspektasi Tujuan',
            'PIC Lembaga',
            'Pejabat Yang Menandatangani',
            'Pejabat Yang Menggantikan',
            'Dokumen'
        ]); // Add more headers as needed
        $i=1;
        foreach ($dataallusers as $product) {
            fputcsv($handle, [
                $i++,
                $product->nama_instansi,
                $product->region, 
                $product->kebun,
                $product->desa,
                $product->jenis_dokumen,
                $product->nomor_mou_pks,
                $product->tanggal_mou_pks,
                $product->kategori,
                $product->masa_berlaku,
                $product->satuan_kerja,
                $product->perihal,
                $product->ekspektasi_tujuan,
                $product->pic_lembaga,
                $product->pejabat_yang_menandatangani,
                $product->pejabat_yang_menggantikan,
                $product->dokumen
                ]
            ); // Add more fields as needed
        }

        fclose($handle);

        return Response::make('', 200, $headers);
    }

    public function get_data_perjanjiankerjasama($id = null){
        $datauser= DB::connection('mysql')->table('dokumenkerjasama')->where('id',$id)->first();
        return response()->json($datauser);
    }

    public function view_detail_perjanjiankerjasama($id = null){
        //dd($id);
        $datauser= DB::connection('mysql')->table('dokumenkerjasama')->where('id',$id)->first();
        if($id){
            
            if(isset($datauser)){
                return view('dokumen.detail_perjanjiankerjasama', compact('datauser'));
            }
            else{
                return view('dokumen.detail_perjanjiankerjasama', compact('datauser'));
            }
            
        }
        else{
            return view('dokumen.detail_perjanjiankerjasama', compact('datauser'));
        }
        
    }

    public function func_storeperjanjiankerjasama(Request $request){
        $validate = Validator::make($request->all(), [
            'region' => 'required',
            'kebun' => 'required',
            'desa' => 'required',
            'nama_instansi' => 'required',
            'nomor_mou_pks' => 'required',
            'tanggal_mou_pks' => 'required',
            'masa_berlaku' => 'required',
            'perihal' => 'required',
            'pejabat_yang_menandatangani' => 'required',
            'dokumen' => 'required',
        ]);
        
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }

        $adddokumen=[
            'id'=> $request->id,
            'region'=>$request->region,
            'kebun'=>$request->kebun,
            'desa'=>$request->desa,
            'jenis_dokumen'=>'PKS',
            'nama_instansi'=>$request->nama_instansi,
            'nomor_mou_pks'=>$request->nomor_mou_pks,
            'tanggal_mou_pks'=>$request->tanggal_mou_pks,
            'kategori'=>$request->kategori,
            'masa_berlaku'=>$request->masa_berlaku,
            'satuan_kerja'=>$request->satuan_kerja,
            'perihal'=>$request->perihal,
            'ekspektasi_tujuan'=>$request->ekspektasi_tujuan,
            'pic_lembaga'=>$request->pic_lembaga,
            'pejabat_yang_menandatangani'=>$request->pejabat_yang_menandatangani,
            'pejabat_yang_menggantikan'=>$request->pejabat_yang_menggantikan,
            'dokumen'=>$request->dokumen
        ];
        // $id = DB::connection('mysql')->table('stakeholder')->insert($addstakeholder);
        try {
            $id = DB::connection('mysql')->table('dokumenkerjasama')->insert($adddokumen);
            return redirect('/dokumen/perjanjiankerjasama')->with('sukses','Berhasil Menambahkan Data Perjanjian Kerjasama');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
        }
    }

    public function func_updateperjanjiankerjasama(Request $request){
        $idnourut = $request->id;
        $adddokumen=[
            'region'=>$request->region,
            'kebun'=>$request->kebun,
            'desa'=>$request->desa,
            'jenis_dokumen'=>'PKS',
            'nama_instansi'=>$request->nama_instansi,
            'nomor_mou_pks'=>$request->nomor_mou_pks,
            'tanggal_mou_pks'=>$request->tanggal_mou_pks,
            'kategori'=>$request->kategori,
            'masa_berlaku'=>$request->masa_berlaku,
            'satuan_kerja'=>$request->satuan_kerja,
            'perihal'=>$request->perihal,
            'ekspektasi_tujuan'=>$request->ekspektasi_tujuan,
            'pic_lembaga'=>$request->pic_lembaga,
            'pejabat_yang_menandatangani'=>$request->pejabat_yang_menandatangani,
            'pejabat_yang_menggantikan'=>$request->pejabat_yang_menggantikan,
            'dokumen'=>$request->dokumen
        ];
        try {
            DB::connection('mysql')->table('dokumenkerjasama')->where('id',$idnourut)->update($adddokumen);
            return redirect('/dokumen/perjanjiankerjasama')->with('sukses','Berhasil Merubah Data Perjanjian Kerjasama');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
        }
        // DB::connection('mysql')->table('stakeholder')->where('id',$idnourut)->update($addstakeholder);
        // return redirect('/dash/stakeholder');
    }

    public function func_deleteperjanjiankerjasama($id = null){
        DB::connection('mysql')->table('dokumenkerjasama')->where('id',$id)->delete();
        return redirect('/dokumen/perjanjiankerjasama')->with('suksesdelete','Berhasil Menghapus Data Perjanjian Kerjasama');

    }
    
    //------------------------ NOTA KESEPAHAMAN

    public function dashmou()
    {
        $dataallusers = DB::connection('mysql')->table('dokumenkerjasama')
            ->where('dokumenkerjasama.jenis_dokumen','MOU');
            // ->whereRaw('(name like "Febri%" or password="1111")')
        $searchregion = "";
        $searchkebun = "";
        $searchdesa = "";
        $searchkategori = "";

        $datakebun = DB::table('stakeholder')->select('kebun')->groupBy('kebun')->get();
        $datadesa = DB::table('stakeholder')->select('desa')->groupBy('desa')->get();
        
        if(isset($_COOKIE['region']) and $_COOKIE['region']!=""){
            $searchregion = $_COOKIE['region'];
            $dataallusers = $dataallusers->where('region','like',$_COOKIE['region']);
        }
        if(isset($_COOKIE['kebun']) and $_COOKIE['kebun']!=""){
            $searchkebun = $_COOKIE['kebun'];
            $dataallusers = $dataallusers->where('kebun','like',$_COOKIE['kebun']);
        }
        if(isset($_COOKIE['desa']) and $_COOKIE['desa']!=""){
            $searchdesa = $_COOKIE['desa'];
            $dataallusers = $dataallusers->where('desa','like',$_COOKIE['desa']);
        }
        if(isset($_COOKIE['kategori']) and $_COOKIE['kategori']!=""){
            $searchkategori = $_COOKIE['kategori'];
            $dataallusers = $dataallusers->where('kategori','like',$_COOKIE['kategori']);
        }
        if(Auth::user()->hakakses =='Admin')
        {
            $dataallusers = $dataallusers;
        }
        else
        {
            $datakebun = DB::table('stakeholder')->select('kebun')->groupBy('kebun')->where('region',Auth::user()->region)->get();
            $datadesa = DB::table('stakeholder')->select('desa')->where('region',Auth::user()->region)->groupBy('desa')->get();
            $dataallusers = $dataallusers->where('region',Auth::user()->region);
        }

        $dataallusers = $dataallusers->get();

        

        $dataalluser= $dataallusers;
            // dd($dataalluser);
            // $status = 0;
            // return view('import.importproduksikaret', compact('data','status'));
        return view('dokumen.mou',compact('dataalluser','searchregion','searchkebun','searchdesa','searchkategori','datakebun','datadesa'));
    }

    public function dashmou_expired()
    {
        $dataallusers = DB::connection('mysql')->table('dokumenkerjasama')
            ->where('dokumenkerjasama.jenis_dokumen','MOU')
            ->whereRaw('(datediff(masa_berlaku, current_date()) < 31)');
            // ->whereRaw('(name like "Febri%" or password="1111")')
        $searchregion = "";
        $searchkebun = "";
        $searchdesa = "";
        $searchkategori = "";

        $datakebun = DB::table('stakeholder')->select('kebun')->groupBy('kebun')->get();
        $datadesa = DB::table('stakeholder')->select('desa')->groupBy('desa')->get();
        
        if(isset($_COOKIE['region']) and $_COOKIE['region']!=""){
            $searchregion = $_COOKIE['region'];
            $dataallusers = $dataallusers->where('region','like',$_COOKIE['region']);
        }
        if(isset($_COOKIE['kebun']) and $_COOKIE['kebun']!=""){
            $searchkebun = $_COOKIE['kebun'];
            $dataallusers = $dataallusers->where('kebun','like',$_COOKIE['kebun']);
        }
        if(isset($_COOKIE['desa']) and $_COOKIE['desa']!=""){
            $searchdesa = $_COOKIE['desa'];
            $dataallusers = $dataallusers->where('desa','like',$_COOKIE['desa']);
        }
        if(isset($_COOKIE['kategori']) and $_COOKIE['kategori']!=""){
            $searchkategori = $_COOKIE['kategori'];
            $dataallusers = $dataallusers->where('kategori','like',$_COOKIE['kategori']);
        }
        if(Auth::user()->hakakses =='Admin')
        {
            $dataallusers = $dataallusers;
        }
        else
        {
            $datakebun = DB::table('stakeholder')->select('kebun')->groupBy('kebun')->where('region',Auth::user()->region)->get();
            $datadesa = DB::table('stakeholder')->select('desa')->where('region',Auth::user()->region)->groupBy('desa')->get();
            $dataallusers = $dataallusers->where('region',Auth::user()->region);
        }

        $dataallusers = $dataallusers->get();

        

        $dataalluser= $dataallusers;
            // dd($dataalluser);
            // $status = 0;
            // return view('import.importproduksikaret', compact('data','status'));
        return view('dokumen.mou_expired',compact('dataalluser','searchregion','searchkebun','searchdesa','searchkategori','datakebun','datadesa'));
    }

    public function get_data_mou($id = null){
        $datauser= DB::connection('mysql')->table('dokumenkerjasama')->where('id',$id)->first();
        return response()->json($datauser);
    }

    public function exportmou()
    {
        $dataallusers = DB::connection('mysql')->table('stakeholder');
            // ->whereIn('tbl_bagian.kode_bagian',$bagian_yang_komoditasnya_karet)
            // ->whereRaw('(name like "Febri%" or password="1111")')
        $searchregion = "";
        $searchkebun = "";
        $searchdesa = "";
        $searchkategori = "";
        
        if(isset($_COOKIE['region']) and $_COOKIE['region']!=""){
            $searchregion = $_COOKIE['region'];
            $dataallusers = $dataallusers->where('region','like',$_COOKIE['region']);
        }
        if(isset($_COOKIE['kebun']) and $_COOKIE['kebun']!=""){
            $searchkebun = $_COOKIE['kebun'];
            $dataallusers = $dataallusers->where('kebun','like',$_COOKIE['kebun']);
        }
        if(isset($_COOKIE['desa']) and $_COOKIE['desa']!=""){
            $searchdesa = $_COOKIE['desa'];
            $dataallusers = $dataallusers->where('desa','like',$_COOKIE['desa']);
        }
        if(isset($_COOKIE['kategori']) and $_COOKIE['kategori']!=""){
            $searchkategori = $_COOKIE['kategori'];
            $dataallusers = $dataallusers->where('kategori','like',$_COOKIE['kategori']);
        }
        if(Auth::user()->hakakses =='Admin')
        {
            $dataallusers = $dataallusers;
        }
        else
        {
            $dataallusers = $dataallusers->where('region',Auth::user()->region);
        }
        $dataallusers = $dataallusers->get();

        // $products = Product::all();
        $csvFileName = 'dokumen '.$searchregion.''.$searchkebun.''.$searchdesa.''.$searchkategori.''.date('Ymd').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment;  filename="' . $csvFileName . '"',
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, [
            'No',
            'Nama Instansi', 
            'Region',
            'Kebun',
            'Desa',
            'Jenis Dokumen',
            'Nomor PKS',
            'Tanggal PKS',
            'Kategori',
            'Masa Berlaku',
            'Satuan Kerja',
            'Perihal',
            'Ekspektasi Tujuan',
            'PIC Lembaga',
            'Pejabat Yang Menandatangani',
            'Pejabat Yang Menggantikan',
            'Dokumen'
        ]); // Add more headers as needed
        $i=1;
        foreach ($dataallusers as $product) {
            fputcsv($handle, [
                $i++,
                $product->nama_instansi,
                $product->region, 
                $product->kebun,
                $product->desa,
                $product->jenis_dokumen,
                $product->nomor_mou_pks,
                $product->tanggal_mou_pks,
                $product->kategori,
                $product->masa_berlaku,
                $product->satuan_kerja,
                $product->perihal,
                $product->ekspektasi_tujuan,
                $product->pic_lembaga,
                $product->pejabat_yang_menandatangani,
                $product->pejabat_yang_menggantikan,
                $product->dokumen
                ]
            ); // Add more fields as needed
        }

        fclose($handle);

        return Response::make('', 200, $headers);
    }

    public function view_detail_mou($id = null){
        //dd($id);
        $datauser= DB::connection('mysql')->table('dokumenkerjasama')->where('id',$id)->first();
        if($id){
            
            if(isset($datauser)){
                return view('dokumen.detail_perjanjiankerjasama', compact('datauser'));
            }
            else{
                return view('dokumen.detail_perjanjiankerjasama', compact('datauser'));
            }
            
        }
        else{
            return view('dokumen.detail_perjanjiankerjasama', compact('datauser'));
        }
        
    }

    public function func_storemou(Request $request){
        $validate = Validator::make($request->all(), [
            'region' => 'required',
            'kebun' => 'required',
            'desa' => 'required',
            'nama_instansi' => 'required',
            'nomor_mou_pks' => 'required',
            'tanggal_mou_pks' => 'required',
            'masa_berlaku' => 'required',
            'perihal' => 'required',
            'pejabat_yang_menandatangani' => 'required',
            'dokumen' => 'required',
        ]);
        
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }

        $adddokumen=[
            'id'=> $request->id,
            'region'=>$request->region,
            'kebun'=>$request->kebun,
            'desa'=>$request->desa,
            'jenis_dokumen'=>'MOU',
            'nama_instansi'=>$request->nama_instansi,
            'nomor_mou_pks'=>$request->nomor_mou_pks,
            'tanggal_mou_pks'=>$request->tanggal_mou_pks,
            'kategori'=>$request->kategori,
            'masa_berlaku'=>$request->masa_berlaku,
            'satuan_kerja'=>$request->satuan_kerja,
            'perihal'=>$request->perihal,
            'ekspektasi_tujuan'=>$request->ekspektasi_tujuan,
            'pic_lembaga'=>$request->pic_lembaga,
            'pejabat_yang_menandatangani'=>$request->pejabat_yang_menandatangani,
            'pejabat_yang_menggantikan'=>$request->pejabat_yang_menggantikan,
            'dokumen'=>$request->dokumen
        ];
        // $id = DB::connection('mysql')->table('stakeholder')->insert($addstakeholder);
        try {
            $id = DB::connection('mysql')->table('dokumenkerjasama')->insert($adddokumen);
            return redirect('/dokumen/mou')->with('sukses','Berhasil Menambahkan Data Perjanjian Kerjasama');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
        }
    }

    public function func_updatemou(Request $request){
        $idnourut = $request->id;
        $adddokumen=[
            'region'=>$request->region,
            'kebun'=>$request->kebun,
            'desa'=>$request->desa,
            'jenis_dokumen'=>'PKS',
            'nama_instansi'=>$request->nama_instansi,
            'nomor_mou_pks'=>$request->nomor_mou_pks,
            'tanggal_mou_pks'=>$request->tanggal_mou_pks,
            'kategori'=>$request->kategori,
            'masa_berlaku'=>$request->masa_berlaku,
            'satuan_kerja'=>$request->satuan_kerja,
            'perihal'=>$request->perihal,
            'ekspektasi_tujuan'=>$request->ekspektasi_tujuan,
            'pic_lembaga'=>$request->pic_lembaga,
            'pejabat_yang_menandatangani'=>$request->pejabat_yang_menandatangani,
            'pejabat_yang_menggantikan'=>$request->pejabat_yang_menggantikan,
            'dokumen'=>$request->dokumen
        ];
        try {
            DB::connection('mysql')->table('dokumenkerjasama')->where('id',$idnourut)->update($adddokumen);
            return redirect('/dokumen/mou')->with('sukses','Berhasil Merubah Data Perjanjian Kerjasama');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
        }
        // DB::connection('mysql')->table('stakeholder')->where('id',$idnourut)->update($addstakeholder);
        // return redirect('/dash/stakeholder');
    }

    public function func_deletemou($id = null){
        DB::connection('mysql')->table('dokumenkerjasama')->where('id',$id)->delete();
        return redirect('/dokumen/mou')->with('suksesdelete','Berhasil Menghapus Data Perjanjian Kerjasama');

    }

    // ------------------------ PERIZINAN -----------------------------------------------
    public function dashperizinan()
    {
        $dataallusers = DB::connection('mysql')->table('perizinan');
        $searchregion = "";
        $searchkebun = "";
        $searchjenisperizinan = "Perizinan";
        $items = DB::table('kebun')->pluck('nama_kebun','id');

        $datakebunall = DB::table('kebun')->get();

        $m_perizinan = DB::table('m_perizinan')->where('jenis_perizinan','like','Perizinan')->get();
        
        if(isset($_COOKIE['region']) and $_COOKIE['region']!=""){
            $searchregion = $_COOKIE['region'];
            $dataallusers = $dataallusers->where('region','like',$_COOKIE['region']);
            $datakebunall = DB::table('kebun')->where('regional','like',$_COOKIE['region'])->get();

        }
        if(isset($_COOKIE['kebun']) and $_COOKIE['kebun']!=""){
            $searchkebun = $_COOKIE['kebun'];
            $dataallusers = $dataallusers->where('kebun','like',$_COOKIE['kebun']);
            $datakebunall = DB::table('kebun')->where('nama_kebun','like',$_COOKIE['kebun'])->get();
        }
        if(isset($_COOKIE['jenis_perizinan']) and $_COOKIE['jenis_perizinan']!=""){
            $searchjenisperizinan = $_COOKIE['jenis_perizinan'];
            $m_perizinan = DB::table('m_perizinan')->where('jenis_perizinan','like',$_COOKIE['jenis_perizinan'])->get();
            // dd($_COOKIE['jenis_perizinan']);
        }
        

        $dataallusers = $dataallusers->get();
        $dataalluser= $dataallusers;
        return view('dokumen.perizinan',compact('dataalluser','searchregion','searchkebun','searchjenisperizinan','datakebunall','items','m_perizinan'));
    }

    public function dashperizinan_expired()
    {
        $dataallusers = DB::connection('mysql')->table('perizinan')
        ->whereRaw('(datediff(tanggal_end, current_date()) < 31)');
        $datakebun = DB::table('stakeholder')->select('kebun')->groupBy('kebun')->get();
        $searchregion = "";
        $searchkebun = "";
        $searchjenisperizinan = "";
        
        if(isset($_COOKIE['region']) and $_COOKIE['region']!=""){
            $searchregion = $_COOKIE['region'];
            $dataallusers = $dataallusers->where('region','like',$_COOKIE['region']);
            $datakebunall = DB::table('kebun')->where('regional','like',$_COOKIE['region'])->get();

        }
        if(isset($_COOKIE['kebun']) and $_COOKIE['kebun']!=""){
            $searchkebun = $_COOKIE['kebun'];
            $dataallusers = $dataallusers->where('kebun','like',$_COOKIE['kebun']);
            $datakebunall = DB::table('kebun')->where('nama_kebun','like',$_COOKIE['kebun'])->get();
        }
        if(isset($_COOKIE['jenis_perizinan']) and $_COOKIE['jenis_perizinan']!=""){
            $searchjenisperizinan = $_COOKIE['jenis_perizinan'];
            $m_perizinan = DB::table('m_perizinan')->where('jenis_perizinan','like',$_COOKIE['jenis_perizinan'])->get();
        }

        $dataallusers = $dataallusers->get();
        $dataalluser= $dataallusers;
        return view('dokumen.perizinan_expired',compact('dataalluser','searchregion','searchkebun','searchjenisperizinan','datakebun'));
    }

    public function get_data_perizinan($id = null){
        $datauser= DB::connection('mysql')->table('perizinan')->where('id',$id)->first();
        return response()->json($datauser);
    }

    public function func_updateperizinan(Request $request){
        $idnourut = $request->id;
        $adddokumen=[
            'nomor'=>$request->nomor,
            'tanggal_start'=>$request->tanggal_start,
            'tanggal_end'=>$request->tanggal_end,
            'dokumen'=>$request->dokumen
        ];
        try {
            DB::connection('mysql')->table('perizinan')->where('id',$idnourut)->update($adddokumen);
            return redirect('/dokumen/detail_perizinan')->with('sukses','Berhasil Merubah Data Perjanjian Kerjasama');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
        }
        // DB::connection('mysql')->table('stakeholder')->where('id',$idnourut)->update($addstakeholder);
        // return redirect('/dash/stakeholder');
    }

    public function view_detail_perizinan($id = null){
        //dd($id);
        $m_perizinan = DB::table('m_perizinan')->get();
        $datauser= DB::table('kebun')->where('kode_plant',$id)->first();
        if(isset($datauser->nama_kebun)){
            $perizinan = DB::table('perizinan')->where('kebun',$datauser->nama_kebun)->get();
            return view('dokumen.detail_perizinan', compact('m_perizinan','datauser','perizinan'));     
        }  
        else{
            return back();
        }
    }

    // ------------------------ SERTIFIKASI -----------------------------------------------
    public function dashsertifikasi()
    {
        $dataallusers = DB::connection('mysql')->table('sertifikasi');
        $searchregion = "";
        $searchkebun = "";
        $searchjenisperizinan = "Perizinan";
        $items = DB::table('kebun')->pluck('nama_kebun','id');

        $datakebunall = DB::table('kebun')->get();

        $m_perizinan = DB::table('m_sertifikasi')->get();
        
        if(isset($_COOKIE['region']) and $_COOKIE['region']!=""){
            $searchregion = $_COOKIE['region'];
            $dataallusers = $dataallusers->where('region','like',$_COOKIE['region']);
            $datakebunall = DB::table('kebun')->where('regional','like',$_COOKIE['region'])->get();

        }
        if(isset($_COOKIE['kebun']) and $_COOKIE['kebun']!=""){
            $searchkebun = $_COOKIE['kebun'];
            $dataallusers = $dataallusers->where('kebun','like',$_COOKIE['kebun']);
            $datakebunall = DB::table('kebun')->where('nama_kebun','like',$_COOKIE['kebun'])->get();
        }
        

        $dataallusers = $dataallusers->get();
        $dataalluser= $dataallusers;
        return view('dokumen.sertifikasi',compact('dataalluser','searchregion','searchkebun','searchjenisperizinan','datakebunall','items','m_perizinan'));
    }


    public function dashsertifikasi_expired()
    {
        $dataallusers = DB::connection('mysql')->table('sertifikasi')
        ->whereRaw('(datediff(tanggal_end, current_date()) < 31)');
        $datakebun = DB::table('stakeholder')->select('kebun')->groupBy('kebun')->get();
        $searchregion = "";
        $searchkebun = "";
        $searchkategori = "";
        
        if(isset($_COOKIE['region']) and $_COOKIE['region']!=""){
            $searchregion = $_COOKIE['region'];
            $dataallusers = $dataallusers->where('region','like',$_COOKIE['region']);
        }
        if(isset($_COOKIE['kebun']) and $_COOKIE['kebun']!=""){
            $searchkebun = $_COOKIE['kebun'];
            $dataallusers = $dataallusers->where('kebun','like',$_COOKIE['kebun']);
        }
        if(isset($_COOKIE['desa']) and $_COOKIE['desa']!=""){
            $searchdesa = $_COOKIE['desa'];
            $dataallusers = $dataallusers->where('desa','like',$_COOKIE['desa']);
        }
        if(isset($_COOKIE['kategori']) and $_COOKIE['kategori']!=""){
            $searchkategori = $_COOKIE['kategori'];
            $dataallusers = $dataallusers->where('kategori','like',$_COOKIE['kategori']);
        }
        if(Auth::user()->hakakses =='Admin')
        {
            $dataallusers = $dataallusers;
        }
        else
        {
            $datakebun = DB::table('sertifikasi')->select('kebun')->groupBy('kebun')->where('region',Auth::user()->region)->get();
            $datadesa = DB::table('sertifikasi')->select('desa')->where('region',Auth::user()->region)->groupBy('desa')->get();
            $dataallusers = $dataallusers->where('region',Auth::user()->region);
        }

        $dataallusers = $dataallusers->get();

        

        $dataalluser= $dataallusers;
        return view('dokumen.sertifikasi_expired',compact('dataalluser','searchregion','searchkebun','searchkategori','datakebun'));
    }
    public function view_detail_sertifikasi($id = null){
        //dd($id);
        $m_perizinan = DB::table('m_sertifikasi')->get();
        $datauser= DB::table('kebun')->where('kode_plant',$id)->first();
        if(isset($datauser->nama_kebun)){
            $perizinan = DB::table('sertifikasi')->where('kebun',$datauser->nama_kebun)->get();
            return view('dokumen.detail_sertifikasi', compact('m_perizinan','datauser','perizinan'));     
        }  
        else{
            return back();
        }
    }

    
}
