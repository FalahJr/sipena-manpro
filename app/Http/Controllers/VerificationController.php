<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Auth;

use App\mMember;

use Session;

class VerificationController extends Controller
{
    public function index($id) {
      return view("auth.verification", compact("id"));
    }

    public function tesemail() {
      SendemailController::Send("Email testing fer", "Your code verification : 101", 'Code Verification Register Account DompetQu', "ferdyp73@gmail.com");
    }

    public function resend($id) {
      $iddecrypt = decrypt($id);

      $codeverif = substr(str_shuffle("0123456789"), 0, 6);

      $users = DB::table("users")
                ->where("users_id", decrypt($iddecrypt))
                ->first();

      DB::beginTransaction();
      try {


        DB::table("users")
                  ->where("users_id", decrypt($iddecrypt))
                  ->update([
                    "users_codeverif" => $codeverif
                  ]);

        SendemailController::Send($users->users_name, "Your code verification : ".$codeverif."", 'Code Verification Register Account DompetQu', $users->users_email);

        $id = $users->users_id;

        DB::commit();
        return back()->with("id");
      } catch (\Exception $e) {
        $id = $users->users_id;
        DB::rollback();
        Session::flash('gagal','gagal kirim ulang code verification!');
        return back()->with("id");
      }
    }

    public function doverification(Request $req) {
      $iddecrypt = decrypt($req->id);
      $code = $req->code;

      // $cekcode = DB::table("users")
      //               ->where("users_id", $iddecrypt)
      //               ->first();

      $cekcode = mMember::where('users_id',decrypt($iddecrypt))
      			          ->first();

      if ($cekcode->users_codeverif == $code) {
        DB::table("users")
                      ->where("users_id", decrypt($iddecrypt))
                      ->update([
                        "users_verification" => "Y"
                      ]);

        Auth::login($cekcode);
        return redirect("home");
      } else {
        Session::flash('verification','code tidak sama!');
        return back();
      }

    }
}
