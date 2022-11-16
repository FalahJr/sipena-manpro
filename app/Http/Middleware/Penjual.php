<?php
namespace App\Http\Middleware;
use Closure;
use Auth;
use Session;
use App\Account;
class Penjual
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        /*'fBaCZemyXbk6uUButDcfLhw1Z21B56Yd4sS4MR3'*/
        /*dd(Auth::user()->m_token.'!='.Session::get('m_token'));*/
        if (Auth::check()) {
          if(Auth::user()->role != "member"){
            return Redirect('/');
          }
        } else {
          return Redirect('/');
        }

        return $next($request);
    }
}
