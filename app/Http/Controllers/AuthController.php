<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;
use Auth;

class AuthController extends Controller
{
    public function index(){
        if(Auth::check()){
            return redirect('/dashboard');

        }
        else {
            return view('login.index');
        }
       
    }
    public function func_login(Request $request){
        $validate = Validator::make($request->all(), [
            'username' => 'required|min:5',
            'password' => 'required',
        ],[
            'username.required' => 'Username is a must.',
            'username.required' => 'Password is a must.',
            'username.min' => 'Username must have min 5 char.',
        ]);
        if($validate->fails()){
            return back()->withErrors($validate->errors());
        }

        $credentials = [
            'username' => $request['username'],
            'password' => $request['password'],
        ];
        
        if(Auth::attempt($credentials)){
           
            return redirect('/dashboard');
        }
        else {
            return back()->with('gagal','Username atau Password salah !');
        }
    }
    public function func_logout(Request $request){
        Auth::logout();
        return redirect('/login');
    }
    
    
}
