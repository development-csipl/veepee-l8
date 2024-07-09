<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */

    public function handle($request, Closure $next){
       if (Auth::check()) {
           if (Auth::user()->status == 0) {  
                $message = 'Your account has been blocked.Please contact to administrator.';        
                Auth::logout();     
                return redirect()->route('login')->withMessage($message);      
            } else {
                return $next($request);   
            }           
        } else {
            return redirect()->route('login')->withMessage('Please login first to perform this action.');
        }     
        
    }  
    
}
