<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RestaurantLoggedOut
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
        // kapag papuntang login pero may session na admin then redirect sa dashboard
        if(Session::has('userType')){
            if(Session::get('userType') == 'customer'){
                // to be followed return redirect('customer/dashboard');
            } else if (Session::get('userType') == 'admin'){
                return redirect('admin/dashboard');
            } else {
                return redirect('restaurant/dashboard');
            }
        } else {
            return $next($request);
        }
    }
}
