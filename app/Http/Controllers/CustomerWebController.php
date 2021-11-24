<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\StoreHour;
use Illuminate\Http\Request;
use App\Models\CustomerAccount;
use App\Models\CustomerReserve;
use App\Models\UnavailableDate;
use App\Models\RestaurantAccount;
use App\Models\CustomerNotification;
use App\Models\OrderSet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CustomerWebController extends Controller{

    public function convertDays($day){
        if ($day == "MO"){
            return "Monday";
        } else if ($day == "TU"){
            return "Tuesday";
        } else if ($day == "WE"){
            return "Wednesday";
        } else if ($day == "TH"){
            return "Thursday";
        } else if ($day == "FR"){
            return "Friday";
        } else if ($day == "SA"){
            return "Saturday";
        } else if ($day == "SU"){
            return "Sunday";
        } else {
            return "";
        }
    }
    public function convertMonths($month){
        switch ($month) {
            case '1':
                return "January";
                break;
            case '2':
                return "February";
                break;
            case '3':
                return "March";
                break;
            case '4':
                return "April";
                break;
            case '5':
                return "May";
                break;
            case '6':
                return "June";
                break;
            case '7':
                return "July";
                break;
            case '8':
                return "August";
                break;
            case '9':
                return "September";
                break;
            case '10':
                return "October";
                break;
            case '11':
                return "November";
                break;
            case '12':
                return "December";
                break;
            default:
                return "";
                break;
        }
    }

    public function checkTodaySched($rest_param_id){
        $restAcc_id = $rest_param_id;
        $restaurant = RestaurantAccount::select('status', 'id')->where('id', $restAcc_id)->first();

        $getAvailability = "Open";
        $rSchedule = "";
        $getUDStartTimeasED = "none";

        $restaurantUnavailableDates = UnavailableDate::select('unavailableDatesDate', 'startTime')->where('restAcc_id', $restaurant->id)->get();
        if(!$restaurantUnavailableDates->isEmpty()){
            foreach ($restaurantUnavailableDates as $restaurantUnavailableDate) {
                if($restaurantUnavailableDate->unavailableDatesDate == date("Y-m-d")){
                    if($restaurantUnavailableDate->startTime <= date('H:i')){
                        $getAvailability = "Closed Now";
                    } else {
                        $getUDStartTimeasED = $restaurantUnavailableDate->startTime;
                    }
                }
            }
        }

        if($getAvailability == "Open"){
            $currentDay = date('l');
            $prevDay = date('l', strtotime(date('Y-m-d') . " -1 days"));

            $isHasCurr = false;
            $isHasCurrIndex = "";

            $isHasPrev = false;
            $isHasPrevIndex = "";

            $currentTime = date("H:i");
            
            $storeDay = array();
            $storeOpeningTime = array();
            $storeClosingTime = array();
            $restaurantStoreHours = StoreHour::where('restAcc_id', $restaurant->id)->get();
            foreach ($restaurantStoreHours as $restaurantStoreHour){
                foreach (explode(",", $restaurantStoreHour->days) as $day){
                    array_push($storeDay, $this->convertDays($day));
                    array_push($storeOpeningTime, $restaurantStoreHour->openingTime);
                    array_push($storeClosingTime, $restaurantStoreHour->closingTime);
                }
            }
            for($i=0; $i<sizeOf($storeDay); $i++){
                if($prevDay == $storeDay[$i]){
                    if(strtotime($storeClosingTime[$i]) >= strtotime("00:00") && strtotime($storeClosingTime[$i]) <= strtotime("04:00")){
                        $isHasPrev = true;
                        $isHasPrevIndex = $i;
                    }
                }
                if($currentDay == $storeDay[$i]){
                    $isHasCurr = true;
                    $isHasCurrIndex = $i;
                }
            }
            
            if(strtotime($currentTime) >= strtotime("00:00") && strtotime($currentTime) <= strtotime("04:00")){
                if($isHasPrev){
                    if(strtotime($currentTime) <= strtotime($storeClosingTime[$isHasPrevIndex])){
                        $openingTime = date("g:i a", strtotime($storeOpeningTime[$isHasPrevIndex]));
                        $closingTime = date("g:i a", strtotime($storeClosingTime[$isHasPrevIndex]));
                        if($getUDStartTimeasED != "none"){
                            $closingTime = date("g:i a", strtotime($getUDStartTimeasED));
                        }
                        $rSchedule = "Open today at ".$openingTime." to ".$closingTime;
                    } else {
                        $rSchedule = "Closed Now";
                    }
                } else {
                    $rSchedule = "Closed Now";
                }
            } else if(strtotime($currentTime) >= strtotime("05:00") && strtotime($currentTime) <= strtotime("23:59")){
                if($isHasCurr){
                    if(strtotime($storeClosingTime[$isHasCurrIndex]) >= strtotime("00:00") && strtotime($storeClosingTime[$isHasCurrIndex]) <= strtotime("04:00")){
                        $openingTime = date("g:i a", strtotime($storeOpeningTime[$isHasCurrIndex]));
                        $closingTime = date("g:i a", strtotime($storeClosingTime[$isHasCurrIndex]));
                        if($getUDStartTimeasED != "none"){
                            $closingTime = date("g:i a", strtotime($getUDStartTimeasED));
                        }
                        $rSchedule = "Open today at ".$openingTime." to ".$closingTime;
                    } else {
                        if(strtotime($currentTime) >= strtotime($storeOpeningTime[$isHasCurrIndex]) && strtotime($currentTime) <= strtotime($storeClosingTime[$isHasCurrIndex])){
                            $openingTime = date("g:i a", strtotime($storeOpeningTime[$isHasCurrIndex]));
                            $closingTime = date("g:i a", strtotime($storeClosingTime[$isHasCurrIndex]));
                            if($getUDStartTimeasED != "none"){
                                $closingTime = date("g:i a", strtotime($getUDStartTimeasED));
                            }
                            $rSchedule = "Open today at ".$openingTime." to ".$closingTime;
                        } else {
                            $rSchedule = "Closed Now";
                        }
                    }
                } else {
                    $rSchedule = "Closed Now";
                }
            } else {
                $rSchedule = "Closed Now";
            }
        } else {
            $rSchedule = "Closed Now";
        }
        return $rSchedule;
    }






    // LOAD VIEWS
    public function customerHome(){
        $custAcc_id = Session::get('loginId');
        $customer = CustomerAccount::where('id', $custAcc_id)->first();

        $customerReserve = CustomerReserve::where('customer_id', $custAcc_id)
        ->where(function ($query) {
            $query->where('status', 'pending')
            ->orWhere('status', 'approved')
            ->orWhere('status', 'validation');
        })->latest()->first();

        $hasLiveStatus = false;
        if($customerReserve != null) {
            $hasLiveStatus = true;
        }

        return view('customer.customer-home', [
            'customer' => $customer,
            'hasLiveStatus' => $hasLiveStatus,
        ]);
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
    public function reservationView($restAcc_id){
        $custAcc_id = Session::get('loginId');
        $customer = CustomerAccount::where('id', $custAcc_id)->first();

        $customerReserve = CustomerReserve::where('customer_id', $custAcc_id)
        ->where(function ($query) {
            $query->where('status', 'pending')
            ->orWhere('status', 'approved')
            ->orWhere('status', 'validation');
        })->latest()->first();

        $hasLiveStatus = false;
        if($customerReserve != null) {
            $hasLiveStatus = true;
        }

        $restaurant = RestaurantAccount::where('id', $restAcc_id)->first();
        $orderSets = OrderSet::where('restAcc_id', $restAcc_id)->where('status', "Visible")->where('available', "Yes")->get();

        $currentDate = date('Y-m-d');

        // GET 7 days starting tomorrow
        $storeSevenDates = array();
        for($i=0; $i<7; $i++){
            $currentDateTime2 = date('Y-m-d', strtotime($currentDate. ' + 1 days'));
            array_push($storeSevenDates, $currentDateTime2);
            $currentDate = $currentDateTime2;
        }

        $storeUDDStartDate = array();
        $storeUDDStartTime = array();

        // IF MAY Sinet na unavailable date tas babanga sa 7 days then do this
        $unavailableDates = UnavailableDate::where('restAcc_id', $restAcc_id)->get();
        if(!$unavailableDates->isEmpty()){
            foreach ($unavailableDates as $unavailableDate){
                if (($key = array_search($unavailableDate->unavailableDatesDate, $storeSevenDates)) !== false) {
                    if(strtotime($unavailableDate->startTime) == strtotime("00:00")){
                        unset($storeSevenDates[$key]);
                    } else {
                        array_push($storeUDDStartDate, $unavailableDate->unavailableDatesDate);
                        array_push($storeUDDStartTime, $unavailableDate->startTime);
                    }
                }
            }
        }



        $modUDFStoreSevenDates = array_values($storeSevenDates);
        $storeDaysBFModUD = array();
        foreach ($modUDFStoreSevenDates as $modUDFStoreSevenDate){
            array_push($storeDaysBFModUD, date('l', strtotime($modUDFStoreSevenDate)));
        }


        $storeHoursTemp = array();
        $restaurantStoreHours = StoreHour::where('restAcc_id', $restAcc_id)->get();
        foreach ($restaurantStoreHours as $restaurantStoreHour){
            foreach (explode(",", $restaurantStoreHour->days) as $day){
                switch ($this->convertDays($day)){
                    case "Monday":
                        $num = 1;
                        break;
                    case "Tuesday":
                        $num = 2;
                        break;
                    case "Wednesday":
                        $num = 3;
                        break;
                    case "Thursday":
                        $num = 4;
                        break;
                    case "Friday":
                        $num = 5;
                        break;
                    case "Saturday":
                        $num = 6;
                        break;
                    case "Sunday":
                        $num = 7;
                        break;
                }
                
                array_push($storeHoursTemp, [
                    'num' => $num,
                    'storeDay' => $this->convertDays($day),
                    'storeOpeningTime' => $restaurantStoreHour->openingTime,
                    'storeClosingTime' => $restaurantStoreHour->closingTime,
                ]);
            }
        }

        usort($storeHoursTemp, function($a, $b) {
            return $a['num'] - $b['num'];
        });



        // 21 22 23 24 26
        // MO TU WE TH SA
        $storeTempDates = array();
        $storeNextHours = array();
        $checkPrevDay = "";
        for($i=0; $i<sizeOf($modUDFStoreSevenDates); $i++){
            //loop for seven dates
            $tempHours = array();
            for($j=0; $j<sizeOf($storeHoursTemp); $j++){
                //loop for schedule
                if(date('l', strtotime($modUDFStoreSevenDates[$i])) == $storeHoursTemp[$j]['storeDay']){
                    //meronng sched so pasok tong date na to

                    //check muna kung may store next hours, kung meron then store sa temp hours then flush
                    if($storeNextHours != null){
                        foreach($storeNextHours as $storeNextHour){
                            if($storeUDDStartDate != null){
                                for($x=0; $x<sizeOf($storeUDDStartDate); $x++){
                                    if($storeUDDStartDate[$x] == $modUDFStoreSevenDates[$i]){
                                        $add2hours = date('H:i', strtotime($storeNextHour) + 60*60*2);
                                        if(strtotime($storeUDDStartTime[$x]) <= strtotime($storeNextHour) || strtotime($storeUDDStartTime[$x]) < strtotime($add2hours)){
                                            //do nothing
                                        } else {
                                            if($checkPrevDay == $storeHoursTemp[$j]['storeDay']){
                                                //check kung yung previous day at current day ay magkasunod (e.g. Monday -> Tuesday)
                                                array_push($tempHours, date("g:i A", strtotime($storeNextHour)));
                                            }
                                        }
                                    }
                                }
                            } else {
                                array_push($tempHours, date("g:i A", strtotime($storeNextHour)));
                            }
                        }
                        $storeNextHours = array();
                    }

                    //then store na naten yung mga time
                    //pero check muna if babangga sa sinet ng unavailable date
                    $startTime = $storeHoursTemp[$j]['storeOpeningTime'];
                    $endTime = $storeHoursTemp[$j]['storeClosingTime'];
                    $status = "Open";
                    if($storeUDDStartDate != null){
                        for($x=0; $x<sizeOf($storeUDDStartDate); $x++){
                            if($storeUDDStartDate[$x] == $modUDFStoreSevenDates[$i]){
                                $add2hours = date('H:i', strtotime($startTime) + 60*60*2);
                                if(strtotime($storeUDDStartTime[$x]) <= strtotime($startTime) || strtotime($storeUDDStartTime[$x]) < strtotime($add2hours)){
                                    $status = "Closed";
                                } else {
                                    $endTime = $storeUDDStartTime[$x];
                                }
                            }
                        }
                    }

                    if($status == "Open"){
                        $time1 = strtotime($startTime);
                        $time2 = strtotime($endTime);
                        if(strtotime($endTime) >= strtotime("00:00") && strtotime($endTime) <= strtotime("04:00")){
                            $time2 = strtotime("22:00");
                            $difference = round(abs($time2 - $time1) / 3600,2);
                            $count = 0;
                            while($count <= $difference){
                                array_push($tempHours, date("g:i A", strtotime($startTime)));
                                $startTime = date('H:i', strtotime($startTime) + 60*60);
                                $count++;
                            }
                        } else {
                            $difference = round(abs($time2 - $time1) / 3600,2);
                            $count = 0;
                            while($count <= $difference-2){
                                array_push($tempHours, date("g:i A", strtotime($startTime)));
                                $startTime = date('H:i', strtotime($startTime) + 60*60);
                                $count++;
                            }
                        }
                    }
                    
                    

                    //then store yung next hours
                    $endTime2 = $storeHoursTemp[$j]['storeClosingTime'];
                    if(strtotime($endTime2) >= strtotime("00:00") && strtotime($endTime2) <= strtotime("04:00")){
                        if(strtotime($endTime2) > strtotime("00:00") && strtotime($endTime2) <= strtotime("04:00")){
                            array_push($tempHours, date("g:i A", strtotime("23:00")));
                        } 
                        
                        if(strtotime($endTime2) > strtotime("01:00") && strtotime($endTime2) <= strtotime("04:00")){
                            $checkPrevDay = $storeHoursTemp[$j]['storeDay'];
                            array_push($storeNextHours, "00:00");
                        } 
                        
                        if(strtotime($endTime2) > strtotime("02:00") && strtotime($endTime2) <= strtotime("04:00")){
                            $checkPrevDay = $storeHoursTemp[$j]['storeDay'];
                            array_push($storeNextHours, "01:00");
                        } 
                        
                        if(strtotime($endTime2) > strtotime("03:00") && strtotime($endTime2) <= strtotime("04:00")){
                            $checkPrevDay = $storeHoursTemp[$j]['storeDay'];
                            array_push($storeNextHours, "02:00");
                        }
                    }

                }
            }
            
            if($tempHours != null){
                
                $trueDate = explode('-', $modUDFStoreSevenDates[$i]);
                $year = $trueDate[0];
                $month = $this->convertMonths($trueDate[1]);
                $day = $trueDate[2];

                array_push($storeTempDates, [
                    'modDaysBFRAD' => date('l', strtotime($modUDFStoreSevenDates[$i])),
                    'modDatesBFRAD' => $modUDFStoreSevenDates[$i],
                    'storeTrueDates' => "$month $day, $year (" . date('l', strtotime($modUDFStoreSevenDates[$i])).")",
                    'modTimeBFRAT' => $tempHours,

                ]);
            }
        }

        $modDaysBFRAD2 = array();
        $modDatesBFRAD2 = array();
        $storeTrueDates2 = array();
        $modTimeBFRAT2 = array();
        foreach ($storeTempDates as $storeTempDate){
            array_push($modDaysBFRAD2, $storeTempDate['modDaysBFRAD']);
            array_push($modDatesBFRAD2, $storeTempDate['modDatesBFRAD']);
            array_push($storeTrueDates2, $storeTempDate['storeTrueDates']);
            array_push($modTimeBFRAT2, $storeTempDate['modTimeBFRAT']);
        }


        return view('customer.reservation.reserve',[
            'customer' => $customer,
            'hasLiveStatus' => $hasLiveStatus,
            'restaurant' => $restaurant,
            'orderSets' => $orderSets,
            'modDaysBFRAD2' => $modDaysBFRAD2,
            'modDatesBFRAD2' => $modDatesBFRAD2,
            'storeTrueDates2' => $storeTrueDates2,
            'modTimeBFRAT2' => $modTimeBFRAT2,
        ]);

    }

    public function confirmReservationView(){
        return view('customer.reservation.confirm-reservation');
    }
    public function viewPromoView(){
        return view('customer.reservation.view-promo');
    }
    public function liveStatusView(){
        return view('customer.reservation.livestatus');

    } 
    public function restaurantPromoView(){
        return view('customer.reservation.restaurant-promo');
    }
    public function viewReservationView(){
        $custAcc_id = Session::get('loginId');
        $customer = CustomerAccount::where('id', $custAcc_id)->first();

        $customerReserve = CustomerReserve::where('customer_id', $custAcc_id)
        ->where(function ($query) {
            $query->where('status', 'pending')
            ->orWhere('status', 'approved')
            ->orWhere('status', 'validation');
        })->latest()->first();

        $hasLiveStatus = false;
        if($customerReserve != null) {
            $hasLiveStatus = true;
        }

        $restaurantSchedule = array();
        $restuarants = RestaurantAccount::where('status', "Published")->get();
        if(!$restuarants->isEmpty()){
            foreach($restuarants as $restuarant){
                $rSchedule = $this->checkTodaySched($restuarant->id);
                array_push($restaurantSchedule, $rSchedule);
            }
        }

        return view('customer.reservation.view-restaurant',[
            'customer' => $customer,
            'hasLiveStatus' => $hasLiveStatus,
            'restuarants' => $restuarants,
            'restaurantSchedule' => $restaurantSchedule,
        ]);
    }


    //LOAD LOGICS
    public function signup(Request $request){
        $request->validate([
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'emailAddress' => 'required|email|unique:customer_accounts,emailAddress',
            'contactNumber' => 'required|digits:11',
            'password' => 'required|min:6|same:confirmPassword',
            'confirmPassword' => 'required',
        ],
        [
            'name.required' => 'Name is required',
            'name.regex' => 'Name must contain letters only',
            'emailAddress.required' => 'Email Address is required',
            'emailAddress.email' => 'Email Address must be a valid email',
            'emailAddress.unique' => 'Email Address has already taken',
            'contactNumber.required' => 'Contact Number is required',
            'contactNumber.digits' => 'Contact Number must be 11 digits',
            'password.required' => 'Password is required',
            'password.min' => 'Password must contain at least 6 characters',
            'password.same' => 'Password does not match Confirm Password',
            'confirmPassword.required' => 'Confirm Password is required',
        ]);

        CustomerAccount::create([
            'name' => $request->name,
            'emailAddress' => $request->emailAddress,
            'emailAddressVerified' => "No",
            'contactNumber' => $request->contactNumber,
            'contactNumberVerified' => "No",
            'password' => Hash::make($request->password),
            'deviceToken' => $request->deviceToken,
        ]);

        $customer = CustomerAccount::latest()->first();
        
        if(!is_dir('uploads')){
            mkdir('uploads');
            mkdir('uploads/customerAccounts');
            mkdir('uploads/customerAccounts/logo');
            mkdir('uploads/customerAccounts/gcashQr');
            mkdir('uploads/restaurantAccounts');
            mkdir('uploads/restaurantAccounts/foodItem');
            mkdir('uploads/restaurantAccounts/foodSet');
            mkdir('uploads/restaurantAccounts/orderSet');
            mkdir('uploads/restaurantAccounts/post');
            mkdir('uploads/restaurantAccounts/gcashQr');
            mkdir('uploads/restaurantAccounts/logo');
            mkdir('uploads/restaurantAccounts/promo');
        }

        mkdir('uploads/customerAccounts/logo/'.$customer->id);
        mkdir('uploads/customerAccounts/gcashQr/'.$customer->id);
        
        CustomerNotification::create([
            'customer_id' => $customer->id,
            'restAcc_id' => 0,
            'notificationType' => "New Account",
            'notificationTitle' => "Welcome to Samquicksal!",
            'notificationDescription' => "Samquicksal",
            'notificationStatus' => "Unread",
        ]);

        $request->session()->put('userType', 'customer');
        $request->session()->put('loginId', $customer->id);
        return redirect('/customer/customer-home');
    }
    public function logout(){
        Session::flush();
        return redirect('/customer/login');
    }
    public function login(Request $request){
        $request->validate([
            'emailAddress' => 'required',
            'password' => 'required'
        ],
        [
            'emailAddress.required' => 'Email Address is required',
            'password.required' => 'Password is required',
        ]);


        $account = CustomerAccount::where('emailAddress', $request->emailAddress)->first();
        if(empty($account)){
            $request->session()->flash('invalidPassword', "Incorrect Username and Password");
            return redirect('/customer/login');
        } else {
            if(Hash::check($request->password, $account->password)){
                $request->session()->put('userType', 'customer');
                $request->session()->put('loginId', $account->id);
                return redirect('/customer/customer-home');
            } else {
                $request->session()->flash('invalidPassword', "Incorrect Username and Password");
                return redirect('/customer/login');
            }
        }
    }
    
}
