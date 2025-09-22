<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class UserController extends Controller
{
    public function index(){
        $dataalluser = DB::table('users')
        //->where('username','like','hangga%')
        //->leftjoin('tbl_bagian', function($join){
          //  $join->on('users.bagian','=','tbl_bagian.bagian');
            //$join->on('users.kota','=','tbl_bagian.kota');
        //})
        //->where('password','12345')
        //->whereIn('tbl.bagian.kode_bagian',$bagiankhusus)
        //->whereRaw('(username like "%Hendry%" or password ="12345")')
        
        ->get();
        //dd($dataalluser);
        $data['dataalluser']=$dataalluser;
        $status=0;
        //ini pakai compact
        return view('user.user',compact('dataalluser'));
        //ini pakai array
        //return view('user.user',$data);

    }

    public function view_form_user($id = null){
        //dd($id);
        $datauser= DB::connection('mysql')->table('users')->where('id',$id)->first();
        if($id){
            
            if(isset($datauser)){
                return view('user.form_user', compact('datauser'));
            }
            else{
                return view('user.form_user', compact('datauser'));
            }
            
        }
        else{
            return view('user.form_user', compact('datauser'));
        }
        
    }

    public function func_storeuser(Request $request){
        // dd($request->name);
        $adduser=[
            'name'=> $request->name,
            'username'=>$request->username,
            'region'=>$request->region,
            'hakakses'=>$request->hakakses,
            'password'=>bcrypt(12345)
        ];
        try {
            DB::connection('mysql')->table('users')->insert($adduser);
            return redirect('/user/index')->with('sukses','Berhasil Menambahkan Data User');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Gagal Menambahkan Data User: ' . $e->getMessage()])->withInput();
        }
    }

    public function func_updateuser(Request $request){
        $idnourut = $request->id;
        $adduser=[
            'name'=> $request->name,
            'username'=>$request->username,
            'region'=>$request->region,
            'hakakses'=>$request->hakakses,
            // 'password'=>bcrypt($request->password)
        ];
        try {
            DB::connection('mysql')->table('users')->where('id',$idnourut)->update($adduser);
            return redirect('/user/index')->with('sukses','Berhasil Merubah Data User');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Gagal Update Data User: ' . $e->getMessage()])->withInput();
        }
    }
    public function func_updatepassword(Request $request){
        $idnourut = $request->id;
        $adduser=[
            'password'=>bcrypt($request->password)
        ];
        if($request->password == $request->password2){
            try {
                DB::connection('mysql')->table('users')->where('id',$idnourut)->update($adduser);
                return redirect('/user/index')->with('sukses','Berhasil Merubah Data Password');
            } catch (\Exception $e) {
                return back()->withErrors(['message' => 'Gagal Update Password: ' . $e->getMessage()])->withInput();
            }
        }else{
            return back();
        }
    }

    public function func_deleteuser($id = null){
        try {
            DB::connection('mysql')->table('users')->where('id',$id)->delete();
            return redirect('/user/index')->with('sukses','Berhasil Menghapus Data User');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Gagal Menghapus User: ' . $e->getMessage()])->withInput();
        }
    }
    
}
