<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;


use DB;

class StakeholderController extends Controller
{
    public function search(Request $request)
    {
        $q = trim((string)$request->input('q', ''));
        $limit = (int)$request->input('limit', 20);
        $query = DB::table('stakeholder')->select('id','nama_instansi','nama_pic','kebun','region');
        if ($q !== '') {
            $query->where(function($w) use ($q) {
                $w->where('nama_instansi','like',"%{$q}%")
                  ->orWhere('nama_pic','like',"%{$q}%");
            });
        }
        if (auth()->user() && auth()->user()->region !== 'PTPN I HO') {
            $query->where('region', auth()->user()->region);
        }
        $items = $query->orderBy('nama_instansi')->limit($limit)->get();
        $results = $items->map(function($row){
            return [
                'id' => $row->id,
                'text' => $row->nama_instansi . ' — ' . ($row->nama_pic ?? '-') . ' (' . ($row->kebun ?? '-') . ')',
            ];
        });
        return response()->json($results);
    }
    public function dashstakeholder()
    {
        $dataallusers = DB::connection('mysql')->table('stakeholder');
            // ->whereIn('tbl_bagian.kode_bagian',$bagian_yang_komoditasnya_karet)
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
        if(Auth::user()->region === "PTPN I HO")
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
        return view('stakeholder.stakeholder',compact('dataalluser','searchregion','searchkebun','searchdesa','searchkategori','datakebun','datadesa'));
    }
    
    public function exportstakeholder() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Assuming $csvData is an array of arrays, where each sub-array represents a row in the spreadsheet
        $dataallusers = DB::connection('mysql')->table('stakeholder');
            // ->whereIn('tbl_bagian.kode_bagian',$bagian_yang_komoditasnya_karet)
            // ->whereRaw('(name like "Febri%" or password="1111")')
        $searchregion = '';
        $searchkebun = '';
        $searchdesa = "";
        $searchkategori = '';
        
        if(isset($_GET['region']) and $_GET['region']!=""){
            $searchregion = $_GET['region'];
            $dataallusers = $dataallusers->where('region','like',$_GET['region']);
        }
        if(isset($_GET['kebun']) and $_GET['kebun']!=""){
            $searchkebun = $_GET['kebun'];
            $dataallusers = $dataallusers->where('kebun','like',$_GET['kebun']);
        }
        if(isset($_GET['kategori']) and $_GET['kategori']!=""){
            $searchkategori = $_GET['kategori'];
            $dataallusers = $dataallusers->where('kategori','like',$_GET['kategori']);
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

        $csvData[] = [
            "No",
            'Nama Instansi', 
            'Region',
            'Kebun',
            'Daerah Instansi',
            'Desa',
            'Current Condition',
            'Nama PIC',
            'Jabatan PIC',
            'Nomor Kontak PIC',
            'Email',
            'Derajat Hubungan',
            'Kategori',
            // 'Tipe Stakeholder',
            'Skala kekuatan',
            'Skala Kepentingan',
            'Ekspektasi PTPN',
            'Ekspektasi Stakeholder'
        ];
        $i=1;
        foreach ($dataallusers as $product) {
            $csvData[] = [
                $i++,
                $product->nama_instansi,
                $product->region, 
                $product->kebun,
                $product->daerah_instansi,
                $product->desa,
                $product->curent_condition,
                $product->nama_pic,
                $product->jabatan_pic,
                $product->nomorkontak_pic,
                $product->email,
                $product->derajat_hubungan,
                $product->kategori,
                // $product->tipe_stakeholder,
                $product->skala_kekuatan,
                $product->skala_kepentingan,
                $product->ekspektasi_ptpn,
                $product->ekspektasi_stakeholder
                ];
        }
        // $csvData = // your data fetching logic here
        $rowNumber = 1;
        foreach ($csvData as $row) {
            $columnLetter = 'A';
            foreach ($row as $cellValue) {
                $sheet->setCellValue($columnLetter++ . $rowNumber, $cellValue);
            }
            $rowNumber++;
        }
        $fileName = 'stakeholder '.$searchregion.''.$searchkebun.''.$searchdesa.''.$searchkategori.''.date('Ymd').'.xlsx';
        // $fileName = 'filename.xlsx';
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ];
    
        $writer = new Xlsx($spreadsheet);
    
        $callback = function() use ($writer) {
            $writer->save('php://output');
        };
    
