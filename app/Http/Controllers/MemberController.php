<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Account;
use Validator;
use Carbon\Carbon;
use Session;
use DB;
use File;
// use App\Authentication;


class MemberController extends Controller
{
    //
    public function login(Request $req) {

        $rules = array(
            'username' => 'required|min:3', // make sure the email is an actual email
            'password' => 'required|min:2' // password can only be alphanumeric and has to be greater than 3 characters
        );
        // dd($req->all());
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return Redirect('/')
                            ->withErrors($validator) // send back all errors to the login form
                            ->withInput($req->except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {
            $username  = $req->username;
            $password  = $req->password;
            $pass_benar = $password;
            // $pass_benar=$password;
            // $username = str_replace('\'', '', $username);

            $user = Account::where("email", $username)->where("role", "member")->first();

            $user_valid = [];
            // dd($req->all());

            if ($user != null) {
                $user_pass = Account::where('email',$username)
                                      ->where('password',$pass_benar)
                                      ->first();

                if ($user_pass != null) {
                    // Account::where('email',$username)->update([
                //      'users_lastlogin'=>Carbon::now(),
                //        ]);

                    Account::where('email',$username)->update([
                         'last_online'=>Carbon::now(),
                         'islogin'=>'Y',
                          ]);
            // $user = Account::where("email", $username)->where("role", "member")->first();

                    Auth::login($user);
                    // Session::put('username',$data->fullname);
                    // dd(Session::put('username',$user->fullname));
                    // Session::put('email',$data->email);
                    // logController::inputlog('Login', 'Login', $username);
                    return Redirect('/')->with('message', 'IT WORKS!');
                    }else{
                    Session::flash('password','Password Yang Anda Masukan Salah!');
                    return back()->with('password','username');
                      // Session()->now('message', 'gagal password salah.');
                      // session()->reflash();
                    }
                }else{
                    Session::flash('username','Username Tidak Ada');
                    return back()->with('password','username');
                        // session()->now('message', 'gagal username salah.');
                        // session()->reflash();

                }


        }
    }

    public function register(Request $req){
        $this->validate($req, [
            // 'fullname' => 'required|string|min:4',
            'email' => 'required|min:4|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
            // 'password_confirm' => 'required_with:password|same:password|min:4',
        ]);


          $max = DB::table("account")->max('id_account') + 1;
          // $user = Account::where("email", $email)->where("role", "member")->first();


          // Define Email Address
          $email = $req['email'];


          // Get the username by slicing string
          $fullname = strstr($email, '@', true);

         $regis = Account::create([
            'id_account' => $max,
            'fullname' => $fullname,
            'email' => $req['email'],
            'password' => $req['password'],
            'confirm_password' => $req['password_confirmation'],
            'role' => 'member',
            'islogin' => 'Y',
            'last_online'=>Carbon::now(),
        ]);

        //     ]);
         if ($regis != null) {
          $user = Account::where("email", $email)->where("role", "member")->first();
        Auth::login($user);
        return redirect('/')->with('alert-success','Kamu berhasil Register');

             // code...
         }
        // Auth::login($user);

        // return redirect('/')->with('alert-success','Kamu berhasil Register');

    }
    public function logout(){

      // dd(Auth::user());
      // Account::where('id_account', Auth::user()->id_account)->update([
      //      'last_online' => Carbon::now(),
      //      'islogin' => "N",
      // ]);

      Auth::logout();

      return Redirect('/');
    }

    public function logoutjson(){

      // Account::where('id_account', Auth::user()->id_account)->update([
      //      'last_online' => Carbon::now(),
      //      'islogin' => "N",
      // ]);

      Auth::logout();

      return response()->json(200);
    }

    public function profile(){

        $data = DB::table("account")->where("id_account", Auth::user()->id_account)->first();

        // $gender = DB::table("account")->select("gender");
        if ($data == null) {
         return view("/");
       } else {
         return view("/pembeli/profil", compact('data'));
       }
    }

    public function edit(Request $req){
        $this->validate($req, [
            'fullname' => 'required|string|min:4',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
            // 'password_confirm' => 'required_with:password|same:password|min:4',
        ]);

      //   Account::where('id_account', Auth::user()->id_account)->update([
      //       'fullname' => $req['fullname'],
      //       'password' => $req['password'],
      //       'confirm_password' => $req['password_confirmation'],
      //       'phone' => $req['phone'],
      //       'address' => $req['address'],
      //       'nomor_rekening' => $req['norek'],
      //       'bank' => $req['bank'],
      //       'last_online' => Carbon::now(),

      // ]);

            // baru

               $imgPath = null;
               $tgl = Carbon::now('Asia/Jakarta');
               $folder = $tgl->year . $tgl->month . $tgl->timestamp;
               $dir = 'image/uploads/User/' . Auth::user()->id_account;
               $childPath = $dir . '/';
               $path = $childPath;
               $file = $req->file('image');
               $name = null;
               if ($file != null) {
                   // $this->deleteDir($dir);
                File::deleteDirectory($dir);
                   $name = $folder . '.' . $file->getClientOriginalExtension();
                   if (!File::exists($path)) {
                       if (File::makeDirectory($path, 0777, true)) {
                         if ($_FILES['image']['type'] == 'image/webp' || $_FILES['image']['type'] == 'image/jpeg') {

                         } else if ($_FILES['image']['type'] == 'webp' || $_FILES['image']['type'] == 'jpeg') {

                         } else {
                           compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],75);
                         }
                           $file->move($path, $name);
                           $imgPath = $childPath . $name;
                       } else
                           $imgPath = null;
                   } else {
                       return 'already exist';
                   }
               }

