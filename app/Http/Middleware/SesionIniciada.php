<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;

class SesionIniciada
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //var_dump(Auth::user());
        // var_dump($next->request->pathInfo());
        // echo '<pre>';
        
        // echo '</pre>';
        
         //if(Auth::user()!=null){
         if(session('auth')){
             return $next($request);
         }else{
            //return $next($request);
            return redirect('login');
         }
        
    }
}
