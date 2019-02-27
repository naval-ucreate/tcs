<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Session;
use Closure;

class TrelloOauthCheck
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
        if($request->session()->exists('userinfo')) {
            //dd( Session::get('userinfo'));
            return $next($request);
        }
        return redirect()->route('login');
       
    }
}
