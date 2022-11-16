<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
//CUSTOM EMAIL OR USERNAME
use Illuminate\Http\Request;
use Auth;
use Session;
use App\UserProfile;
use App\User;
// END

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

     // CUSTOM LOGIN EMAIL OR USERNAME

    public function login(Request $request)
    {
        $this->validate($request, [
                'username'    => 'required',
                'password' => 'required',
            ]);
        
        //Store Email field Value
        $loginValue = $request->input('username');
        
        //Get Login Type
        $login_type = $this->getLoginType( $loginValue);
 
        //Change request type based on user input
        $request->merge([
            $login_type => $loginValue
        ]);
        
        //Check Credentials and redirect
        if (Auth::attempt($request->only($login_type, 'password'))) {
            return redirect()->intended($this->redirectPath());
        } 
         return redirect()->back()->withInput()->withErrors([ 'username' => "These credentials do not match our records." ]);
    } 
 
    //Check user input type
    public function getLoginType($loginValue) {
        return filter_var($loginValue, FILTER_VALIDATE_EMAIL ) 
            ? 'email' 
            : ( (preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $loginValue)) ? 'mobile' : 'username' );
    }
}
