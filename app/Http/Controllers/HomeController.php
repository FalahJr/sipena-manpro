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

class HomeController extends Controller
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

     public function index() {

       // $cekuseronline = DB::table("account")->where("islogin", 'Y')->get();

       // foreach ($cekuseronline as $key => $value) {
       //    if (Carbon::now()->diffInMinutes($value->last_online) == 120) {
       //        DB::table('account')->update(['islogin'=>'N']);
       //    }
       // }

      //  $useronline = DB::table("user")->where("is_login", 'Y')->count();

      //  $alluser = DB::table("user")->count();
      // $pegawai = DB::table('pegawai')->where("user_id", Auth::user()->id)->first();


      //  $alltoko = DB::table("user")->where("is_login", 'Y')->count();
       return view("home");
     }

    //  public function main() {

    //   // $cekuseronline = DB::table("account")->where("islogin", 'Y')->get();

    //   // foreach ($cekuseronline as $key => $value) {
    //   //    if (Carbon::now()->diffInMinutes($value->last_online) == 120) {
    //   //        DB::table('account')->update(['islogin'=>'N']);
    //   //    }
    //   // }
    //   // Auth::user()->role != 'admin'
    //   $pegawai = DB::table('pegawai')->where("user_id", Auth::user()->id)->first();

    //   // $alluser = DB::table("user")->count();

    //  //  $alltoko = DB::table("user")->where("is_login", 'Y')->count();

    //   return view("layouts._sidebar", compact('pegawai'));
    // }

    public function logout(){
        Session::flush();

        Auth::logout();

        Session::forget('key');
        return Redirect('/');
    }

    public function checklogin() {
      // dd("asd");
      if (Auth::check()) {
        // if(Auth::user()->role_id == 1) {
          return Redirect('/admin/home');
        // } else {
          Account::where('id', Auth::user()->id)->update([
              //  'last_online' => Carbon::now(),
               'is_login' => "Y",
          ]);

          Auth::logout();

          return Redirect('/admin/login');
      } else {
        return Redirect('/admin/login');
      }
    }
}
