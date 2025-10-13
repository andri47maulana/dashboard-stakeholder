<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

use DB;

class MasterDataController extends Controller
{
    public function dashkebun()
    {
        $dataallusers = DB::connection('mysql')->table('kebun');
            // ->whereRaw('(name like "Febri%" or password="1111")')
        $searchregion = "";
        $searchkebun = "";

        $datakebun = DB::table('kebun')->select('nama_kebun')->get();
        
        if(isset($_COOKIE['region']) and $_COOKIE['region']!=""){
            $searchregion = $_COOKIE['region'];
            $dataallusers = $dataallusers->where('regional','like',$_COOKIE['region']);
        }
        if(isset($_COOKIE['kebun']) and $_COOKIE['kebun']!=""){
            $searchkebun = $_COOKIE['kebun'];
            $dataallusers = $dataallusers->where('nama_kebun','like',$_COOKIE['kebun']);
        }

        if(Auth::user()->hakakses =='Admin')
        {
            $dataallusers = $dataallusers;
        }
        else
        {
            $datakebun = DB::table('kebun')->select('nama_kebun')->groupBy('nama_kebun')->where('region',Auth::user()->region)->get();
            $dataallusers = $dataallusers->where('region',Auth::user()->region);
        }

        $dataallusers = $dataallusers->get();

        

        $dataalluser= $dataallusers;
            // dd($dataalluser);
            // $status = 0;
            // return view('import.importproduksikaret', compact('data','status'));
        return view('masterdata.kebun',compact('dataalluser','searchregion','searchkebun','datakebun'));
    }

