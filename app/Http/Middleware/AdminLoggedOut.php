<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminLoggedOut
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
                redirect('customer/customer-home');
            } else if (Session::get('userType') == 'restaurant'){
                return redirect('restaurant/dashboard');
            } else {
                return redirect('admin/dashboard');
            }
        } else {
            return $next($request);
        }
        return $next($request);
    }
}
