<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\mMember;

use App\Authentication;

use Auth;

use Carbon\Carbon;

use Session;

use DB;

class ForgotpasswordController extends Controller
{
    public function index() {
      return view("auth.forgotpassword");
    }

    public function doforgot(Request $req) {

      $cekemail = DB::table("users")
                    ->where("users_email", $req->email)
                    ->first();

      if ($cekemail != null) {
        SendemailController::Send($cekemail->users_name, "Your link reset password : ".url('/forgotlink')."/".$cekemail->users_id."/".$cekemail->users_accesstoken."", 'Link Reset Password Account DompetQu', $cekemail->users_email);
        Session::flash('sukses','Berhasil');
        return back();
      } else {
        Session::flash('email','Berhasil');
        return back();
      }

    }

    public function forgotlink($id, $accesstoken) {
        $ceklink = DB::table("users")->where("users_id", $id)->first();

        if ($ceklink->users_accesstoken != $accesstoken) {
          return view("errors.404");
        } else{
          return view("auth.forgotlink", compact("id"));
        }
    }

    public function doforgotlink(Request $req) {
      DB::beginTransaction();
      try {

        if ($req->password != $req->confirmpass) {
          Session::flash('password','Berhasil');
          return back();
        } else {
          DB::table("users")
                ->where("users_id", decrypt($req->id))
                ->update([
                  "users_password" => sha1(md5('passwordAllah').$req->password),
                ]);
        }
        DB::commit();
        Session::flash('sukses','Berhasil');
        return back();
      } catch (\Exception $e) {
        DB::rollback();
        Session::flash('gagal','Berhasil');
        return back();
      }

    }

    public function forgotlogin($id){
      $iddecrypt = decrypt($id);

      $users = mMember::where("users_id", $iddecrypt)->first();

      Auth::login($users);
      return redirect('/home');
    }
}
