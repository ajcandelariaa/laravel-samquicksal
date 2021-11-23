<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerWebController extends Controller{
    // LOAD VIEWS
    public function customerHome(){
        return view('customer.customer-home');
    }
    public function loginView(){
        return view('customer.login');
    }
    public function signupView(){
        return view('customer.signup');
    }
    public function editProfileView(){
        return view('customer.editProfile');
    }
    public function profileView(){
        return view('customer.profile');
    }
    public function reservationView(){
        return view('customer.reservation.reserve');
    }


    //LOAD LOGICS
    
}
