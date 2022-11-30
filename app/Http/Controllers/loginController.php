<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Account;
use Validator;
use Carbon\Carbon;
use Session;
use DB;
use App\Http\Controllers\logController;

use Response;

class loginController extends Controller
{

    public function __construct(){
        $this->middleware('guest');
    }

    public function loginApi(Request $req) {
        $username = $req->username;
        $password = $req->password;
        $user = DB::table("user")->select("user.*", "role.*", "role.nama as rolenama")->where("username", $username)->join("role", "role.id", '=', "user.role_id")->first();
        if ($user && $user->password == $password) {
            return response()->json([
                "status" => 1,
                "data" => $user
            ]);
        } else {
            return response()->json([
                'success' => 1,
            ]);
        }
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

            $user = Account::where("username", $username)->where("role_id", 1)->first();

            $user_valid = [];
            // dd($req->all());

           	if ($user != null) {
           		$user_pass = Account::where('username',$username)
	            			          ->where('password',$pass_benar)
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
                return Redirect('/admin/home');
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


}
