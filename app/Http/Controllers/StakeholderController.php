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
        $datauser= DB::connection('mysql')->table('stakeholder')->where('id',$id)->first();
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
        $datauser= DB::connection('mysql')->table('stakeholder')->where('id',$id)->first();
        return response()->json($datauser);
    }

    public function view_detail_stakeholder($id = null){
        //dd($id);
        $datauser= DB::connection('mysql')->table('stakeholder')->where('id',$id)->first();
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
        $validate = Validator::make($request->all(), [
            'region' => 'required',
            'kebun' => 'required',
            'desa' => 'required',
            'nama_instansi' => 'required',
            'daerah_instansi' => 'required',
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
            'desa'=>$request->desa,
            'curent_condition'=>$request->curent_condition,
            'nama_instansi'=>$request->nama_instansi,
            'daerah_instansi'=>$request->daerah_instansi,
            'nama_pic'=>$request->nama_pic,
            'jabatan_pic'=>$request->jabatan_pic,
            'nomorkontak_pic'=>$request->nomorkontak_pic,
            'derajat_hubungan'=>$request->derajat_hubungan,
            'kategori'=>$request->kategori,
            'tipe_stakeholder'=>$request->tipe_stakeholder,
            'skala_kekuatan'=>$request->skala_kekuatan,
            'skala_kepentingan'=>$request->skala_kepentingan,
            'email'=>$request->email,
            'ekspektasi_ptpn'=>$request->ekspektasi_ptpn,
            'ekspektasi_stakeholder'=>$request->ekspektasi_stakeholder
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
            'region'=>$request->region,
            'kebun'=>$request->kebun,
            'desa'=>$request->desa,
            'curent_condition'=>$request->curent_condition,
            'nama_instansi'=>$request->nama_instansi,
            'daerah_instansi'=>$request->daerah_instansi,
            'nama_pic'=>$request->nama_pic,
            'jabatan_pic'=>$request->jabatan_pic,
            'nomorkontak_pic'=>$request->nomorkontak_pic,
            'derajat_hubungan'=>$request->derajat_hubungan,
            'kategori'=>$request->kategori,
            'tipe_stakeholder'=>$request->tipe_stakeholder,
            'skala_kekuatan'=>$request->skala_kekuatan,
            'skala_kepentingan'=>$request->skala_kepentingan,
            'email'=>$request->email,
            'ekspektasi_ptpn'=>$request->ekspektasi_ptpn,
            'ekspektasi_stakeholder'=>$request->ekspektasi_stakeholder
        ];
        // dd($request->hasFile('dokumenpendukung'));
        if ($request->hasFile('dokumenpendukung')) {
            

            $file = $request->file('dokumenpendukung');
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
    
}
