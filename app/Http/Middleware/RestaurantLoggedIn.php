<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RestaurantLoggedIn
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
        // kapag papuntang dashboard or logout pero wala namang session then redirect to login

        if(!Session::has('userType') || !Session::has('userValidation')){
            $request->session()->flush();
            return redirect('restaurant/login');
        } else {
            if(Session::get('userValidation') == 'S@mquiks@lwav'){
                if(Session::get('userType') == 'customer'){
                    redirect('customer/login');
                } else if (Session::get('userType') == 'admin'){
                    return redirect('admin/login');
                } else {
                    return $next($request);
                }
            } else {
                $request->session()->flush();
                return redirect('admin/login');
            }
        }
    }
}