    public function exportkebun()
    {
        $dataallusers = DB::connection('mysql')->table('stakeholder');
            // ->whereIn('tbl_bagian.kode_bagian',$bagian_yang_komoditasnya_karet)
            // ->whereRaw('(name like "Febri%" or password="1111")')
        $searchregion = "";
        $searchkebun = "";

        
        if(isset($_COOKIE['region']) and $_COOKIE['region']!=""){
            $searchregion = $_COOKIE['region'];
            $dataallusers = $dataallusers->where('regional','like',$_COOKIE['region']);
        }
        if(isset($_COOKIE['kebun']) and $_COOKIE['kebun']!=""){
            $searchkebun = $_COOKIE['kebun'];
            $dataallusers = $dataallusers->where('nama_kebun','like',$_COOKIE['kebun']);
        }
        
        $dataallusers = $dataallusers->get();

        // $products = Product::all();
        $csvFileName = 'dokumen '.$searchregion.''.$searchkebun.''.date('Ymd').'.csv';
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
            'Kode Plant', 
            'Nama Kebun',
            'Regional',
            'Provinsi',
            'Kabupaten'
        ]); // Add more headers as needed
        $i=1;
        foreach ($dataallusers as $product) {
            fputcsv($handle, [
                $i++,
                $product->regional,
                $product->nama_kebun,
                $product->region, 
                $product->provinsi,
                $product->kabupaten
                ]
            ); // Add more fields as needed
        }

        fclose($handle);

        return Response::make('', 200, $headers);
    }

    public function get_data_kebun($id = null){
        $datauser= DB::connection('mysql')->table('kebun')->where('id',$id)->first();
        return response()->json($datauser);
    }

    public function view_detail_kebun($id = null){
        //dd($id);
        $datauser= DB::connection('mysql')->table('kebun')->where('id',$id)->first();
        if($id){
            
            if(isset($datauser)){
                return view('masterdata.detail_kebun', compact('datauser'));
            }
            else{
                return view('masterdata.detail_kebun', compact('datauser'));
            }
            
        }
        else{
            return view('masterdata.detail_kebun', compact('datauser'));
        }
        
    }

    public function func_storekebun(Request $request){
        $validate = Validator::make($request->all(), [
            'nama_kebun' => 'required',
            'provinsi' => 'required'          
        ]);
        
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }

        $adddokumen=[
            'id'=> $request->id,
            'kode_plant'=>$request->kode_plant,
            'nama_kebun'=>$request->nama_kebun,
            'regional'=>$request->region,
            'provinsi'=>$request->provinsi,
            'kabupaten'=>$request->kabupaten
        ];
        // $id = DB::connection('mysql')->table('stakeholder')->insert($addstakeholder);
        try {
            $id = DB::connection('mysql')->table('kebun')->insert($adddokumen);
            return redirect('/masterdata/kebun')->with('sukses','Berhasil Menambahkan Data Kebun');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
        }
    }

    public function func_updatekebun(Request $request){
        $idnourut = $request->id;
        $adddokumen=[
            'kode_plant'=>$request->kode_plant,
            'nama_kebun'=>$request->nama_kebun,
            'regional'=>$request->region,
            'provinsi'=>$request->provinsi,
            'kabupaten'=>$request->kabupaten
        ];
        try {
            DB::connection('mysql')->table('kebun')->where('id',$idnourut)->update($adddokumen);
            return redirect('/masterdata/kebun')->with('sukses','Berhasil Merubah Data Kebun');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
        }
        // DB::connection('mysql')->table('stakeholder')->where('id',$idnourut)->update($addstakeholder);
        // return redirect('/dash/stakeholder');
    }

    public function func_deletekebun($id = null){
        DB::connection('mysql')->table('kebun')->where('id',$id)->delete();
        return redirect('/masterdata/kebun')->with('suksesdelete','Berhasil Menghapus Data Kebun');

    }
    
   // -------------- MASTER DATA PERIZINAN

   public function dashperizinan()
    {
        $dataallusers = DB::connection('mysql')->table('m_perizinan');
            // ->whereRaw('(name like "Febri%" or password="1111")')
        $searchperizinan = "";
        $searchnama = "";

        $datajenisperizinan = DB::table('m_perizinan')->select('jenis_perizinan')->groupBy('jenis_perizinan')->get();
        $datanamaperizinan = DB::table('m_perizinan')->select('nama')->get();
        
        if(isset($_COOKIE['jenis_perizinan']) and $_COOKIE['jenis_perizinan']!=""){
            $searchperizinan = $_COOKIE['jenis_perizinan'];
            $dataallusers = $dataallusers->where('jenis_perizinan','like',$_COOKIE['jenis_perizinan']);
        }

        $dataallusers = $dataallusers->get();

        $dataalluser= $dataallusers;

        return view('masterdata.perizinan',compact('dataalluser','datajenisperizinan','datanamaperizinan','searchperizinan'));
    }

    public function exportperizinan()
    {
        $dataallusers = DB::connection('mysql')->table('m_perizinan');
            // ->whereIn('tbl_bagian.kode_bagian',$bagian_yang_komoditasnya_karet)
            // ->whereRaw('(name like "Febri%" or password="1111")')
        $searchperizinan = "";

        $datajenisperizinan = DB::table('m_perizinan')->select('nama')->get();
            
        if(isset($_COOKIE['perizinan']) and $_COOKIE['perizinan']!=""){
            $searchperizinan = $_COOKIE['perizinan'];
            $dataallusers = $dataallusers->where('nama','like',$_COOKIE['perizinan']);
        }
        $dataallusers = $dataallusers->get();

        // $products = Product::all();
        $csvFileName = 'master_perizinan '.$searchperizinan.''.date('Ymd').'.csv';
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
            'Nama', 
            'Status',
            'Jenis Perizinan'
        ]); // Add more headers as needed
        $i=1;
        foreach ($dataallusers as $product) {
            fputcsv($handle, [
                $i++,
                $product->nama,
                $product->status, 
                $product->jenis_perizinan
                ]
            ); // Add more fields as needed
        }

        fclose($handle);

        return Response::make('', 200, $headers);
    }

    public function get_data_perizinan($id = null){
        $datauser= DB::connection('mysql')->table('m_perizinan')->where('id',$id)->first();
        return response()->json($datauser);
    }

    public function view_detail_perizinan($id = null){
        //dd($id);
        $datauser= DB::connection('mysql')->table('m_perizinan')->where('id',$id)->first();
        if($id){
            
            if(isset($datauser)){
                return view('masterdata.detail_perizinan', compact('datauser'));
            }
            else{
                return view('masterdata.detail_perizinan', compact('datauser'));
            }
            
        }
        else{
            return view('masterdata.detail_perizinan', compact('datauser'));
        }
        
    }

    public function func_storeperizinan(Request $request){
        $validate = Validator::make($request->all(), [
            'nama' => 'required',
            'jenis_perizinan' => 'required'         
        ]);
        
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }

        $adddokumen=[
            'id'=> $request->id,
            'nama'=>$request->nama,
            'status'=>1,
            'jenis_perizinan'=>$request->jenis_perizinan
        ];
        // $id = DB::connection('mysql')->table('stakeholder')->insert($addstakeholder);
        try {
            $id = DB::connection('mysql')->table('m_perizinan')->insert($adddokumen);
            return redirect('/masterdata/perizinan')->with('sukses','Berhasil Menambahkan Data Perizinan');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
        }
    }

    public function func_updateperizinan(Request $request){
        $idnourut = $request->id;
        $adddokumen=[
            'nama'=>$request->nama,
            'status'=>1,
            'jenis_perizinan'=>$request->jenis_perizinan
        ];
        try {
            DB::connection('mysql')->table('m_perizinan')->where('id',$idnourut)->update($adddokumen);
            return redirect('/masterdata/perizinan')->with('sukses','Berhasil Merubah Data Perizinan');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
        }
        // DB::connection('mysql')->table('stakeholder')->where('id',$idnourut)->update($addstakeholder);
        // return redirect('/dash/stakeholder');
    }

    public function func_deleteperizinan(Request $request){
        $idnourut = $request->id;
        $adddokumen=[
            'nama'=>$request->nama,
            'status'=>0,
            'jenis_perizinan'=>$request->jenis_perizinan
        ];
        try {
            DB::connection('mysql')->table('m_perizinan')->where('id',$idnourut)->update($adddokumen);
            return redirect('/masterdata/perizinan')->with('sukses','Berhasil Menghapus Data Perizinan');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
        }

    }

    // -------------- MASTER DATA SERTIFIKASI ------------------------------

   public function dashsertifikasi()
   {
       $dataallusers = DB::connection('mysql')->table('m_sertifikasi');
           // ->whereRaw('(name like "Febri%" or password="1111")')
        $searchsertifikasi = "";

        $datasertifikasi = DB::table('m_sertifikasi')->select('nama')->get();
               
        if(isset($_COOKIE['sertifikasi']) and $_COOKIE['sertifikasi']!=""){
            $searchsertifikasi = $_COOKIE['sertifikasi'];
            $dataallusers = $dataallusers->where('nama','like',$_COOKIE['sertifikasi']);
        }

       $dataallusers = $dataallusers->get();

       $dataalluser= $dataallusers;
           // dd($dataalluser);
           // $status = 0;
           // return view('import.importproduksikaret', compact('data','status'));
       return view('masterdata.sertifikasi',compact('dataalluser','datasertifikasi'));
   }

   public function exportsertifikasi()
   {
       $dataallusers = DB::connection('mysql')->table('m_sertifikasi');
           // ->whereIn('tbl_bagian.kode_bagian',$bagian_yang_komoditasnya_karet)
           // ->whereRaw('(name like "Febri%" or password="1111")')
           $searchsertifikasi = "";
   
           $datasertifikasi = DB::table('m_sertifikasi')->select('nama')->get();
           
           if(isset($_COOKIE['sertifikasi']) and $_COOKIE['sertifikasi']!=""){
               $searchsertifikasi = $_COOKIE['sertifikasi'];
               $dataallusers = $dataallusers->where('nama','like',$_COOKIE['sertifikasi']);
           }
   
       $dataallusers = $dataallusers->get();

       // $products = Product::all();
       $csvFileName = 'dokumen '.$searchsertifikasi.''.date('Ymd').'.csv';
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
           'Nama', 
           'Status'
       ]); // Add more headers as needed
       $i=1;
       foreach ($dataallusers as $product) {
           fputcsv($handle, [
               $i++,
               $product->nama,
               $product->status
               ]
           ); // Add more fields as needed
       }

       fclose($handle);

       return Response::make('', 200, $headers);
   }

   public function get_data_sertifikasi($id = null){
       $datauser= DB::connection('mysql')->table('m_sertifikasi')->where('id',$id)->first();
       return response()->json($datauser);
   }

   public function view_detail_sertifikasi($id = null){
       //dd($id);
       $datauser= DB::connection('mysql')->table('m_sertifikasi')->where('id',$id)->first();
       if($id){
           
           if(isset($datauser)){
               return view('masterdata.detail_sertifikasi', compact('datauser'));
           }
           else{
               return view('masterdata.detail_sertifikasi', compact('datauser'));
           }
           
       }
       else{
           return view('masterdata.detail_sertifikasi', compact('datauser'));
       }
       
   }

   public function func_storesertifikasi(Request $request){
       $validate = Validator::make($request->all(), [
           'nama' => 'required'         
       ]);
       
       if($validate->fails()){
           return back()->withErrors($validate->errors())->withInput();
       }

       $adddokumen=[
           'id'=> $request->id,
           'nama'=>$request->nama,
           'status'=>1
           
       ];
       // $id = DB::connection('mysql')->table('stakeholder')->insert($addstakeholder);
       try {
           $id = DB::connection('mysql')->table('m_sertifikasi')->insert($adddokumen);
           return redirect('/masterdata/sertifikasi')->with('sukses','Berhasil Menambahkan Data Sertifikasi');
       } catch (\Exception $e) {
           return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
       }
   }

   public function func_updatesertifikasi(Request $request){
       $idnourut = $request->id;
       $adddokumen=[
           'nama'=>$request->nama,
           'status'=>1
       ];
       try {
           DB::connection('mysql')->table('m_sertifikasi')->where('id',$idnourut)->update($adddokumen);
           return redirect('/masterdata/sertifikasi')->with('sukses','Berhasil Merubah Data Sertifikasi');
       } catch (\Exception $e) {
           return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
       }
       // DB::connection('mysql')->table('stakeholder')->where('id',$idnourut)->update($addstakeholder);
       // return redirect('/dash/stakeholder');
   }

   public function func_deletesertifikasi  (Request $request){
    $idnourut = $request->id;
    $adddokumen=[
        'nama'=>$request->nama,
        'status'=>0
    ];
    try {
        DB::connection('mysql')->table('m_sertifikasi')->where('id',$idnourut)->update($adddokumen);
        return redirect('/masterdata/sertifikasi')->with('sukses','Berhasil Menghapus Data Sertifikasi');
    } catch (\Exception $e) {
        return back()->withErrors(['message' => 'Unable to insert data: ' . $e->getMessage()])->withInput();
    }

   }


   public function data_kebun()
    {
        $user = Auth::user()->region;
        if ($user === "PTPN I HO") {
            $units = DB::table('tb_unit')->get(); // query langsung ke tabel units
            $regions = DB::table('tb_unit')->select('region')->whereNotNull('region')->groupBy('region')->orderBy('region')->get();
        } else {
            $units = DB::table('tb_unit')->where('region', $user)->get();
            $regions = DB::table('tb_unit')->select('region')->where('region',$user)->groupBy('region')->orderBy('region')->get();
        }
        return view('masterdata/data_kebun', compact('units','regions'));
    }

    public function detail_unit($id)
    {
        // ambil unit berdasarkan id
        $unit = DB::table('tb_unit')->where('id', $id)->first();

        // ambil data kebun_json berdasarkan unit_id
        $kebunJsons = DB::table('kebun_json')->where('unit_id', $id)->get();

        // decode JSON agar bisa dipakai di view
        foreach ($kebunJsons as $kebun) {
            $kebun->decoded = json_decode($kebun->json, true);
        }

        return view('masterdata/detail_unit', compact('unit', 'kebunJsons'));
    }

    // Update Unit (tb_unit) name/region
    public function update_unit(Request $request, $id)
    {
        $request->validate([
            'unit' => 'required|string|max:255',
            'region' => 'required|string|max:255',
        ]);

        try {
            DB::table('tb_unit')->where('id', $id)->update([
                'unit' => $request->input('unit'),
                'region' => $request->input('region'),
            ]);
            return redirect()->route('units.list')->with('success', 'Unit berhasil diperbarui');
        } catch (\Throwable $e) {
            return back()->withErrors(['message' => 'Gagal memperbarui unit: '.$e->getMessage()])->withInput();
        }
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'unit_id' => 'required|exists:tb_unit,id',
            'polygon_type' => 'required|in:url,json',
            'polygon_url' => 'required_if:polygon_type,url|nullable|url',
            'polygon_json' => 'required_if:polygon_type,json|nullable|string',
            'judul' => 'required|string',
        ]);

        $jsonData = null;

        if($request->polygon_type === 'url'){
            // Ambil JSON dari URL
            try {
                $jsonData = file_get_contents($request->polygon_url);
                if(!$jsonData){
                    return back()->with('error', 'Gagal mengambil data dari URL.');
                }
                // Validasi JSON
                json_decode($jsonData, true);
                if(json_last_error() !== JSON_ERROR_NONE){
                    return back()->with('error', 'Data dari URL bukan JSON valid.');
                }
            } catch (\Exception $e){
                return back()->with('error', 'Error mengambil data dari URL: '.$e->getMessage());
            }
        } else {
            // Ambil JSON dari textarea
            $jsonData = $request->polygon_json;
            json_decode($jsonData, true);
            if(json_last_error() !== JSON_ERROR_NONE){
                return back()->with('error', 'Data JSON tidak valid.');
            }
        }

        // Simpan ke database
        DB::table('kebun_json')->insert([
            'unit_id' => $request->unit_id,
            'json' => $jsonData,
            'created_at' => now(),
            'updated_at' => now(),
            'title' => $request->judul,
        ]);

        return back()->with('success', 'Polygon berhasil disimpan.');
    }

    // Update polygon
    public function update(Request $request, $id)
    {
        // dd($id);
        // dd(DB::table('kebun_json')->where('id', $id)->first());
        $request->validate([
            'judul_edit' => 'required|string|max:255',
            'polygon_type_edit' => 'required|in:url,json',
            'polygon_url_edit' => 'required_if:polygon_type,url|nullable|url',
            'polygon_json_edit' => 'required_if:polygon_type,json|nullable',
        ]);

        // Siapkan data update
        $data = [
            'title' => $request->judul_edit,
            'updated_at' => now(),
        ];
        $jsonData = null;
        if ($request->polygon_type_edit === 'url') {
            // $data['json'] = json_encode(['tileurl' => $request->polygon_url_edit]);
            try {
                $jsonData = file_get_contents($request->polygon_url_edit);
                if(!$jsonData){
                    return back()->with('error', 'Gagal mengambil data dari URL.');
                }
                // Validasi JSON
                json_decode($jsonData, true);
                if(json_last_error() !== JSON_ERROR_NONE){
                    return back()->with('error', 'Data dari URL bukan JSON valid.');
                }
                $data['json'] = $jsonData;
            } catch (\Exception $e){
                return back()->with('error', 'Error mengambil data dari URL: '.$e->getMessage());
            }
        } else {
            $decoded = json_decode($request->polygon_json_edit, true);
            if ($decoded === null) {
                return back()->withErrors(['polygon_json' => 'JSON tidak valid']);
            }
            $data['json'] = json_encode($decoded);
            
        }
        // dd($data['json']);
        // dd($data);
        // Update langsung
        // DB::table('kebun_json')->where('id', $id)->update($data);
        DB::table('kebun_json')->where('id', $id)->update($data);
        // $affected =DB::connection('mysql')->table('kebun_json')->where('id',$id)->update($data);

        // if ($affected) {
        //     dd("Update berhasil, $affected baris terpengaruh");
        // } else {
        //     dd("Tidak ada baris yang berubah");
        // }


        return redirect()->back()->with('success', 'Polygon berhasil diupdate!');
    }



    // Delete polygon
    public function destroy($id)
    {
        DB::table('kebun_json')->where('id', $id)->delete();
        return back()->with('success', 'Polygon berhasil dihapus.');
    }

   
}
