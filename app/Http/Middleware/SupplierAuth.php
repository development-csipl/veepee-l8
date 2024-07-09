<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class SupplierAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
       if (Auth::check()) {
           if (Auth::user()->status == 0) {  
                $message = 'Your account has been blocked.Please contact to administrator.';        
                Auth::logout();     
                return redirect()->route('supplier.login')->withMessage($message);      
            } else {
                return $next($request);   
            }           
        } else {
            return redirect()->route('supplier.login')->withMessage('Please login first to perform this action.');
        }     
        
    } 

}
