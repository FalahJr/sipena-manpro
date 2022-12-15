<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Account;

use App\Authentication;

use Auth;

use Carbon\Carbon;

use Session;

use DB;

use Response;

class PublicController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('admin');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

     
     public function homepage() {


    $siswa = DB::table('siswa')->count();
    $guru = DB::table('guru')->count();
    $pegawai = DB::table('pegawai')->count();

       return view("homepage/index",  compact('siswa','guru', 'pegawai'));
     }

     public function registerWalimurid() {


      // $siswa = DB::table('siswa')->count();
      // $guru = DB::table('guru')->count();
      // $pegawai = DB::table('pegawai')->count();
  
         return view("homepage/ppdb-register-walimurid");
       }

       public function loginWalimurid() {


        // $siswa = DB::table('siswa')->count();
        // $guru = DB::table('guru')->count();
        // $pegawai = DB::table('pegawai')->count();
    
           return view("homepage/ppdb-login-walimurid");
         }
    

         public function registerMurid() {


          // $siswa = DB::table('siswa')->count();
          // $guru = DB::table('guru')->count();
          // $pegawai = DB::table('pegawai')->count();
      
             return view("homepage/ppdb-register-murid");
           }
    
}
