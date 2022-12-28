<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Account;

use App\Authentication;

use Auth;

use Carbon\Carbon;

use Session;

use Validator;

use DB;
//  use App\Http\Controllers\File;
use File;

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
    $info_ppdb = DB::table('ppdb')->where("id", 1)->first();

       return view("homepage/index",  compact('siswa','guru', 'pegawai', 'info_ppdb'));
     }

     public function registerWalimurid() {


      // $siswa = DB::table('siswa')->count();
      // $guru = DB::table('guru')->count();
      // $pegawai = DB::table('pegawai')->count();
  
         return view("homepage/ppdb-register-walimurid");
       }

       public function registerWalimuridSimpan(Request $req){

        if (!$this->cekemail($req->username)) {
          return response()->json(["status" => 7, "message" => "Data username sudah digunakan, tidak dapat disimpan!"]);
        }
        DB::beginTransaction();
        try {
          $max = DB::table("user")->max('id') + 1;
          $maxWaliMurid = DB::table("wali_murid")->max('id') + 1;
  
          $imgPath = null;
          $tgl = Carbon::now('Asia/Jakarta');
          $folder = $tgl->year . $tgl->month . $tgl->timestamp;
          $dir = 'image/uploads/Walimurid/' . $maxWaliMurid;
          $childPath = $dir . '/';
          $path = $childPath;
  
          $file = $req->file('image');
          $name = null;
          if ($file != null) {
            // $this->deleteDir($dir);
            $name = $folder . '.' . $file->getClientOriginalExtension();
            if (!File::exists($path)) {
              if (File::makeDirectory($path, 0777, true)) {
                if ($_FILES['image']['type'] == 'image/webp' || $_FILES['image']['type'] == 'image/jpeg') {
                } else if ($_FILES['image']['type'] == 'webp' || $_FILES['image']['type'] == 'jpeg') {
                } else {
                  compressImage($_FILES['image']['type'], $_FILES['image']['tmp_name'], $_FILES['image']['tmp_name'], 75);
                }
                $file->move($path, $name);
                $imgPath = $childPath . $name;
              } else
                $imgPath = null;
            } else {
              return 'already exist';
            }
          }
  
        $tes=DB::table("user")
            ->insert([
              "id" => $max,
              "username" => $req->username,
              "password" => $req->password,
              "role_id" => 3,
              "is_active" => 'Y',
              "saldo" => 0,
              "created_at" => Carbon::now('Asia/Jakarta'),
            ]);
          
            DB::table("wali_murid")->insert([
              "id"=>$maxWaliMurid,
              "user_id" => $max,
              "nama_lengkap" => $req->nama_lengkap,
              "tempat_lahir" => $req->tempat_lahir,
              "tanggal_lahir" => $req->tanggal_lahir,
              "jenis_kelamin" => $req->jk,
              "alamat" => $req->alamat,
              "phone" => $req->phone,
              "foto_profil" => $imgPath,
            ]);
          
            DB::commit();
  
          

        
            // return response()->json(["status" => 1]);

          // }
          // return response()->json(["status" => 1,'success' => 'Data berhasil diupdate']);
    return back()->with(['success' => 'Data berhasil diupdate']);

        } catch (\Exception $e) {
          DB::rollback();
          return response()->json(["status" => 2, "pesan"=>$e->getMessage()]);
        }
    // $newData = request()->except(['_token']);
    // $data = DB::table("guru")->where('id',$request->id)->update($newData);

    // dd($data);
    // return back()->with(['success' => 'Data berhasil diupdate']);

    
  }


  public function registerMuridSimpan(Request $req){

    if (!$this->cekemail($req->username)) {
      return response()->json(["status" => 7, "message" => "Data username sudah digunakan, tidak dapat disimpan!"]);
    }
    DB::beginTransaction();
    try {
      $max = DB::table("user")->max('id') + 1;
      $maxSiswa = DB::table("siswa")->max('id') + 1;
      $walimurid = DB::table('wali_murid')->where("user_id", $req->wali_murid_id)->first();

      $imgPath = null;
      $tgl = Carbon::now('Asia/Jakarta');
      $folder = $tgl->year . $tgl->month . $tgl->timestamp;
      $dir = 'image/uploads/Murid/' . $max;
      $childPath = $dir . '/';
      $path = $childPath;

      $file = $req->file('image');
    $name = null;
      if ($file != null) {
        // $this->deleteDir($dir);
        $name = $folder . '.' . $file->getClientOriginalExtension();
        if (!File::exists($path)) {
          if (File::makeDirectory($path, 0777, true)) {
            if ($_FILES['image']['type'] == 'image/webp' || $_FILES['image']['type'] == 'image/jpeg') {
            } else if ($_FILES['image']['type'] == 'webp' || $_FILES['image']['type'] == 'jpeg') {
            } else {
              compressImage($_FILES['image']['type'], $_FILES['image']['tmp_name'], $_FILES['image']['tmp_name'], 75);
            }
            $file->move($path, $name);
            $imgPath = $childPath . $name;
          } else
            $imgPath = null;
        } else {
          return 'already exist';
        }
      }

    $tes=DB::table("user")
        ->insert([
          "id" => $max,
          "username" => $req->username,
          "password" => $req->username."123",
          "role_id" => 2,
          "is_active" => 'N',
          "saldo" => 0,
          "created_at" => Carbon::now('Asia/Jakarta'),
        ]);
        if($tes){
      
        DB::table("siswa")->insert([
          "id"=>$maxSiswa,
          "user_id" => $max,
          "wali_murid_id" => $walimurid->id,
          // "kelas_id" => $req->kelas_id,
          "nama_lengkap" => $req->nama_lengkap,
          "nisn" => $req->nisn,
          "tempat_lahir" => $req->tempat_lahir,
          "tanggal_lahir" => $req->tanggal_lahir,
          "nama_ayah" => $req->nama_ayah,
          "nama_ibu" => $req->nama_ibu,
          "jenis_kelamin" => $req->jk,
          "alamat" => $req->alamat,
          "agama" => $req->agama,
          "phone" => $req->phone,
          "foto_profil" => $imgPath,
          // "kartu_digital" => $linkCode,
          "is_osis" => 'N',
          "tanggal_daftar" => Carbon::now('Asia/Jakarta'),
        ]);
        }
        DB::commit();

      

    
        // return response()->json(["status" => 1]);

      // }
      // return response()->json(["status" => 1,'success' => 'Data berhasil diupdate']);
return back()->with(['success' => 'Data berhasil diupdate']);

    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(["status" => 2, "pesan"=>$e->getMessage()]);
    }
// $newData = request()->except(['_token']);
// $data = DB::table("guru")->where('id',$request->id)->update($newData);

// dd($data);
// return back()->with(['success' => 'Data berhasil diupdate']);


}
       public function loginWalimurid() {


        // $siswa = DB::table('siswa')->count();
        // $guru = DB::table('guru')->count();
        // $pegawai = DB::table('pegawai')->count();
    
           return view("homepage/ppdb-login-walimurid");
         }
    
         public function authenticate(Request $req) {

          $rules = array(
              'username' => 'required|min:3', // make sure the email is an actual email
              'password' => 'required|min:2' // password can only be alphanumeric and has to be greater than 3 characters
          );
        // dd($req->all());
          $validator = Validator::make($req->all(), $rules);
          if ($validator->fails()) {
            Session::flash('username','Username Tidak Ada');
            Session::flash('password','Password Yang Anda Masukan Salah!');
            return back()->with('password','username');
              // return Redirect('/')
              //                 ->withErrors($validator) // send back all errors to the login form
              //                 ->withInput($req->except('password')); // send back the input (not the password) so that we can repopulate the form
          } else {
              $username  = $req->username;
              $password  = $req->password;
               $pass_benar = $password;
              // $pass_benar=$password;
              // $username = str_replace('\'', '', $username);
  
              $user = Account::where("username", $username)->first();
  
              $user_valid = [];
              // dd($req->all());
  
               if ($user != null) {
                 $user_pass = Account::where('username',$username)
                                ->where('password',$pass_benar)
                                ->where('role_id', 3)
                                ->first();
  
                if ($user_pass != null) {
                   // Account::where('email',$username)->update([
                  //      'users_lastlogin'=>Carbon::now(),
                  //  	  ]);
  
                  Account::where('username',$username)->update([
                      //  'last_online'=>Carbon::now(),
                       'is_login'=>'Y',
                       ]);
                  Auth::login($user);
                  // logController::inputlog('Login', 'Login', $username);
                  return Redirect('/ppdb-register-murid');
                }else{
                  Session::flash('password','Password Yang Anda Masukan Salah!');
                  return back()->with('password','username');
                }
               }else{
                 Session::flash('username','Username Tidak Ada');
                 return back()->with('password','username');
               }
  
  
          }
      }
         public function registerMurid() {


          // $siswa = DB::table('siswa')->count();
          // $guru = DB::table('guru')->count();
          // $pegawai = DB::table('pegawai')->count();
      
             return view("homepage/ppdb-register-murid");
           }
    
           public static function cekemail($username, $id = null)
  {

    $cek = DB::table('user')->where("username", $username)->first();

    if ($cek != null) {
      if ($id != null) {
        if ($cek->id != $id) {
          return false;
        } else {
          return true;
        }
      } else {
        return false;
      }
    } else {
      return true;
    }
  }

  
}