        return new StreamedResponse($callback, 200, $headers);
    }
    
    public function view_form_stakeholder($id = null){
        //dd($id);
        // $datauser= DB::connection('mysql')->table('stakeholder')->where('id',$id)->first();
        $datauser= DB::table('stakeholder as s')
        ->where('s.id', $id)
        ->leftJoin('wilayah as prov', 'prov.kode', '=', 's.prov_id')
        ->leftJoin('wilayah as kab', 'kab.kode', '=', 's.kab_id')
        ->leftJoin('wilayah as kec', 'kec.kode', '=', 's.kec_id')
        ->leftJoin('wilayah as desa', 'desa.kode', '=', 's.desa_id')
        ->select(
            's.*',
            'prov.nama as prov_nama',
            'kab.nama as kab_nama',
            'kec.nama as kec_nama',
            'desa.nama as desa_nama'
        )
        ->first();
        // dd($datauser);
        if($id){
            
            if(isset($datauser)){
                return view('stakeholder.form_stakeholder', compact('datauser'));
            }
            else{
                return view('stakeholder.form_stakeholder', compact('datauser'));
            }
            
        }
        else{
            return view('stakeholder.form_stakeholder', compact('datauser'));
        }
        
    }

    public function get_data_stakeholder($id = null){
        // $datauser= DB::connection('mysql')->table('stakeholder')->where('id',$id)->first();
        // return response()->json($datauser);
        $data = DB::table('stakeholder as s')
        ->where('s.id', $id)
        ->leftJoin('wilayah as prov', 'prov.kode', '=', 's.prov_id')
        ->leftJoin('wilayah as kab', 'kab.kode', '=', 's.kab_id')
        ->leftJoin('wilayah as kec', 'kec.kode', '=', 's.kec_id')
        ->leftJoin('wilayah as desa', 'desa.kode', '=', 's.desa_id')
        ->select(
            's.*',
            'prov.nama as prov_nama',
            'kab.nama as kab_nama',
            'kec.nama as kec_nama',
            'desa.nama as desa_nama'
        )
        ->first();

    return response()->json($data);
    }

    public function view_detail_stakeholder($id = null){
        //dd($id);
        // $datauser= DB::connection('mysql')->table('stakeholder')->where('id',$id)->first();
        $datauser= DB::table('stakeholder as s')
        ->where('s.id', $id)
        ->leftJoin('wilayah as prov', 'prov.kode', '=', 's.prov_id')
        ->leftJoin('wilayah as kab', 'kab.kode', '=', 's.kab_id')
        ->leftJoin('wilayah as kec', 'kec.kode', '=', 's.kec_id')
        ->leftJoin('wilayah as desa', 'desa.kode', '=', 's.desa_id')
        ->select(
            's.*',
            'prov.nama as prov_nama',
            'kab.nama as kab_nama',
            'kec.nama as kec_nama',
            'desa.nama as desa_nama'
        )
        ->first();
        // dd($datauser);
        if($id){
            
            if(isset($datauser)){
                return view('stakeholder.detail_stakeholder', compact('datauser'));
            }
            else{
                return view('stakeholder.detail_stakeholder', compact('datauser'));
            }
            
        }
        else{
            return view('stakeholder.detail_stakeholder', compact('datauser'));
        }
        
    }

    public function func_storestakeholder(Request $request){
        // dd( $request->all());
        $validate = Validator::make($request->all(), [
            'region' => 'required',
            'kebun' => 'required',
            'kategori' => 'required',
            'nama_instansi' => 'required',
            'desaw' => 'required',
            'nama_pic' => 'required',
            'jabatan_pic' => 'required',
        ]);
        
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }
        
        $addstakeholder=[
            'id'=> $request->id,
            'region'=>$request->region,
            'kebun'=>$request->kebun,
            'desa_id'=>$request->desaw,
            'curent_condition'=>$request->curent_condition,
            'nama_instansi'=>$request->nama_instansi,
            'latlong'=>$request->latlong,
            'prov_id'=>$request->provinsi,
            'kab_id'=>$request->kabupaten,
            'kec_id'=>$request->kecamatan,
            'nama_pic'=>$request->nama_pic,
            'jabatan_pic'=>$request->jabatan_pic,
            'nomorkontak_pic'=>$request->nomorkontak_pic,
            'nama_pic2'=>$request->nama_pic2,
            'jabatan_pic2'=>$request->jabatan_pic2,
            'nomorkontak_pic2'=>$request->nomorkontak_pic2,
            'derajat_hubungan'=>$request->derajat_hubungan,
            'kategori'=>$request->kategori,
            'tipe_stakeholder'=>$request->tipe_stakeholder,
            'skala_pengaruh'=>$request->skala_pengaruh,
            'skala_kepentingan'=>$request->skala_kepentingan,
            'email'=>$request->email,
            'email2'=>$request->email2,
            'ekspektasi_ptpn'=>$request->ekspektasi_ptpn,
            'ekspektasi_stakeholder'=>$request->ekspektasi_stakeholder,
            'saranbagimanajemen'=>$request->saran_bagi_manajemen,
            'hasil_skala'=>$request->keterangan_kuadran,
            'modified_date' => now(),
            'input_date' => now(),
        ];
        
        if ($request->hasFile('dokumenpendukung')) {
            
            $file = $request->file('dokumenpendukung');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('pdf'), $fileName);
            $addstakeholder['dokumenpendukung'] = $fileName;
        }
        // $id = DB::connection('mysql')->table('stakeholder')->insert($addstakeholder);
        try {
            $id = DB::connection('mysql')->table('stakeholder')->insert($addstakeholder);
            return redirect('/dash/stakeholder')->with('sukses','Berhasil Menambahkan Data Stakeholder');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
        }
    }

    public function func_updatestakeholder(Request $request){
        $idnourut = $request->id;
        $addstakeholder=[
            'region'=>$request->edit_region,
            'kebun'=>$request->edit_kebun,
            // 'desa'=>$request->edit_desa,
            'curent_condition'=>$request->edit_curent_condition,
            'nama_instansi'=>$request->edit_nama_instansi,
            'latlong'=>$request->latlong,
            // 'daerah_instansi'=>$request->edit_daerah_instansi,
            'nama_pic'=>$request->edit_nama_pic,
            'jabatan_pic'=>$request->edit_jabatan_pic,
            'nomorkontak_pic'=>$request->edit_nomorkontak_pic,
            // 'derajat_hubungan'=>$request->edit_derajat_hubungan,
            'kategori'=>$request->edit_kategori,
            // 'tipe_stakeholder'=>$request->edit_tipe_stakeholder,
            'skala_kepentingan'=>$request->edit_skala_kepentingan,
            'skala_pengaruh'=>$request->edit_skala_pengaruh,
            'email'=>$request->edit_email,
            'ekspektasi_ptpn'=>$request->edit_ekspektasi_ptpn,
            'ekspektasi_stakeholder'=>$request->edit_ekspektasi_stakeholder,
            // 'dokumenpendukung'=>$request->edit_dokumenpendukung,
            'nama_pic2'=>$request->edit_nama_pic2,
            'jabatan_pic2'=>$request->edit_jabatan_pic2,
            'nomorkontak_pic2'=>$request->edit_nomorkontak_pic2,
            'saranbagimanajemen'=>$request->edit_saran_bagi_manajemen,
            'prov_id'=>$request->edit_provinsi,
            'kab_id'=>$request->edit_kabupaten,
            'kec_id'=>$request->edit_kecamatan,
            'desa_id'=>$request->edit_desaw,
            'hasil_skala'=>$request->edit_keterangan_kuadran,
            'email2'=>$request->edit_email2,
            'modified_date' => now(),

        ];
        // dd($request->hasFile('dokumenpendukung'));
        if ($request->hasFile('edit_dokumenpendukung')) {

            $file = $request->file('edit_dokumenpendukung');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('pdf'), $fileName);
            $addstakeholder['dokumenpendukung'] = $fileName;
        }
        try {
            DB::connection('mysql')->table('stakeholder')->where('id',$idnourut)->update($addstakeholder);
            return redirect('/dash/stakeholder')->with('sukses','Berhasil Merubah Data Stakeholder');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
        }
        // DB::connection('mysql')->table('stakeholder')->where('id',$idnourut)->update($addstakeholder);
        // return redirect('/dash/stakeholder');
    }

    public function func_deletestakeholder($id = null){
        DB::connection('mysql')->table('stakeholder')->where('id',$id)->delete();
        return redirect('/dash/stakeholder')->with('suksesdelete','Berhasil Menghapus Data Stakeholder');

    }

    Public function getKebunByRegion(Request $request)
    {
        $region = $request->region;

        $kebun = DB::table('tb_unit')
            ->select('id', 'unit')
            ->where('region', $region)
            ->orderBy('unit', 'asc')
            ->get();

        return response()->json($kebun);
    }
    
}