                   if ($imgPath == null) {
                     DB::table("account")
                         ->where('id_account', Auth::user()->id_account)
                         ->update([
                            "fullname" => $req['fullname'],
                            "password" => $req['password'],
                            "confirm_password" => $req['password_confirmation'],
                            "phone" => $req['phone'],
                            "address" => $req['address'],
                            "nomor_rekening" => $req['norek'],
                            "bank" => $req['bank'],
                            "codeforgot" => $req['code'],
                            "last_online" => Carbon::now(),
                            "updated_at" => Carbon::now('Asia/Jakarta'),
                       ]);
                   } else {
                     DB::table("account")
                         ->where('id_account', Auth::user()->id_account)
                         ->update([
                            "fullname" => $req['fullname'],
                            "password" => $req['password'],
                            "confirm_password" => $req['password_confirmation'],
                            "phone" => $req['phone'],
                            "address" => $req['address'],
                            "profile_picture" => $imgPath,
                            "nomor_rekening" => $req['norek'],
                            "bank" => $req['bank'],
                            "codeforgot" => $req['code'],
                            "last_online" => Carbon::now(),
                            "updated_at" => Carbon::now('Asia/Jakarta'),
                       ]);
                   }

               DB::commit();
               Session::flash('sukses', 'sukses');

               return back()->with('sukses','sukses');
      // return redirect('/pembeli/profile')->with('alert-success','Edit Profil Berhasil');
    }

    public function apilogin(Request $req) {

        $rules = array(
            'username' => 'required|min:3', // make sure the email is an actual email
            'password' => 'required|min:2' // password can only be alphanumeric and has to be greater than 3 characters
        );
        // dd($req->all());
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
          return response()->json([
            "code" => 400,
            "message" => $validator->errors()->first()
          ]);
        } else {

          $username  = $req->username;
          $password  = $req->password;
          $pass_benar = $password;
          // $pass_benar=$password;
          // $username = str_replace('\'', '', $username);

          $user = Account::where("email", $username)->where("role", "member")->first();

          $user_valid = [];
          // dd($req->all());

          if ($user != null) {
              $user_pass = Account::where('email',$username)
                                    ->where('password',$pass_benar)
                                    ->first();

              if ($user_pass != null) {

                  Account::where('email',$username)->update([
                       'last_online'=>Carbon::now(),
                       'islogin'=>'Y',
                    ]);

                    return response()->json([
                      "code" => 200,
                      "message" => "Sukses Login",
                      "data" => $user_pass
                    ]);
                }
              }else{
                return response()->json([
                  "code" => 400,
                  "message" => "Username Tidak Ada"
                ]);

              }
        }
    }

    public function apiregister(Request $req){
        // $this->validate($req, [
        //     // 'fullname' => 'required|string|min:4',
        //     'email' => 'required|min:4|email',
        //     'password' => 'required',
        //     'password_confirmation' => 'required|same:password'
        //     // 'password_confirm' => 'required_with:password|same:password|min:4',
        // ]);

        $rules = array(
          'email' => 'required|min:4|email',
          'password' => 'required',
          'password_confirmation' => 'required|same:password'
        );

        // dd($req->all());
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
          return response()->json([
            "code" => 400,
            "message" => $validator->errors()->first()
          ]);
        } else {

          $max = DB::table("account")->max('id_account') + 1;
          // $user = Account::where("email", $email)->where("role", "member")->first();


          // Define Email Address
          $email = $req['email'];


          // Get the username by slicing string
          $fullname = strstr($email, '@', true);

         $regis = Account::create([
            'id_account' => $max,
            'fullname' => $fullname,
            'email' => $req['email'],
            'password' => $req['password'],
            'confirm_password' => $req['password_confirmation'],
            'role' => 'member',
            'islogin' => 'Y',
            'last_online'=>Carbon::now(),
        ]);

        //     ]);
         if ($regis != null) {
          $user = Account::where("email", $email)->where("role", "member")->first();

             return response()->json([
               "code" => 200,
               "message" => "Kamu berhasil Register",
               "data" => $user
             ]);
         } else {
           return response()->json([
             "code" => 400,
             "message" => "Gagal register"
           ]);
         }

        }

    }

    public function apilogout(Request $req){

      Account::where('id_account', $req->id_account)->update([
           'last_online' => Carbon::now(),
           'islogin' => "N",
      ]);

      return response()->json([
        "code" => 200,
        "message" => "Kamu berhasil logout",
      ]);

    }

    public function apiprofile(Request $req){

        $data = DB::table("account")->where("id_account", $req->id_account)->first();
        // $gender = DB::table("account")->select("gender");
        if ($data == null) {
          return response()->json([
            "code" => 400,
            "message" => "Data tidak ditemukan"
          ]);
       } else {
         return response()->json([
           "code" => 200,
           "message" => "Sukses",
           "data" => $data
         ]);
       }
    }

    public function apiedit(Request $req){
      $rules = array(
        'fullname' => 'required|string|min:4',
        'password' => 'required',
        'password_confirmation' => 'required|same:password'
      );

      // dd($req->all());
      $validator = Validator::make($req->all(), $rules);
      if ($validator->fails()) {
        return response()->json([
          "code" => 400,
          "message" => $validator->errors()->first()
        ]);
      } else {
        $imgPath = null;
        $tgl = Carbon::now('Asia/Jakarta');
        $folder = $tgl->year . $tgl->month . $tgl->timestamp;
        $dir = 'image/uploads/User/' . $req->id_account;
        $childPath = $dir . '/';
        $path = $childPath;
        $file = $req->file('image');
        $name = null;
        if ($file != null) {
            // $this->deleteDir($dir);
         File::deleteDirectory($dir);
            $name = $folder . '.' . $file->getClientOriginalExtension();
            if (!File::exists($path)) {
                if (File::makeDirectory($path, 0777, true)) {
                  if ($_FILES['image']['type'] == 'image/webp' || $_FILES['image']['type'] == 'image/jpeg') {

                  } else if ($_FILES['image']['type'] == 'webp' || $_FILES['image']['type'] == 'jpeg') {

                  } else {
                    compressImage($_FILES['image']['type'],$_FILES['image']['tmp_name'],$_FILES['image']['tmp_name'],75);
                  }
                    $file->move($path, $name);
                    $imgPath = $childPath . $name;
                } else
                    $imgPath = null;
            } else {
                return 'already exist';
            }
        }

            if ($imgPath == null) {
              DB::table("account")
                  ->where('id_account', $req->id_account)
                  ->update([
                     "fullname" => $req['fullname'],
                     "password" => $req['password'],
                     "confirm_password" => $req['password_confirmation'],
                     "phone" => $req['phone'],
                     "address" => $req['address'],
                     "nomor_rekening" => $req['norek'],
                     "bank" => $req['bank'],
                     "codeforgot" => $req['code'],
                     "last_online" => Carbon::now(),
                     "updated_at" => Carbon::now('Asia/Jakarta'),
                ]);
            } else {
              DB::table("account")
                  ->where('id_account', $req->id_account)
                  ->update([
                     "fullname" => $req['fullname'],
                     "password" => $req['password'],
                     "confirm_password" => $req['password_confirmation'],
                     "phone" => $req['phone'],
                     "address" => $req['address'],
                     "profile_picture" => $imgPath,
                     "nomor_rekening" => $req['norek'],
                     "bank" => $req['bank'],
                     "codeforgot" => $req['code'],
                     "last_online" => Carbon::now(),
                     "updated_at" => Carbon::now('Asia/Jakarta'),
                ]);
            }

        $data = DB::table("account")
                    ->where('id_account', $req->id_account)
                    ->first();

        DB::commit();
        return response()->json([
          "code" => 200,
          "message" => "Sukses",
          "data" => $data
        ]);
      }

    }

    public function forgot(Request $req) {

        $cek = DB::table('account')
                ->where("email", $req->email)
                ->first();

        if ($cek == null) {
          return response()->json([
            'status' => 2
          ]);
        } else {
          if ($cek->codeforgot == $req->code) {
            DB::table("account")
                ->where("email", $req->email)
                ->update([
                  'password' => $req->password,
                  'confirm_password' => $req->password,
                  'updated_at' => Carbon::now('Asia/Jakarta')
                ]);

              return response()->json([
                'status' => 1
              ]);
          } else {
            return response()->json([
              'status' => 2
            ]);
          }
        }

    }

    public function apiforgot(Request $req) {

        $cek = DB::table('account')
                ->where("email", $req->email)
                ->first();

        if ($cek == null) {
          return response()->json([
            "code" => 400,
            "message" => "User not registered!",
          ]);
        } else {
          if ($cek->codeforgot == $req->code) {
            DB::table("account")
                ->where("email", $req->email)
                ->update([
                  'password' => $req->password,
                  'confirm_password' => $req->password,
                  'updated_at' => Carbon::now('Asia/Jakarta')
                ]);

              return response()->json([
                "code" => 200,
                "message" => "Successfully forgot password!",
              ]);
          } else {
            return response()->json([
              "code" => 400,
              "message" => "Code forgot is not the same!",
            ]);
          }
        }

    }

}
