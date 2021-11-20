<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Promo;
use App\Models\NoShow;
use App\Models\Policy;
use App\Models\FoodSet;
use App\Models\Runaway;
use App\Models\FoodItem;
use App\Models\OrderSet;
use App\Models\StampCard;
use App\Models\StoreHour;
use App\Models\FoodSetItem;
use App\Models\Cancellation;
use Illuminate\Http\Request;
use App\Models\CustomerQueue;
use App\Models\CustomerLOrder;
use App\Models\PromoMechanics;
use App\Models\StampCardTasks;
use Illuminate\Support\Carbon;
use App\Models\CustOffenseEach;
use App\Models\CustOffenseMain;
use App\Models\CustomerAccount;
use App\Models\CustomerReserve;
use App\Models\OrderSetFoodSet;
use App\Models\UnavailableDate;
use App\Mail\RestaurantVerified;
use App\Models\CustomerLRequest;
use App\Models\CustomerOrdering;
use App\Models\CustomerQrAccess;
use App\Models\OrderSetFoodItem;
use App\Models\CustomerStampCard;
use App\Models\CustomerTasksDone;
use App\Models\RestaurantAccount;
use App\Models\CustomerStampTasks;
use App\Models\RestaurantTaskList;
use App\Mail\RestaurantUpdateEmail;
use App\Models\RestaurantApplicant;
use App\Models\CustomerNotification;
use App\Models\RestaurantRewardList;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\RestaurantForgotPassword;
use App\Mail\RestaurantPasswordChanged;
use App\Models\RestaurantResetPassword;
use Illuminate\Support\Facades\Session;
use App\Mail\RestaurantFormAppreciation;
use App\Models\RestStampCardHis;
use App\Models\RestStampTasksHis;

class RestaurantController extends Controller
{
    public $RESTAURANT_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/logo";
    public $ACCOUNT_NO_IMAGE_PATH = "http://192.168.1.53:8000/images";


    // FOR CHECKING THE EXPIRATION DATES OF STAMPS
    public function __construct(){}

    public function sendFirebaseNotification($to, $notification, $data){
        $server_key = env('FIREBASE_SERVER_KEY');
        $url = "https://fcm.googleapis.com/fcm/send";
        $fields = json_encode(array(
            'to' => $to,
            'notification' => $notification,
            'data' => $data,
        ));

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($fields));

        $headers = array();
        $headers[] = 'Authorization: key ='.$server_key;
        $headers[] = 'Content-type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if(curl_errno($ch)){
            echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);
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
    public function checkTodaySched(){
        $restAcc_id = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status', 'id')->where('id', $restAcc_id)->first();

        $getAvailability = "Open";
        $rSchedule = "";

        $restaurantUnavailableDates = UnavailableDate::select('unavailableDatesDate')->where('restAcc_id', $restaurant->id)->get();
        foreach ($restaurantUnavailableDates as $restaurantUnavailableDate) {
            if($restaurantUnavailableDate->unavailableDatesDate == date("Y-m-d") && $restaurantUnavailableDate->startTime <= date('H:i')){
                $getAvailability = "Closed Now";
            }
        }
        
        if($getAvailability == "Open"){
            $storeDay = array();
            $storeOpeningTime = array();
            $storeClosingTime = array();

            $currentDay = date('l');
            $prevDay = date('l', strtotime(date('Y-m-d') . " -1 days"));

            $isHasCurr = false;
            $isHasCurrIndex = "";

            $isHasPrev = false;
            $isHasPrevIndex = "";

            $currentTime = date("H:i");

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
                        $rSchedule = "Open today at ".$openingTime." to ".$closingTime;
                    } else {
                        $rSchedule = "Closed Now";
                    }
                } else {
                    $rSchedule = "Closed Now";
                }
            } else if(strtotime($currentTime) >= strtotime("05:00") && strtotime($currentTime) <= strtotime("23:59")){
                if($isHasCurr){
                    if(strtotime($currentTime) >= strtotime($storeOpeningTime[$isHasCurrIndex]) && strtotime($currentTime) <= strtotime($storeClosingTime[$isHasCurrIndex])){
                        $openingTime = date("g:i a", strtotime($storeOpeningTime[$isHasCurrIndex]));
                        $closingTime = date("g:i a", strtotime($storeClosingTime[$isHasCurrIndex]));
                        $rSchedule = "Open today at ".$openingTime." to ".$closingTime;
                    } else {
                        $rSchedule = "Closed Now";
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





    // RENDER VIEWS
    public function soCustStampHistPartView($stamp_id){
        $restAcc_id = Session::get('loginId');
        $custStampCards = CustomerStampCard::where('id', $stamp_id)->where('restAcc_id', $restAcc_id)->first();
        $customerInfo = CustomerAccount::where('id', $custStampCards->customer_id)->first();

        $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
        $month2 = $this->convertMonths($userSinceDate[1]);
        $year2 = $userSinceDate[0];
        $day2  = $userSinceDate[2];
        
        $stampValidity = explode('-', date('Y-m-d', strtotime($custStampCards->stampValidity)));
        $month4 = $this->convertMonths($stampValidity[1]);
        $year4 = $stampValidity[0];
        $day4  = $stampValidity[2];

        $claimedDate = "";
        if($custStampCards->claimed == "Yes"){
            $stampClaimedDate = explode('-', date('Y-m-d', strtotime($custStampCards->updated_at)));
            $month3 = $this->convertMonths($stampClaimedDate[1]);
            $year3 = $stampClaimedDate[0];
            $day3  = $stampClaimedDate[2];

            $claimedDate = $month3." ".$day3.", ".$year3;
        }

        $storeTasksDone = array();
        $custStampTasks = CustomerTasksDone::where('customerStampCard_id', $stamp_id)->get();

        foreach($custStampTasks as $custStampTask){

            $taskDate = explode('-', date('Y-m-d', strtotime($custStampTask->taskAccomplishDate)));
            $month5 = $this->convertMonths($taskDate[1]);
            $year5 = $taskDate[0];
            $day5  = $taskDate[2];
            $taskDateFinal = $month5." ".$day5.", ".$year5;

            $bookType = "Queue";
            $transactionLink = "/restaurant/transaction-history/completed/queue/".$custStampTask->booking_id;
            if($custStampTask->booking_type == "reserve"){
                $bookType = "Reserve";
                $transactionLink = "/restaurant/transaction-history/completed/reserve/".$custStampTask->booking_id;
            }

            array_push($storeTasksDone, [
                'taskName' => $custStampTask->taskName,
                'taskDate' => $taskDateFinal,
                'bookType' => $bookType,
                'transactionLink' => $transactionLink,
            ]);
        }

        $customerTasks = CustomerStampTasks::where('customerStampCard_id', $stamp_id)->get();

        
        return view('restaurant.stampOffenses.stamp.custStampCardPart', [
            'custStampCards' => $custStampCards,
            'customerInfo' => $customerInfo,
            'userSinceDate' => $month2." ".$day2.", ".$year2,
            'stampValidity' => $month4." ".$day4.", ".$year4,
            'claimedDate' => $claimedDate,
            'storeTasksDone' => $storeTasksDone,
            'customerTasks' => $customerTasks,
        ]);
    }
    public function soCustStampHistListView(){
        $restAcc_id = Session::get('loginId');
        $storeCustStampCards = array();
        $custStampCards = CustomerStampCard::where('restAcc_id', $restAcc_id)
        ->orderBy('updated_at', 'DESC')
        ->paginate(10);

        foreach($custStampCards as $custStampCard){
            $customer = CustomerAccount::where('id', $custStampCard->customer_id)->first();

            $stampValidity = explode('-', date('Y-m-d', strtotime($custStampCard->stampValidity)));
            $month1 = $this->convertMonths($stampValidity[1]);
            $year1 = $stampValidity[0];
            $day1  = $stampValidity[2];

            array_push($storeCustStampCards, [
                'stamp_id' => $custStampCard->id,
                'custName' => $customer->name,
                'stampStatus' => $custStampCard->status,
                'stampClaimed' => $custStampCard->claimed,
                'stampReward' => $custStampCard->stampReward,
                'stampValidity' => $month1." ".$day1.", ".$year1,
            ]);
        }
        
        return view('restaurant.stampOffenses.stamp.custStampCardList', [
            'storeCustStampCards' => $storeCustStampCards,
            'custStampCards' => $custStampCards,
        ]);
    }
    public function soRunawayPartView($offense_id){
        $restAcc_id = Session::get('loginId');
        $custMainOffenses = CustOffenseMain::where('id', $offense_id)
        ->where('restAcc_id', $restAcc_id)
        ->where('offenseType', 'runaway')
        ->first();

        $customerInfo = CustomerAccount::where('id', $custMainOffenses->customer_id)->first();

        $custBlockCount = CustOffenseMain::where('customer_id', $custMainOffenses->customer_id)
        ->where('restAcc_id', $restAcc_id)
        ->where('offenseValidity', '!=', NULL)
        ->where('offenseType', 'runaway')
        ->count();

        $offenseValidity = "Not Yet Block";
        if($custMainOffenses->offenseValidity != null){
            if($custMainOffenses->offenseDaysBlock == "Permanent"){
                $offenseValidity = "Permanent Block";
            } else {
                $offenseValidityTemp = explode('-', date('Y-m-d', strtotime($custMainOffenses->offenseValidity)));
                $month1 = $this->convertMonths($offenseValidityTemp[1]);
                $year1 = $offenseValidityTemp[0];
                $day1  = $offenseValidityTemp[2];
                
                $offenseValidity = $month1." ".$day1.", ".$year1;
            }
        }


        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $custMainOffenses->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $custMainOffenses->customer_id)->count();
        $countCancelled = $countCancelled1 + $countCancelled2;

        $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
        $month2 = $this->convertMonths($userSinceDate[1]);
        $year2 = $userSinceDate[0];
        $day2  = $userSinceDate[2];

        $storeOffenseEach = array();

        $custOffenseEaches = CustOffenseEach::where('custOffMain_id', $offense_id)->where('restAcc_id', $restAcc_id)->get();
        foreach($custOffenseEaches as $custOffenseEach){
            $bookType = "Queue";
            $transactionLink = "/restaurant/transaction-history/runaway/queue/".$custOffenseEach->book_id;
            if($custOffenseEach->book_type == "reserve"){
                $bookType = "Reserve";
                $transactionLink = "/restaurant/transaction-history/runaway/reserve/".$custOffenseEach->book_id;
            }
            
            $custOffenseEachTime = date('g:i a', strtotime($custOffenseEach->created_at));
            $custOffenseEachDate = explode('-', date('Y-m-d', strtotime($custOffenseEach->created_at)));
            $month5 = $this->convertMonths($custOffenseEachDate[1]);
            $year5 = $custOffenseEachDate[0];
            $day5  = $custOffenseEachDate[2];

            array_push($storeOffenseEach, [
                'date' => $month5." ".$day5.", ".$year5,
                'time' => $custOffenseEachTime,
                'bookType' => $bookType,
                'transactionLink' => $transactionLink,
            ]);
        }
        
        return view('restaurant.stampOffenses.runaway.runawayPartView', [
            'customerInfo' => $customerInfo,
            'custMainOffenses' => $custMainOffenses,
            'countCancelled' => $countCancelled,
            'userSinceDate' => $month2." ".$day2.", ".$year2,
            'custBlockCount' => $custBlockCount,
            'offenseValidity' => $offenseValidity,
            'storeOffenseEach' => $storeOffenseEach,
        ]);
    }
    public function soRunawayListView(){
        $restAcc_id = Session::get('loginId');
        $storeName = array();
        $storeValidity = array();
        $custMainOffenses = CustOffenseMain::where('restAcc_id', $restAcc_id)
        ->where('offenseType', 'runaway')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        if(!$custMainOffenses->isEmpty()){
            foreach($custMainOffenses as $custMainOffense){
                $customer = CustomerAccount::where('id', $custMainOffense->customer_id)->first();
                array_push($storeName, $customer->name);

                if($custMainOffense->offenseValidity != null){
                    $bookDate = explode('-', date('Y-m-d', strtotime($custMainOffense->offenseValidity)));
                    $month = $this->convertMonths($bookDate[1]);
                    $year = $bookDate[0];
                    $day  = $bookDate[2];
                    array_push($storeValidity, "$month $day, $year");
                } else {
                    array_push($storeValidity, "Not Yet Block");
                }
                
            }
        }
        return view('restaurant.stampOffenses.runaway.runawayListView', [
            'custMainOffenses' => $custMainOffenses,
            'storeName' => $storeName,
            'storeValidity' => $storeValidity,
        ]);
    }
    public function soNoshowPartView($offense_id){
        $restAcc_id = Session::get('loginId');
        $custMainOffenses = CustOffenseMain::where('id', $offense_id)
        ->where('restAcc_id', $restAcc_id)
        ->where('offenseType', 'noshow')
        ->first();

        $customerInfo = CustomerAccount::where('id', $custMainOffenses->customer_id)->first();

        $custBlockCount = CustOffenseMain::where('customer_id', $custMainOffenses->customer_id)
        ->where('restAcc_id', $restAcc_id)
        ->where('offenseValidity', '!=', NULL)
        ->where('offenseType', 'noshow')
        ->count();

        $offenseValidity = "Not Yet Block";
        if($custMainOffenses->offenseValidity != null){
            if($custMainOffenses->offenseDaysBlock == "Permanent"){
                $offenseValidity = "Permanent Block";
            } else {
                $offenseValidityTemp = explode('-', date('Y-m-d', strtotime($custMainOffenses->offenseValidity)));
                $month1 = $this->convertMonths($offenseValidityTemp[1]);
                $year1 = $offenseValidityTemp[0];
                $day1  = $offenseValidityTemp[2];
                
                $offenseValidity = $month1." ".$day1.", ".$year1;
            }
        }


        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noshow')->where('customer_id', $custMainOffenses->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noshow')->where('customer_id', $custMainOffenses->customer_id)->count();
        $countCancelled = $countCancelled1 + $countCancelled2;

        $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
        $month2 = $this->convertMonths($userSinceDate[1]);
        $year2 = $userSinceDate[0];
        $day2  = $userSinceDate[2];

        $storeOffenseEach = array();

        $custOffenseEaches = CustOffenseEach::where('custOffMain_id', $offense_id)->where('restAcc_id', $restAcc_id)->get();
        foreach($custOffenseEaches as $custOffenseEach){
            $bookType = "Queue";
            $transactionLink = "/restaurant/transaction-history/no-show/queue/".$custOffenseEach->book_id;
            if($custOffenseEach->book_type == "reserve"){
                $bookType = "Reserve";
                $transactionLink = "/restaurant/transaction-history/no-show/reserve/".$custOffenseEach->book_id;
            }
            
            $custOffenseEachTime = date('g:i a', strtotime($custOffenseEach->created_at));
            $custOffenseEachDate = explode('-', date('Y-m-d', strtotime($custOffenseEach->created_at)));
            $month5 = $this->convertMonths($custOffenseEachDate[1]);
            $year5 = $custOffenseEachDate[0];
            $day5  = $custOffenseEachDate[2];

            array_push($storeOffenseEach, [
                'date' => $month5." ".$day5.", ".$year5,
                'time' => $custOffenseEachTime,
                'bookType' => $bookType,
                'transactionLink' => $transactionLink,
            ]);
        }
        
        return view('restaurant.stampOffenses.noshow.noshowPartView', [
            'customerInfo' => $customerInfo,
            'custMainOffenses' => $custMainOffenses,
            'countCancelled' => $countCancelled,
            'userSinceDate' => $month2." ".$day2.", ".$year2,
            'custBlockCount' => $custBlockCount,
            'offenseValidity' => $offenseValidity,
            'storeOffenseEach' => $storeOffenseEach,
        ]);
    }
    public function soNoshowListView(){
        $restAcc_id = Session::get('loginId');
        $storeName = array();
        $storeValidity = array();
        $custMainOffenses = CustOffenseMain::where('restAcc_id', $restAcc_id)
        ->where('offenseType', 'noshow')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        if(!$custMainOffenses->isEmpty()){
            foreach($custMainOffenses as $custMainOffense){
                $customer = CustomerAccount::where('id', $custMainOffense->customer_id)->first();
                array_push($storeName, $customer->name);

                if($custMainOffense->offenseValidity != null){
                    $bookDate = explode('-', date('Y-m-d', strtotime($custMainOffense->offenseValidity)));
                    $month = $this->convertMonths($bookDate[1]);
                    $year = $bookDate[0];
                    $day  = $bookDate[2];
                    array_push($storeValidity, "$month $day, $year");
                } else {
                    array_push($storeValidity, "Not Yet Block");
                }
                
            }
        }
        return view('restaurant.stampOffenses.noshow.noshowListView', [
            'custMainOffenses' => $custMainOffenses,
            'storeName' => $storeName,
            'storeValidity' => $storeValidity,
        ]);
    }
    public function soCancellationPartView($offense_id){
        $restAcc_id = Session::get('loginId');
        $custMainOffenses = CustOffenseMain::where('id', $offense_id)
        ->where('restAcc_id', $restAcc_id)
        ->where('offenseType', 'cancellation')
        ->first();

        $customerInfo = CustomerAccount::where('id', $custMainOffenses->customer_id)->first();

        $custBlockCount = CustOffenseMain::where('customer_id', $custMainOffenses->customer_id)
        ->where('restAcc_id', $restAcc_id)
        ->where('offenseValidity', '!=', NULL)
        ->where('offenseType', 'cancellation')
        ->count();

        $offenseValidity = "Not Yet Block";
        if($custMainOffenses->offenseValidity != null){
            if($custMainOffenses->offenseDaysBlock == "Permanent"){
                $offenseValidity = "Permanent Block";
            } else {
                $offenseValidityTemp = explode('-', date('Y-m-d', strtotime($custMainOffenses->offenseValidity)));
                $month1 = $this->convertMonths($offenseValidityTemp[1]);
                $year1 = $offenseValidityTemp[0];
                $day1  = $offenseValidityTemp[2];
                
                $offenseValidity = $month1." ".$day1.", ".$year1;
            }
        }


        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $custMainOffenses->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $custMainOffenses->customer_id)->count();
        $countCancelled = $countCancelled1 + $countCancelled2;

        $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
        $month2 = $this->convertMonths($userSinceDate[1]);
        $year2 = $userSinceDate[0];
        $day2  = $userSinceDate[2];

        $storeOffenseEach = array();

        $custOffenseEaches = CustOffenseEach::where('custOffMain_id', $offense_id)->where('restAcc_id', $restAcc_id)->get();
        foreach($custOffenseEaches as $custOffenseEach){
            $bookType = "Queue";
            $transactionLink = "/restaurant/transaction-history/cancelled/queue/".$custOffenseEach->book_id;
            if($custOffenseEach->book_type == "reserve"){
                $bookType = "Reserve";
                $transactionLink = "/restaurant/transaction-history/cancelled/reserve/".$custOffenseEach->book_id;
            }
            
            $custOffenseEachTime = date('g:i a', strtotime($custOffenseEach->created_at));
            $custOffenseEachDate = explode('-', date('Y-m-d', strtotime($custOffenseEach->created_at)));
            $month5 = $this->convertMonths($custOffenseEachDate[1]);
            $year5 = $custOffenseEachDate[0];
            $day5  = $custOffenseEachDate[2];

            array_push($storeOffenseEach, [
                'date' => $month5." ".$day5.", ".$year5,
                'time' => $custOffenseEachTime,
                'bookType' => $bookType,
                'transactionLink' => $transactionLink,
            ]);
        }
        
        return view('restaurant.stampOffenses.cancellation.cancellationPartView', [
            'customerInfo' => $customerInfo,
            'custMainOffenses' => $custMainOffenses,
            'countCancelled' => $countCancelled,
            'userSinceDate' => $month2." ".$day2.", ".$year2,
            'custBlockCount' => $custBlockCount,
            'offenseValidity' => $offenseValidity,
            'storeOffenseEach' => $storeOffenseEach,
        ]);
    }
    public function soCancellationListView(){
        $restAcc_id = Session::get('loginId');
        $storeName = array();
        $storeValidity = array();
        $custMainOffenses = CustOffenseMain::where('restAcc_id', $restAcc_id)
        ->where('offenseType', 'cancellation')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        if(!$custMainOffenses->isEmpty()){
            foreach($custMainOffenses as $custMainOffense){
                $customer = CustomerAccount::where('id', $custMainOffense->customer_id)->first();
                array_push($storeName, $customer->name);

                if($custMainOffense->offenseValidity != null){
                    $bookDate = explode('-', date('Y-m-d', strtotime($custMainOffense->offenseValidity)));
                    $month = $this->convertMonths($bookDate[1]);
                    $year = $bookDate[0];
                    $day  = $bookDate[2];
                    array_push($storeValidity, "$month $day, $year");
                } else {
                    array_push($storeValidity, "Not Yet Block");
                }
                
            }
        }
        return view('restaurant.stampOffenses.cancellation.cancellationListView', [
            'custMainOffenses' => $custMainOffenses,
            'storeName' => $storeName,
            'storeValidity' => $storeValidity,
        ]);
    }
    public function thCompletedPartRView($book_id){
        $restAcc_id = Session::get('loginId');
        $customerReserve = CustomerReserve::where('id', $book_id)->where('restAcc_id', $restAcc_id)->where('status', 'completed')->first();
        $customerInfo = CustomerAccount::where('id', $customerReserve->customer_id)->first();
        $orderSetName = $customerReserve->orderSetName;

        $countBook1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('customer_id', $customerReserve->customer_id)->count();
        $countBook2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('customer_id', $customerReserve->customer_id)->count();

        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerReserve->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerReserve->customer_id)->count();

        $countNoShow1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerReserve->customer_id)->count();
        $countNoShow2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerReserve->customer_id)->count();

        $countRunaway1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerReserve->customer_id)->count();
        $countRunaway2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerReserve->customer_id)->count();
        
        $countQueues = $countBook1 + $countBook2;
        $countCancelled = $countCancelled1 + $countCancelled2;
        $countNoShow = $countNoShow1 + $countNoShow2;
        $countRunaway = $countRunaway1 + $countRunaway2;

        $bookTime = date('g:i a', strtotime($customerReserve->created_at));

        $bookDate = explode('-', date('Y-m-d', strtotime($customerReserve->created_at)));
        $month1 = $this->convertMonths($bookDate[1]);
        $year1 = $bookDate[0];
        $day1  = $bookDate[2];

        $userSinceDateF = "N/A";
        if($customerInfo != null){
            $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
            $month2 = $this->convertMonths($userSinceDate[1]);
            $year2 = $userSinceDate[0];
            $day2  = $userSinceDate[2];
            $userSinceDateF = $month2." ".$day2.", ".$year2;
        }

        $customerOrdering = CustomerOrdering::where('custBook_id', $customerReserve->id)
        ->where('restAcc_id', $restAcc_id)
        ->where('custBookType', 'reserve')
        ->first();
        
        $mainTable = explode(',', $customerOrdering->tableNumbers);

        $cancelledTime = date('g:i a', strtotime($customerReserve->completeDateTime));
        $cancelledDate = explode('-', date('Y-m-d', strtotime($customerReserve->completeDateTime)));
        $month3 = $this->convertMonths($cancelledDate[1]);
        $year3 = $cancelledDate[0];
        $day3  = $cancelledDate[2];
        
        $reserveDate = explode('-', $customerReserve->reserveDate);
        $month4 = $this->convertMonths($reserveDate[1]);
        $year4 = $reserveDate[0];
        $day4 = $reserveDate[2];
        
        $customerOrders = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', 'Yes')->get();
        $addOns = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', 'Yes')->sum('price');
        $subTotal = ($customerReserve->orderSetPrice * $customerReserve->numberOfPersons) + $addOns;

        $finalReward = "None";
        $rewardDiscount = null;
        if($customerReserve->rewardStatus == "Complete" && $customerReserve->rewardClaimed == "Yes"){
            switch($customerReserve->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerReserve->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) * ($customerReserve->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerReserve->rewardInput person in a group";
                    $rewardDiscount = $customerReserve->orderSetPrice * $customerReserve->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerReserve->numberOfPersons * $customerReserve->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }

        $seniorDiscount = $customerReserve->orderSetPrice * ($customerReserve->numberOfPwd * 0.2);
        $childrenDiscount = $customerReserve->orderSetPrice * ($customerReserve->numberOfChildren * ($customerReserve->childrenDiscount / 100));
        $totalPrice = $subTotal - (
            $rewardDiscount + 
            $seniorDiscount + 
            $childrenDiscount + 
            $customerReserve->additionalDiscount + 
            $customerReserve->promoDiscount 
        ) + $customerReserve->offenseCharges;


        $customerQrAccess = CustomerQrAccess::where('custOrdering_id', $customerOrdering->id)
        ->where('status', "completed")
        ->get();

        $finalCustomerAccess = array();
        if(!$customerQrAccess->isEmpty()){
            foreach($customerQrAccess as $customerQr){
                $customer = CustomerAccount::where('id', $customerQr->subCust_id)->first();

                if($customer != null){
                    $userSinceDate = explode('-', date('Y-m-d', strtotime($customer->created_at)));
                    $month2 = $this->convertMonths($userSinceDate[1]);
                    $year2 = $userSinceDate[0];
                    $day2  = $userSinceDate[2];
    
                    array_push($finalCustomerAccess, [
                        'custId' => $customer->id,
                        'custName' => $customer->name,
                        'custImage' => $customer->profileImage,
                        'tableNumber' => $customerQr->tableNumber,
                        'userSince' => "$month2 $day2, $year2",
                    ]);
                }
            }
        }



        return view('restaurant.transactionHistory.completed.completedViewR', [
            'customerReserve' => $customerReserve,
            'customerInfo' => $customerInfo,
            'countQueues' => $countQueues,
            'countCancelled' => $countCancelled,
            'countNoShow' => $countNoShow,
            'countRunaway' => $countRunaway,
            'bookTime' => $bookTime,
            'bookDate' => $month1." ".$day1.", ".$year1,
            'userSinceDate' => $userSinceDateF,
            'cancelledDate' => $month3." ".$day3.", ".$year3,
            'cancelledTime' => $cancelledTime,
            'finalReward' => $finalReward,
            'rewardDiscount' => $rewardDiscount,
            'seniorDiscount' => $seniorDiscount,
            'childrenDiscount' => $childrenDiscount,
            'reserveDate' => $month4." ".$day4.", ".$year4,
            'customerOrdering' => $customerOrdering,
            'customerOrders' => $customerOrders,
            'order' => $orderSetName,
            'subTotal' => $subTotal,
            'totalPrice' => $totalPrice,
            'mainTable' => $mainTable[0],
            
            'finalCustomerAccess' => $finalCustomerAccess,
        ]);
    }
    public function thCompletedPartQView($book_id){
        $restAcc_id = Session::get('loginId');
        $customerQueue = CustomerQueue::where('id', $book_id)->where('restAcc_id', $restAcc_id)->where('status', 'completed')->first();
        $customerInfo = CustomerAccount::where('id', $customerQueue->customer_id)->first();
        $orderSetName = $customerQueue->orderSetName;

        $countBook1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('customer_id', $customerQueue->customer_id)->count();
        $countBook2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('customer_id', $customerQueue->customer_id)->count();

        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerQueue->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerQueue->customer_id)->count();

        $countNoShow1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerQueue->customer_id)->count();
        $countNoShow2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerQueue->customer_id)->count();

        $countRunaway1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerQueue->customer_id)->count();
        $countRunaway2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerQueue->customer_id)->count();
        
        $countQueues = $countBook1 + $countBook2;
        $countCancelled = $countCancelled1 + $countCancelled2;
        $countNoShow = $countNoShow1 + $countNoShow2;
        $countRunaway = $countRunaway1 + $countRunaway2;

        $bookTime = date('g:i a', strtotime($customerQueue->created_at));

        $bookDate = explode('-', date('Y-m-d', strtotime($customerQueue->created_at)));
        $month1 = $this->convertMonths($bookDate[1]);
        $year1 = $bookDate[0];
        $day1  = $bookDate[2];

        $userSinceDateF = "N/A";
        if($customerInfo != null){
            $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
            $month2 = $this->convertMonths($userSinceDate[1]);
            $year2 = $userSinceDate[0];
            $day2  = $userSinceDate[2];
            $userSinceDateF = $month2." ".$day2.", ".$year2;
        }

        $customerOrdering = CustomerOrdering::where('custBook_id', $customerQueue->id)
        ->where('restAcc_id', $restAcc_id)
        ->where('custBookType', 'queue')
        ->first();

        
        $mainTable = explode(',', $customerOrdering->tableNumbers);

        $cancelledTime = date('g:i a', strtotime($customerQueue->completeDateTime));
        $cancelledDate = explode('-', date('Y-m-d', strtotime($customerQueue->completeDateTime)));
        $month3 = $this->convertMonths($cancelledDate[1]);
        $year3 = $cancelledDate[0];
        $day3  = $cancelledDate[2];
        
        $customerOrders = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', 'Yes')->get();
        $addOns = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', 'Yes')->sum('price');
        $subTotal = ($customerQueue->orderSetPrice * $customerQueue->numberOfPersons) + $addOns;

        $finalReward = "None";
        $rewardDiscount = null;
        if($customerQueue->rewardStatus == "Complete" && $customerQueue->rewardClaimed == "Yes"){
            switch($customerQueue->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerQueue->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) * ($customerQueue->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerQueue->rewardInput person in a group";
                    $rewardDiscount = $customerQueue->orderSetPrice * $customerQueue->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerQueue->numberOfPersons * $customerQueue->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }

        $seniorDiscount = $customerQueue->orderSetPrice * ($customerQueue->numberOfPwd * 0.2);
        $childrenDiscount = $customerQueue->orderSetPrice * ($customerQueue->numberOfChildren * ($customerQueue->childrenDiscount / 100));
        $totalPrice = $subTotal - (
            $rewardDiscount + 
            $seniorDiscount + 
            $childrenDiscount + 
            $customerQueue->additionalDiscount + 
            $customerQueue->promoDiscount 
        ) + $customerQueue->offenseCharges;


        $customerQrAccess = CustomerQrAccess::where('custOrdering_id', $customerOrdering->id)
        ->where('status', "completed")
        ->get();

        $finalCustomerAccess = array();
        if(!$customerQrAccess->isEmpty()){
            foreach($customerQrAccess as $customerQr){
                $customer = CustomerAccount::where('id', $customerQr->subCust_id)->first();

                if($customer != null){
                    $userSinceDate = explode('-', date('Y-m-d', strtotime($customer->created_at)));
                    $month2 = $this->convertMonths($userSinceDate[1]);
                    $year2 = $userSinceDate[0];
                    $day2  = $userSinceDate[2];
    
                    array_push($finalCustomerAccess, [
                        'custId' => $customer->id,
                        'custName' => $customer->name,
                        'custImage' => $customer->profileImage,
                        'tableNumber' => $customerQr->tableNumber,
                        'userSince' => "$month2 $day2, $year2",
                    ]);
                }
            }
        }



        return view('restaurant.transactionHistory.completed.completedViewQ', [
            'customerQueue' => $customerQueue,
            'customerInfo' => $customerInfo,
            'countQueues' => $countQueues,
            'countCancelled' => $countCancelled,
            'countNoShow' => $countNoShow,
            'countRunaway' => $countRunaway,
            'bookTime' => $bookTime,
            'bookDate' => $month1." ".$day1.", ".$year1,
            'userSinceDate' => $userSinceDateF,
            'cancelledDate' => $month3." ".$day3.", ".$year3,
            'cancelledTime' => $cancelledTime,
            'finalReward' => $finalReward,
            'rewardDiscount' => $rewardDiscount,
            'seniorDiscount' => $seniorDiscount,
            'childrenDiscount' => $childrenDiscount,
            
            'customerOrdering' => $customerOrdering,
            'customerOrders' => $customerOrders,
            'order' => $orderSetName,
            'subTotal' => $subTotal,
            'totalPrice' => $totalPrice,
            'mainTable' => $mainTable[0],
            
            'finalCustomerAccess' => $finalCustomerAccess,
        ]);
    }
    public function thRunawayPartRView($book_id){
        $restAcc_id = Session::get('loginId');
        $customerReserve = CustomerReserve::where('id', $book_id)->where('restAcc_id', $restAcc_id)->where('status', 'runaway')->first();
        $customerInfo = CustomerAccount::where('id', $customerReserve->customer_id)->first();
        $orderSetName = $customerReserve->orderSetName;

        $countBook1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('customer_id', $customerReserve->customer_id)->count();
        $countBook2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('customer_id', $customerReserve->customer_id)->count();

        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerReserve->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerReserve->customer_id)->count();

        $countNoShow1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerReserve->customer_id)->count();
        $countNoShow2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerReserve->customer_id)->count();

        $countRunaway1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerReserve->customer_id)->count();
        $countRunaway2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerReserve->customer_id)->count();
        
        $countQueues = $countBook1 + $countBook2;
        $countCancelled = $countCancelled1 + $countCancelled2;
        $countNoShow = $countNoShow1 + $countNoShow2;
        $countRunaway = $countRunaway1 + $countRunaway2;

        $bookTime = date('g:i a', strtotime($customerReserve->created_at));

        $bookDate = explode('-', date('Y-m-d', strtotime($customerReserve->created_at)));
        $month1 = $this->convertMonths($bookDate[1]);
        $year1 = $bookDate[0];
        $day1  = $bookDate[2];

        $userSinceDateF = "N/A";
        if($customerInfo != null){
            $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
            $month2 = $this->convertMonths($userSinceDate[1]);
            $year2 = $userSinceDate[0];
            $day2  = $userSinceDate[2];
            $userSinceDateF = $month2." ".$day2.", ".$year2;
        }

        $customerOrdering = CustomerOrdering::where('custBook_id', $customerReserve->id)
        ->where('restAcc_id', $restAcc_id)
        ->where('custBookType', 'reserve')
        ->first();
        
        $mainTable = explode(',', $customerOrdering->tableNumbers);

        $cancelledTime = date('g:i a', strtotime($customerReserve->runawayDateTime));
        $cancelledDate = explode('-', date('Y-m-d', strtotime($customerReserve->runawayDateTime)));
        $month3 = $this->convertMonths($cancelledDate[1]);
        $year3 = $cancelledDate[0];
        $day3  = $cancelledDate[2];
        
        $reserveDate = explode('-', $customerReserve->reserveDate);
        $month4 = $this->convertMonths($reserveDate[1]);
        $year4 = $reserveDate[0];
        $day4 = $reserveDate[2];
        
        $customerOrders = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', 'Yes')->get();
        $addOns = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', 'Yes')->sum('price');
        $subTotal = ($customerReserve->orderSetPrice * $customerReserve->numberOfPersons) + $addOns;

        $finalReward = "None";
        $rewardDiscount = null;
        if($customerReserve->rewardStatus == "Complete" && $customerReserve->rewardClaimed == "Yes"){
            switch($customerReserve->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerReserve->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) * ($customerReserve->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerReserve->rewardInput person in a group";
                    $rewardDiscount = $customerReserve->orderSetPrice * $customerReserve->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerReserve->numberOfPersons * $customerReserve->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }

        $seniorDiscount = $customerReserve->orderSetPrice * ($customerReserve->numberOfPwd * 0.2);
        $childrenDiscount = $customerReserve->orderSetPrice * ($customerReserve->numberOfChildren * ($customerReserve->childrenDiscount / 100));
        $totalPrice = $subTotal - (
            $rewardDiscount + 
            $seniorDiscount + 
            $childrenDiscount + 
            $customerReserve->additionalDiscount + 
            $customerReserve->promoDiscount 
        ) + $customerReserve->offenseCharges;


        $customerQrAccess = CustomerQrAccess::where('custOrdering_id', $customerOrdering->id)
        ->where('status', "completed")
        ->get();

        $finalCustomerAccess = array();
        if(!$customerQrAccess->isEmpty()){
            foreach($customerQrAccess as $customerQr){
                $customer = CustomerAccount::where('id', $customerQr->subCust_id)->first();

                if($customer != null){
                    $userSinceDate = explode('-', date('Y-m-d', strtotime($customer->created_at)));
                    $month2 = $this->convertMonths($userSinceDate[1]);
                    $year2 = $userSinceDate[0];
                    $day2  = $userSinceDate[2];
    
                    array_push($finalCustomerAccess, [
                        'custId' => $customer->id,
                        'custName' => $customer->name,
                        'custImage' => $customer->profileImage,
                        'tableNumber' => $customerQr->tableNumber,
                        'userSince' => "$month2 $day2, $year2",
                    ]);
                }
            }
        }



        return view('restaurant.transactionHistory.runaway.runawayViewR', [
            'customerReserve' => $customerReserve,
            'customerInfo' => $customerInfo,
            'countQueues' => $countQueues,
            'countCancelled' => $countCancelled,
            'countNoShow' => $countNoShow,
            'countRunaway' => $countRunaway,
            'bookTime' => $bookTime,
            'bookDate' => $month1." ".$day1.", ".$year1,
            'userSinceDate' => $userSinceDateF,
            'cancelledDate' => $month3." ".$day3.", ".$year3,
            'cancelledTime' => $cancelledTime,
            'finalReward' => $finalReward,
            'rewardDiscount' => $rewardDiscount,
            'seniorDiscount' => $seniorDiscount,
            'childrenDiscount' => $childrenDiscount,
            'reserveDate' => $month4." ".$day4.", ".$year4,
            'customerOrdering' => $customerOrdering,
            'customerOrders' => $customerOrders,
            'order' => $orderSetName,
            'subTotal' => $subTotal,
            'totalPrice' => $totalPrice,
            'mainTable' => $mainTable[0],
            
            'finalCustomerAccess' => $finalCustomerAccess,
        ]);
    }
    public function thRunawayPartQView($book_id){
        $restAcc_id = Session::get('loginId');
        $customerQueue = CustomerQueue::where('id', $book_id)->where('restAcc_id', $restAcc_id)->where('status', 'runaway')->first();
        $customerInfo = CustomerAccount::where('id', $customerQueue->customer_id)->first();
        $orderSetName = $customerQueue->orderSetName;

        $countBook1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('customer_id', $customerQueue->customer_id)->count();
        $countBook2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('customer_id', $customerQueue->customer_id)->count();

        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerQueue->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerQueue->customer_id)->count();

        $countNoShow1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerQueue->customer_id)->count();
        $countNoShow2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerQueue->customer_id)->count();

        $countRunaway1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerQueue->customer_id)->count();
        $countRunaway2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerQueue->customer_id)->count();
        
        $countQueues = $countBook1 + $countBook2;
        $countCancelled = $countCancelled1 + $countCancelled2;
        $countNoShow = $countNoShow1 + $countNoShow2;
        $countRunaway = $countRunaway1 + $countRunaway2;

        $bookTime = date('g:i a', strtotime($customerQueue->created_at));

        $bookDate = explode('-', date('Y-m-d', strtotime($customerQueue->created_at)));
        $month1 = $this->convertMonths($bookDate[1]);
        $year1 = $bookDate[0];
        $day1  = $bookDate[2];

        $userSinceDateF = "N/A";
        if($customerInfo != null){
            $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
            $month2 = $this->convertMonths($userSinceDate[1]);
            $year2 = $userSinceDate[0];
            $day2  = $userSinceDate[2];
            $userSinceDateF = $month2." ".$day2.", ".$year2;
        }

        $customerOrdering = CustomerOrdering::where('custBook_id', $customerQueue->id)
        ->where('restAcc_id', $restAcc_id)
        ->where('custBookType', 'queue')
        ->first();

        
        $mainTable = explode(',', $customerOrdering->tableNumbers);

        $cancelledTime = date('g:i a', strtotime($customerQueue->runawayDateTime));
        $cancelledDate = explode('-', date('Y-m-d', strtotime($customerQueue->runawayDateTime)));
        $month3 = $this->convertMonths($cancelledDate[1]);
        $year3 = $cancelledDate[0];
        $day3  = $cancelledDate[2];
        
        $customerOrders = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', 'Yes')->get();
        $addOns = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', 'Yes')->sum('price');
        $subTotal = ($customerQueue->orderSetPrice * $customerQueue->numberOfPersons) + $addOns;

        $finalReward = "None";
        $rewardDiscount = null;
        if($customerQueue->rewardStatus == "Complete" && $customerQueue->rewardClaimed == "Yes"){
            switch($customerQueue->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerQueue->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) * ($customerQueue->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerQueue->rewardInput person in a group";
                    $rewardDiscount = $customerQueue->orderSetPrice * $customerQueue->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerQueue->numberOfPersons * $customerQueue->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }

        $seniorDiscount = $customerQueue->orderSetPrice * ($customerQueue->numberOfPwd * 0.2);
        $childrenDiscount = $customerQueue->orderSetPrice * ($customerQueue->numberOfChildren * ($customerQueue->childrenDiscount / 100));
        $totalPrice = $subTotal - (
            $rewardDiscount + 
            $seniorDiscount + 
            $childrenDiscount + 
            $customerQueue->additionalDiscount + 
            $customerQueue->promoDiscount 
        ) + $customerQueue->offenseCharges ;


        $customerQrAccess = CustomerQrAccess::where('custOrdering_id', $customerOrdering->id)
        ->where('status', "completed")
        ->get();

        $finalCustomerAccess = array();
        if(!$customerQrAccess->isEmpty()){
            foreach($customerQrAccess as $customerQr){
                $customer = CustomerAccount::where('id', $customerQr->subCust_id)->first();

                if($customer != null){
                    $userSinceDate = explode('-', date('Y-m-d', strtotime($customer->created_at)));
                    $month2 = $this->convertMonths($userSinceDate[1]);
                    $year2 = $userSinceDate[0];
                    $day2  = $userSinceDate[2];
    
                    array_push($finalCustomerAccess, [
                        'custId' => $customer->id,
                        'custName' => $customer->name,
                        'custImage' => $customer->profileImage,
                        'tableNumber' => $customerQr->tableNumber,
                        'userSince' => "$month2 $day2, $year2",
                    ]);
                }
            }
        }



        return view('restaurant.transactionHistory.runaway.runawayViewQ', [
            'customerQueue' => $customerQueue,
            'customerInfo' => $customerInfo,
            'countQueues' => $countQueues,
            'countCancelled' => $countCancelled,
            'countNoShow' => $countNoShow,
            'countRunaway' => $countRunaway,
            'bookTime' => $bookTime,
            'bookDate' => $month1." ".$day1.", ".$year1,
            'userSinceDate' => $userSinceDateF,
            'cancelledDate' => $month3." ".$day3.", ".$year3,
            'cancelledTime' => $cancelledTime,
            'finalReward' => $finalReward,
            'rewardDiscount' => $rewardDiscount,
            'seniorDiscount' => $seniorDiscount,
            'childrenDiscount' => $childrenDiscount,
            
            'customerOrdering' => $customerOrdering,
            'customerOrders' => $customerOrders,
            'order' => $orderSetName,
            'subTotal' => $subTotal,
            'totalPrice' => $totalPrice,
            'mainTable' => $mainTable[0],
            
            'finalCustomerAccess' => $finalCustomerAccess,
        ]);
    }
    public function thNoshowPartRView($book_id){
        $restAcc_id = Session::get('loginId');
        $customerReserve = CustomerReserve::where('id', $book_id)->where('restAcc_id', $restAcc_id)->where('status', 'noShow')->first();
        $customerInfo = CustomerAccount::where('id', $customerReserve->customer_id)->first();

        $countBook1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('customer_id', $customerReserve->customer_id)->count();
        $countBook2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('customer_id', $customerReserve->customer_id)->count();

        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerReserve->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerReserve->customer_id)->count();

        $countNoShow1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerReserve->customer_id)->count();
        $countNoShow2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerReserve->customer_id)->count();

        $countRunaway1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerReserve->customer_id)->count();
        $countRunaway2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerReserve->customer_id)->count();

        $countQueues = $countBook1 + $countBook2;
        $countCancelled = $countCancelled1 + $countCancelled2;
        $countNoShow = $countNoShow1 + $countNoShow2;
        $countRunaway = $countRunaway1 + $countRunaway2;

        $bookTime = date('g:i a', strtotime($customerReserve->created_at));

        $bookDate = explode('-', date('Y-m-d', strtotime($customerReserve->created_at)));
        $month1 = $this->convertMonths($bookDate[1]);
        $year1 = $bookDate[0];
        $day1  = $bookDate[2];

        $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
        $month2 = $this->convertMonths($userSinceDate[1]);
        $year2 = $userSinceDate[0];
        $day2  = $userSinceDate[2];

        $cancelledTime = date('g:i a', strtotime($customerReserve->updated_at));
        $cancelledDate = explode('-', date('Y-m-d', strtotime($customerReserve->updated_at)));
        $month3 = $this->convertMonths($cancelledDate[1]);
        $year3 = $cancelledDate[0];
        $day3  = $cancelledDate[2];
        
        $reserveDate = explode('-', $customerReserve->reserveDate);
        $month4 = $this->convertMonths($reserveDate[1]);
        $year4 = $reserveDate[0];
        $day4 = $reserveDate[2];

        $finalReward = "None";
        $rewardDiscount = null;
        if($customerReserve->rewardStatus == "Complete" && $customerReserve->rewardClaimed == "Yes"){
            switch($customerReserve->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerReserve->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) * ($customerReserve->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerReserve->rewardInput person in a group";
                    $rewardDiscount = $customerReserve->orderSetPrice * $customerReserve->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerReserve->numberOfPersons * $customerReserve->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }

        $seniorDiscount = $customerReserve->orderSetPrice * ($customerReserve->numberOfPwd * 0.2);
        $childrenDiscount = $customerReserve->orderSetPrice * $customerReserve->numberOfChildren;


        return view('restaurant.transactionHistory.noshow.noshowViewR', [
            'customerReserve' => $customerReserve,
            'customerInfo' => $customerInfo,
            'countQueues' => $countQueues,
            'countCancelled' => $countCancelled,
            'countNoShow' => $countNoShow,
            'countRunaway' => $countRunaway,
            'bookTime' => $bookTime,
            'bookDate' => $month1." ".$day1.", ".$year1,
            'userSinceDate' => $month2." ".$day2.", ".$year2,
            'cancelledDate' => $month3." ".$day3.", ".$year3,
            'cancelledTime' => $cancelledTime,
            'reserveDate' => $month4." ".$day4.", ".$year4,
            'finalReward' => $finalReward,
            'rewardDiscount' => $rewardDiscount,
            'seniorDiscount' => $seniorDiscount,
            'childrenDiscount' => $childrenDiscount,
        ]);
    }
    public function thNoshowPartQView($book_id){
        $restAcc_id = Session::get('loginId');
        $customerQueue = CustomerQueue::where('id', $book_id)->where('restAcc_id', $restAcc_id)->where('status', 'noShow')->first();
        $customerInfo = CustomerAccount::where('id', $customerQueue->customer_id)->first();

        $countBook1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('customer_id', $customerQueue->customer_id)->count();
        $countBook2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('customer_id', $customerQueue->customer_id)->count();

        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerQueue->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerQueue->customer_id)->count();

        $countNoShow1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerQueue->customer_id)->count();
        $countNoShow2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerQueue->customer_id)->count();

        $countRunaway1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerQueue->customer_id)->count();
        $countRunaway2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerQueue->customer_id)->count();
        
        $countQueues = $countBook1 + $countBook2;
        $countCancelled = $countCancelled1 + $countCancelled2;
        $countNoShow = $countNoShow1 + $countNoShow2;
        $countRunaway = $countRunaway1 + $countRunaway2;

        $bookTime = date('g:i a', strtotime($customerQueue->created_at));

        $bookDate = explode('-', date('Y-m-d', strtotime($customerQueue->created_at)));
        $month1 = $this->convertMonths($bookDate[1]);
        $year1 = $bookDate[0];
        $day1  = $bookDate[2];

        $userSinceDateF = "N/A";
        if($customerInfo != null){
            $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
            $month2 = $this->convertMonths($userSinceDate[1]);
            $year2 = $userSinceDate[0];
            $day2  = $userSinceDate[2];
            $userSinceDateF = $month2." ".$day2.", ".$year2;
        }

        $cancelledTime = date('g:i a', strtotime($customerQueue->updated_at));
        $cancelledDate = explode('-', date('Y-m-d', strtotime($customerQueue->updated_at)));
        $month3 = $this->convertMonths($cancelledDate[1]);
        $year3 = $cancelledDate[0];
        $day3  = $cancelledDate[2];

        $finalReward = "None";
        $rewardDiscount = null;
        if($customerQueue->rewardStatus == "Complete" && $customerQueue->rewardClaimed == "Yes"){
            switch($customerQueue->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerQueue->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) * ($customerQueue->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerQueue->rewardInput person in a group";
                    $rewardDiscount = $customerQueue->orderSetPrice * $customerQueue->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerQueue->numberOfPersons * $customerQueue->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }

        $seniorDiscount = $customerQueue->orderSetPrice * ($customerQueue->numberOfPwd * 0.2);
        $childrenDiscount = $customerQueue->orderSetPrice * $customerQueue->numberOfChildren;

        return view('restaurant.transactionHistory.noshow.noshowViewQ', [
            'customerQueue' => $customerQueue,
            'customerInfo' => $customerInfo,
            'countQueues' => $countQueues,
            'countCancelled' => $countCancelled,
            'countNoShow' => $countNoShow,
            'countRunaway' => $countRunaway,
            'bookTime' => $bookTime,
            'bookDate' => $month1." ".$day1.", ".$year1,
            'userSinceDate' => $userSinceDateF,
            'cancelledDate' => $month3." ".$day3.", ".$year3,
            'cancelledTime' => $cancelledTime,
            'finalReward' => $finalReward,
            'rewardDiscount' => $rewardDiscount,
            'seniorDiscount' => $seniorDiscount,
            'childrenDiscount' => $childrenDiscount,
        ]);

    }
    public function thDeclinedPartRView($book_id){
        $restAcc_id = Session::get('loginId');
        $customerReserve = CustomerReserve::where('id', $book_id)->where('restAcc_id', $restAcc_id)->where('status', 'declined')->first();
        $customerInfo = CustomerAccount::where('id', $customerReserve->customer_id)->first();

        $countBook1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('customer_id', $customerReserve->customer_id)->count();
        $countBook2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('customer_id', $customerReserve->customer_id)->count();

        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerReserve->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerReserve->customer_id)->count();

        $countNoShow1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerReserve->customer_id)->count();
        $countNoShow2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerReserve->customer_id)->count();

        $countRunaway1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerReserve->customer_id)->count();
        $countRunaway2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerReserve->customer_id)->count();

        $countQueues = $countBook1 + $countBook2;
        $countCancelled = $countCancelled1 + $countCancelled2;
        $countNoShow = $countNoShow1 + $countNoShow2;
        $countRunaway = $countRunaway1 + $countRunaway2;

        $bookTime = date('g:i a', strtotime($customerReserve->created_at));

        $bookDate = explode('-', date('Y-m-d', strtotime($customerReserve->created_at)));
        $month1 = $this->convertMonths($bookDate[1]);
        $year1 = $bookDate[0];
        $day1  = $bookDate[2];

        $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
        $month2 = $this->convertMonths($userSinceDate[1]);
        $year2 = $userSinceDate[0];
        $day2  = $userSinceDate[2];

        $cancelledTime = date('g:i a', strtotime($customerReserve->declinedDateTime));
        $cancelledDate = explode('-', date('Y-m-d', strtotime($customerReserve->declinedDateTime)));
        $month3 = $this->convertMonths($cancelledDate[1]);
        $year3 = $cancelledDate[0];
        $day3  = $cancelledDate[2];
        
        $reserveDate = explode('-', $customerReserve->reserveDate);
        $month4 = $this->convertMonths($reserveDate[1]);
        $year4 = $reserveDate[0];
        $day4 = $reserveDate[2];

        $finalReward = "None";
        $rewardDiscount = null;
        if($customerReserve->rewardStatus == "Complete" && $customerReserve->rewardClaimed == "Yes"){
            switch($customerReserve->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerReserve->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) * ($customerReserve->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerReserve->rewardInput person in a group";
                    $rewardDiscount = $customerReserve->orderSetPrice * $customerReserve->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerReserve->numberOfPersons * $customerReserve->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }

        $seniorDiscount = $customerReserve->orderSetPrice * ($customerReserve->numberOfPwd * 0.2);
        $childrenDiscount = $customerReserve->orderSetPrice * $customerReserve->numberOfChildren;



        return view('restaurant.transactionHistory.declined.declinedViewR', [
            'customerReserve' => $customerReserve,
            'customerInfo' => $customerInfo,
            'countQueues' => $countQueues,
            'countCancelled' => $countCancelled,
            'countNoShow' => $countNoShow,
            'countRunaway' => $countRunaway,
            'bookTime' => $bookTime,
            'bookDate' => $month1." ".$day1.", ".$year1,
            'userSinceDate' => $month2." ".$day2.", ".$year2,
            'cancelledDate' => $month3." ".$day3.", ".$year3,
            'cancelledTime' => $cancelledTime,
            'reserveDate' => $month4." ".$day4.", ".$year4,
            'finalReward' => $finalReward,
            'rewardDiscount' => $rewardDiscount,
            'seniorDiscount' => $seniorDiscount,
            'childrenDiscount' => $childrenDiscount,
        ]);
    }
    public function thDeclinedPartQView($book_id){
        $restAcc_id = Session::get('loginId');
        $customerQueue = CustomerQueue::where('id', $book_id)->where('restAcc_id', $restAcc_id)->where('status', 'declined')->first();
        $customerInfo = CustomerAccount::where('id', $customerQueue->customer_id)->first();

        $countBook1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('customer_id', $customerQueue->customer_id)->count();
        $countBook2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('customer_id', $customerQueue->customer_id)->count();

        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerQueue->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerQueue->customer_id)->count();

        $countNoShow1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerQueue->customer_id)->count();
        $countNoShow2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerQueue->customer_id)->count();

        $countRunaway1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerQueue->customer_id)->count();
        $countRunaway2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerQueue->customer_id)->count();

        $countQueues = $countBook1 + $countBook2;
        $countCancelled = $countCancelled1 + $countCancelled2;
        $countNoShow = $countNoShow1 + $countNoShow2;
        $countRunaway = $countRunaway1 + $countRunaway2;

        $bookTime = date('g:i a', strtotime($customerQueue->created_at));

        $bookDate = explode('-', date('Y-m-d', strtotime($customerQueue->created_at)));
        $month1 = $this->convertMonths($bookDate[1]);
        $year1 = $bookDate[0];
        $day1  = $bookDate[2];

        $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
        $month2 = $this->convertMonths($userSinceDate[1]);
        $year2 = $userSinceDate[0];
        $day2  = $userSinceDate[2];

        $cancelledTime = date('g:i a', strtotime($customerQueue->declinedDateTime));
        $cancelledDate = explode('-', date('Y-m-d', strtotime($customerQueue->declinedDateTime)));
        $month3 = $this->convertMonths($cancelledDate[1]);
        $year3 = $cancelledDate[0];
        $day3  = $cancelledDate[2];

        $finalReward = "None";
        $rewardDiscount = null;
        if($customerQueue->rewardStatus == "Complete" && $customerQueue->rewardClaimed == "Yes"){
            switch($customerQueue->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerQueue->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) * ($customerQueue->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerQueue->rewardInput person in a group";
                    $rewardDiscount = $customerQueue->orderSetPrice * $customerQueue->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerQueue->numberOfPersons * $customerQueue->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }

        $seniorDiscount = $customerQueue->orderSetPrice * ($customerQueue->numberOfPwd * 0.2);
        $childrenDiscount = $customerQueue->orderSetPrice * $customerQueue->numberOfChildren;


        return view('restaurant.transactionHistory.declined.declinedViewQ', [
            'customerQueue' => $customerQueue,
            'customerInfo' => $customerInfo,
            'countQueues' => $countQueues,
            'countCancelled' => $countCancelled,
            'countNoShow' => $countNoShow,
            'countRunaway' => $countRunaway,
            'bookTime' => $bookTime,
            'bookDate' => $month1." ".$day1.", ".$year1,
            'userSinceDate' => $month2." ".$day2.", ".$year2,
            'cancelledDate' => $month3." ".$day3.", ".$year3,
            'cancelledTime' => $cancelledTime,
            'finalReward' => $finalReward,
            'rewardDiscount' => $rewardDiscount,
            'seniorDiscount' => $seniorDiscount,
            'childrenDiscount' => $childrenDiscount,
        ]);
        
    }
    public function thCancelledPartRView($book_id){
        $restAcc_id = Session::get('loginId');
        $customerReserve = CustomerReserve::where('id', $book_id)->where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->first();
        $customerInfo = CustomerAccount::where('id', $customerReserve->customer_id)->first();

        $countBook1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('customer_id', $customerReserve->customer_id)->count();
        $countBook2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('customer_id', $customerReserve->customer_id)->count();

        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerReserve->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerReserve->customer_id)->count();

        $countNoShow1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerReserve->customer_id)->count();
        $countNoShow2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerReserve->customer_id)->count();

        $countRunaway1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerReserve->customer_id)->count();
        $countRunaway2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerReserve->customer_id)->count();

        $countQueues = $countBook1 + $countBook2;
        $countCancelled = $countCancelled1 + $countCancelled2;
        $countNoShow = $countNoShow1 + $countNoShow2;
        $countRunaway = $countRunaway1 + $countRunaway2;

        $bookTime = date('g:i a', strtotime($customerReserve->created_at));

        $bookDate = explode('-', date('Y-m-d', strtotime($customerReserve->created_at)));
        $month1 = $this->convertMonths($bookDate[1]);
        $year1 = $bookDate[0];
        $day1  = $bookDate[2];

        $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
        $month2 = $this->convertMonths($userSinceDate[1]);
        $year2 = $userSinceDate[0];
        $day2  = $userSinceDate[2];

        $cancelledTime = date('g:i a', strtotime($customerReserve->cancelDateTime));
        $cancelledDate = explode('-', date('Y-m-d', strtotime($customerReserve->cancelDateTime)));
        $month3 = $this->convertMonths($cancelledDate[1]);
        $year3 = $cancelledDate[0];
        $day3  = $cancelledDate[2];
        
        $reserveDate = explode('-', $customerReserve->reserveDate);
        $month4 = $this->convertMonths($reserveDate[1]);
        $year4 = $reserveDate[0];
        $day4 = $reserveDate[2];

        $finalReward = "None";
        $rewardDiscount = null;
        if($customerReserve->rewardStatus == "Complete" && $customerReserve->rewardClaimed == "Yes"){
            switch($customerReserve->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerReserve->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) * ($customerReserve->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerReserve->rewardInput person in a group";
                    $rewardDiscount = $customerReserve->orderSetPrice * $customerReserve->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerReserve->numberOfPersons * $customerReserve->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }

        $seniorDiscount = $customerReserve->orderSetPrice * ($customerReserve->numberOfPwd * 0.2);
        $childrenDiscount = $customerReserve->orderSetPrice * $customerReserve->numberOfChildren;



        return view('restaurant.transactionHistory.cancelled.cancelledViewR', [
            'customerReserve' => $customerReserve,
            'customerInfo' => $customerInfo,
            'countQueues' => $countQueues,
            'countCancelled' => $countCancelled,
            'countNoShow' => $countNoShow,
            'countRunaway' => $countRunaway,
            'bookTime' => $bookTime,
            'bookDate' => $month1." ".$day1.", ".$year1,
            'userSinceDate' => $month2." ".$day2.", ".$year2,
            'cancelledDate' => $month3." ".$day3.", ".$year3,
            'cancelledTime' => $cancelledTime,
            'reserveDate' => $month4." ".$day4.", ".$year4,
            'finalReward' => $finalReward,
            'rewardDiscount' => $rewardDiscount,
            'seniorDiscount' => $seniorDiscount,
            'childrenDiscount' => $childrenDiscount,
        ]);
    }
    public function thCancelledPartQView($book_id){
        $restAcc_id = Session::get('loginId');
        $customerQueue = CustomerQueue::where('id', $book_id)->where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->first();
        $customerInfo = CustomerAccount::where('id', $customerQueue->customer_id)->first();

        $countBook1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('customer_id', $customerQueue->customer_id)->count();
        $countBook2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('customer_id', $customerQueue->customer_id)->count();

        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerQueue->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerQueue->customer_id)->count();

        $countNoShow1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerQueue->customer_id)->count();
        $countNoShow2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerQueue->customer_id)->count();

        $countRunaway1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerQueue->customer_id)->count();
        $countRunaway2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerQueue->customer_id)->count();

        $countQueues = $countBook1 + $countBook2;
        $countCancelled = $countCancelled1 + $countCancelled2;
        $countNoShow = $countNoShow1 + $countNoShow2;
        $countRunaway = $countRunaway1 + $countRunaway2;

        $bookTime = date('g:i a', strtotime($customerQueue->created_at));

        $bookDate = explode('-', date('Y-m-d', strtotime($customerQueue->created_at)));
        $month1 = $this->convertMonths($bookDate[1]);
        $year1 = $bookDate[0];
        $day1  = $bookDate[2];

        $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
        $month2 = $this->convertMonths($userSinceDate[1]);
        $year2 = $userSinceDate[0];
        $day2  = $userSinceDate[2];

        $cancelledTime = date('g:i a', strtotime($customerQueue->cancelDateTime));
        $cancelledDate = explode('-', date('Y-m-d', strtotime($customerQueue->cancelDateTime)));
        $month3 = $this->convertMonths($cancelledDate[1]);
        $year3 = $cancelledDate[0];
        $day3  = $cancelledDate[2];

        $finalReward = "None";
        $rewardDiscount = null;
        if($customerQueue->rewardStatus == "Complete" && $customerQueue->rewardClaimed == "Yes"){
            switch($customerQueue->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerQueue->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) * ($customerQueue->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerQueue->rewardInput person in a group";
                    $rewardDiscount = $customerQueue->orderSetPrice * $customerQueue->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerQueue->numberOfPersons * $customerQueue->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }

        $seniorDiscount = $customerQueue->orderSetPrice * ($customerQueue->numberOfPwd * 0.2);
        $childrenDiscount = $customerQueue->orderSetPrice * $customerQueue->numberOfChildren;



        return view('restaurant.transactionHistory.cancelled.cancelledViewQ', [
            'customerQueue' => $customerQueue,
            'customerInfo' => $customerInfo,
            'countQueues' => $countQueues,
            'countCancelled' => $countCancelled,
            'countNoShow' => $countNoShow,
            'countRunaway' => $countRunaway,
            'bookTime' => $bookTime,
            'bookDate' => $month1." ".$day1.", ".$year1,
            'userSinceDate' => $month2." ".$day2.", ".$year2,
            'cancelledDate' => $month3." ".$day3.", ".$year3,
            'cancelledTime' => $cancelledTime,
            'finalReward' => $finalReward,
            'rewardDiscount' => $rewardDiscount,
            'seniorDiscount' => $seniorDiscount,
            'childrenDiscount' => $childrenDiscount,
        ]);
    }
    public function soStampHistoryListView(){
        $restAcc_id = Session::get('loginId');
        $storeStamps = array();

        $stampCards = RestStampCardHis::where('restAcc_id', $restAcc_id)
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        
        
        if(!$stampCards->isEmpty()){
            foreach($stampCards as $stampCard){
                $bookDate = explode('-', date('Y-m-d', strtotime($stampCard->stampValidity)));
                $month = $this->convertMonths($bookDate[1]);
                $year = $bookDate[0];
                $day  = $bookDate[2];

                $storeTasks = array();
                $stampCardTasks = RestStampTasksHis::where('restStampCardHis_id', $stampCard->id)->get();
                foreach($stampCardTasks as $stampCardTask){
                    array_push($storeTasks, $stampCardTask->taskName);
                }

                array_push($storeStamps, [
                    'stampCapacity' => $stampCard->stampCapacity,
                    'finalReward' => $stampCard->stampReward,
                    'stampValidity' => "$month $day, $year",
                    'storeTasks' => $storeTasks,
                ]);
            }
        }

        return view('restaurant.stampOffenses.stamp.stampCardList', [
            'storeStamps' => $storeStamps,
            'stampCards' => $stampCards,
        ]);
    }
    public function thCompletedListRView(){
        $restAcc_id = Session::get('loginId');
        $storeCustomer = array();
        $customerReserve = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'completed')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        if(!$customerReserve->isEmpty()){
            foreach($customerReserve as $custR){
                $customer = CustomerAccount::where('id', $custR->customer_id)->first();

                $bookDate = explode('-', date('Y-m-d', strtotime($custR->created_at)));
                $month = $this->convertMonths($bookDate[1]);
                $year = $bookDate[0];
                $day  = $bookDate[2];

                $time = date('g:i a', strtotime($custR->created_at));

                array_push($storeCustomer, [
                    'bookId' => $custR->id,
                    'custName' => $customer->name,
                    'bookDate' => "$month $day, $year",
                    'bookTime' => $time,
                ]);
            }
        } 

        return view('restaurant.transactionHistory.completed.completedListR', [
            'storeCustomer' => $storeCustomer,
            'customerReserve' => $customerReserve
        ]);
    }
    public function thCompletedListQView(){
        $restAcc_id = Session::get('loginId');
        $storeCustomer = array();
        $customerQueue = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'completed')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        if(!$customerQueue->isEmpty()){
            foreach($customerQueue as $custQ){
                if($custQ->customer_id == 0){
                    $customerName = $custQ->name;
                    $queueType = "Walk-in Queue";
                } else {
                    $customer = CustomerAccount::where('id', $custQ->customer_id)->first();
                    $customerName = $customer->name;
                    $queueType = "App Queue";
                }

                $bookDate = explode('-', date('Y-m-d', strtotime($custQ->created_at)));
                $month = $this->convertMonths($bookDate[1]);
                $year = $bookDate[0];
                $day  = $bookDate[2];

                $time = date('g:i a', strtotime($custQ->created_at));

                array_push($storeCustomer, [
                    'bookId' => $custQ->id,
                    'queueType' => $queueType,
                    'custName' => $customerName,
                    'bookDate' => "$month $day, $year",
                    'bookTime' => $time,
                ]);
            }
        }

        return view('restaurant.transactionHistory.completed.completedListQ', [
            'storeCustomer' => $storeCustomer,
            'customerQueue' => $customerQueue
        ]);
    }
    public function thRunawayListRView(){
        $restAcc_id = Session::get('loginId');
        $storeCustomer = array();
        $customerReserve = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        if(!$customerReserve->isEmpty()){
            foreach($customerReserve as $custR){
                $customer = CustomerAccount::where('id', $custR->customer_id)->first();

                $bookDate = explode('-', date('Y-m-d', strtotime($custR->created_at)));
                $month = $this->convertMonths($bookDate[1]);
                $year = $bookDate[0];
                $day  = $bookDate[2];

                $time = date('g:i a', strtotime($custR->created_at));

                array_push($storeCustomer, [
                    'bookId' => $custR->id,
                    'custName' => $customer->name,
                    'bookDate' => "$month $day, $year",
                    'bookTime' => $time,
                ]);
            }
        } 

        return view('restaurant.transactionHistory.runaway.runawayListR', [
            'storeCustomer' => $storeCustomer,
            'customerReserve' => $customerReserve
        ]);
    }
    public function thRunawayListQView(){
        $restAcc_id = Session::get('loginId');
        $storeCustomer = array();
        $customerQueue = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        if(!$customerQueue->isEmpty()){
            foreach($customerQueue as $custQ){
                if($custQ->customer_id == 0){
                    $customerName = $custQ->name;
                    $queueType = "Walk-in Queue";
                } else {
                    $customer = CustomerAccount::where('id', $custQ->customer_id)->first();
                    $customerName = $customer->name;
                    $queueType = "App Queue";
                }

                $bookDate = explode('-', date('Y-m-d', strtotime($custQ->created_at)));
                $month = $this->convertMonths($bookDate[1]);
                $year = $bookDate[0];
                $day  = $bookDate[2];

                $time = date('g:i a', strtotime($custQ->created_at));

                array_push($storeCustomer, [
                    'bookId' => $custQ->id,
                    'queueType' => $queueType,
                    'custName' => $customerName,
                    'bookDate' => "$month $day, $year",
                    'bookTime' => $time,
                ]);
            }
        }

        return view('restaurant.transactionHistory.runaway.runawayListQ', [
            'storeCustomer' => $storeCustomer,
            'customerQueue' => $customerQueue
        ]);
    }
    public function thNoShowListRView(){
        $restAcc_id = Session::get('loginId');
        $storeCustomer = array();
        $customerReserve = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        if(!$customerReserve->isEmpty()){
            foreach($customerReserve as $custR){
                $customer = CustomerAccount::where('id', $custR->customer_id)->first();

                $bookDate = explode('-', date('Y-m-d', strtotime($custR->created_at)));
                $month = $this->convertMonths($bookDate[1]);
                $year = $bookDate[0];
                $day  = $bookDate[2];

                $time = date('g:i a', strtotime($custR->created_at));

                array_push($storeCustomer, [
                    'bookId' => $custR->id,
                    'custName' => $customer->name,
                    'bookDate' => "$month $day, $year",
                    'bookTime' => $time,
                ]);
            }
        } 

        return view('restaurant.transactionHistory.noshow.noshowListR', [
            'storeCustomer' => $storeCustomer,
            'customerReserve' => $customerReserve
        ]);
    }
    public function thNoshowListQView(){
        $restAcc_id = Session::get('loginId');
        $storeCustomer = array();
        $customerQueue = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        if(!$customerQueue->isEmpty()){
            foreach($customerQueue as $custQ){
                if($custQ->customer_id == 0){
                    $customerName = $custQ->name;
                    $queueType = "Walk-in Queue";
                } else {
                    $customer = CustomerAccount::where('id', $custQ->customer_id)->first();
                    $customerName = $customer->name;
                    $queueType = "App Queue";
                }

                $bookDate = explode('-', date('Y-m-d', strtotime($custQ->created_at)));
                $month = $this->convertMonths($bookDate[1]);
                $year = $bookDate[0];
                $day  = $bookDate[2];

                $time = date('g:i a', strtotime($custQ->created_at));

                array_push($storeCustomer, [
                    'bookId' => $custQ->id,
                    'queueType' => $queueType,
                    'custName' => $customerName,
                    'bookDate' => "$month $day, $year",
                    'bookTime' => $time,
                ]);
            }
        } 

        return view('restaurant.transactionHistory.noshow.noshowListQ', [
            'storeCustomer' => $storeCustomer,
            'customerQueue' => $customerQueue
        ]);
    }
    public function thDeclinedListRView(){
        $restAcc_id = Session::get('loginId');
        $storeCustomer = array();
        $customerReserve = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'declined')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        if(!$customerReserve->isEmpty()){
            foreach($customerReserve as $custR){
                $customer = CustomerAccount::where('id', $custR->customer_id)->first();

                $bookDate = explode('-', date('Y-m-d', strtotime($custR->created_at)));
                $month = $this->convertMonths($bookDate[1]);
                $year = $bookDate[0];
                $day  = $bookDate[2];

                $time = date('g:i a', strtotime($custR->created_at));

                array_push($storeCustomer, [
                    'bookId' => $custR->id,
                    'custName' => $customer->name,
                    'bookDate' => "$month $day, $year",
                    'bookTime' => $time,
                ]);
            }
        } 

        return view('restaurant.transactionHistory.declined.declinedListR', [
            'storeCustomer' => $storeCustomer,
            'customerReserve' => $customerReserve
        ]);
    }
    public function thDeclinedListQView(){
        $restAcc_id = Session::get('loginId');
        $storeCustomer = array();
        $customerQueue = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'declined')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        if(!$customerQueue->isEmpty()){
            foreach($customerQueue as $custQ){
                $customer = CustomerAccount::where('id', $custQ->customer_id)->first();

                $bookDate = explode('-', date('Y-m-d', strtotime($custQ->created_at)));
                $month = $this->convertMonths($bookDate[1]);
                $year = $bookDate[0];
                $day  = $bookDate[2];

                $time = date('g:i a', strtotime($custQ->created_at));

                array_push($storeCustomer, [
                    'bookId' => $custQ->id,
                    'custName' => $customer->name,
                    'bookDate' => "$month $day, $year",
                    'bookTime' => $time,
                ]);
            }
        } 

        return view('restaurant.transactionHistory.declined.declinedListQ', [
            'storeCustomer' => $storeCustomer,
            'customerQueue' => $customerQueue
        ]);
    }
    public function thCancelledListRView(){
        $restAcc_id = Session::get('loginId');
        $storeCustomer = array();
        $customerReserve = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        if(!$customerReserve->isEmpty()){
            foreach($customerReserve as $custR){
                $customer = CustomerAccount::where('id', $custR->customer_id)->first();

                $bookDate = explode('-', date('Y-m-d', strtotime($custR->created_at)));
                $month = $this->convertMonths($bookDate[1]);
                $year = $bookDate[0];
                $day  = $bookDate[2];

                $time = date('g:i a', strtotime($custR->created_at));

                array_push($storeCustomer, [
                    'bookId' => $custR->id,
                    'custName' => $customer->name,
                    'bookDate' => "$month $day, $year",
                    'bookTime' => $time,
                ]);
            }
        } 

        return view('restaurant.transactionHistory.cancelled.cancelledListR', [
            'storeCustomer' => $storeCustomer,
            'customerReserve' => $customerReserve
        ]);
    }
    public function thCancelledListQView(){
        $restAcc_id = Session::get('loginId');
        $storeCustomer = array();
        $customerQueue = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        if(!$customerQueue->isEmpty()){
            foreach($customerQueue as $custQ){
                $customer = CustomerAccount::where('id', $custQ->customer_id)->first();

                $bookDate = explode('-', date('Y-m-d', strtotime($custQ->created_at)));
                $month = $this->convertMonths($bookDate[1]);
                $year = $bookDate[0];
                $day  = $bookDate[2];

                $time = date('g:i a', strtotime($custQ->created_at));

                array_push($storeCustomer, [
                    'bookId' => $custQ->id,
                    'custName' => $customer->name,
                    'bookDate' => "$month $day, $year",
                    'bookTime' => $time,
                ]);
            }
        } 

        return view('restaurant.transactionHistory.cancelled.cancelledListQ', [
            'storeCustomer' => $storeCustomer,
            'customerQueue' => $customerQueue
        ]);
    }
    public function ltCustOrderOSPartView($id){
        $restAcc_id = Session::get('loginId');
        $getDateToday = date('Y-m-d');
        $customerOrdering = CustomerOrdering::where('id', $id)
        ->where('restAcc_id', $restAcc_id)
        ->where('status', 'eating')
        ->where('orderingDate', $getDateToday)
        ->first();

        $gcashReceipt = null;
        if($customerOrdering->custBookType == "queue"){
            $customerQueue = CustomerQueue::where('id', $customerOrdering->custBook_id)
            ->where('restAcc_id', $restAcc_id)
            ->first();
            
            $orderSetName = $customerQueue->orderSetName;
            $customerBook = $customerQueue;

            $customer = CustomerAccount::select('profileImage', 'id')->where('id', $customerQueue->customer_id)->first();
            $gcashReceipt = $customerQueue->gcashCheckoutReceipt;
        } else {
            $customerReserve = CustomerReserve::where('id', $customerOrdering->custBook_id)
            ->where('restAcc_id', $restAcc_id)
            ->first();

            $orderSetName = $customerReserve->orderSetName;
            $customerBook = $customerReserve;

            $customer = CustomerAccount::select('profileImage', 'id')->where('id', $customerReserve->customer_id)->first();
            $gcashReceipt = $customerReserve->gcashCheckoutReceipt;
        }

        $customerOrders = CustomerLOrder::where('custOrdering_id', $id)->where('orderDone', 'Yes')->get();
        $customerRequests = CustomerLRequest::where('custOrdering_id', $id)->where('requestDone', 'Yes')->get();

        $endTime = Carbon::now();
        $customerTOrders = CustomerLOrder::select('orderSubmitDT')->where('custOrdering_id', $id)->latest('orderSubmitDT')->first();
        $customerTRequests = CustomerLRequest::select('requestSubmitDT')->where('custOrdering_id', $id)->latest('requestSubmitDT')->first();


        if($customerTOrders != null){
            $rAppstartTime = Carbon::parse($customerTOrders->orderSubmitDT);
            $rAppDiffSeconds = $endTime->diffInSeconds($rAppstartTime);
            $rAppDiffMinutes = $endTime->diffInMinutes($rAppstartTime);
            $rAppDiffHours = $endTime->diffInHours($rAppstartTime);

            if ($rAppDiffHours > 0){
                if ($rAppDiffHours == 1){
                    $rAppDiffTime = "1 hour ago";
                } else {
                    $rAppDiffTime = "$rAppDiffHours hours ago";
                }
            } else if ($rAppDiffMinutes > 0){
                if ($rAppDiffMinutes == 1){
                    $rAppDiffTime = "1 minute ago";
                } else {
                    $rAppDiffTime = "$rAppDiffMinutes minutes ago";
                }
            } else {
                if ($rAppDiffSeconds == 1){
                    $rAppDiffTime = "1 second ago";
                } else {
                    $rAppDiffTime = "$rAppDiffSeconds seconds ago";
                }
            }
        } else {
            $rAppDiffTime = "None";
        }
        

        if($customerTRequests != null){
            $rAppstartTime2 = Carbon::parse($customerTRequests->requestSubmitDT);
            $rAppDiffSeconds2 = $endTime->diffInSeconds($rAppstartTime2);
            $rAppDiffMinutes2 = $endTime->diffInMinutes($rAppstartTime2);
            $rAppDiffHours2 = $endTime->diffInHours($rAppstartTime2);
            
            if ($rAppDiffHours2 > 0){
                if ($rAppDiffHours2 == 1){
                    $rAppDiffTime2 = "1 hour ago";
                } else {
                    $rAppDiffTime2 = "$rAppDiffHours2 hours ago";
                }
            } else if ($rAppDiffMinutes2 > 0){
                if ($rAppDiffMinutes2 == 1){
                    $rAppDiffTime2 = "1 minute ago";
                } else {
                    $rAppDiffTime2 = "$rAppDiffMinutes2 minutes ago";
                }
            } else {
                if ($rAppDiffSeconds2 == 1){
                    $rAppDiffTime2 = "1 second ago";
                } else {
                    $rAppDiffTime2 = "$rAppDiffSeconds2 seconds ago";
                }
            }
        } else {
            $rAppDiffTime2 = "None";
        }
        
        $mainTable = explode(',', $customerOrdering->tableNumbers);

        $addOns = CustomerLOrder::where('custOrdering_id', $id)->where('orderDone', 'Yes')->sum('price');
        $subTotal = ($customerBook->orderSetPrice * $customerBook->numberOfPersons) + $addOns;

        $finalReward = "None";
        $rewardDiscount = 0.00;
        if($customerBook->rewardStatus == "Complete" && $customerBook->rewardClaimed == "Yes"){
            switch($customerBook->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerBook->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerBook->numberOfPersons * $customerBook->orderSetPrice) * ($customerBook->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerBook->rewardInput person in a group";
                    $rewardDiscount = $customerBook->orderSetPrice * $customerBook->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerBook->numberOfPersons * $customerBook->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerBook->numberOfPersons * $customerBook->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }

        $seniorDiscount = $customerBook->orderSetPrice * ($customerBook->numberOfPwd * 0.2);
        $childrenDiscount = $customerBook->orderSetPrice * ($customerBook->numberOfChildren * ($customerBook->childrenDiscount / 100));
        $totalPrice = $subTotal - (
            $rewardDiscount + 
            $seniorDiscount + 
            $childrenDiscount + 
            $customerBook->additionalDiscount + 
            $customerBook->promoDiscount 
        ) + $customerBook->offenseCharges ;
        
        return view('restaurant.liveTransactions.customerOrdering.custOrderSum', [
            'customerOrdering' => $customerOrdering,
            'customerOrders' => $customerOrders,
            'customerRequests' => $customerRequests,
            'order' => $orderSetName,
            'rAppDiffTime' => $rAppDiffTime,
            'rAppDiffTime2' => $rAppDiffTime2,
            'customerImage' => $customer,
            'customerBook' => $customerBook,
            'subTotal' => $subTotal,
            'seniorDiscount' => $seniorDiscount,
            'childrenDiscount' => $childrenDiscount,
            'rewardDiscount' => $rewardDiscount,
            'finalReward' => $finalReward,
            'subTotal' => $subTotal,
            'totalPrice' => $totalPrice,
            'mainTable' => $mainTable[0],
            'gcashReceipt' => $gcashReceipt,
        ]);

    }
    public function ltCustOrderORPartView($id){
        $restAcc_id = Session::get('loginId');
        $getDateToday = date('Y-m-d');
        $customerOrdering = CustomerOrdering::where('id', $id)
        ->where('restAcc_id', $restAcc_id)
        ->where('status', 'eating')
        ->where('orderingDate', $getDateToday)
        ->first();

        $customerQrAccess = CustomerQrAccess::where('custOrdering_id', $id)
        ->where('status', "approved")
        ->get();

        $finalCustomerAccess = array();
        if(!$customerQrAccess->isEmpty()){
            foreach($customerQrAccess as $customerQr){
                $customer = CustomerAccount::where('id', $customerQr->subCust_id)->first();

                if($customer != null){
                    $userSinceDate = explode('-', date('Y-m-d', strtotime($customer->created_at)));
                    $month2 = $this->convertMonths($userSinceDate[1]);
                    $year2 = $userSinceDate[0];
                    $day2  = $userSinceDate[2];
    
                    array_push($finalCustomerAccess, [
                        'custId' => $customer->id,
                        'custName' => $customer->name,
                        'custImage' => $customer->profileImage,
                        'tableNumber' => $customerQr->tableNumber,
                        'userSince' => "$month2 $day2, $year2",
                    ]);
                }
            }
        }
        
        if($customerOrdering->custBookType == "queue"){
            $customerQueue = CustomerQueue::select('orderSet_id', 'customer_id')
            ->where('id', $customerOrdering->custBook_id)
            ->where('restAcc_id', $restAcc_id)
            ->first();
            
            $orderSetName = $customerQueue->orderSetName;

            $customer = CustomerAccount::select('profileImage', 'id')->where('id', $customerQueue->customer_id)->first();
            
        } else {
            $customerReserve = CustomerReserve::select('orderSet_id', 'customer_id')
            ->where('id', $customerOrdering->custBook_id)
            ->where('restAcc_id', $restAcc_id)
            ->first();

            $orderSetName = $customerReserve->orderSetName;

            $customer = CustomerAccount::select('profileImage', 'id')->where('id', $customerReserve->customer_id)->first();
        }

        $customerOrders = CustomerLOrder::where('custOrdering_id', $id)->where('orderDone', 'No')->get();
        $customerRequests = CustomerLRequest::where('custOrdering_id', $id)->where('requestDone', 'No')->get();

        $endTime = Carbon::now();
        $customerTOrders = CustomerLOrder::select('orderSubmitDT')->where('custOrdering_id', $id)->latest('orderSubmitDT')->first();
        $customerTRequests = CustomerLRequest::select('requestSubmitDT')->where('custOrdering_id', $id)->latest('requestSubmitDT')->first();

        if($customerTOrders != null){
            $rAppstartTime = Carbon::parse($customerTOrders->orderSubmitDT);
            $rAppDiffSeconds = $endTime->diffInSeconds($rAppstartTime);
            $rAppDiffMinutes = $endTime->diffInMinutes($rAppstartTime);
            $rAppDiffHours = $endTime->diffInHours($rAppstartTime);

            if ($rAppDiffHours > 0){
                if ($rAppDiffHours == 1){
                    $rAppDiffTime = "1 hour ago";
                } else {
                    $rAppDiffTime = "$rAppDiffHours hours ago";
                }
            } else if ($rAppDiffMinutes > 0){
                if ($rAppDiffMinutes == 1){
                    $rAppDiffTime = "1 minute ago";
                } else {
                    $rAppDiffTime = "$rAppDiffMinutes minutes ago";
                }
            } else {
                if ($rAppDiffSeconds == 1){
                    $rAppDiffTime = "1 second ago";
                } else {
                    $rAppDiffTime = "$rAppDiffSeconds seconds ago";
                }
            }
        } else {
            $rAppDiffTime = "None";
        }
        

        if($customerTRequests != null){
            $rAppstartTime2 = Carbon::parse($customerTRequests->requestSubmitDT);
            $rAppDiffSeconds2 = $endTime->diffInSeconds($rAppstartTime2);
            $rAppDiffMinutes2 = $endTime->diffInMinutes($rAppstartTime2);
            $rAppDiffHours2 = $endTime->diffInHours($rAppstartTime2);
            
            if ($rAppDiffHours2 > 0){
                if ($rAppDiffHours2 == 1){
                    $rAppDiffTime2 = "1 hour ago";
                } else {
                    $rAppDiffTime2 = "$rAppDiffHours2 hours ago";
                }
            } else if ($rAppDiffMinutes2 > 0){
                if ($rAppDiffMinutes2 == 1){
                    $rAppDiffTime2 = "1 minute ago";
                } else {
                    $rAppDiffTime2 = "$rAppDiffMinutes2 minutes ago";
                }
            } else {
                if ($rAppDiffSeconds2 == 1){
                    $rAppDiffTime2 = "1 second ago";
                } else {
                    $rAppDiffTime2 = "$rAppDiffSeconds2 seconds ago";
                }
            }
        } else {
            $rAppDiffTime2 = "None";
        }


        $mainTable = explode(',', $customerOrdering->tableNumbers);
        
        $finalCustomerOrders = array();
        foreach($customerOrders as $customerOrder){
            $food = FoodItem::where('id', $customerOrder->foodItem_id)->first();
            array_push($finalCustomerOrders, [
                'orderId' => $customerOrder->id,
                'foodItemImage' => $food->foodItemImage,
                'foodItemName' => $customerOrder->foodItemName,
                'quantity' => $customerOrder->quantity,
                'price' => $customerOrder->price,
                'tableNumber' => $customerOrder->tableNumber,
            ]);
        }

        return view('restaurant.liveTransactions.customerOrdering.custOrderReq', [
            'customerOrdering' => $customerOrdering,
            'finalCustomerOrders' => $finalCustomerOrders,
            'customerOrders' => $customerOrders,
            'customerRequests' => $customerRequests,
            'order' => $orderSetName,
            'rAppDiffTime' => $rAppDiffTime,
            'rAppDiffTime2' => $rAppDiffTime2,
            'customer' => $customer,
            'finalCustomerAccess' => $finalCustomerAccess,
            'restAcc_id' => $restAcc_id,
            'mainTable' => $mainTable[0],
        ]);
    }
    public function ltCustOrderListView(){
        $restAcc_id = Session::get('loginId');
        $getDateToday = date('Y-m-d');
        $eatingCustomers = CustomerOrdering::where('restAcc_id', $restAcc_id)->where('status', 'eating')->where('orderingDate', $getDateToday)->get();
        $restaurant = RestaurantAccount::select('rNumberOfTables')->where('id', $restAcc_id)->first();


        $customers = array();
        foreach ($eatingCustomers as $eatingCustomer){
            $customerTableNumbers = explode(',', $eatingCustomer->tableNumbers);

            $countOrders = CustomerLOrder::where('custOrdering_id', $eatingCustomer->id)->where('orderDone', 'No')->count();
            $countRequests = CustomerLRequest::where('custOrdering_id', $eatingCustomer->id)->where('requestDone', 'No')->count();

            if($countOrders == null){
                $countOrders = 0;
            }
            if($countRequests == null){
                $countRequests = 0;
            }

            if($eatingCustomer->custBookType == "queue"){
                $customerQueue = CustomerQueue::select('eatingDateTime', 'hoursOfStay')->where('id', $eatingCustomer->custBook_id)->first();
                $finaltime = "";
                $finalminutes = "";
                $finalseconds = "";
                if($customerQueue->eatingDateTime != null){
                    $starTime = Carbon::now(0);
                    $endTime = Carbon::parse($customerQueue->eatingDateTime)->addHours($customerQueue->hoursOfStay);
                    
                    $cAccDiffSeconds = $endTime->diffInSeconds($starTime);
                    $cAccDiffMinutes = $endTime->diffInMinutes($starTime);
                    $cAccDiffHours = $endTime->diffInHours($starTime);

                    if($cAccDiffHours == 0){
                        $getminutes = $cAccDiffMinutes;
                    } else {
                        $getminutes = $cAccDiffMinutes - (60 * ($customerQueue->hoursOfStay - $cAccDiffHours));
                    }

                    $getSeconds = $cAccDiffSeconds - (3600 * ($customerQueue->hoursOfStay - $cAccDiffHours) + (60 * ($cAccDiffMinutes - (60 * ($customerQueue->hoursOfStay - $cAccDiffHours)))));

                    if($getminutes <= 9){
                        $finalminutes = "0$getminutes";
                    } else {
                        $finalminutes = "$getminutes";
                    }

                    if($getSeconds <= 9){
                        $finalseconds = "0$getSeconds";
                    } else {
                        $finalseconds = "$getSeconds";
                    }
                    
                    if($starTime >= $endTime){
                        $finaltime = "00h : 00m : 00s";
                    } else {
                        $finaltime = "0$cAccDiffHours"."h : ".$finalminutes."m : ".$finalseconds."s";
                    }

                } else {
                    $finaltime = "0".$customerQueue->hoursOfStay."h : 00m : 00s";
                }
            } else {
                $customerReserve = CustomerReserve::select('eatingDateTime', 'hoursOfStay')->where('id', $eatingCustomer->custBook_id)->first();
                $finaltime = "";
                $finalminutes = "";
                $finalseconds = "";
                if($customerReserve->eatingDateTime != null){
                    $starTime = Carbon::now(0);
                    $endTime = Carbon::parse($customerReserve->eatingDateTime)->addHours($customerReserve->hoursOfStay);
                    
                    $cAccDiffSeconds = $endTime->diffInSeconds($starTime);
                    $cAccDiffMinutes = $endTime->diffInMinutes($starTime);
                    $cAccDiffHours = $endTime->diffInHours($starTime);

                    if($cAccDiffHours == 0){
                        $getminutes = $cAccDiffMinutes;
                    } else {
                        $getminutes = $cAccDiffMinutes - (60 * ($customerReserve->hoursOfStay - $cAccDiffHours));
                    }

                    $getSeconds = $cAccDiffSeconds - (3600 * ($customerReserve->hoursOfStay - $cAccDiffHours) + (60 * ($cAccDiffMinutes - (60 * ($customerReserve->hoursOfStay - $cAccDiffHours)))));

                    if($getminutes <= 9){
                        $finalminutes = "0$getminutes";
                    } else {
                        $finalminutes = "$getminutes";
                    }

                    if($getSeconds <= 9){
                        $finalseconds = "0$getSeconds";
                    } else {
                        $finalseconds = "$getSeconds";
                    }
                    
                    if($starTime >= $endTime){
                        $finaltime = "00h : 00m : 00s";
                    } else {
                        $finaltime = "0$cAccDiffHours"."h : ".$finalminutes."m : ".$finalseconds."s";
                    }

                } else {
                    $finaltime = "0".$customerReserve->hoursOfStay."h : 00m : 00s";
                }
            }

            if(sizeOf($customerTableNumbers) == 1){
                array_push($customers, [
                    'custOrdering_id' => $eatingCustomer->id,
                    'tableNumber' => $eatingCustomer->tableNumbers,
                    'countOrders' => $countOrders,
                    'countRequests' => $countRequests,
                    'grantedAccess' => $eatingCustomer->grantedAccess,
                    'finaltime' => $finaltime,
                ]);
            } else if (sizeOf($customerTableNumbers) > 1){
                foreach ($customerTableNumbers as $customerTableNumber){
                    array_push($customers, [
                        'custOrdering_id' => $eatingCustomer->id,
                        'tableNumber' => $customerTableNumber,
                        'countOrders' => $countOrders,
                        'countRequests' => $countRequests,
                        'grantedAccess' => $eatingCustomer->grantedAccess,
                        'finaltime' => $finaltime,
                    ]);
                }
            } else {}
        }

        $finalCustomers = array();
        for ($i=1; $i<=$restaurant->rNumberOfTables; $i++){
            $checker = 0;
            foreach ($customers as $customer){
                if($i == $customer['tableNumber']){
                    $checker++;
                    array_push($finalCustomers, [
                        'custOrdering_id' => $customer['custOrdering_id'],
                        'tableNumber' => "T$i",
                        'countOrders' => $customer['countOrders'],
                        'countRequests' => $customer['countRequests'],
                        'grantedAccess' => $customer['grantedAccess'],
                        'finaltime' => $customer['finaltime'],
                    ]);
                }
            }
            if($checker == 0){
                array_push($finalCustomers, [
                    'custOrdering_id' => "",
                    'tableNumber' => "T$i",
                    'countOrders' => "",
                    'countRequests' => "",
                    'grantedAccess' => "",
                    'finaltime' => "",
                ]);
            }
        }
        return view('restaurant.liveTransactions.customerOrdering.custOrderingList', [
            'eatingCustomers' => $eatingCustomers,
            'finalCustomers' => $finalCustomers,
        ]);
    }
    public function ltAppCustQAddWalkInView(){
        $restAcc_id = Session::get('loginId');
        $restaurant = RestaurantAccount::select('rTimeLimit', 'rCapacityPerTable')->where('id', $restAcc_id)->first();
        $orderSets = OrderSet::where('restAcc_id', $restAcc_id)->where('status', "Visible")->where('available', "Yes")->get();

        return view('restaurant.liveTransactions.approvedCustomer.addWalkin', [
            'rTimeLimit' => $restaurant->rTimeLimit,
            'rCapacityPerTable' => $restaurant->rCapacityPerTable,
            'orderSets' => $orderSets,
        ]);
    }
    public function ltAppCustRParticularView($id){
        $restAcc_id = Session::get('loginId');
        $customerReserve = CustomerReserve::where('id', $id)
        ->where('restAcc_id', $restAcc_id)
        ->where(function ($query) {
            $query->where('status', "approved")
            ->orWhere('status', "validation");
        })->first();

        $restaurant = RestaurantAccount::select('rNumberOfTables')->where('id', $restAcc_id)->first();
        $totalTables = 0;
        $customerOrderings = CustomerOrdering::where('restAcc_id', $restAcc_id)->where('status', 'eating')->get();
        if(!$customerOrderings->isEmpty()){
            foreach($customerOrderings as $customerOrdering){
                if($customerOrdering->custBookType == "queue"){
                    $customerQueue2 = CustomerQueue::select('numberOfTables')->where('id', $customerOrdering->custBook_id)->first();
                    $totalTables += $customerQueue2->numberOfTables;
                } else {
                    $customerReserve2 = CustomerReserve::select('numberOfTables')->where('id', $customerOrdering->custBook_id)->first();
                    $totalTables += $customerReserve2->numberOfTables;
                }
            }
        }

        $customerInfo = CustomerAccount::where('id', $customerReserve->customer_id)->first();
    
        $countBook1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('customer_id', $customerReserve->customer_id)->count();
        $countBook2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('customer_id', $customerReserve->customer_id)->count();

        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerReserve->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerReserve->customer_id)->count();

        $countNoShow1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerReserve->customer_id)->count();
        $countNoShow2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerReserve->customer_id)->count();

        $countRunaway1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerReserve->customer_id)->count();
        $countRunaway2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerReserve->customer_id)->count();

        $countQueues = $countBook1 + $countBook2;
        $countCancelled = $countCancelled1 + $countCancelled2;
        $countNoShow = $countNoShow1 + $countNoShow2;
        $countRunaway = $countRunaway1 + $countRunaway2;

        $bookTime = date('g:i a', strtotime($customerReserve->created_at));

        $bookDate = explode('-', date('Y-m-d', strtotime($customerReserve->created_at)));
        $month1 = $this->convertMonths($bookDate[1]);
        $year1 = $bookDate[0];
        $day1  = $bookDate[2];

        $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
        $month2 = $this->convertMonths($userSinceDate[1]);
        $year2 = $userSinceDate[0];
        $day2  = $userSinceDate[2];

        $finalReward = "None";
        $rewardDiscount = null;
        if($customerReserve->rewardStatus == "Complete" && $customerReserve->rewardClaimed == "Yes"){
            switch($customerReserve->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerReserve->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) * ($customerReserve->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerReserve->rewardInput person in a group";
                    $rewardDiscount = $customerReserve->orderSetPrice * $customerReserve->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerReserve->numberOfPersons * $customerReserve->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }

        $seniorDiscount = $customerReserve->orderSetPrice * ($customerReserve->numberOfPwd * 0.2);
        $childrenDiscount = 0.0;

        $dateTime = date("Y-m-d H:i:s", strtotime("$customerReserve->reserveDate $customerReserve->reserveTime"));
        $endTime = Carbon::parse($dateTime);
        $currentTime = Carbon::now();

        $rAppDiffSeconds = $endTime->diffInSeconds($currentTime);
        $rAppDiffMinutes = $endTime->diffInMinutes($currentTime);
        $rAppDiffHours = $endTime->diffInHours($currentTime);
        $rAppDiffDays = $endTime->diffInDays($currentTime);


        if($rAppDiffDays > 0){
            if ($rAppDiffDays == 1){
                $rAppDiffTime = "1";
                $rAppDiffTimeLabel = "day left";
            } else {
                $rAppDiffTime = "$rAppDiffDays";
                $rAppDiffTimeLabel = "days left";
            }
        } else if ($rAppDiffHours > 0){
            if ($rAppDiffHours == 1){
                $rAppDiffTime = "1";
                $rAppDiffTimeLabel = "hour left";
            } else {
                $rAppDiffTime = "$rAppDiffHours";
                $rAppDiffTimeLabel = "hours left";
            }
        } else if ($rAppDiffMinutes > 0){
            if ($rAppDiffMinutes == 1){
                $rAppDiffTime = "1";
                $rAppDiffTimeLabel = "minute left";
            } else {
                $rAppDiffTime = "$rAppDiffMinutes";
                $rAppDiffTimeLabel = "minutes left";
            }
        } else {
            if ($rAppDiffSeconds == 1){
                $rAppDiffTime = "1";
                $rAppDiffTimeLabel = "second left";
            } else {
                $rAppDiffTime = "$rAppDiffSeconds";
                $rAppDiffTimeLabel = "seconds left";
            }
        }

        if($endTime < $currentTime){
            $rAppDiffTime = 0;
            $rAppDiffTimeLabel = "second left";
        }

        $remainingTables = $restaurant->rNumberOfTables - $totalTables;

        $canValidate = false;
        if(($rAppDiffTime <= 15 && ($rAppDiffTimeLabel == "minutes left" || $rAppDiffTimeLabel == "minute left")) || 
            ($rAppDiffTime <= 60 && ($rAppDiffTimeLabel == "seconds left" || $rAppDiffTimeLabel == "second left")) ){
            $canValidate = true;
        }

        $reserveDate = explode('-', $customerReserve->reserveDate);
        $month3 = $this->convertMonths($reserveDate[1]);
        $year3 = $reserveDate[0];
        $day3  = $reserveDate[2];

        $validationTimeLeft = null;
        if($customerReserve->status == "validation"){
            $currentTime = Carbon::now();
            $rAppstartTime = Carbon::parse($customerReserve->validationDateTime);
            $rAppDiffSeconds = $currentTime->diffInSeconds($rAppstartTime);
            $rAppDiffMinutes = $currentTime->diffInMinutes($rAppstartTime);

            $rAppDiffMinutes = 15 - $rAppDiffMinutes;
            $rAppDiffSeconds = (15 * 60) - $rAppDiffSeconds;

            if ($rAppDiffMinutes > 1){
                $validationTimeLeft = "$rAppDiffMinutes minutes";
            } else if ($rAppDiffMinutes == 1) {
                if ($rAppDiffSeconds == 1){
                    $validationTimeLeft = "1 second";
                } else {
                    $validationTimeLeft = "$rAppDiffSeconds seconds";
                }
            } else {
                $validationTimeLeft = "0 second";
            }
        }

        return view('restaurant.liveTransactions.approvedCustomer.reserveView',[
            'customerReserve' => $customerReserve,
            'customerInfo' => $customerInfo,
            'countQueues' => $countQueues,
            'countCancelled' => $countCancelled,
            'countNoShow' => $countNoShow,
            'countRunaway' => $countRunaway,
            'bookTime' => $bookTime,
            'bookDate' => $month1." ".$day1.", ".$year1,
            'userSinceDate' => $month2." ".$day2.", ".$year2,
            'finalReward' => $finalReward,
            'rewardDiscount' => $rewardDiscount,
            'seniorDiscount' => $seniorDiscount,
            'childrenDiscount' => $childrenDiscount,
            'remainingTables' => $remainingTables,
            'validationTimeLeft' => $validationTimeLeft,
            'rAppDiffTime' => $rAppDiffTime,
            'rAppDiffTimeLabel' => $rAppDiffTimeLabel,
            'reserveDate' => $month3." ".$day3.", ".$year3,
            'canValidate' => $canValidate,
        ]);
        
    }
    public function ltAppCustRListView(){
        $restAcc_id = Session::get('loginId');

        $customerReserves = CustomerReserve::where('restAcc_id', $restAcc_id)
        ->where(function ($query) {
            $query->where('status', "approved")
            ->orWhere('status', "validation");
        })
        ->orderBy('reserveDate', 'ASC')
        ->paginate(10);

        $storeDate = array();
        $storeDateTimeLeft = array();
        $customerNames = array();
        if(!$customerReserves->isEmpty()){
            foreach ($customerReserves as $customerReserve){
                $getCustName = CustomerAccount::select('name')->where('id', $customerReserve->customer_id)->first();
                array_push($customerNames, $getCustName->name);

                $reserveDate = explode('-', date('Y-m-d', strtotime($customerReserve->reserveDate)));
                $month1 = $this->convertMonths($reserveDate[1]);
                $year1 = $reserveDate[0];
                $day1  = $reserveDate[2];
                array_push($storeDate, "$month1 $day1, $year1");

                $dateTime = date("Y-m-d H:i:s", strtotime("$customerReserve->reserveDate $customerReserve->reserveTime"));
                $endTime = Carbon::parse($dateTime);
                $currentTime = Carbon::now();

                $rAppDiffSeconds = $endTime->diffInSeconds($currentTime);
                $rAppDiffMinutes = $endTime->diffInMinutes($currentTime);
                $rAppDiffHours = $endTime->diffInHours($currentTime);
                $rAppDiffDays = $endTime->diffInDays($currentTime);

                if($rAppDiffDays > 0){
                    if ($rAppDiffDays == 1){
                        $rAppDiffTime = "1 day left";
                    } else {
                        $rAppDiffTime = "$rAppDiffDays days left";
                    }
                } else if ($rAppDiffHours > 0){
                    if ($rAppDiffHours == 1){
                        $rAppDiffTime = "1 hour left";
                    } else {
                        $rAppDiffTime = "$rAppDiffHours hours left";
                    }
                } else if ($rAppDiffMinutes > 0){
                    if ($rAppDiffMinutes == 1){
                        $rAppDiffTime = "1 minute left";
                    } else {
                        $rAppDiffTime = "$rAppDiffMinutes minutes left";
                    }
                } else {
                    if ($rAppDiffSeconds == 1){
                        $rAppDiffTime = "1 second left";
                    } else {
                        $rAppDiffTime = "$rAppDiffSeconds seconds left";
                    }
                }
                array_push($storeDateTimeLeft, $rAppDiffTime);
            }
        }

        return view('restaurant.liveTransactions.approvedCustomer.reserveList',[
            'customerReserves' => $customerReserves,
            'customerNames' => $customerNames,
            'storeDateTimeLeft' => $storeDateTimeLeft,
            'storeDate' => $storeDate,
        ]);

    }
    public function ltAppCustQParticularView($id){
        $restAcc_id = Session::get('loginId');
        $customerQueue = CustomerQueue::where('id', $id)
        ->where('restAcc_id', $restAcc_id)
        ->where(function ($query) {
            $query->where('status', "approved")
            ->orWhere('status', "validation");
        })->first();
        $restaurant = RestaurantAccount::select('rNumberOfTables')->where('id', $restAcc_id)->first();
        $totalTables = 0;
        $customerOrderings = CustomerOrdering::where('restAcc_id', $restAcc_id)->where('status', 'eating')->get();
        if(!$customerOrderings->isEmpty()){
            foreach($customerOrderings as $customerOrdering){
                if($customerOrdering->custBookType == "queue"){
                    $customerQueue2 = CustomerQueue::select('numberOfTables')->where('id', $customerOrdering->custBook_id)->first();
                    $totalTables += $customerQueue2->numberOfTables;
                } else {
                    $customerReserve2 = CustomerReserve::select('numberOfTables')->where('id', $customerOrdering->custBook_id)->first();
                    $totalTables += $customerReserve2->numberOfTables;
                }
            }
        }

        if($customerQueue->customer_id != 0){
            $customerInfo = CustomerAccount::where('id', $customerQueue->customer_id)->first();
    
            $countBook1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('customer_id', $customerQueue->customer_id)->count();
            $countBook2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('customer_id', $customerQueue->customer_id)->count();

            $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerQueue->customer_id)->count();
            $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerQueue->customer_id)->count();

            $countNoShow1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerQueue->customer_id)->count();
            $countNoShow2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerQueue->customer_id)->count();

            $countRunaway1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerQueue->customer_id)->count();
            $countRunaway2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerQueue->customer_id)->count();

            $countQueues = $countBook1 + $countBook2;
            $countCancelled = $countCancelled1 + $countCancelled2;
            $countNoShow = $countNoShow1 + $countNoShow2;
            $countRunaway = $countRunaway1 + $countRunaway2;
    
            $bookTime = date('g:i a', strtotime($customerQueue->created_at));
    
            $bookDate = explode('-', date('Y-m-d', strtotime($customerQueue->created_at)));
            $month1 = $this->convertMonths($bookDate[1]);
            $year1 = $bookDate[0];
            $day1  = $bookDate[2];
    
            $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
            $month2 = $this->convertMonths($userSinceDate[1]);
            $year2 = $userSinceDate[0];
            $day2  = $userSinceDate[2];
    
            $finalReward = "None";
            $rewardDiscount = null;
            if($customerQueue->rewardStatus == "Complete" && $customerQueue->rewardClaimed == "Yes"){
                switch($customerQueue->rewardType){
                    case "DSCN": 
                        $finalReward = "Discount $customerQueue->rewardInput% in a Total Bill";
                        $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) * ($customerQueue->rewardInput / 100);
                        break;
                    case "FRPE": 
                        $finalReward = "Free $customerQueue->rewardInput person in a group";
                        $rewardDiscount = $customerQueue->orderSetPrice * $customerQueue->rewardInput;
                        break;
                    case "HLF": 
                        $finalReward = "Half in the group will be free";
                        $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) / 2;
                        break;
                    case "ALL": 
                        $finalReward = "All people in the group will be free";
                        $rewardDiscount = $customerQueue->numberOfPersons * $customerQueue->orderSetPrice;
                        break;
                    default: 
                        $finalReward = "None";
                }
            }
    
    
            $seniorDiscount = $customerQueue->orderSetPrice * ($customerQueue->numberOfPwd * 0.2);
            $childrenDiscount = $customerQueue->orderSetPrice * $customerQueue->numberOfChildren;

            $getDateToday = date("Y-m-d");
            $checkPriorities = CustomerQueue::where('restAcc_id', $restAcc_id)
                            ->where(function ($query) {
                                $query->where('status', "approved")
                                ->orWhere('status', "validation");
                            })
                            ->where('queueDate', $getDateToday)
                            ->orderBy('totalPwdChild', 'DESC')
                            ->orderBy('approvedDateTime', 'ASC')->first();

            $isPriority = false;
            if($checkPriorities->id == $id){
                $isPriority = true;
            } else {
                $isPriority = false;
            }

            $countCustomersApproved = CustomerQueue::where('queueDate', $getDateToday)
                ->where(function ($query) {
                    $query->where('status', "approved")
                    ->orWhere('status', "validation");
                })
                ->orderBy('totalPwdChild', 'DESC')
                ->orderBy('validationDateTime', 'ASC')
                ->orderBy('approvedDateTime', 'ASC')
                ->get();

            $finalQueueNumber = 0;
            $queueNumber = 0;
            foreach ($countCustomersApproved as $countCustomerApproved){
                $queueNumber++;
                if($countCustomerApproved->id == $id){
                    $finalQueueNumber = $queueNumber;
                }
            }

            $remainingTables = $restaurant->rNumberOfTables - $totalTables;

            $validationTimeLeft = null;
            if($customerQueue->status == "validation"){
                $currentTime = Carbon::now();
                $rAppstartTime = Carbon::parse($customerQueue->validationDateTime);
                $rAppDiffSeconds = $currentTime->diffInSeconds($rAppstartTime);
                $rAppDiffMinutes = $currentTime->diffInMinutes($rAppstartTime);

                $rAppDiffMinutes = 15 - $rAppDiffMinutes;
                $rAppDiffSeconds = (15 * 60) - $rAppDiffSeconds;

                if ($rAppDiffMinutes > 1){
                    $validationTimeLeft = "$rAppDiffMinutes minutes";
                } else if ($rAppDiffMinutes == 1) {
                    if ($rAppDiffSeconds == 1){
                        $validationTimeLeft = "1 second";
                    } else {
                        $validationTimeLeft = "$rAppDiffSeconds seconds";
                    }
                } else {
                    $validationTimeLeft = "0 second";
                }
            }


            return view('restaurant.liveTransactions.approvedCustomer.queueView',[
                'customerQueue' => $customerQueue,
                'customerInfo' => $customerInfo,
                'countQueues' => $countQueues,
                'countCancelled' => $countCancelled,
                'countNoShow' => $countNoShow,
                'countRunaway' => $countRunaway,
                'bookTime' => $bookTime,
                'bookDate' => $month1." ".$day1.", ".$year1,
                'userSinceDate' => $month2." ".$day2.", ".$year2,
                'finalReward' => $finalReward,
                'rewardDiscount' => $rewardDiscount,
                'seniorDiscount' => $seniorDiscount,
                'childrenDiscount' => $childrenDiscount,
                'isPriority' => $isPriority,
                'finalQueueNumber' => $finalQueueNumber,
                'remainingTables' => $remainingTables,
                'validationTimeLeft' => $validationTimeLeft,
            ]);
        } else {
            $orderSet = OrderSet::where('id', $customerQueue->orderSet_id)->first();

            $bookTime = date('g:i a', strtotime($customerQueue->created_at));
    
            $bookDate = explode('-', date('Y-m-d', strtotime($customerQueue->created_at)));
            $month1 = $this->convertMonths($bookDate[1]);
            $year1 = $bookDate[0];
            $day1  = $bookDate[2];

            $seniorDiscount = $orderSet->orderSetPrice * ($customerQueue->numberOfPwd * 0.2);
            $childrenDiscount = $orderSet->orderSetPrice * $customerQueue->numberOfChildren;

            $getDateToday = date("Y-m-d");
            $checkPriorities = CustomerQueue::where('restAcc_id', $restAcc_id)
                            ->where(function ($query) {
                                $query->where('status', "approved")
                                ->orWhere('status', "validation");
                            })
                            ->where('queueDate', $getDateToday)
                            ->orderBy('totalPwdChild', 'DESC')
                            ->orderBy('approvedDateTime', 'ASC')->first();

            $isPriority = false;
            if($checkPriorities->id == $id){
                $isPriority = true;
            } else {
                $isPriority = false;
            }

            $countCustomersApproved = CustomerQueue::where('queueDate', $getDateToday)
                ->where(function ($query) {
                    $query->where('status', "approved")
                    ->orWhere('status', "validation");
                })
                ->orderBy('totalPwdChild', 'DESC')
                ->orderBy('validationDateTime', 'ASC')
                ->orderBy('approvedDateTime', 'ASC')
                ->get();

            $finalQueueNumber = 0;
            $queueNumber = 0;
            foreach ($countCustomersApproved as $countCustomerApproved){
                $queueNumber++;
                if($countCustomerApproved->id == $id){
                    $finalQueueNumber = $queueNumber;
                }
            }

            $remainingTables = $restaurant->rNumberOfTables - $totalTables;

            $validationTimeLeft = null;
            if($customerQueue->status == "validation"){
                $currentTime = Carbon::now();
                $rAppstartTime = Carbon::parse($customerQueue->validationDateTime);
                $rAppDiffSeconds = $currentTime->diffInSeconds($rAppstartTime);
                $rAppDiffMinutes = $currentTime->diffInMinutes($rAppstartTime);

                $rAppDiffMinutes = 15 - $rAppDiffMinutes;
                $rAppDiffSeconds = (15 * 60) - $rAppDiffSeconds;

                if ($rAppDiffMinutes > 1){
                    $validationTimeLeft = "$rAppDiffMinutes minutes";
                } else if ($rAppDiffMinutes == 1) {
                    if ($rAppDiffSeconds == 1){
                        $validationTimeLeft = "1 second";
                    } else {
                        $validationTimeLeft = "$rAppDiffSeconds seconds";
                    }
                } else {
                    $validationTimeLeft = "0 second";
                }
            }
    
            return view('restaurant.liveTransactions.approvedCustomer.queueView',[
                'customerQueue' => $customerQueue,
                'countQueues' => "N/A",
                'countCancelled' => "N/A",
                'countNoShow' => "N/A",
                'countRunaway' => "N/A",
                'bookTime' => $bookTime,
                'bookDate' => $month1." ".$day1.", ".$year1,
                'userSinceDate' => "N/A",
                'finalReward' => "N/A",
                'rewardDiscount' => null,
                'orderSet' => $orderSet,
                'seniorDiscount' => $seniorDiscount,
                'childrenDiscount' => $childrenDiscount,
                'isPriority' => $isPriority,
                'finalQueueNumber' => $finalQueueNumber,
                'remainingTables' => $remainingTables,
                'validationTimeLeft' => $validationTimeLeft,
            ]);
        }
    }
    public function ltAppCustQListView(){
        $restAcc_id = Session::get('loginId');
        $getDateToday = date("Y-m-d");
        $customerNames = array();

        $customerQueues = CustomerQueue::where('restAcc_id', $restAcc_id)
                        ->where(function ($query) {
                            $query->where('status', "approved")
                            ->orWhere('status', "validation");
                        })
                        ->where('queueDate', $getDateToday)
                        ->orderBy('totalPwdChild', 'DESC')
                        ->orderBy('approvedDateTime', 'ASC')
                        ->paginate(10);

        if(!$customerQueues->isEmpty()){
            foreach ($customerQueues as $customerQueue){
                if($customerQueue->customer_id == 0){
                    array_push($customerNames, $customerQueue->name);
                } else {
                    $getCustName = CustomerAccount::select('name')->where('id', $customerQueue->customer_id)->first();
                    array_push($customerNames, $getCustName->name);
                }
            }
        }

        return view('restaurant.liveTransactions.approvedCustomer.queueList',[
            'customerQueues' => $customerQueues,
            'customerNames' => $customerNames,
        ]);
    }


    public function ltCustBookRParticularView($id){
        $restAcc_id = Session::get('loginId');
        $customerReserve = CustomerReserve::where('id', $id)->where('restAcc_id', $restAcc_id)->where('status', 'pending')->first();
        $customerInfo = CustomerAccount::where('id', $customerReserve->customer_id)->first();

        $currentTime = Carbon::now();
        $rAppstartTime = Carbon::parse($customerReserve->created_at);
        $rAppDiffSeconds = $currentTime->diffInSeconds($rAppstartTime);
        $rAppDiffMinutes = $currentTime->diffInMinutes($rAppstartTime);

        $rAppDiffMinutes = 15 - $rAppDiffMinutes;
        $rAppDiffSeconds = (15 * 60) - $rAppDiffSeconds;

        if ($rAppDiffMinutes > 1){
            $rAppDiffTime = "$rAppDiffMinutes minutes";
        } else if ($rAppDiffMinutes == 1) {
            if ($rAppDiffSeconds == 1){
                $rAppDiffTime = "1 second";
            } else {
                $rAppDiffTime = "$rAppDiffSeconds seconds";
            }
        } else {
            $rAppDiffTime = "0 second";
        }

        $countBook1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('customer_id', $customerReserve->customer_id)->count();
        $countBook2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('customer_id', $customerReserve->customer_id)->count();

        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerReserve->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerReserve->customer_id)->count();

        $countNoShow1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerReserve->customer_id)->count();
        $countNoShow2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerReserve->customer_id)->count();

        $countRunaway1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerReserve->customer_id)->count();
        $countRunaway2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerReserve->customer_id)->count();

        $countQueues = $countBook1 + $countBook2;
        $countCancelled = $countCancelled1 + $countCancelled2;
        $countNoShow = $countNoShow1 + $countNoShow2;
        $countRunaway = $countRunaway1 + $countRunaway2;

        $bookTime = date('g:i a', strtotime($customerReserve->created_at));

        $bookDate = explode('-', date('Y-m-d', strtotime($customerReserve->created_at)));
        $month1 = $this->convertMonths($bookDate[1]);
        $year1 = $bookDate[0];
        $day1  = $bookDate[2];

        $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
        $month2 = $this->convertMonths($userSinceDate[1]);
        $year2 = $userSinceDate[0];
        $day2  = $userSinceDate[2];
        
        $reserveDate = explode('-', $customerReserve->reserveDate);
        $month3 = $this->convertMonths($reserveDate[1]);
        $year3 = $reserveDate[0];
        $day3  = $reserveDate[2];

        $finalReward = "None";
        $rewardDiscount = null;
        if($customerReserve->rewardStatus == "Complete" && $customerReserve->rewardClaimed == "Yes"){
            switch($customerReserve->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerReserve->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) * ($customerReserve->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerReserve->rewardInput person in a group";
                    $rewardDiscount = $customerReserve->orderSetPrice * $customerReserve->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerReserve->numberOfPersons * $customerReserve->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }

        $seniorDiscount = $customerReserve->orderSetPrice * ($customerReserve->numberOfPwd * 0.2);
        $childrenDiscount = 0.0;

        return view('restaurant.liveTransactions.customerBooking.reserveView',[
            'customerReserve' => $customerReserve,
            'customerInfo' => $customerInfo,
            'countQueues' => $countQueues,
            'countCancelled' => $countCancelled,
            'countNoShow' => $countNoShow,
            'countRunaway' => $countRunaway,
            'bookTime' => $bookTime,
            'bookDate' => $month1." ".$day1.", ".$year1,
            'userSinceDate' => $month2." ".$day2.", ".$year2,
            'reserveDate' => $month3." ".$day3.", ".$year3,
            'rAppDiffTime' => $rAppDiffTime,
            'finalReward' => $finalReward,
            'rewardDiscount' => $rewardDiscount,
            'seniorDiscount' => $seniorDiscount,
            'childrenDiscount' => $childrenDiscount,
        ]);
    }
    public function ltCustBookRListView(){
        $restAcc_id = Session::get('loginId');
        $customerNames = array();
        $customerTimeLimits = array();
        $currentTime = Carbon::now();

        $customerReserves = CustomerReserve::where('restAcc_id', $restAcc_id)
        ->where('status', 'pending')
        ->orderBy('reserveDate', 'ASC')
        ->paginate(10);

        $storeResDate = array();
        if(!$customerReserves->isEmpty()){
            foreach ($customerReserves as $customerReserve){
                $getCustName = CustomerAccount::select('name')->where('id', $customerReserve->customer_id)->first();
                $bookDate = explode('-', date('Y-m-d', strtotime($customerReserve->reserveDate)));
                $month1 = $this->convertMonths($bookDate[1]);
                $year1 = $bookDate[0];
                $day1  = $bookDate[2];

                array_push($storeResDate, "$month1 $day1, $year1");
                array_push($customerNames, $getCustName->name);
                
            }
    
            foreach ($customerReserves as $customerReserve){
                $rAppstartTime = Carbon::parse($customerReserve->created_at);
                $rAppDiffSeconds = $currentTime->diffInSeconds($rAppstartTime);
                $rAppDiffMinutes = $currentTime->diffInMinutes($rAppstartTime);

                $rAppDiffMinutes = 15 - $rAppDiffMinutes;
                $rAppDiffSeconds = (15 * 60) - $rAppDiffSeconds;

                if ($rAppDiffMinutes > 1){
                    $rAppDiffTime = "$rAppDiffMinutes minutes";
                } else if ($rAppDiffMinutes == 1) {
                    if ($rAppDiffSeconds == 1){
                        $rAppDiffTime = "1 second";
                    } else {
                        $rAppDiffTime = "$rAppDiffSeconds seconds";
                    }
                } else {
                    $rAppDiffTime = "0 second";
                }
                array_push($customerTimeLimits, $rAppDiffTime);
            }
        }
        return view('restaurant.liveTransactions.customerBooking.reserveList',[
            'customerReserves' => $customerReserves,
            'customerNames' => $customerNames,
            'customerTimeLimits' => $customerTimeLimits,
            'storeResDate' => $storeResDate,
        ]);
    }
    public function ltCustBookQParticularView($id){
        $restAcc_id = Session::get('loginId');
        $customerQueue = CustomerQueue::where('id', $id)->where('restAcc_id', $restAcc_id)->where('status', 'pending')->first();
        $customerInfo = CustomerAccount::where('id', $customerQueue->customer_id)->first();

        $currentTime = Carbon::now();
        $rAppstartTime = Carbon::parse($customerQueue->created_at);
        $rAppDiffSeconds = $currentTime->diffInSeconds($rAppstartTime);
        $rAppDiffMinutes = $currentTime->diffInMinutes($rAppstartTime);

        $rAppDiffMinutes = 15 - $rAppDiffMinutes;
        $rAppDiffSeconds = (15 * 60) - $rAppDiffSeconds;

        if ($rAppDiffMinutes > 1){
            $rAppDiffTime = "$rAppDiffMinutes minutes";
        } else if ($rAppDiffMinutes == 1) {
            if ($rAppDiffSeconds == 1){
                $rAppDiffTime = "1 second";
            } else {
                $rAppDiffTime = "$rAppDiffSeconds seconds";
            }
        } else {
            $rAppDiffTime = "0 second";
        }

        

        $countBook1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('customer_id', $customerQueue->customer_id)->count();
        $countBook2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('customer_id', $customerQueue->customer_id)->count();

        $countCancelled1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerQueue->customer_id)->count();
        $countCancelled2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'cancelled')->where('customer_id', $customerQueue->customer_id)->count();

        $countNoShow1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerQueue->customer_id)->count();
        $countNoShow2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'noShow')->where('customer_id', $customerQueue->customer_id)->count();

        $countRunaway1 = CustomerQueue::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerQueue->customer_id)->count();
        $countRunaway2 = CustomerReserve::where('restAcc_id', $restAcc_id)->where('status', 'runaway')->where('customer_id', $customerQueue->customer_id)->count();

        $countQueues = $countBook1 + $countBook2;
        $countCancelled = $countCancelled1 + $countCancelled2;
        $countNoShow = $countNoShow1 + $countNoShow2;
        $countRunaway = $countRunaway1 + $countRunaway2;

        $bookTime = date('g:i a', strtotime($customerQueue->created_at));

        $bookDate = explode('-', date('Y-m-d', strtotime($customerQueue->created_at)));
        $month1 = $this->convertMonths($bookDate[1]);
        $year1 = $bookDate[0];
        $day1  = $bookDate[2];

        $userSinceDate = explode('-', date('Y-m-d', strtotime($customerInfo->created_at)));
        $month2 = $this->convertMonths($userSinceDate[1]);
        $year2 = $userSinceDate[0];
        $day2  = $userSinceDate[2];

        $finalReward = "None";
        $rewardDiscount = null;
        if($customerQueue->rewardStatus == "Complete" && $customerQueue->rewardClaimed == "Yes"){
            switch($customerQueue->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerQueue->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) * ($customerQueue->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerQueue->rewardInput person in a group";
                    $rewardDiscount = $customerQueue->orderSetPrice * $customerQueue->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerQueue->numberOfPersons * $customerQueue->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }


        $seniorDiscount = $customerQueue->orderSetPrice * ($customerQueue->numberOfPwd * 0.2);
        $childrenDiscount = $customerQueue->orderSetPrice * $customerQueue->numberOfChildren;

        
        $getDateToday = date("Y-m-d");
        $checkPriorities = CustomerQueue::where('restAcc_id', $restAcc_id)
                        ->where('status', 'pending')
                        ->where('queueDate', $getDateToday)
                        ->orderBy('totalPwdChild', 'DESC')
                        ->orderBy('created_at', 'ASC')->first();

        $isPriority = false;
        if($checkPriorities->id == $id){
            $isPriority = true;
        } else {
            $isPriority = false;
        }

        return view('restaurant.liveTransactions.customerBooking.queueView',[
            'customerQueue' => $customerQueue,
            'customerInfo' => $customerInfo,
            'countQueues' => $countQueues,
            'countCancelled' => $countCancelled,
            'countNoShow' => $countNoShow,
            'countRunaway' => $countRunaway,
            'bookTime' => $bookTime,
            'bookDate' => $month1." ".$day1.", ".$year1,
            'userSinceDate' => $month2." ".$day2.", ".$year2,
            'rAppDiffTime' => $rAppDiffTime,
            'finalReward' => $finalReward,
            'rewardDiscount' => $rewardDiscount,
            'seniorDiscount' => $seniorDiscount,
            'childrenDiscount' => $childrenDiscount,
            'isPriority' => $isPriority,
        ]);
    }
    public function ltCustBookQListView(){
        $restAcc_id = Session::get('loginId');
        $getDateToday = date("Y-m-d");
        $customerNames = array();
        $customerTimeLimits = array();
        $currentTime = Carbon::now();

        $customerQueues = CustomerQueue::where('restAcc_id', $restAcc_id)
                        ->where('status', 'pending')
                        ->where('queueDate', $getDateToday)
                        ->orderBy('totalPwdChild', 'DESC')
                        ->orderBy('created_at', 'ASC')
                        ->paginate(10);

        if(!$customerQueues->isEmpty()){
            foreach ($customerQueues as $customerQueue){
                $getCustName = CustomerAccount::select('name')->where('id', $customerQueue->customer_id)->first();
                array_push($customerNames, $getCustName->name);
            }
    
            foreach ($customerQueues as $customerQueue){
                $rAppstartTime = Carbon::parse($customerQueue->created_at);
                $rAppDiffSeconds = $currentTime->diffInSeconds($rAppstartTime);
                $rAppDiffMinutes = $currentTime->diffInMinutes($rAppstartTime);

                $rAppDiffMinutes = 15 - $rAppDiffMinutes;
                $rAppDiffSeconds = (15 * 60) - $rAppDiffSeconds;

    
                if ($rAppDiffMinutes > 1){
                    $rAppDiffTime = "$rAppDiffMinutes minutes";
                } else if ($rAppDiffMinutes == 1) {
                    if ($rAppDiffSeconds == 1){
                        $rAppDiffTime = "1 second";
                    } else {
                        $rAppDiffTime = "$rAppDiffSeconds seconds";
                    }
                } else {
                    $rAppDiffTime = "0 second";
                }
                array_push($customerTimeLimits, $rAppDiffTime);
            }
        }

        return view('restaurant.liveTransactions.customerBooking.queueList',[
            'customerQueues' => $customerQueues,
            'customerNames' => $customerNames,
            'customerTimeLimits' => $customerTimeLimits,
        ]);
    }
    public function addPromoView(){
        return view('restaurant.manageRestaurant.promo.promoAdd');
    }
    public function resetPasswordView($token, $emailAddress){
        $checkIfExist = RestaurantResetPassword::where('token', $token)->where('emailAddress', $emailAddress)->where('resetStatus', 'Pending')->first();
        if($checkIfExist == null){
            abort(404);
        } else {
            return view('restaurant.resetPassword',[
                'token' => $token,
                'emailAddress' => $emailAddress,
            ]);
        }
    }
    public function forgotPasswordView(){
        return view('restaurant.forgotPassword');
    }
    public function policyView(){
        $id = Session::get('loginId');
        $policies = Policy::where('restAcc_id', $id)->get();
        return view('restaurant.manageRestaurant.offense.policy',[
            'policies' => $policies,
            'title' => 'Manage Offense',
            'id' => $id,
        ]);
    }
    public function offensesView(){
        $id = Session::get('loginId');
        $cancellation = Cancellation::where('restAcc_id', $id)->first();
        $noShow = NoShow::where('restAcc_id', $id)->first();
        $runaway = Runaway::where('restAcc_id', $id)->first();
        return view('restaurant.manageRestaurant.offense.offenses',[
            'title' => 'Manage Offense',
            'noShow' => $noShow,
            'cancellation' => $cancellation,
            'runaway' => $runaway
        ]);
    }
    public function tasksView(){
        $id = Session::get('loginId');
        $tasks = RestaurantTaskList::where('restAcc_id', $id)->get();
        return view('restaurant.manageRestaurant.tasksRewards.tasks',[
            'title' => 'Manage Task Rewards',
            'tasks' => $tasks,
        ]);
    }
    public function rewardsView(){
        $id = Session::get('loginId');
        $rewards = RestaurantRewardList::where('restAcc_id', $id)->get();
        return view('restaurant.manageRestaurant.tasksRewards.rewards',[
            'title' => 'Manage Task Rewards',
            'rewards' => $rewards
        ]);
    }
    public function stampCardView(){
        $getDateToday = date("Y-m-d");
        $resAccid = Session::get('loginId');
        $stamp = StampCard::where('restAcc_id', $resAccid)->latest()->first();
        if($stamp == null){
            $rewards = RestaurantRewardList::where('restAcc_id', $resAccid)->get();
            $tasks = RestaurantTaskList::where('restAcc_id', $resAccid)->get();
            return view('restaurant.manageRestaurant.tasksRewards.stampCard',[
                'title' => 'Manage Task Rewards',
                'stamp' => $stamp,
                'rewards' => $rewards,
                'tasks' => $tasks,
            ]);
        } else {
            if($getDateToday <= $stamp->stampValidity){
                $reward = RestaurantRewardList::where('restAcc_id', $resAccid)->where('id', $stamp->stampReward_id)->first();
                $rewards = RestaurantRewardList::where('restAcc_id', $resAccid)->get();
                $tasks = RestaurantTaskList::where('restAcc_id', $resAccid)->get();
                $getTasks = StampCardTasks::where('stampCards_id', $stamp->id)->get();
                $storeTasksId = array();
                foreach ($getTasks as $getTask){
                    array_push($storeTasksId, $getTask->restaurantTaskLists_id);
                }
                return view('restaurant.manageRestaurant.tasksRewards.stampCard',[
                    'title' => 'Manage Task Rewards',
                    'resAccid' => $resAccid,
                    'stamp' => $stamp,
                    'reward' => $reward,
                    'rewards' => $rewards,
                    'tasks' => $tasks,
                    'storeTasksId' => $storeTasksId,
                ]);
            } else {
                $stamp = null;
                $rewards = RestaurantRewardList::where('restAcc_id', $resAccid)->get();
                $tasks = RestaurantTaskList::where('restAcc_id', $resAccid)->get();
                return view('restaurant.manageRestaurant.tasksRewards.stampCard',[
                    'title' => 'Manage Task Rewards',
                    'stamp' => $stamp,
                    'rewards' => $rewards,
                    'tasks' => $tasks,
                ]);
            }
        }
    }
    public function editTimeLimitView(){
        $id = Session::get('loginId');
        $account = RestaurantAccount::where('id', '=', $id)->first();
        $optionsArray = [0, 2, 3, 4, 5];
        return view('restaurant.manageRestaurant.time.timeLimitEdit',[
            'account' => $account,
            'title' => 'Manage Time',
            'optionsArray' => $optionsArray,
        ]);
    }
    public function unavailableDatesView(){
        $id = Session::get('loginId');
        $unavailableDates = UnavailableDate::where('restAcc_id', '=', $id)->orderBy('unavailableDatesDate', 'ASC')->get();
        return view('restaurant.manageRestaurant.time.unavailableDates',[
            'unavailableDates' => $unavailableDates,
            'title' => 'Manage Date'
        ]);
    }
    public function timeLimitView(){
        $id = Session::get('loginId');
        $account = RestaurantAccount::where('id', '=', $id)->first();
        return view('restaurant.manageRestaurant.time.timeLimit',[
            'account' => $account,
            'title' => 'Manage Time'
        ]);
    }
    public function storeHoursView(){
        $id = Session::get('loginId');
        $storeHours = StoreHour::where('restAcc_id', '=', $id)->get();
        $existingDays = array();
        foreach ($storeHours as $storeHour){
            foreach (explode(",", $storeHour->days) as $day){
                array_push($existingDays, $day);
            }
        }
        return view('restaurant.manageRestaurant.time.storeHours',[
            'storeHours' => $storeHours,
            'title' => 'Manage Time',
            'existingDays' => $existingDays,
        ]);
    }
    public function editPromoView($id){
        $resAccid = Session::get('loginId');
        $promo = Promo::where('restAcc_id', $resAccid)->where('id', $id)->first();
        $promoMechanics = PromoMechanics::where('promo_id', $id)->get();
        return view('restaurant.manageRestaurant.promo.promoEdit',[
            'promo' => $promo,
            'title' => 'Manage Promo',
            'resAccid' => $resAccid,
            'promoMechanics' => $promoMechanics,
        ]);
    }
    public function editPostView($id){
        $resAccid = Session::get('loginId');
        $post = Post::where('restAcc_id', '=', $resAccid)
                            ->where('id', '=', $id)
                            ->first();
        return view('restaurant.manageRestaurant.aboutRestaurant.restaurantPostEdit',[
            'post' => $post,
            'title' => 'Manage Restaurant',
            'resAccid' => $resAccid
        ]);
    }
    public function orderSetEditView($id){
        $resAccid = Session::get('loginId');
        $orderSet = OrderSet::where('restAcc_id', '=', $resAccid)
                            ->where('id', '=', $id)
                            ->first();
        return view('restaurant.manageRestaurant.foodMenu.orderSetEdit',[
            'orderSet' => $orderSet,
            'title' => 'Manage Food Menu',
            'resAccid' => $resAccid
        ]);
    }
    public function orderSetDetailView($id){
        $resAccid = Session::get('loginId');
        $orderSet = OrderSet::where('restAcc_Id', $resAccid)->where('id', $id)->first();
        $foodItems = FoodItem::where('restAcc_id', $resAccid)->get();
        $foodSets = FoodSet::where('restAcc_id', $resAccid)->get();
        $orderSetFoodItems = OrderSetFoodItem::where('orderSet_id', $id)->get();
        $orderSetFoodSets = OrderSetFoodSet::where('orderSet_id', $id)->get();
        return view('restaurant.manageRestaurant.foodMenu.orderSetDetail',[
            'orderSet' => $orderSet,
            'title' => 'Manage Food Menu',
            'id' => $resAccid,
            'foodItems' => $foodItems,
            'orderSetFoodItems' => $orderSetFoodItems,
            'foodSets' => $foodSets,
            'orderSetFoodSets' => $orderSetFoodSets
        ]);
    }
    public function foodSetEditView($id){
        $resAccid = Session::get('loginId');
        $foodSet = FoodSet::where('restAcc_id', $resAccid)->where('id', $id)->first();
        return view('restaurant.manageRestaurant.foodMenu.foodSetEdit',[
            'foodSet' => $foodSet,
            'title' => 'Manage Food Menu',
            'resAccid' => $resAccid
        ]);
    }
    public function editFoodItemView($id){
        $resAccid = Session::get('loginId');
        $foodItem = FoodItem::where('restAcc_id', $resAccid)->where('id', $id)->first();
        return view('restaurant.manageRestaurant.foodMenu.foodItemEdit',[
            'foodItem' => $foodItem,
            'title' => 'Manage Food Menu',
            'resAccid' => $resAccid
        ]);
    }
    public function foodSetDetailView($id){
        $resAccid = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $resAccid)->first();
        $foodSet = FoodSet::where('restAcc_Id', $resAccid)->where('id', $id)->first();
        $foodItems = FoodItem::where('restAcc_id', $resAccid)->get();
        $foodSetItems = FoodSetItem::where('foodSet_id', $id)->get();
        return view('restaurant.manageRestaurant.foodMenu.foodSetDetail',[
            'foodSet' => $foodSet,
            'title' => 'Manage Food Menu',
            'id' => $resAccid,
            'foodItems' => $foodItems,
            'foodSetItems' => $foodSetItems,
            'restaurant' => $restaurant
        ]);
    }
    public function manageRestaurantChecklistView(){
        $restAcc_id = Session::get('loginId');
        $restaurant = RestaurantAccount::select('emailAddress', 'status', 'verified', 'rLogo', 'rGcashQrCodeImage')->where('id', $restAcc_id)->first();

        $orderSet = OrderSet::where('restAcc_id', $restAcc_id)->count();


        return view('restaurant.manageRestaurant.checkList.checkList',[
            'restaurant' => $restaurant,
            'title' => 'Manage Checklist',
            'orderSet' => $orderSet
        ]);
    }
    public function manageRestaurantPromoView(Request $request){
        $id = Session::get('loginId');
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $searchText = '%'.$_GET['q'].'%';
            $promos = Promo::where('restAcc_Id', '=', $id)
                        ->where(function ($query) use ($searchText) {
                            $query->where('promoPosted', 'LIKE', $searchText)
                                  ->orWhere('promoTitle', 'LIKE', $searchText)
                                  ->orWhere('promoDescription', 'LIKE', $searchText)
                                  ->orWhere('promoMechanics', 'LIKE', $searchText);
                        })
                        ->paginate(4);
            $promos->appends($request->all());
            return view('restaurant.manageRestaurant.promo.promo',[
                'promos' => $promos,
                'title' => 'Manage Promos',
                'id' => $id
            ]);
        } else {
            $promos = Promo::where('restAcc_Id', '=', $id)->paginate(4);
            return view('restaurant.manageRestaurant.promo.promo',[
                'promos' => $promos,
                'title' => 'Manage Promos',
                'id' => $id
            ]);
        }
    }
    public function manageRestaurantOrderSetView(Request $request){
        $id = Session::get('loginId');
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $searchText = '%'.$_GET['q'].'%';
            $orderSets = OrderSet::where('restAcc_Id', '=', $id)
                        ->where(function ($query) use ($searchText) {
                            $query->where('orderSetName', 'LIKE', $searchText)
                                ->orWhere('status', 'LIKE', $searchText)
                                ->orWhere('available', 'LIKE', $searchText)
                                ->orWhere('orderSetTagline', 'LIKE', $searchText)
                                ->orWhere('orderSetDescription', 'LIKE', $searchText)
                                ->orWhere('orderSetPrice', 'LIKE', $searchText);
                        })
                        ->paginate(10);
            $orderSets->appends($request->all());
            return view('restaurant.manageRestaurant.foodMenu.orderSet',[
                'orderSets' => $orderSets,
                'title' => 'Manage Food Menu',
                'id' => $id
            ]);
        } else {
            $orderSets = OrderSet::where('restAcc_Id', '=', $id)->paginate(10);
            return view('restaurant.manageRestaurant.foodMenu.orderSet',[
                'orderSets' => $orderSets,
                'title' => 'Manage Food Menu',
                'id' => $id
            ]);
        }
    }
    public function manageRestaurantFoodSetView(Request $request){
        $id = Session::get('loginId');
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $searchText = '%'.$_GET['q'].'%';
            $foodSets = FoodSet::where('restAcc_Id', '=', $id)
                        ->where(function ($query) use ($searchText) {
                            $query->where('foodSetName', 'LIKE', $searchText)
                                ->orWhere('status', 'LIKE', $searchText)
                                ->orWhere('available', 'LIKE', $searchText)
                                ->orWhere('foodSetDescription', 'LIKE', $searchText)
                                ->orWhere('foodSetPrice', 'LIKE', $searchText);
                        })
                        ->paginate(10);
            $foodSets->appends($request->all());
            return view('restaurant.manageRestaurant.foodMenu.foodSet',[
                'foodSets' => $foodSets,
                'title' => 'Manage Food Menu',
                'id' => $id
            ]);
        } else {
            $foodSets = FoodSet::where('restAcc_Id', '=', $id)->paginate(10);
            return view('restaurant.manageRestaurant.foodMenu.foodSet',[
                'foodSets' => $foodSets,
                'title' => 'Manage Food Menu',
                'id' => $id
            ]);
        }
    }
    public function manageRestaurantFoodItemView(Request $request){
        $id = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $id)->first();
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $searchText = '%'.$_GET['q'].'%';
            $foodItems = FoodItem::where('restAcc_Id', $id)
                        ->where(function ($query) use ($searchText) {
                            $query->where('foodItemName', 'LIKE', $searchText)
                                ->orWhere('status', 'LIKE', $searchText)
                                ->orWhere('available', 'LIKE', $searchText)
                                ->orWhere('foodItemDescription', 'LIKE', $searchText)
                                ->orWhere('foodItemPrice', 'LIKE', $searchText);
                        })
                        ->orderBy('id', 'DESC')
                        ->paginate(10);
            $foodItems->appends($request->all());
            return view('restaurant.manageRestaurant.foodMenu.foodItem',[
                'foodItems' => $foodItems,
                'title' => 'Manage Food Menu',
                'id' => $id,
                'status' => $restaurant->status
            ]);
        } else {
            $foodItems = FoodItem::where('restAcc_Id', $id)->orderBy('id', 'DESC')->paginate(10);
            return view('restaurant.manageRestaurant.foodMenu.foodItem',[
                'foodItems' => $foodItems,
                'title' => 'Manage Food Menu',
                'id' => $id,
                'status' => $restaurant->status
            ]);
        }
    }
    public function manageRestaurantInformationView(){
        $id = Session::get('loginId');
        $account = RestaurantAccount::where('id', '=', $id)->first();
        return view('restaurant.manageRestaurant.aboutRestaurant.restaurantInformation',[
            'account' => $account,
            'title' => 'Manage About Restaurant',
            'id' => $id,
        ]);
    }
    public function manageRestaurantPostView(){
        $id = Session::get('loginId');
        $posts = Post::where('restAcc_id', $id)->orderBy('id', 'DESC')->get();
        return view('restaurant.manageRestaurant.aboutRestaurant.restaurantPost',[
            'posts' => $posts,
            'title' => 'Manage About Restaurant',
            'id' => $id,
        ]);
    }
    public function dashboardView(){
        $id = Session::get('loginId');
        $account = RestaurantAccount::where('id', '=', $id)->first();
        return view('restaurant.dashboard.dashboard',[
            'account' => $account,
            'title' => 'Dashboard'
        ]);
    }
    public function register2(Request $request){
        $validatedData = $request->validate([
            'fname' => 'required|regex:/^[\pL\s\-]+$/u',
            'mname' => 'nullable|regex:/^[\pL\s\-]+$/u',
            'lname' => 'required|regex:/^[\pL\s\-]+$/u',
            'role' => 'required',
            'birthDate' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postalCode' => 'required',
            'state' => 'required',
            'country' => 'required',
            'emailAddress' => 'required|email|unique:restaurant_accounts,emailAddress',
            'contactNumber' => 'required|digits:11',
            'landlineNumber' => 'nullable|digits:8',
            'rName' => 'required',
            'rBranch' => 'required',
            'rAddress' => 'required',
            'rCity' => 'required',
            'rPostalCode' => 'required',
            'rState' => 'required',
            'rCountry' => 'required',
            'bir' => 'required|mimes:pdf|max:12288',
            'dti' => 'required|mimes:pdf|max:12288',
            'mayorsPermit' => 'required|mimes:pdf|max:12288',
            'staffValidId' => 'required|mimes:pdf|max:12288',
        ], 
        [
            'fname.required' => 'First Name is required',
            'fname.regex' => 'First Name must contain letters only',
            'mname.regex' => 'Middle Name must contain letters only',
            'lname.required' => 'Last Name is required',
            'lname.regex' => 'Last Name must contain letters only',
            'role.required' => 'Role is required',
            'birthDate.required' => 'Birthday is required',
            'gender.required' => 'Gender is required',
            'address.required' => 'Address is required',
            'city.required' => 'City is required',
            'postalCode.required' => 'Postal/Zip Code is required',
            'state.required' => 'State/Province is required',
            'country.required' => 'Country is required',
            'emailAddress.required' => 'Email Address is required',
            'emailAddress.email' => 'Email Address must be a valid email',
            'emailAddress.unique' => 'Email Address has already taken',
            'contactNumber.required' => 'Contact Number is required',
            'contactNumber.digits' => 'Contact Number must be 11 digits',
            'landlineNumber.digits' => 'Landline Number must be 8 digits',
            'rName.required' => 'Name is required',
            'rBranch.required' => 'Branch is required',
            'rAddress.required' => 'Address is required',
            'rCity.required' => 'City is required',
            'rPostalCode.required' => 'Postal/Zip Code is required',
            'rState.required' => 'State/Province is required',
            'rCountry.required' => 'Country is required',
            'bir.required' => 'BIR Certificate of Registration is required',
            'bir.mimes' => 'BIR Certificate of Registration must be in pdf format',
            'bir.max' => 'BIR Certificate of Registration must be 12mb below',
            'dti.required' => 'DTI Business Registration is required',
            'dti.mimes' => 'DTI Business Registration must be in pdf format',
            'dti.max' => 'DTI Business Registration must be 12mb below',
            'mayorsPermit.required' => 'Mayor’s Permit is required',
            'mayorsPermit.mimes' => 'Mayor’s Permit must be in pdf format',
            'mayorsPermit.max' => 'Mayor’s Permit must be 12mb below',
            'staffValidId.required' => 'Owner/Staff Valid ID is required',
            'staffValidId.mimes' => 'Owner/Staff Valid ID must be in pdf format',
            'staffValidId.max' => 'Owner/Staff Valid ID must be 12mb below',
        ]);

        $result = RestaurantApplicant::create([
            'status' => 'Pending',
            'fname' => $request->fname,
            'mname' => $request->mname,
            'lname' => $request->lname,
            'address' => $request->address,
            'city' => $request->city,
            'postalCode' => $request->postalCode,
            'state' => $request->state,
            'country' => $request->country,
            'role' => $request->role,
            'birthDate' => $request->birthDate,
            'gender' => $request->gender,
            'contactNumber' => $request->contactNumber,
            'landlineNumber' => $request->landlineNumber,
            'emailAddress' => $request->emailAddress,

            'rName' => $request->rName,
            'rBranch' => $request->rBranch,
            'rAddress' => $request->rAddress,
            'rCity' => $request->rCity,
            'rPostalCode' => $request->rPostalCode,
            'rState' => $request->rState,
            'rCountry' => $request->rCountry,

            'bir' => "",
            'dti' => "",
            'mayorsPermit' => "",
            'staffValidId' => "",
        ]);
        
        $nextIdFolderName = $result->id;
        $birName = 'BIR_'.str_replace(' ', '', $validatedData['bir']->getClientOriginalName());
        $dtiName = 'DTI_'.str_replace(' ', '', $validatedData['dti']->getClientOriginalName());
        $mayorsPermitName = 'MayorsPermit_'.str_replace(' ', '', $validatedData['mayorsPermit']->getClientOriginalName());
        $staffValidIdName = 'ValidId_'.str_replace(' ', '', $validatedData['staffValidId']->getClientOriginalName());
        
        $request->bir->storeAs('uploads/restaurantApplicants/'.$nextIdFolderName, $birName, 'public');
        $request->dti->storeAs('uploads/restaurantApplicants/'.$nextIdFolderName, $dtiName, 'public');
        $request->mayorsPermit->storeAs('uploads/restaurantApplicants/'.$nextIdFolderName, $mayorsPermitName, 'public');
        $request->staffValidId->storeAs('uploads/restaurantApplicants/'.$nextIdFolderName, $staffValidIdName, 'public');
        
        RestaurantApplicant::where('id', $nextIdFolderName)
            ->update([
            'bir' => $birName,
            'dti' => $dtiName,
            'mayorsPermit' => $mayorsPermitName,
            'staffValidId' => $staffValidIdName
        ]);

        $details = [
            'applicantName' => $request->fname.' '.$request->lname
        ];

        Mail::to($request->emailAddress)->send(new RestaurantFormAppreciation($details));
        Session::flash('registered');
        return redirect('/restaurant/register');
    }
    public function registerView2(){
        return view('restaurant.register2');
    }
    public function loginView(){
        return view('restaurant.login');
    }







    

    










    // RENDER LOGICS
    public function mHiddenOS($id){
        $restAcc_id = Session::get('loginId');
        $restaurant = RestaurantAccount::where('id', $restAcc_id)->first();

        if($restaurant->status == "Published"){

            if($this->checkTodaySched() == "Closed Now"){

                $customerReserves = CustomerReserve::where('restAcc_id', $restAcc_id)
                ->where('orderSet_id', $id)
                ->where(function ($query) {
                    $query->where('status', "pending")
                    ->orWhere('status', "approved");
                })
                ->get();

                if(!$customerReserves->isEmpty()){
                    foreach($customerReserves as $customerReserve){
                        $customerAccount = CustomerAccount::where('id', $customerReserve->customer_id)->first();

                        $finalImageUrl = "";
                        if ($restaurant->rLogo == ""){
                            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                        } else {
                            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                        }

                        if($customerAccount != null){
                            $to = $customerAccount->deviceToken;
                            $notification = array(
                                'title' => "$restaurant->rAddress, $restaurant->rCity",
                                'body' => "Your reservation has been void due to some changes on your order. Were sorry for the inconvenience.",
                            );
                            $data = array(
                                'notificationType' => "Order Set Change",
                                'notificationId' => 0,
                                'notificationRLogo' => $finalImageUrl,
                            );
                            $this->sendFirebaseNotification($to, $notification, $data);
                        }

                        CustomerNotification::where('restAcc_id', $restAcc_id)
                        ->where('notificationType', "Pending")
                        ->where('created_at', $customerReserve->created_at)
                        ->delete();

                        if($customerReserve->approvedDateTime != null){
                            CustomerNotification::where('restAcc_id', $restAcc_id)
                            ->where('notificationType', "Approved")
                            ->where('created_at', $customerReserve->approvedDateTime)
                            ->delete();
                        }

                        CustomerReserve::where('id', $customerReserve->id)->delete();
                    }
                }

                OrderSet::where('id', $id)
                ->update([
                    'status' => "Hidden"
                ]);
                
                Session::flash('fiHidden');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
            } else {
                Session::flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
            }
        } else {
            OrderSet::where('id', $id)
            ->update([
                'status' => "Hidden"
            ]);
            
            Session::flash('fiHidden');
            return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
        }
    }
    public function mVisibleOS($id){
        $restAcc_id = Session::get('loginId');
        $restaurant = RestaurantAccount::where('id', $restAcc_id)->first();

        if($restaurant->status == "Published"){
            if($this->checkTodaySched() == "Closed Now"){

                OrderSet::where('id', $id)
                ->update([
                    'status' => "Visible"
                ]);
                
                Session::flash('fiVisible');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
            } else {
                Session::flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
            }
        } else {
            OrderSet::where('id', $id)
            ->update([
                'status' => "Visible"
            ]);
            
            Session::flash('fiVisible');
            return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
        }
    }
    public function mUnavailableOS($id){
        OrderSet::where('id', $id)
        ->update([
            'available' => "No"
        ]);
        
        Session::flash('fiUnavailable');
        return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
    }
    public function mAvailableOS($id){
        OrderSet::where('id', $id)
        ->update([
            'available' => "Yes"
        ]);
        
        Session::flash('fiAvailable');
        return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
    }
    public function mHiddenFS($id){
        $restAcc_id = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAcc_id)->first();

        if($restaurant->status == "Published"){
            if($this->checkTodaySched() == "Closed Now"){
                FoodSet::where('id', $id)
                ->update([
                    'status' => "Hidden"
                ]);
                
                Session::flash('fiHidden');
                return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$id);
            } else {
                Session::flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$id);
            }
        } else {
            FoodSet::where('id', $id)
            ->update([
                'status' => "Hidden"
            ]);
            
            Session::flash('fiHidden');
            return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$id);
        }
    }
    public function mVisibleFS($id){
        $restAcc_id = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAcc_id)->first();

        if($restaurant->status == "Published"){
            if($this->checkTodaySched() == "Closed Now"){
                FoodSet::where('id', $id)
                ->update([
                    'status' => "Visible"
                ]);
                
                Session::flash('fiVisible');
                return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$id);
            } else {
                Session::flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$id);
            }
        } else {
            FoodSet::where('id', $id)
            ->update([
                'status' => "Visible"
            ]);
            
            Session::flash('fiVisible');
            return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$id);
        }
    }
    public function mUnavailableFS($id){
        FoodSet::where('id', $id)
        ->update([
            'available' => "No"
        ]);
        
        Session::flash('fiUnavailable');
        return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$id);
    }
    public function mAvailableFS($id){
        FoodSet::where('id', $id)
        ->update([
            'available' => "Yes"
        ]);
        
        Session::flash('fiAvailable');
        return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$id);
    }
    public function mHiddenFI($id){
        $restAcc_id = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAcc_id)->first();

        if($restaurant->status == "Published"){
            if($this->checkTodaySched() == "Closed Now"){
                FoodItem::where('id', $id)
                ->update([
                    'status' => "Hidden"
                ]);
                
                Session::flash('fiHidden');
                return redirect('/restaurant/manage-restaurant/food-menu/food-item');
            } else {
                Session::flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/food-item');
            }
        } else {
            FoodItem::where('id', $id)
            ->update([
                'status' => "Hidden"
            ]);
            
            Session::flash('fiHidden');
            return redirect('/restaurant/manage-restaurant/food-menu/food-item');
        }
    }
    public function mVisibleFI($id){
        $restAcc_id = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAcc_id)->first();

        if($restaurant->status == "Published"){
            if($this->checkTodaySched() == "Closed Now"){
                FoodItem::where('id', $id)
                ->update([
                    'status' => "Visible"
                ]);
                
                Session::flash('fiVisible');
                return redirect('/restaurant/manage-restaurant/food-menu/food-item');
            } else {
                Session::flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/food-item');
            }
        } else {
            FoodItem::where('id', $id)
            ->update([
                'status' => "Visible"
            ]);
            
            Session::flash('fiVisible');
            return redirect('/restaurant/manage-restaurant/food-menu/food-item');
        }
    }
    public function mUnavailableFI($id){
        FoodItem::where('id', $id)
        ->update([
            'available' => "No"
        ]);
        
        Session::flash('fiUnavailable');
        return redirect('/restaurant/manage-restaurant/food-menu/food-item');
    }
    public function mAvailableFI($id){
        FoodItem::where('id', $id)
        ->update([
            'available' => "Yes"
        ]);
        
        Session::flash('fiAvailable');
        return redirect('/restaurant/manage-restaurant/food-menu/food-item');
    }
    public function manageRestaurantPublish(){
        $restAcc_id = Session::get('loginId');
        //publish restaurant
        
        $restaurant = RestaurantAccount::select('emailAddress', 'status', 'verified', 'rLogo', 'rGcashQrCodeImage')->where('id', $restAcc_id)->first();
        $orderSet = OrderSet::where('restAcc_id', $restAcc_id)->count();

        if($restaurant->status == "Unpublished" 
            && $restaurant->verified == "Yes"
            && $restaurant->rLogo != null 
            && $restaurant->rGcashQrCodeImage != null 
            && $orderSet > 0){
            
            RestaurantAccount::where('id', $restAcc_id)
            ->update([
                'status' => "Published"
            ]);

            // LAGAY SANA NANG PANG NOTIFY SA LAHAT NG CUSTOMERS


            Session::flash('published');
            return redirect('/restaurant/manage-restaurant/checklist');
        } else {
            Session::flash('cantPublished');
            return redirect('/restaurant/manage-restaurant/checklist');
        }
    }
    public function ltCustOrderOSComplete($id){
        $getDateToday = date("Y-m-d");

        $customerOrdering = CustomerOrdering::where('id', $id)->where('status', 'eating')->first();
        $customerLOrders = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', "Yes")->get();
        $addOnQuantity = 0;
        foreach($customerLOrders as $customerLOrder){
            if($customerLOrder->price != 0.00){
                $addOnQuantity += $customerLOrder->quantity;
            }
        }

        if($customerOrdering->custBookType == "queue"){
            //check muna kung clinaime nya
            $customerQueue = CustomerQueue::where('id', $customerOrdering->custBook_id)->where('status', 'eating')->first();

            if($customerQueue->customer_id != 0){
            
                $eatingTime = date("H:i", strtotime($customerQueue->eatingDateTime));
                $restaurant = RestaurantAccount::where('id', $customerQueue->restAcc_id)->first();
                //check muna kung may stamp card na inimplement yung restaurant
                $stampCard = StampCard::where('restAcc_id', $customerQueue->restAcc_id)->latest()->first();
                if($stampCard != null){
                    //kung meron icompare yung validity date sa current date, kapag sobra meaning tapos na yon di na valid.
                    
                    if($getDateToday <= $stampCard->stampValidity){
                        //valid pa, so check kung yung validitiy at restaccid is same sa custoemr stamp card
                        $custStampCard = CustomerStampCard::where('customer_id', $customerQueue->customer_id)
                        ->where('restAcc_id', $restaurant->id)
                        ->where('stampValidity', $stampCard->stampValidity)
                        ->first();
    
                        if($customerQueue->rewardClaimed == "Yes"){
                            CustomerStampCard::where('id', $custStampCard->id)
                            ->update([
                                'claimed' => "Yes",
                            ]);
                        } else {
                            // CHECK MUNA KUNG ANO MGA NAGING REWARD
                            $storeTasks = array();
                            $storeDoneTasks = array();
                            if($custStampCard != null){
                                //then iupdate mag update lang, mag add ng mga tasks done
                                $stampCardTasks = StampCardTasks::where('stampCards_id', $stampCard->id)->get();
                                foreach($stampCardTasks as $stampCardTask){
                                    $task = RestaurantTaskList::where('id', $stampCardTask->restaurantTaskLists_id)->first();
                                    switch($task->taskCode){
                                        case "SPND":
                                            if($customerQueue->totalPrice >= $task->taskInput){
                                                array_push($storeDoneTasks, "Spend ".$task->taskInput." pesos in 1 visit only");
                                            }
                                            break;
                                        case "BRNG":
                                            if($customerQueue->numberOfPersons >= $task->taskInput){
                                                array_push($storeDoneTasks, "Bring ".$task->taskInput." friends in our store");
                                            }
                                            break;
                                        case "ORDR":
                                            if($addOnQuantity >= $task->taskInput){
                                                array_push($storeDoneTasks, "Order ".$task->taskInput." add on/s per visit");
                                            }
                                            break;
                                        case "VST":
                                            array_push($storeDoneTasks, "Visit in our store");
                                            break;
                                        case "FDBK":
                                            array_push($storeTasks, "Give a feedback/review per visit");
                                            break;
                                        case "PUGC":
                                            if($customerQueue->gcashCheckoutReceipt != null){
                                                array_push($storeDoneTasks, "Pay using gcash");
                                            }
                                            break;
                                        case "LUDN":
                                            if("11:00" >= $eatingTime && "14:00" <= $eatingTime){
                                                array_push($storeDoneTasks, "Eat during lunch/dinner hours");
                                            } else if("17:00" >= $eatingTime && "22:00" <= $eatingTime){
                                                array_push($storeDoneTasks, "Eat during lunch/dinner hours");
                                            } else {}
                                            break;
                                        default: 
                                    }
                                }
    
                                $currentStamp = $custStampCard->currentStamp + sizeof($storeDoneTasks);
                                if($currentStamp >= $stampCard->stampCapacity){
                                    $stampStatus = "Complete";
                                    $currentStamp = $stampCard->stampCapacity;
                                } else {
                                    $stampStatus = "Incomplete";
                                }
    
                                CustomerStampCard::where('id', $custStampCard->id)
                                ->update([
                                    'status' => $stampStatus,
                                    'claimed' => "No",
                                    'currentStamp' => $currentStamp,
                                ]);
    
                                foreach($storeDoneTasks as $storeDoneTask){
                                    CustomerTasksDone::create([
                                        'customer_id' => $customerQueue->customer_id,
                                        'customerStampCard_id' => $custStampCard->id,
                                        'taskName' => $storeDoneTask,
                                        'taskAccomplishDate' => $getDateToday,
                                        'booking_id' => $customerQueue->id,
                                        'booking_type' => "queue",
                                    ]);
                                }
    
                            } else {
                                //mag add din pati sa mga tasks
                                $stampCardTasks = StampCardTasks::where('stampCards_id', $stampCard->id)->get();
                                foreach($stampCardTasks as $stampCardTask){
                                    $task = RestaurantTaskList::where('id', $stampCardTask->restaurantTaskLists_id)->first();
                                    switch($task->taskCode){
                                        case "SPND":
                                            array_push($storeTasks, "Spend ".$task->taskInput." pesos in 1 visit only");
                                            if($customerQueue->totalPrice >= $task->taskInput){
                                                array_push($storeDoneTasks, "Spend ".$task->taskInput." pesos in 1 visit only");
                                            }
                                            break;
                                        case "BRNG":
                                            array_push($storeTasks, "Bring ".$task->taskInput." friends in our store");
                                            if($customerQueue->numberOfPersons >= $task->taskInput){
                                                array_push($storeDoneTasks, "Bring ".$task->taskInput." friends in our store");
                                            }
                                            break;
                                        case "ORDR":
                                            array_push($storeTasks, "Order ".$task->taskInput." add on/s per visit");
                                            if($addOnQuantity >= $task->taskInput){
                                                array_push($storeDoneTasks, "Order ".$task->taskInput." add on/s per visit");
                                            }
                                            break;
                                        case "VST":
                                            array_push($storeTasks, "Visit in our store");
                                            array_push($storeDoneTasks, "Visit in our store");
                                            break;
                                        case "FDBK":
                                            array_push($storeTasks, "Give a feedback/review per visit");
                                            break;
                                        case "PUGC":
                                            array_push($storeTasks, "Pay using gcash");
                                            if($customerQueue->gcashCheckoutReceipt != null){
                                                array_push($storeDoneTasks, "Pay using gcash");
                                            }
                                            break;
                                        case "LUDN":
                                            // dd($time > date("H:i", strtotime("10:00 am")));
                                            //11:00am - 2:00pm (lunch)
                                            //5:00pm - 10:00pm (dinner)
                                            array_push($storeTasks, "Eat during lunch/dinner hours");
                                            if("11:00" >= $eatingTime && "14:00" <= $eatingTime){
                                                array_push($storeDoneTasks, "Eat during lunch/dinner hours");
                                            } else if("17:00" >= $eatingTime && "22:00" <= $eatingTime){
                                                array_push($storeDoneTasks, "Eat during lunch/dinner hours");
                                            } else {}
                                            break;
                                        default: 
                                    }
                                }
    
                                $stampReward = RestaurantRewardList::where('restAcc_id', $customerQueue->restAcc_id)->where('id', $stampCard->stampReward_id)->first();
    
                                switch($stampReward->rewardCode){
                                    case "DSCN": 
                                        $finalReward = "Discount $stampReward->rewardInput% in a Total Bill";
                                        break;
                                    case "FRPE": 
                                        $finalReward = "Free $stampReward->rewardInput person in a group";
                                        break;
                                    case "HLF": 
                                        $finalReward = "Half in the group will be free";
                                        break;
                                    case "ALL": 
                                        $finalReward = "All people in the group will be free";
                                        break;
                                    default: 
                                        $finalReward = "None";
                                }
    
                                $finalStampCapac = sizeof($storeDoneTasks);
                                if(sizeof($storeDoneTasks) >= $stampCard->stampCapacity){
                                    $finalStampCapac = $stampCard->stampCapacity;
                                }
    
                                $custStampCardNew = CustomerStampCard::create([
                                    'customer_id' => $customerQueue->customer_id,
                                    'restAcc_id' => $customerQueue->restAcc_id,
                                    'status' => "Incomplete",
                                    'claimed' => "No",
                                    'currentStamp' => $finalStampCapac,
                                    'stampReward' => $finalReward,
                                    'stampValidity' => $stampCard->stampValidity,
                                    'stampCapacity' => $stampCard->stampCapacity,
                                ]);
    
                                foreach($storeTasks as $storeTask){
                                    CustomerStampTasks::create([
                                        'customerStampCard_id' => $custStampCardNew->id,
                                        'taskName' => $storeTask,
                                    ]);
                                }
    
                                foreach($storeDoneTasks as $storeDoneTask){
                                    CustomerTasksDone::create([
                                        'customer_id' => $customerQueue->customer_id,
                                        'customerStampCard_id' => $custStampCardNew->id,
                                        'taskName' => $storeDoneTask,
                                        'taskAccomplishDate' => $getDateToday,
                                        'booking_id' => $customerQueue->id,
                                        'booking_type' => "queue",
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
                CustomerOrdering::where('id', $id)->where('status', 'eating')
                ->update([
                    'status' => "checkout",
                ]);
    
                CustomerQueue::where('id', $customerOrdering->custBook_id)->where('status', 'eating')
                ->update([
                    'status' => "completed",
                    'checkoutStatus' => "customerFeedback",
                    'completeDateTime' => date("Y-m-d H:i:s"),
                ]);
    
            if($customerQueue->customer_id != 0){
                $notif = CustomerNotification::create([
                    'customer_id' => $customerQueue->customer_id,
                    'restAcc_id' => $customerQueue->restAcc_id,
                    'notificationType' => "Complete",
                    'notificationTitle' => "Your payment is successful! Thank you for dining! You may give us a rating and share your experience.",
                    'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
                    'notificationStatus' => "Unread",
                ]);
        
                $customerAccount = CustomerAccount::where('id', $customerQueue->customer_id)->first();
                
                $finalImageUrl = "";
                if ($restaurant->rLogo == ""){
                    $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                } else {
                    $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                }
        
                if($customerAccount != null){
                    $to = $customerAccount->deviceToken;
                    $notification = array(
                        'title' => "$restaurant->rAddress, $restaurant->rCity",
                        'body' => "Your payment is successful! Thank you for dining! You may give us a rating and share your experience.",
                    );
                    $data = array(
                        'notificationType' => "Complete",
                        'notificationId' => $notif->id,
                        'notificationRLogo' => $finalImageUrl,
                    );
                    $this->sendFirebaseNotification($to, $notification, $data);
                }
            }
            Session::flash('completed');
            return redirect('/restaurant/live-transaction/customer-ordering/list');


        } else {
            //check muna kung clinaime nya
            $customerReserve = CustomerReserve::where('id', $customerOrdering->custBook_id)->where('status', 'eating')->first();

            if($customerReserve->customer_id != 0){
                $eatingTime = date("H:i", strtotime($customerReserve->eatingDateTime));
                $restaurant = RestaurantAccount::where('id', $customerReserve->restAcc_id)->first();
                //check muna kung may stamp card na inimplement yung restaurant
                $stampCard = StampCard::where('restAcc_id', $customerReserve->restAcc_id)->first();
                if($stampCard != null){
                    //kung meron icompare yung validity date sa current date, kapag sobra meaning tapos na yon di na valid.
                    if($getDateToday <= $stampCard->stampValidity){
                        //valid pa, so check kung yung validitiy at restaccid is same sa custoemr stamp card
                        $custStampCard = CustomerStampCard::where('customer_id', $customerReserve->customer_id)
                        ->where('restAcc_id', $restaurant->id)
                        ->where('stampValidity', $stampCard->stampValidity)
                        ->first();
    
                        if($customerReserve->rewardClaimed == "Yes"){
                            CustomerStampCard::where('id', $custStampCard->id)
                            ->update([
                                'claimed' => "Yes",
                            ]);
                        } else {
                            // CHECK MUNA KUNG ANO MGA NAGING REWARD
                            $storeTasks = array();
                            $storeDoneTasks = array();
                            if($custStampCard != null){
                                //then iupdate mag update lang, mag add ng mga tasks done
                                $stampCardTasks = StampCardTasks::where('stampCards_id', $stampCard->id)->get();
                                foreach($stampCardTasks as $stampCardTask){
                                    $task = RestaurantTaskList::where('id', $stampCardTask->restaurantTaskLists_id)->first();
                                    switch($task->taskCode){
                                        case "SPND":
                                            if($customerReserve->totalPrice >= $task->taskInput){
                                                array_push($storeDoneTasks, "Spend ".$task->taskInput." pesos in 1 visit only");
                                            }
                                            break;
                                        case "BRNG":
                                            if($customerReserve->numberOfPersons >= $task->taskInput){
                                                array_push($storeDoneTasks, "Bring ".$task->taskInput." friends in our store");
                                            }
                                            break;
                                        case "ORDR":
                                            if($addOnQuantity >= $task->taskInput){
                                                array_push($storeDoneTasks, "Order ".$task->taskInput." add on/s per visit");
                                            }
                                            break;
                                        case "VST":
                                            array_push($storeDoneTasks, "Visit in our store");
                                            break;
                                        case "FDBK":
                                            array_push($storeTasks, "Give a feedback/review per visit");
                                            break;
                                        case "PUGC":
                                            if($customerReserve->gcashCheckoutReceipt != null){
                                                array_push($storeDoneTasks, "Pay using gcash");
                                            }
                                            break;
                                        case "LUDN":
                                            if("11:00" >= $eatingTime && "14:00" <= $eatingTime){
                                                array_push($storeDoneTasks, "Eat during lunch/dinner hours");
                                            } else if("17:00" >= $eatingTime && "22:00" <= $eatingTime){
                                                array_push($storeDoneTasks, "Eat during lunch/dinner hours");
                                            } else {}
                                            break;
                                        default: 
                                    }
                                }
    
                                $currentStamp = $custStampCard->currentStamp + sizeof($storeDoneTasks);
                                if($currentStamp >= $stampCard->stampCapacity){
                                    $stampStatus = "Complete";
                                    $currentStamp = $stampCard->stampCapacity;
                                } else {
                                    $stampStatus = "Incomplete";
                                }
    
                                CustomerStampCard::where('id', $custStampCard->id)
                                ->update([
                                    'status' => $stampStatus,
                                    'claimed' => "No",
                                    'currentStamp' => $currentStamp,
                                ]);
    
                                foreach($storeDoneTasks as $storeDoneTask){
                                    CustomerTasksDone::create([
                                        'customer_id' => $customerReserve->customer_id,
                                        'customerStampCard_id' => $custStampCard->id,
                                        'taskName' => $storeDoneTask,
                                        'taskAccomplishDate' => $getDateToday,
                                        'booking_id' => $customerReserve->id,
                                        'booking_type' => "reserve",
                                    ]);
                                }
    
                            } else {
                                //mag add din pati sa mga tasks
                                $stampCardTasks = StampCardTasks::where('stampCards_id', $stampCard->id)->get();
                                foreach($stampCardTasks as $stampCardTask){
                                    $task = RestaurantTaskList::where('id', $stampCardTask->restaurantTaskLists_id)->first();
                                    switch($task->taskCode){
                                        case "SPND":
                                            array_push($storeTasks, "Spend ".$task->taskInput." pesos in 1 visit only");
                                            if($customerReserve->totalPrice >= $task->taskInput){
                                                array_push($storeDoneTasks, "Spend ".$task->taskInput." pesos in 1 visit only");
                                            }
                                            break;
                                        case "BRNG":
                                            array_push($storeTasks, "Bring ".$task->taskInput." friends in our store");
                                            if($customerReserve->numberOfPersons >= $task->taskInput){
                                                array_push($storeDoneTasks, "Bring ".$task->taskInput." friends in our store");
                                            }
                                            break;
                                        case "ORDR":
                                            array_push($storeTasks, "Order ".$task->taskInput." add on/s per visit");
                                            if($addOnQuantity >= $task->taskInput){
                                                array_push($storeDoneTasks, "Order ".$task->taskInput." add on/s per visit");
                                            }
                                            break;
                                        case "VST":
                                            array_push($storeTasks, "Visit in our store");
                                            array_push($storeDoneTasks, "Visit in our store");
                                            break;
                                        case "FDBK":
                                            array_push($storeTasks, "Give a feedback/review per visit");
                                            break;
                                        case "PUGC":
                                            array_push($storeTasks, "Pay using gcash");
                                            if($customerReserve->gcashCheckoutReceipt != null){
                                                array_push($storeDoneTasks, "Pay using gcash");
                                            }
                                            break;
                                        case "LUDN":
                                            // dd($time > date("H:i", strtotime("10:00 am")));
                                            //11:00am - 2:00pm (lunch)
                                            //5:00pm - 10:00pm (dinner)
                                            array_push($storeTasks, "Eat during lunch/dinner hours");
                                            if("11:00" >= $eatingTime && "14:00" <= $eatingTime){
                                                array_push($storeDoneTasks, "Eat during lunch/dinner hours");
                                            } else if("17:00" >= $eatingTime && "22:00" <= $eatingTime){
                                                array_push($storeDoneTasks, "Eat during lunch/dinner hours");
                                            } else {}
                                            break;
                                        default: 
                                    }
                                }
    
                                $stampReward = RestaurantRewardList::where('restAcc_id', $customerReserve->restAcc_id)->where('id', $stampCard->stampReward_id)->first();
    
                                switch($stampReward->rewardCode){
                                    case "DSCN": 
                                        $finalReward = "Discount $stampReward->rewardInput% in a Total Bill";
                                        break;
                                    case "FRPE": 
                                        $finalReward = "Free $stampReward->rewardInput person in a group";
                                        break;
                                    case "HLF": 
                                        $finalReward = "Half in the group will be free";
                                        break;
                                    case "ALL": 
                                        $finalReward = "All people in the group will be free";
                                        break;
                                    default: 
                                        $finalReward = "None";
                                }
    
                                $finalStampCapac = sizeof($storeDoneTasks);
                                if(sizeof($storeDoneTasks) >= $stampCard->stampCapacity){
                                    $finalStampCapac = $stampCard->stampCapacity;
                                }
    
                                $custStampCardNew = CustomerStampCard::create([
                                    'customer_id' => $customerReserve->customer_id,
                                    'restAcc_id' => $customerReserve->restAcc_id,
                                    'status' => "Incomplete",
                                    'claimed' => "No",
                                    'currentStamp' => $finalStampCapac,
                                    'stampReward' => $finalReward,
                                    'stampValidity' => $stampCard->stampValidity,
                                    'stampCapacity' => $stampCard->stampCapacity,
                                ]);
    
                                foreach($storeTasks as $storeTask){
                                    CustomerStampTasks::create([
                                        'customerStampCard_id' => $custStampCardNew->id,
                                        'taskName' => $storeTask,
                                    ]);
                                }
    
                                foreach($storeDoneTasks as $storeDoneTask){
                                    CustomerTasksDone::create([
                                        'customer_id' => $customerReserve->customer_id,
                                        'customerStampCard_id' => $custStampCardNew->id,
                                        'taskName' => $storeDoneTask,
                                        'taskAccomplishDate' => $getDateToday,
                                        'booking_id' => $customerReserve->id,
                                        'booking_type' => "reserve",
                                    ]);
                                }
    
                            }
                        }
                    }
                }
            }
    
                CustomerOrdering::where('id', $id)->where('status', 'eating')
                ->update([
                    'status' => "checkout",
                ]);
    
                CustomerReserve::where('id', $customerOrdering->custBook_id)->where('status', 'eating')
                ->update([
                    'status' => "completed",
                    'checkoutStatus' => "customerFeedback",
                    'completeDateTime' => date("Y-m-d H:i:s"),
                ]);
                
                if($customerReserve->customer_id != 0){
                    $notif = CustomerNotification::create([
                        'customer_id' => $customerReserve->customer_id,
                        'restAcc_id' => $customerReserve->restAcc_id,
                        'notificationType' => "Complete",
                        'notificationTitle' => "Your payment is successful! Thank you for dining! You may give us a rating and share your experience.",
                        'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
                        'notificationStatus' => "Unread",
                    ]);
            
                    $customerAccount = CustomerAccount::where('id', $customerReserve->customer_id)->first();
                    
                    $finalImageUrl = "";
                    if ($restaurant->rLogo == ""){
                        $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                    } else {
                        $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                    }
            
                    if($customerAccount != null){
                        $to = $customerAccount->deviceToken;
                        $notification = array(
                            'title' => "$restaurant->rAddress, $restaurant->rCity",
                            'body' => "Your payment is successful! Thank you for dining! You may give us a rating and share your experience.",
                        );
                        $data = array(
                            'notificationType' => "Complete",
                            'notificationId' => $notif->id,
                            'notificationRLogo' => $finalImageUrl,
                        );
                        $this->sendFirebaseNotification($to, $notification, $data);
                    }
                }
            Session::flash('completed');
            return redirect('/restaurant/live-transaction/customer-ordering/list');
        }
    }
    public function ltCustOrderOSInsAmount($id){
        $customerOrdering = CustomerOrdering::where('id', $id)->where('status', 'eating')->first();

        if($customerOrdering->custBookType == "queue"){
            $customerQueue = CustomerQueue::where('id', $customerOrdering->custBook_id)->first();
            CustomerQueue::where('id', $customerOrdering->custBook_id)
            ->update([
                'checkoutStatus' => "gcashInsufficientAmount"
            ]);

            $customerAccount = CustomerAccount::where('id', $customerQueue->customer_id)->first();
            $restaurant = RestaurantAccount::select('id', 'rAddress', 'rCity', 'rLogo')->where('id', $customerQueue->restAcc_id)->first();

            $finalImageUrl = "";
            if ($restaurant->rLogo == ""){
                $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
            } else {
                $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
            }
    
            if($customerAccount != null){
                $to = $customerAccount->deviceToken;
                $notification = array(
                    'title' => "$restaurant->rAddress, $restaurant->rCity",
                    'body' => "Your payment via gcash is insufficient please wait for the staff to collect your remaining balance.",
                );
                $data = array(
                    'notificationType' => "Insufficient Amount",
                    'notificationId' => 0,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }
        } else {
            $customerReserve = CustomerReserve::where('id', $customerOrdering->custBook_id)->first();
            CustomerReserve::where('id', $customerOrdering->custBook_id)
            ->update([
                'checkoutStatus' => "gcashInsufficientAmount"
            ]);

            $customerAccount = CustomerAccount::where('id', $customerReserve->customer_id)->first();
            $restaurant = RestaurantAccount::select('id', 'rAddress', 'rCity', 'rLogo')->where('id', $customerReserve->restAcc_id)->first();

            $finalImageUrl = "";
            if ($restaurant->rLogo == ""){
                $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
            } else {
                $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
            }
    
            if($customerAccount != null){
                $to = $customerAccount->deviceToken;
                $notification = array(
                    'title' => "$restaurant->rAddress, $restaurant->rCity",
                    'body' => "Your payment via gcash is insufficient please wait for the staff to collect your remaining balance.",
                );
                $data = array(
                    'notificationType' => "Insufficient Amount",
                    'notificationId' => 0,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }
        }
        Session::flash('responseSubmitted');
        return redirect('/restaurant/live-transaction/customer-ordering/list/'.$id.'/order-summary');
    }
    public function ltCustOrderOSInvReceipt($id){
        $customerOrdering = CustomerOrdering::where('id', $id)->where('status', 'eating')->first();

        if($customerOrdering->custBookType == "queue"){
            $customerQueue = CustomerQueue::where('id', $customerOrdering->custBook_id)->first();
            CustomerQueue::where('id', $customerOrdering->custBook_id)
            ->update([
                'checkoutStatus' => "gcashIncorretFormat"
            ]);

            $customerAccount = CustomerAccount::where('id', $customerQueue->customer_id)->first();
            $restaurant = RestaurantAccount::select('id', 'rAddress', 'rCity', 'rLogo')->where('id', $customerQueue->restAcc_id)->first();

            $finalImageUrl = "";
            if ($restaurant->rLogo == ""){
                $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
            } else {
                $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
            }
    
            if($customerAccount != null){
                $to = $customerAccount->deviceToken;
                $notification = array(
                    'title' => "$restaurant->rAddress, $restaurant->rCity",
                    'body' => "Your gcash receipt is invalid please upload the correct receipt.",
                );
                $data = array(
                    'notificationType' => "Invalid Receipt",
                    'notificationId' => 0,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }
        } else {
            $customerReserve = CustomerReserve::where('id', $customerOrdering->custBook_id)->first();
            CustomerReserve::where('id', $customerOrdering->custBook_id)
            ->update([
                'checkoutStatus' => "gcashIncorretFormat"
            ]);

            $customerAccount = CustomerAccount::where('id', $customerReserve->customer_id)->first();
            $restaurant = RestaurantAccount::select('id', 'rAddress', 'rCity', 'rLogo')->where('id', $customerReserve->restAcc_id)->first();

            $finalImageUrl = "";
            if ($restaurant->rLogo == ""){
                $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
            } else {
                $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
            }
    
            if($customerAccount != null){
                $to = $customerAccount->deviceToken;
                $notification = array(
                    'title' => "$restaurant->rAddress, $restaurant->rCity",
                    'body' => "Your gcash receipt is invalid please upload the correct receipt.",
                );
                $data = array(
                    'notificationType' => "Invalid Receipt",
                    'notificationId' => 0,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }
        }
        Session::flash('responseSubmitted');
        return redirect('/restaurant/live-transaction/customer-ordering/list/'.$id.'/order-summary');
    }
    public function ltCustOrderOSRunaway($id){
        $getDateToday = date("Y-m-d");
        $getDateTimeToday = date('Y-m-d H:i:s');
        $customerOrdering = CustomerOrdering::where('id', $id)->where('status', 'eating')->first();

        if($customerOrdering->custBookType == "queue"){
            $customerQueue = CustomerQueue::where('id', $customerOrdering->custBook_id)->where('status', 'eating')->first();
            $restaurant = RestaurantAccount::where('id', $customerQueue->restAcc_id)->first();

            CustomerOrdering::where('id', $id)->where('status', 'eating')
            ->update([
                'status' => "checkout",
            ]);

            CustomerQueue::where('id', $customerOrdering->custBook_id)->where('status', 'eating')
            ->update([
                'status' => "runaway",
                'checkoutStatus' => "runaway",
                'runawayDateTime' => date("Y-m-d H:i:s"),
            ]);

            if($customerQueue->customer_id != 0){
                // CHECK IF THERES LATEST CANCEL OFFENSE
                $mainOffense = CustOffenseMain::where('customer_id', $customerQueue->customer_id)
                ->where('restAcc_id', $restaurant->id)
                ->where('offenseType', "runaway")
                ->latest()
                ->first();

                $restOffense = Cancellation::where('restAcc_id', $restaurant->id)->first();
                $custMainOffenseId = "";

                if($restOffense->blockDays != 0){
                    //check muna kung nag apply yuing restaurant ng offense
                    if($mainOffense != null){
                        //kapag may exisint offense yung customer
                        if($mainOffense->offenseValidity != null){
                            //kapag hindi null meaning nakablock na sya sa part na yon so dapat icompare naten yung dates kung tapos na ba, kung hindi pa then walang mangyayari kung tapos na then block natin ule
                            if(strtotime($mainOffense->offenseValidity) < strtotime($getDateTimeToday)){
                                // meanning nag lift na yung ban kaso check muna kung permanent ba or hindi kase kung permanent yan ibigsabihin wala na yan di na magdadagdag pero kung hindi edi mag dagdagtayo
                                if($mainOffense->offenseDaysBlock != "Permanent"){
                                    //so hindi sya permanent block, so pwede pa natin ule sya mablock
                                    if($restOffense->noOfCancellation == 1){
                                        //check kung isa lang ininput, kase kung isa lang matik maboblock agad
                                        if($restOffense->blockDays == "Permanent"){
                                            //check kung permanent, kase yung ngayon date ang ilalagay
                                            $createMainOffense = CustOffenseMain::create([
                                                'customer_id' => $customerQueue->customer_id,
                                                'restAcc_id' => $restaurant->id,
                                                'offenseType' => "runaway",
                                                'totalOffense' => 1,
                                                'offenseCapacity' => $restOffense->noOfCancellation,
                                                'offenseDaysBlock' => $restOffense->blockDays,
                                                'offenseValidity' => $getDateTimeToday,
                                            ]);
                                            $custMainOffenseId = $createMainOffense->id;
                                        } else {
                                            //check kung hindi permanent kase bibilangin yung kung ilang days ang ban tas iseset yung date na yon sa offensevalidity
                                            for($i=1; $i<=$restOffense->blockDays; $i++){
                                                $finalOffenseValidity = date('Y-m-d H:i:s', strtotime($getDateTimeToday. ' + 1 days'));
                                                $getDateTimeToday = $finalOffenseValidity;
                                            }
                                            $createMainOffense = CustOffenseMain::create([
                                                'customer_id' => $customerQueue->customer_id,
                                                'restAcc_id' => $restaurant->id,
                                                'offenseType' => "runaway",
                                                'totalOffense' => 1,
                                                'offenseCapacity' => $restOffense->noOfCancellation,
                                                'offenseDaysBlock' => $restOffense->blockDays,
                                                'offenseValidity' => $finalOffenseValidity,
                                            ]);
                                            $custMainOffenseId = $createMainOffense->id;
                                        }
                                    } else {
                                        //kung yung noOfcanccellation ay lagpas sa isa then create na tayo tas di pa to block
                                        $createMainOffense = CustOffenseMain::create([
                                            'customer_id' => $customerQueue->customer_id,
                                            'restAcc_id' => $restaurant->id,
                                            'offenseType' => "runaway",
                                            'totalOffense' => 1,
                                            'offenseCapacity' => $restOffense->noOfCancellation,
                                            'offenseDaysBlock' => $restOffense->blockDays,
                                        ]);
                                        $custMainOffenseId = $createMainOffense->id;
                                    }
                                } 
                            } 
                        } else {
                            // null sya so meaning di pa sya na bablock
                            if(($mainOffense->totalOffense + 1) == $mainOffense->offenseCapacity) {
                                // dito naman syempre mag iinsert tayo ng isa, kapag nag insert tayo ng isa tas nag equal sa capacity ma bablock yon
                                if($mainOffense->offenseDaysBlock == "Permanent"){
                                    //so kapag permanent yung pagkablock nya ganito mangyayari
                                    CustOffenseMain::where('id', $mainOffense->id)
                                    ->update([
                                        'totalOffense' => $mainOffense->totalOffense + 1,
                                        'offenseValidity' => $getDateTimeToday,
                                    ]);
                                    $custMainOffenseId = $mainOffense->id;
                                } else {
                                    //kung hindi permanent yung pagkablock gawin naten yung pag add ng offenseValidity
                                    for($i=1; $i<=$mainOffense->offenseDaysBlock; $i++){
                                        $finalOffenseValidity = date('Y-m-d H:i:s', strtotime($getDateTimeToday. ' + 1 days'));
                                        $getDateTimeToday = $finalOffenseValidity;
                                    }
                                    CustOffenseMain::where('id', $mainOffense->id)
                                    ->update([
                                        'totalOffense' => $mainOffense->totalOffense + 1,
                                        'offenseValidity' => $finalOffenseValidity,
                                    ]);
                                    $custMainOffenseId = $mainOffense->id;
                                }
                            } else {
                                //walang block na mangyayari, update lang
                                CustOffenseMain::where('id', $mainOffense->id)
                                ->update([
                                    'totalOffense' => $mainOffense->totalOffense + 1,
                                ]);
                                $custMainOffenseId = $mainOffense->id;
                            }
                        }
                    } else {
                        //so wala pang offense totally like first time neto, lalagyan naten ng offense
                        if($restOffense->noOfCancellation == 1){
                            //check kung isa lang ininput, kase kung isa lang matik maboblock agad
                            if($restOffense->blockDays == "Permanent"){
                                //check kung permanent, kase yung ngayon date ang ilalagay
                                $createMainOffense = CustOffenseMain::create([
                                    'customer_id' => $customerQueue->customer_id,
                                    'restAcc_id' => $restaurant->id,
                                    'offenseType' => "runaway",
                                    'totalOffense' => 1,
                                    'offenseCapacity' => $restOffense->noOfCancellation,
                                    'offenseDaysBlock' => $restOffense->blockDays,
                                    'offenseValidity' => $getDateTimeToday,
                                ]);
                                $custMainOffenseId = $createMainOffense->id;
                            } else {
                                //check kung hindi permanent kase bibilangin yung kung ilang days ang ban tas iseset yung date na yon sa offensevalidity
                                for($i=1; $i<=$restOffense->blockDays; $i++){
                                    $finalOffenseValidity = date('Y-m-d H:i:s', strtotime($getDateTimeToday. ' + 1 days'));
                                    $getDateTimeToday = $finalOffenseValidity;
                                }
                                $createMainOffense = CustOffenseMain::create([
                                    'customer_id' => $customerQueue->customer_id,
                                    'restAcc_id' => $restaurant->id,
                                    'offenseType' => "runaway",
                                    'totalOffense' => 1,
                                    'offenseCapacity' => $restOffense->noOfCancellation,
                                    'offenseDaysBlock' => $restOffense->blockDays,
                                    'offenseValidity' => $finalOffenseValidity,
                                ]);
                                $custMainOffenseId = $createMainOffense->id;
                            }
                        } else {
                            //kung yung noOfcanccellation ay lagpas sa isa then create na tayo tas di pa to block
                            $createMainOffense = CustOffenseMain::create([
                                'customer_id' => $customerQueue->customer_id,
                                'restAcc_id' => $restaurant->id,
                                'offenseType' => "runaway",
                                'totalOffense' => 1,
                                'offenseCapacity' => $restOffense->noOfCancellation,
                                'offenseDaysBlock' => $restOffense->blockDays,
                            ]);
                            $custMainOffenseId = $createMainOffense->id;
                        }
                    }
                    //lastly add naten to sa database
                    if($custMainOffenseId != ""){
                        CustOffenseEach::create([
                            'custOffMain_id' => $custMainOffenseId,
                            'customer_id' => $customerQueue->customer_id,
                            'restAcc_id' => $restaurant->id,
                            'book_id' => $customerQueue->id,
                            'book_type' => "queue",
                            'offenseType' => "runaway",
                        ]);
                    }
                }


                $notif = CustomerNotification::create([
                    'customer_id' => $customerQueue->customer_id,
                    'restAcc_id' => $customerQueue->restAcc_id,
                    'notificationType' => "Runaway",
                    'notificationTitle' => "You have left without Paying",
                    'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
                    'notificationStatus' => "Unread",
                ]);
                
                $customerAccount = CustomerAccount::where('id', $customerQueue->customer_id)->first();

                $finalImageUrl = "";
                if ($restaurant->rLogo == ""){
                    $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                } else {
                    $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                }

                if($customerAccount != null){
                    $to = $customerAccount->deviceToken;
                    $notification = array(
                        'title' => "$restaurant->rAddress, $restaurant->rCity",
                        'body' => "You have left without Paying!",
                    );
                    $data = array(
                        'notificationType' => "Runaway",
                        'notificationId' => $notif->id,
                        'notificationRLogo' => $finalImageUrl,
                    );
                    $this->sendFirebaseNotification($to, $notification, $data);
                }
            }
            Session::flash('runaway');
            return redirect('/restaurant/live-transaction/customer-ordering/list');
        } else {
            $customerReserve = CustomerReserve::where('id', $customerOrdering->custBook_id)->where('status', 'eating')->first();
            $restaurant = RestaurantAccount::where('id', $customerReserve->restAcc_id)->first();
            CustomerOrdering::where('id', $id)->where('status', 'eating')
            ->update([
                'status' => "checkout",
            ]);

            CustomerReserve::where('id', $customerOrdering->custBook_id)->where('status', 'eating')
            ->update([
                'status' => "runaway",
                'checkoutStatus' => "runaway",
                'runawayDateTime' => date("Y-m-d H:i:s"),
            ]);

            if($customerReserve->customer_id != 0){
                // CHECK IF THERES LATEST CANCEL OFFENSE
                $mainOffense = CustOffenseMain::where('customer_id', $customerReserve->customer_id)
                ->where('restAcc_id', $restaurant->id)
                ->where('offenseType', "runaway")
                ->latest()
                ->first();

                $restOffense = Cancellation::where('restAcc_id', $restaurant->id)->first();
                $custMainOffenseId = "";

                if($restOffense->blockDays != 0){
                    //check muna kung nag apply yuing restaurant ng offense
                    if($mainOffense != null){
                        //kapag may exisint offense yung customer
                        if($mainOffense->offenseValidity != null){
                            //kapag hindi null meaning nakablock na sya sa part na yon so dapat icompare naten yung dates kung tapos na ba, kung hindi pa then walang mangyayari kung tapos na then block natin ule
                            if(strtotime($mainOffense->offenseValidity) < strtotime($getDateTimeToday)){
                                // meanning nag lift na yung ban kaso check muna kung permanent ba or hindi kase kung permanent yan ibigsabihin wala na yan di na magdadagdag pero kung hindi edi mag dagdagtayo
                                if($mainOffense->offenseDaysBlock != "Permanent"){
                                    //so hindi sya permanent block, so pwede pa natin ule sya mablock
                                    if($restOffense->noOfCancellation == 1){
                                        //check kung isa lang ininput, kase kung isa lang matik maboblock agad
                                        if($restOffense->blockDays == "Permanent"){
                                            //check kung permanent, kase yung ngayon date ang ilalagay
                                            $createMainOffense = CustOffenseMain::create([
                                                'customer_id' => $customerReserve->customer_id,
                                                'restAcc_id' => $restaurant->id,
                                                'offenseType' => "runaway",
                                                'totalOffense' => 1,
                                                'offenseCapacity' => $restOffense->noOfCancellation,
                                                'offenseDaysBlock' => $restOffense->blockDays,
                                                'offenseValidity' => $getDateTimeToday,
                                            ]);
                                            $custMainOffenseId = $createMainOffense->id;
                                        } else {
                                            //check kung hindi permanent kase bibilangin yung kung ilang days ang ban tas iseset yung date na yon sa offensevalidity
                                            for($i=1; $i<=$restOffense->blockDays; $i++){
                                                $finalOffenseValidity = date('Y-m-d H:i:s', strtotime($getDateTimeToday. ' + 1 days'));
                                                $getDateTimeToday = $finalOffenseValidity;
                                            }
                                            $createMainOffense = CustOffenseMain::create([
                                                'customer_id' => $customerReserve->customer_id,
                                                'restAcc_id' => $restaurant->id,
                                                'offenseType' => "runaway",
                                                'totalOffense' => 1,
                                                'offenseCapacity' => $restOffense->noOfCancellation,
                                                'offenseDaysBlock' => $restOffense->blockDays,
                                                'offenseValidity' => $finalOffenseValidity,
                                            ]);
                                            $custMainOffenseId = $createMainOffense->id;
                                        }
                                    } else {
                                        //kung yung noOfcanccellation ay lagpas sa isa then create na tayo tas di pa to block
                                        $createMainOffense = CustOffenseMain::create([
                                            'customer_id' => $customerReserve->customer_id,
                                            'restAcc_id' => $restaurant->id,
                                            'offenseType' => "runaway",
                                            'totalOffense' => 1,
                                            'offenseCapacity' => $restOffense->noOfCancellation,
                                            'offenseDaysBlock' => $restOffense->blockDays,
                                        ]);
                                        $custMainOffenseId = $createMainOffense->id;
                                    }
                                } 
                            } 
                        } else {
                            // null sya so meaning di pa sya na bablock
                            if(($mainOffense->totalOffense + 1) == $mainOffense->offenseCapacity) {
                                // dito naman syempre mag iinsert tayo ng isa, kapag nag insert tayo ng isa tas nag equal sa capacity ma bablock yon
                                if($mainOffense->offenseDaysBlock == "Permanent"){
                                    //so kapag permanent yung pagkablock nya ganito mangyayari
                                    CustOffenseMain::where('id', $mainOffense->id)
                                    ->update([
                                        'totalOffense' => $mainOffense->totalOffense + 1,
                                        'offenseValidity' => $getDateTimeToday,
                                    ]);
                                    $custMainOffenseId = $mainOffense->id;
                                } else {
                                    //kung hindi permanent yung pagkablock gawin naten yung pag add ng offenseValidity
                                    for($i=1; $i<=$mainOffense->offenseDaysBlock; $i++){
                                        $finalOffenseValidity = date('Y-m-d H:i:s', strtotime($getDateTimeToday. ' + 1 days'));
                                        $getDateTimeToday = $finalOffenseValidity;
                                    }
                                    CustOffenseMain::where('id', $mainOffense->id)
                                    ->update([
                                        'totalOffense' => $mainOffense->totalOffense + 1,
                                        'offenseValidity' => $finalOffenseValidity,
                                    ]);
                                    $custMainOffenseId = $mainOffense->id;
                                }
                            } else {
                                //walang block na mangyayari, update lang
                                CustOffenseMain::where('id', $mainOffense->id)
                                ->update([
                                    'totalOffense' => $mainOffense->totalOffense + 1,
                                ]);
                                $custMainOffenseId = $mainOffense->id;
                            }
                        }
                    } else {
                        //so wala pang offense totally like first time neto, lalagyan naten ng offense
                        if($restOffense->noOfCancellation == 1){
                            //check kung isa lang ininput, kase kung isa lang matik maboblock agad
                            if($restOffense->blockDays == "Permanent"){
                                //check kung permanent, kase yung ngayon date ang ilalagay
                                $createMainOffense = CustOffenseMain::create([
                                    'customer_id' => $customerReserve->customer_id,
                                    'restAcc_id' => $restaurant->id,
                                    'offenseType' => "runaway",
                                    'totalOffense' => 1,
                                    'offenseCapacity' => $restOffense->noOfCancellation,
                                    'offenseDaysBlock' => $restOffense->blockDays,
                                    'offenseValidity' => $getDateTimeToday,
                                ]);
                                $custMainOffenseId = $createMainOffense->id;
                            } else {
                                //check kung hindi permanent kase bibilangin yung kung ilang days ang ban tas iseset yung date na yon sa offensevalidity
                                for($i=1; $i<=$restOffense->blockDays; $i++){
                                    $finalOffenseValidity = date('Y-m-d H:i:s', strtotime($getDateTimeToday. ' + 1 days'));
                                    $getDateTimeToday = $finalOffenseValidity;
                                }
                                $createMainOffense = CustOffenseMain::create([
                                    'customer_id' => $customerReserve->customer_id,
                                    'restAcc_id' => $restaurant->id,
                                    'offenseType' => "runaway",
                                    'totalOffense' => 1,
                                    'offenseCapacity' => $restOffense->noOfCancellation,
                                    'offenseDaysBlock' => $restOffense->blockDays,
                                    'offenseValidity' => $finalOffenseValidity,
                                ]);
                                $custMainOffenseId = $createMainOffense->id;
                            }
                        } else {
                            //kung yung noOfcanccellation ay lagpas sa isa then create na tayo tas di pa to block
                            $createMainOffense = CustOffenseMain::create([
                                'customer_id' => $customerReserve->customer_id,
                                'restAcc_id' => $restaurant->id,
                                'offenseType' => "runaway",
                                'totalOffense' => 1,
                                'offenseCapacity' => $restOffense->noOfCancellation,
                                'offenseDaysBlock' => $restOffense->blockDays,
                            ]);
                            $custMainOffenseId = $createMainOffense->id;
                        }
                    }
                    //lastly add naten to sa database
                    if($custMainOffenseId != ""){
                        CustOffenseEach::create([
                            'custOffMain_id' => $custMainOffenseId,
                            'customer_id' => $customerReserve->customer_id,
                            'restAcc_id' => $restaurant->id,
                            'book_id' => $customerReserve->id,
                            'book_type' => "reserve",
                            'offenseType' => "runaway",
                        ]);
                    }
                }


                $notif = CustomerNotification::create([
                    'customer_id' => $customerReserve->customer_id,
                    'restAcc_id' => $customerReserve->restAcc_id,
                    'notificationType' => "Runaway",
                    'notificationTitle' => "You have left without Paying",
                    'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
                    'notificationStatus' => "Unread",
                ]);
                
                $customerAccount = CustomerAccount::where('id', $customerReserve->customer_id)->first();

                $finalImageUrl = "";
                if ($restaurant->rLogo == ""){
                    $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                } else {
                    $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                }

                if($customerAccount != null){
                    $to = $customerAccount->deviceToken;
                    $notification = array(
                        'title' => "$restaurant->rAddress, $restaurant->rCity",
                        'body' => "You have left without Paying!",
                    );
                    $data = array(
                        'notificationType' => "Runaway",
                        'notificationId' => $notif->id,
                        'notificationRLogo' => $finalImageUrl,
                    );
                    $this->sendFirebaseNotification($to, $notification, $data);
                }
            }

            Session::flash('runaway');
            return redirect('/restaurant/live-transaction/customer-ordering/list');
        }

    }
    public function ltCustOrderORRequestDone($id, $request_id){
        CustomerLRequest::where('id', $request_id)->where('custOrdering_id', $id)
        ->update([
            'requestDone' => "Yes"
        ]);
        Session::flash('requestDone');
        return redirect('/restaurant/live-transaction/customer-ordering/list/'.$id.'/order-request');
    }
    public function ltCustOrderORServe(Request $request, $id){
        if($request->order == null){
            $request->session()->flash('selectAtLeastOne');
            return redirect('/restaurant/live-transaction/customer-ordering/list/'.$id.'/order-request');
        } else {
            foreach($request->order as $orderId){
                CustomerLOrder::where('id', $orderId)->where('custOrdering_id', $id)
                ->update([
                    'orderDone' => "Yes"
                ]);
            }
            $request->session()->flash('servedComplete');
            return redirect('/restaurant/live-transaction/customer-ordering/list/'.$id.'/order-request');
        }
    }
    public function ltCustOrderApplyDiscounts(Request $request, $id){
        $restAcc_id = Session::get('loginId');
        // LAGyAN NA MUNA NG VALIDATION
        $customerOrder = CustomerOrdering::where('id', $id)->where('restAcc_id', $restAcc_id)->first();
        if($customerOrder->custBookType == "queue"){
            CustomerQueue::where('id', $customerOrder->custBook_id)
            ->update([
                'numberOfPwd' => $request->numberOfPwd,
                'numberOfChildren' => $request->numberOfChildren,
                'childrenDiscount' => $request->childrenDiscount,
                'additionalDiscount' => $request->additionalDiscount,
                'promoDiscount' => $request->promoDiscount,
                'offenseCharges' => $request->offenseCharges,
                'totalPrice' => $request->totalPrice,
            ]);
        } else {
            CustomerReserve::where('id', $customerOrder->custBook_id)
            ->update([
                'numberOfPwd' => $request->numberOfPwd,
                'numberOfChildren' => $request->numberOfChildren,
                'childrenDiscount' => $request->childrenDiscount,
                'additionalDiscount' => $request->additionalDiscount,
                'promoDiscount' => $request->promoDiscount,
                'offenseCharges' => $request->offenseCharges,
                'totalPrice' => $request->totalPrice,
            ]);
        }
        Session::flash('discounted');
        return redirect('/restaurant/live-transaction/customer-ordering/list/'.$id.'/order-summary');
    }
    public function ltCustOrderGrantAccess($id){
        $restAcc_id = Session::get('loginId');
        $getDateToday = date('Y-m-d');
        $customerOrder = CustomerOrdering::where('id', $id)->where('orderingDate', $getDateToday)->first();
        $restaurant = RestaurantAccount::where('id', $customerOrder->restAcc_id)->first();
        $customerId = "";
        if($customerOrder->custBookType == "queue"){
            $customer = CustomerQueue::where('id', $customerOrder->custBook_id)->first();
            CustomerQueue::where('id', $customerOrder->custBook_id)
            ->update([
                'status' => 'eating',
                'eatingDateTime' => date("Y-m-d H:i:s")
            ]);
            $customerId = $customer->customer_id;
        } else {
            $customer = CustomerReserve::where('id', $customerOrder->custBook_id)->first();
            CustomerReserve::where('id', $customerOrder->custBook_id)
            ->update([
                'status' => 'eating',
                'eatingDateTime' => date("Y-m-d H:i:s")
            ]);
            $customerId = $customer->customer_id;
        }

        CustomerOrdering::where('id', $id)->where('orderingDate', $getDateToday)
        ->update([
            'grantedAccess' => "Yes"
        ]);
        
        if($customerId != 0){
            $notif = CustomerNotification::create([
                'customer_id' => $customerId,
                'restAcc_id' => $restAcc_id,
                'notificationType' => "Table is Ready",
                'notificationTitle' => "Your table is now Ready. Enjoy your meal!",
                'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
                'notificationStatus' => "Unread",
            ]);
    
            $customerAccount = CustomerAccount::where('id', $customerId)->first();
            
            $finalImageUrl = "";
            if ($restaurant->rLogo == ""){
                $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
            } else {
                $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
            }
    
            if($customerAccount != null){
                $to = $customerAccount->deviceToken;
                $notification = array(
                    'title' => "$restaurant->rAddress, $restaurant->rCity",
                    'body' => "Your table is now Ready. Enjoy your meal!",
                );
                $data = array(
                    'notificationType' => "Table is Ready",
                    'notificationId' => $notif->id,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }
        }
        Session::flash('grantedAccess');
        return redirect('/restaurant/live-transaction/customer-ordering/list/'.$id.'/order-request');
    }
    public function ltAppCustQAddWalkIn(Request $request){
        $restAcc_id = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAcc_id)->first();

        if($restaurant->status == "Published"){
            if($this->checkTodaySched() != "Closed Now"){
                $request->validate([
                    'walkInName' => 'required',
                    'walkInPersons' => 'required',
                    'walkInOrder' => 'required',
                ],
                [
                    'walkInName.required' => 'Name is required',
                    'walkInPersons.required' => 'No. of Persons is required',
                    'walkInOrder.required' => 'Order is required',
                ]);
        
        
                $orderSet = OrderSet::where('id', $request->walkInOrder)->first();
                
                $totalBill = $orderSet->orderSetPrice * $request->walkInPersons;
                $seniorDiscount = $orderSet->orderSetPrice * ($request->walkInPwd * 0.2);
                $childrenDiscount = $orderSet->orderSetPrice * $request->walkInChildren;
                
                $totalBill = $totalBill - ($seniorDiscount + $childrenDiscount);
        
                $finalNoOfTables = "";
                if($request->walkInPersons <= $request->capacityPerTable){
                    $finalNoOfTables = 1;
                } else {
                    $x = intval($request->walkInPersons / $request->capacityPerTable);
                    $remainder = intval($request->walkInPersons % $request->capacityPerTable);
                    if($remainder > 0){
                        $x += 1;
                    }
                    $finalNoOfTables = $x;
                }
        
                CustomerQueue::create([
                    'customer_id' => 0,
                    'restAcc_id' => $restAcc_id,
                    'orderSet_id' => $request->walkInOrder,
                    'status' => "approved",
                    'name' => $request->walkInName,
                    'numberOfPersons' => $request->walkInPersons,
                    'numberOfTables' => $finalNoOfTables,
                    'hoursOfStay' => $request->walkInHoursOfStay,
                    'numberOfChildren' => $request->walkInChildren,
                    'numberOfPwd' => $request->walkInPwd,
                    'totalPwdChild' => ($request->walkInChildren + $request->walkInPwd),
                    'notes' => $request->walkInNotes,
                    'totalPrice' => $totalBill,
                    'queueDate' => date("Y-m-d"),
                ]);
        
                $request->session()->flash('walkInAdded');
                return redirect('/restaurant/live-transaction/approved-customer/queue');
            } else {
                $request->session()->flash('currentlyClosed');
                return redirect('/restaurant/live-transaction/approved-customer/queue/add-walk-in');
            }
        } else {
            $request->session()->flash('currentlyUnpublished');
            return redirect('/restaurant/live-transaction/approved-customer/queue/add-walk-in');
        }

        
    }
    public function ltAppCustRAdmit(Request $request, $id){
        $restAcc_id = Session::get('loginId');
        $request->validate([
            'tableNumbers' => 'required',
        ]);
        $getDateToday = date('Y-m-d');

        $customerReserve = CustomerReserve::where('id', $id)->where('restAcc_id', $restAcc_id)->where('reserveDate', $getDateToday)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch', 'rAddress', 'rCity', 'rLogo', 'id', 'rNumberOfTables')->where('id', $customerReserve->restAcc_id)->first();

        $tableNumbers = explode(',', $request->tableNumbers);

        $count = 0;
        $storeNumbers = array();
        foreach($tableNumbers as $tableNumber){
            array_push($storeNumbers, (int)$tableNumber);
            if (!is_numeric($tableNumber) || $tableNumber <= 0) {
                $count++;
            }
        }
        if($count > 0){
            $request->session()->flash('inputCorrectFormat');
            return redirect('/restaurant/live-transaction/approved-customer/reserve/'.$id);
        } else {
            if($customerReserve->numberOfTables != sizeOf($storeNumbers)){
                $request->session()->flash('inputCorrectNumberOfTables');
                return redirect('/restaurant/live-transaction/approved-customer/reserve/'.$id);
            } else {
                $countError = 0;
                foreach($storeNumbers as $storeNumber){
                    if($storeNumber > $restaurant->rNumberOfTables){
                        $countError++;
                    }
                }
                if($countError > 0){
                    $request->session()->flash('inputCorrectTableNumber');
                    return redirect('/restaurant/live-transaction/approved-customer/reserve/'.$id);
                } else {
                    $result = array_unique($storeNumbers);
                    if(sizeOf($result) != sizeOf($storeNumbers)){
                        $request->session()->flash('mustBeDiffrentTables');
                        return redirect('/restaurant/live-transaction/approved-customer/reserve/'.$id);
                    } else {
                        $finalTableNumber = "";
                        if(sizeOf($storeNumbers) == 1){
                            $finalTableNumber = $storeNumbers[0];
                        } else {
                            for ($i=0; $i<count($storeNumbers); $i++){
                                if($i == 0){
                                    $finalTableNumber = $storeNumbers[$i].',';
                                } else if ($i == count($storeNumbers)-1){
                                    $finalTableNumber = $finalTableNumber.$storeNumbers[$i];
                                } else {
                                    $finalTableNumber = $finalTableNumber.$storeNumbers[$i].',';
                                }
                            }
                        }
            
                        $count2 = 0;
                        $customerTables = CustomerOrdering::where('restAcc_id', $restAcc_id)
                        ->where('status', 'eating')
                        ->where('orderingDate', $getDateToday)
                        ->get();
                        foreach ($customerTables as $customerTable){
                            $customerTableNumbers = explode(',', $customerTable->tableNumbers);
                            $result = array_intersect($storeNumbers, $customerTableNumbers);
                            if($result != null){
                                $count2++;
                            }
                        }
                        if($count2 > 0){
                            $request->session()->flash('tableExist');
                            return redirect('/restaurant/live-transaction/approved-customer/reserve/'.$id);
                        } else {
                            // UPDATE NA NG DATA
                            $restaurant = RestaurantAccount::select('rName', 'rBranch', 'rAddress', 'rCity', 'rLogo', 'id')->where('id', $customerReserve->restAcc_id)->first();
                            
                            if($customerReserve->name == null){
                                $customer = CustomerAccount::where('id', $customerReserve->customer_id)->first();
                                $finaleCustName = $customer->name;
                            } else {
                                $finaleCustName = $customerReserve->name;
                            }

                            CustomerOrdering::create([
                                'custBook_id' => $id,
                                'restAcc_id' => $customerReserve->restAcc_id,
                                'custName' => $finaleCustName,
                                'custBookType' => "reserve",
                                'tableNumbers' => $finalTableNumber,
                                'availableQrAccess' => $customerReserve->numberOfTables - 1,
                                'grantedAccess' => "No",
                                'status' => "eating",
                                'orderingDate' => date('Y-m-d'),
                            ]);

                            CustomerReserve::where('id', $id)
                            ->update([
                                'status' => 'tableSettingUp',
                                'tableSettingDateTime' => date('Y-m-d H:i:s'),
                            ]);

                            $notif = CustomerNotification::create([
                                'customer_id' => $customerReserve->customer_id,
                                'restAcc_id' => $customerReserve->restAcc_id,
                                'notificationType' => "Table Setting Up",
                                'notificationTitle' => "Your Table is Setting up",
                                'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
                                'notificationStatus' => "Unread",
                            ]);

                            $finalImageUrl = "";
                            if ($restaurant->rLogo == ""){
                                $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                            } else {
                                $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                            }

                            if($customer != null){
                                $to = $customer->deviceToken;
                                $notification = array(
                                    'title' => "$restaurant->rAddress, $restaurant->rCity",
                                    'body' => "Your Table is now being prepared",
                                );
                                $data = array(
                                    'notificationType' => "Table Setting Up",
                                    'notificationId' => $notif->id,
                                    'notificationRLogo' => $finalImageUrl,
                                );
                                $this->sendFirebaseNotification($to, $notification, $data);
                            }

                            $request->session()->flash('admitted');
                            return redirect('/restaurant/live-transaction/approved-customer/reserve');
                        }
                    }
                }
            }
        }
    }
    public function ltAppCustRNoShow($id){
        $getDateTimeToday = date('Y-m-d H:i:s');
        $customerReserve = CustomerReserve::where('id', $id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch', 'rAddress', 'rCity', 'rLogo', 'id')->where('id', $customerReserve->restAcc_id)->first();
        $customer = CustomerAccount::where('id', $customerReserve->customer_id)->first();

        CustomerReserve::where('id', $id)
        ->update([
            'status' => 'noShow',
        ]);


        // CHECK IF THERES LATEST CANCEL OFFENSE
        $mainOffense = CustOffenseMain::where('customer_id', $customerReserve->customer_id)
        ->where('restAcc_id', $restaurant->id)
        ->where('offenseType', "noshow")
        ->latest()
        ->first();

        $restOffense = Cancellation::where('restAcc_id', $restaurant->id)->first();
        $custMainOffenseId = "";

        if($restOffense->blockDays != 0){
            //check muna kung nag apply yuing restaurant ng offense
            if($mainOffense != null){
                //kapag may exisint offense yung customer
                if($mainOffense->offenseValidity != null){
                    //kapag hindi null meaning nakablock na sya sa part na yon so dapat icompare naten yung dates kung tapos na ba, kung hindi pa then walang mangyayari kung tapos na then block natin ule
                    if(strtotime($mainOffense->offenseValidity) < strtotime($getDateTimeToday)){
                        // meanning nag lift na yung ban kaso check muna kung permanent ba or hindi kase kung permanent yan ibigsabihin wala na yan di na magdadagdag pero kung hindi edi mag dagdagtayo
                        if($mainOffense->offenseDaysBlock != "Permanent"){
                            //so hindi sya permanent block, so pwede pa natin ule sya mablock
                            if($restOffense->noOfCancellation == 1){
                                //check kung isa lang ininput, kase kung isa lang matik maboblock agad
                                if($restOffense->blockDays == "Permanent"){
                                    //check kung permanent, kase yung ngayon date ang ilalagay
                                    $createMainOffense = CustOffenseMain::create([
                                        'customer_id' => $customerReserve->customer_id,
                                        'restAcc_id' => $restaurant->id,
                                        'offenseType' => "noshow",
                                        'totalOffense' => 1,
                                        'offenseCapacity' => $restOffense->noOfCancellation,
                                        'offenseDaysBlock' => $restOffense->blockDays,
                                        'offenseValidity' => $getDateTimeToday,
                                    ]);
                                    $custMainOffenseId = $createMainOffense->id;
                                } else {
                                    //check kung hindi permanent kase bibilangin yung kung ilang days ang ban tas iseset yung date na yon sa offensevalidity
                                    for($i=1; $i<=$restOffense->blockDays; $i++){
                                        $finalOffenseValidity = date('Y-m-d H:i:s', strtotime($getDateTimeToday. ' + 1 days'));
                                        $getDateTimeToday = $finalOffenseValidity;
                                    }
                                    $createMainOffense = CustOffenseMain::create([
                                        'customer_id' => $customerReserve->customer_id,
                                        'restAcc_id' => $restaurant->id,
                                        'offenseType' => "noshow",
                                        'totalOffense' => 1,
                                        'offenseCapacity' => $restOffense->noOfCancellation,
                                        'offenseDaysBlock' => $restOffense->blockDays,
                                        'offenseValidity' => $finalOffenseValidity,
                                    ]);
                                    $custMainOffenseId = $createMainOffense->id;
                                }
                            } else {
                                //kung yung noOfcanccellation ay lagpas sa isa then create na tayo tas di pa to block
                                $createMainOffense = CustOffenseMain::create([
                                    'customer_id' => $customerReserve->customer_id,
                                    'restAcc_id' => $restaurant->id,
                                    'offenseType' => "noshow",
                                    'totalOffense' => 1,
                                    'offenseCapacity' => $restOffense->noOfCancellation,
                                    'offenseDaysBlock' => $restOffense->blockDays,
                                ]);
                                $custMainOffenseId = $createMainOffense->id;
                            }
                        } 
                    } 
                } else {
                    // null sya so meaning di pa sya na bablock
                    if(($mainOffense->totalOffense + 1) == $mainOffense->offenseCapacity) {
                        // dito naman syempre mag iinsert tayo ng isa, kapag nag insert tayo ng isa tas nag equal sa capacity ma bablock yon
                        if($mainOffense->offenseDaysBlock == "Permanent"){
                            //so kapag permanent yung pagkablock nya ganito mangyayari
                            CustOffenseMain::where('id', $mainOffense->id)
                            ->update([
                                'totalOffense' => $mainOffense->totalOffense + 1,
                                'offenseValidity' => $getDateTimeToday,
                            ]);
                            $custMainOffenseId = $mainOffense->id;
                        } else {
                            //kung hindi permanent yung pagkablock gawin naten yung pag add ng offenseValidity
                            for($i=1; $i<=$mainOffense->offenseDaysBlock; $i++){
                                $finalOffenseValidity = date('Y-m-d H:i:s', strtotime($getDateTimeToday. ' + 1 days'));
                                $getDateTimeToday = $finalOffenseValidity;
                            }
                            CustOffenseMain::where('id', $mainOffense->id)
                            ->update([
                                'totalOffense' => $mainOffense->totalOffense + 1,
                                'offenseValidity' => $finalOffenseValidity,
                            ]);
                            $custMainOffenseId = $mainOffense->id;
                        }
                    } else {
                        //walang block na mangyayari, update lang
                        CustOffenseMain::where('id', $mainOffense->id)
                        ->update([
                            'totalOffense' => $mainOffense->totalOffense + 1,
                        ]);
                        $custMainOffenseId = $mainOffense->id;
                    }
                }
            } else {
                //so wala pang offense totally like first time neto, lalagyan naten ng offense
                if($restOffense->noOfCancellation == 1){
                    //check kung isa lang ininput, kase kung isa lang matik maboblock agad
                    if($restOffense->blockDays == "Permanent"){
                        //check kung permanent, kase yung ngayon date ang ilalagay
                        $createMainOffense = CustOffenseMain::create([
                            'customer_id' => $customerReserve->customer_id,
                            'restAcc_id' => $restaurant->id,
                            'offenseType' => "noshow",
                            'totalOffense' => 1,
                            'offenseCapacity' => $restOffense->noOfCancellation,
                            'offenseDaysBlock' => $restOffense->blockDays,
                            'offenseValidity' => $getDateTimeToday,
                        ]);
                        $custMainOffenseId = $createMainOffense->id;
                    } else {
                        //check kung hindi permanent kase bibilangin yung kung ilang days ang ban tas iseset yung date na yon sa offensevalidity
                        for($i=1; $i<=$restOffense->blockDays; $i++){
                            $finalOffenseValidity = date('Y-m-d H:i:s', strtotime($getDateTimeToday. ' + 1 days'));
                            $getDateTimeToday = $finalOffenseValidity;
                        }
                        $createMainOffense = CustOffenseMain::create([
                            'customer_id' => $customerReserve->customer_id,
                            'restAcc_id' => $restaurant->id,
                            'offenseType' => "noshow",
                            'totalOffense' => 1,
                            'offenseCapacity' => $restOffense->noOfCancellation,
                            'offenseDaysBlock' => $restOffense->blockDays,
                            'offenseValidity' => $finalOffenseValidity,
                        ]);
                        $custMainOffenseId = $createMainOffense->id;
                    }
                } else {
                    //kung yung noOfcanccellation ay lagpas sa isa then create na tayo tas di pa to block
                    $createMainOffense = CustOffenseMain::create([
                        'customer_id' => $customerReserve->customer_id,
                        'restAcc_id' => $restaurant->id,
                        'offenseType' => "noshow",
                        'totalOffense' => 1,
                        'offenseCapacity' => $restOffense->noOfCancellation,
                        'offenseDaysBlock' => $restOffense->blockDays,
                    ]);
                    $custMainOffenseId = $createMainOffense->id;
                }
            }
            //lastly add naten to sa database
            if($custMainOffenseId != ""){
                CustOffenseEach::create([
                    'custOffMain_id' => $custMainOffenseId,
                    'customer_id' => $customerReserve->customer_id,
                    'restAcc_id' => $restaurant->id,
                    'book_id' => $customerReserve->id,
                    'book_type' => "reserve",
                    'offenseType' => "noshow",
                ]);
            }
        }

        
        $notif = CustomerNotification::create([
            'customer_id' => $customerReserve->customer_id,
            'restAcc_id' => $customerReserve->restAcc_id,
            'notificationType' => "No Show",
            'notificationTitle' => "Your Booking is labelled as No Show",
            'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
            'notificationStatus' => "Unread",
        ]);

        $finalImageUrl = "";
        if ($restaurant->rLogo == ""){
            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
        } else {
            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
        }
        
        if($customer != null){
            $to = $customer->deviceToken;
            $notification = array(
                'title' => "$restaurant->rName, $restaurant->rBranch",
                'body' => "Your Booking is labelled as No Show",
            );
            $data = array(
                'notificationType' => "No Show",
                'notificationId' => $notif->id,
                'notificationRLogo' => $finalImageUrl,
            );
            $this->sendFirebaseNotification($to, $notification, $data);
        }

        Session::flash('noShow');
        return redirect('/restaurant/live-transaction/approved-customer/reserve');

    }
    public function ltAppCustQAdmit(Request $request, $id){
        $restAcc_id = Session::get('loginId');
        $request->validate([
            'tableNumbers' => 'required',
        ]);
        $getDateToday = date('Y-m-d');

        $customerQueue = CustomerQueue::where('id', $id)->where('restAcc_id', $restAcc_id)->where('queueDate', $getDateToday)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch', 'rAddress', 'rCity', 'rLogo', 'id', 'rNumberOfTables')->where('id', $customerQueue->restAcc_id)->first();
        $customer = CustomerAccount::where('id', $customerQueue->customer_id)->first();
        $tableNumbers = explode(',', $request->tableNumbers);
        
        $count = 0;
        $storeNumbers = array();
        foreach($tableNumbers as $tableNumber){
            array_push($storeNumbers, (int)$tableNumber);
            if (!is_numeric($tableNumber) || $tableNumber <= 0) {
                $count++;
            }
        }
        if($count > 0){
            $request->session()->flash('inputCorrectFormat');
            return redirect('/restaurant/live-transaction/approved-customer/queue/'.$id);
        } else {
            if($customerQueue->numberOfTables != sizeOf($storeNumbers)){
                $request->session()->flash('inputCorrectNumberOfTables');
                return redirect('/restaurant/live-transaction/approved-customer/queue/'.$id);
            } else {
                $countError = 0;
                foreach($storeNumbers as $storeNumber){
                    if($storeNumber > $restaurant->rNumberOfTables){
                        $countError++;
                    }
                }
                if($countError > 0){
                    $request->session()->flash('inputCorrectTableNumber');
                    return redirect('/restaurant/live-transaction/approved-customer/queue/'.$id);
                } else {
                    $result = array_unique($storeNumbers);
                    if(sizeOf($result) != sizeOf($storeNumbers)){
                        $request->session()->flash('mustBeDiffrentTables');
                        return redirect('/restaurant/live-transaction/approved-customer/queue/'.$id);
                    } else {
                        $finalTableNumber = "";
                        if(sizeOf($storeNumbers) == 1){
                            $finalTableNumber = $storeNumbers[0];
                        } else {
                            for ($i=0; $i<count($storeNumbers); $i++){
                                if($i == 0){
                                    $finalTableNumber = $storeNumbers[$i].',';
                                } else if ($i == count($storeNumbers)-1){
                                    $finalTableNumber = $finalTableNumber.$storeNumbers[$i];
                                } else {
                                    $finalTableNumber = $finalTableNumber.$storeNumbers[$i].',';
                                }
                            }
                        }
            
                        $count2 = 0;
                        $customerTables = CustomerOrdering::where('restAcc_id', $restAcc_id)
                        ->where('status', 'eating')
                        ->where('orderingDate', $getDateToday)
                        ->get();
                        foreach ($customerTables as $customerTable){
                            $customerTableNumbers = explode(',', $customerTable->tableNumbers);
                            $result = array_intersect($storeNumbers, $customerTableNumbers);
                            if($result != null){
                                $count2++;
                            }
                        }
                        if($count2 > 0){
                            $request->session()->flash('tableExist');
                            return redirect('/restaurant/live-transaction/approved-customer/queue/'.$id);
                        } else {
                            // UPDATE NA NG DATA
                            if($customerQueue->name == null){
                                $finaleCustName = $customer->name;
                            } else {
                                $finaleCustName = $customerQueue->name;
                            }

                            CustomerOrdering::create([
                                'custBook_id' => $id,
                                'restAcc_id' => $customerQueue->restAcc_id,
                                'custName' => $finaleCustName,
                                'custBookType' => "queue",
                                'tableNumbers' => $finalTableNumber,
                                'availableQrAccess' => $customerQueue->numberOfTables - 1,
                                'grantedAccess' => "No",
                                'status' => "eating",
                                'orderingDate' => date('Y-m-d'),
                            ]);

                            CustomerQueue::where('id', $id)
                            ->update([
                                'status' => 'tableSettingUp',
                                'tableSettingDateTime' => date('Y-m-d H:i:s'),
                            ]);

                            if($customer != null){
                                $notif = CustomerNotification::create([
                                    'customer_id' => $customerQueue->customer_id,
                                    'restAcc_id' => $customerQueue->restAcc_id,
                                    'notificationType' => "Table Setting Up",
                                    'notificationTitle' => "Your Table is Setting up",
                                    'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
                                    'notificationStatus' => "Unread",
                                ]);


                                $finalImageUrl = "";
                                if ($restaurant->rLogo == ""){
                                    $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                                } else {
                                    $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                                }

                                $to = $customer->deviceToken;
                                $notification = array(
                                    'title' => "$restaurant->rAddress, $restaurant->rCity",
                                    'body' => "Your Table is now being prepared",
                                );
                                $data = array(
                                    'notificationType' => "Table Setting Up",
                                    'notificationId' => $notif->id,
                                    'notificationRLogo' => $finalImageUrl,
                                );
                                $this->sendFirebaseNotification($to, $notification, $data);
                            }
                            
                            $request->session()->flash('admitted');
                            return redirect('/restaurant/live-transaction/approved-customer/queue');
                        }
                    }
                }
            }
        }
        
    }
    public function ltAppCustQNoShow($id){
        $getDateTimeToday = date('Y-m-d H:i:s');
        $customerQueue = CustomerQueue::where('id', $id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch', 'rAddress', 'rCity', 'rLogo', 'id')->where('id', $customerQueue->restAcc_id)->first();
        $customer = CustomerAccount::where('id', $customerQueue->customer_id)->first();

        CustomerQueue::where('id', $id)
        ->update([
            'status' => 'noShow',
        ]);

        if($customer != null){
            // CHECK IF THERES LATEST CANCEL OFFENSE
            $mainOffense = CustOffenseMain::where('customer_id', $customerQueue->customer_id)
            ->where('restAcc_id', $restaurant->id)
            ->where('offenseType', "noshow")
            ->latest()
            ->first();

            $restOffense = Cancellation::where('restAcc_id', $restaurant->id)->first();
            $custMainOffenseId = "";

            if($restOffense->blockDays != 0){
                //check muna kung nag apply yuing restaurant ng offense
                if($mainOffense != null){
                    //kapag may exisint offense yung customer
                    if($mainOffense->offenseValidity != null){
                        //kapag hindi null meaning nakablock na sya sa part na yon so dapat icompare naten yung dates kung tapos na ba, kung hindi pa then walang mangyayari kung tapos na then block natin ule
                        if(strtotime($mainOffense->offenseValidity) < strtotime($getDateTimeToday)){
                            // meanning nag lift na yung ban kaso check muna kung permanent ba or hindi kase kung permanent yan ibigsabihin wala na yan di na magdadagdag pero kung hindi edi mag dagdagtayo
                            if($mainOffense->offenseDaysBlock != "Permanent"){
                                //so hindi sya permanent block, so pwede pa natin ule sya mablock
                                if($restOffense->noOfCancellation == 1){
                                    //check kung isa lang ininput, kase kung isa lang matik maboblock agad
                                    if($restOffense->blockDays == "Permanent"){
                                        //check kung permanent, kase yung ngayon date ang ilalagay
                                        $createMainOffense = CustOffenseMain::create([
                                            'customer_id' => $customerQueue->customer_id,
                                            'restAcc_id' => $restaurant->id,
                                            'offenseType' => "noshow",
                                            'totalOffense' => 1,
                                            'offenseCapacity' => $restOffense->noOfCancellation,
                                            'offenseDaysBlock' => $restOffense->blockDays,
                                            'offenseValidity' => $getDateTimeToday,
                                        ]);
                                        $custMainOffenseId = $createMainOffense->id;
                                    } else {
                                        //check kung hindi permanent kase bibilangin yung kung ilang days ang ban tas iseset yung date na yon sa offensevalidity
                                        for($i=1; $i<=$restOffense->blockDays; $i++){
                                            $finalOffenseValidity = date('Y-m-d H:i:s', strtotime($getDateTimeToday. ' + 1 days'));
                                            $getDateTimeToday = $finalOffenseValidity;
                                        }
                                        $createMainOffense = CustOffenseMain::create([
                                            'customer_id' => $customerQueue->customer_id,
                                            'restAcc_id' => $restaurant->id,
                                            'offenseType' => "noshow",
                                            'totalOffense' => 1,
                                            'offenseCapacity' => $restOffense->noOfCancellation,
                                            'offenseDaysBlock' => $restOffense->blockDays,
                                            'offenseValidity' => $finalOffenseValidity,
                                        ]);
                                        $custMainOffenseId = $createMainOffense->id;
                                    }
                                } else {
                                    //kung yung noOfcanccellation ay lagpas sa isa then create na tayo tas di pa to block
                                    $createMainOffense = CustOffenseMain::create([
                                        'customer_id' => $customerQueue->customer_id,
                                        'restAcc_id' => $restaurant->id,
                                        'offenseType' => "noshow",
                                        'totalOffense' => 1,
                                        'offenseCapacity' => $restOffense->noOfCancellation,
                                        'offenseDaysBlock' => $restOffense->blockDays,
                                    ]);
                                    $custMainOffenseId = $createMainOffense->id;
                                }
                            } 
                        } 
                    } else {
                        // null sya so meaning di pa sya na bablock
                        if(($mainOffense->totalOffense + 1) == $mainOffense->offenseCapacity) {
                            // dito naman syempre mag iinsert tayo ng isa, kapag nag insert tayo ng isa tas nag equal sa capacity ma bablock yon
                            if($mainOffense->offenseDaysBlock == "Permanent"){
                                //so kapag permanent yung pagkablock nya ganito mangyayari
                                CustOffenseMain::where('id', $mainOffense->id)
                                ->update([
                                    'totalOffense' => $mainOffense->totalOffense + 1,
                                    'offenseValidity' => $getDateTimeToday,
                                ]);
                                $custMainOffenseId = $mainOffense->id;
                            } else {
                                //kung hindi permanent yung pagkablock gawin naten yung pag add ng offenseValidity
                                for($i=1; $i<=$mainOffense->offenseDaysBlock; $i++){
                                    $finalOffenseValidity = date('Y-m-d H:i:s', strtotime($getDateTimeToday. ' + 1 days'));
                                    $getDateTimeToday = $finalOffenseValidity;
                                }
                                CustOffenseMain::where('id', $mainOffense->id)
                                ->update([
                                    'totalOffense' => $mainOffense->totalOffense + 1,
                                    'offenseValidity' => $finalOffenseValidity,
                                ]);
                                $custMainOffenseId = $mainOffense->id;
                            }
                        } else {
                            //walang block na mangyayari, update lang
                            CustOffenseMain::where('id', $mainOffense->id)
                            ->update([
                                'totalOffense' => $mainOffense->totalOffense + 1,
                            ]);
                            $custMainOffenseId = $mainOffense->id;
                        }
                    }
                } else {
                    //so wala pang offense totally like first time neto, lalagyan naten ng offense
                    if($restOffense->noOfCancellation == 1){
                        //check kung isa lang ininput, kase kung isa lang matik maboblock agad
                        if($restOffense->blockDays == "Permanent"){
                            //check kung permanent, kase yung ngayon date ang ilalagay
                            $createMainOffense = CustOffenseMain::create([
                                'customer_id' => $customerQueue->customer_id,
                                'restAcc_id' => $restaurant->id,
                                'offenseType' => "noshow",
                                'totalOffense' => 1,
                                'offenseCapacity' => $restOffense->noOfCancellation,
                                'offenseDaysBlock' => $restOffense->blockDays,
                                'offenseValidity' => $getDateTimeToday,
                            ]);
                            $custMainOffenseId = $createMainOffense->id;
                        } else {
                            //check kung hindi permanent kase bibilangin yung kung ilang days ang ban tas iseset yung date na yon sa offensevalidity
                            for($i=1; $i<=$restOffense->blockDays; $i++){
                                $finalOffenseValidity = date('Y-m-d H:i:s', strtotime($getDateTimeToday. ' + 1 days'));
                                $getDateTimeToday = $finalOffenseValidity;
                            }
                            $createMainOffense = CustOffenseMain::create([
                                'customer_id' => $customerQueue->customer_id,
                                'restAcc_id' => $restaurant->id,
                                'offenseType' => "noshow",
                                'totalOffense' => 1,
                                'offenseCapacity' => $restOffense->noOfCancellation,
                                'offenseDaysBlock' => $restOffense->blockDays,
                                'offenseValidity' => $finalOffenseValidity,
                            ]);
                            $custMainOffenseId = $createMainOffense->id;
                        }
                    } else {
                        //kung yung noOfcanccellation ay lagpas sa isa then create na tayo tas di pa to block
                        $createMainOffense = CustOffenseMain::create([
                            'customer_id' => $customerQueue->customer_id,
                            'restAcc_id' => $restaurant->id,
                            'offenseType' => "noshow",
                            'totalOffense' => 1,
                            'offenseCapacity' => $restOffense->noOfCancellation,
                            'offenseDaysBlock' => $restOffense->blockDays,
                        ]);
                        $custMainOffenseId = $createMainOffense->id;
                    }
                }
                //lastly add naten to sa database
                if($custMainOffenseId != ""){
                    CustOffenseEach::create([
                        'custOffMain_id' => $custMainOffenseId,
                        'customer_id' => $customerQueue->customer_id,
                        'restAcc_id' => $restaurant->id,
                        'book_id' => $customerQueue->id,
                        'book_type' => "queue",
                        'offenseType' => "noshow",
                    ]);
                }
            }



            $notif = CustomerNotification::create([
                'customer_id' => $customerQueue->customer_id,
                'restAcc_id' => $customerQueue->restAcc_id,
                'notificationType' => "No Show",
                'notificationTitle' => "Your Booking is labelled as No Show",
                'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
                'notificationStatus' => "Unread",
            ]);

            
            $finalImageUrl = "";
            if ($restaurant->rLogo == ""){
                $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
            } else {
                $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
            }
            
            $to = $customer->deviceToken;
            $notification = array(
                'title' => "$restaurant->rName, $restaurant->rBranch",
                'body' => "Your Booking is labelled as No Show",
            );
            $data = array(
                'notificationType' => "No Show",
                'notificationId' => $notif->id,
                'notificationRLogo' => $finalImageUrl,
            );
            $this->sendFirebaseNotification($to, $notification, $data);
        }

        Session::flash('noShow');
        return redirect('/restaurant/live-transaction/approved-customer/queue');
    }
    public function ltAppCustRValidate($id){
        $customerReserve = CustomerReserve::where('id', $id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch', 'rAddress', 'rCity', 'rLogo', 'id')->where('id', $customerReserve->restAcc_id)->first();
        $customer = CustomerAccount::where('id', $customerReserve->customer_id)->first();

        CustomerReserve::where('id', $id)
        ->update([
            'status' => 'validation',
            'validationDateTime' => date('Y-m-d H:i:s'),
        ]);

        $notif = CustomerNotification::create([
            'customer_id' => $customerReserve->customer_id,
            'restAcc_id' => $customerReserve->restAcc_id,
            'notificationType' => "Validation",
            'notificationTitle' => "Please go to the front desk to Confirm your Booking",
            'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
            'notificationStatus' => "Unread",
        ]);

        $finalImageUrl = "";
        if ($restaurant->rLogo == ""){
            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
        } else {
            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
        }

        if($customer != null){
            $to = $customer->deviceToken;
            $notification = array(
                'title' => "$restaurant->rName, $restaurant->rBranch",
                'body' => "Please go to the front desk to Confirm your Booking",
            );
            $data = array(
                'notificationType' => "Validation",
                'notificationId' => $notif->id,
                'notificationRLogo' => $finalImageUrl,
            );
            $this->sendFirebaseNotification($to, $notification, $data);
        }

        Session::flash('validation');
        return redirect('/restaurant/live-transaction/approved-customer/reserve/'.$id);
    }
    public function ltAppCustQValidate($id){
        $customerQueue = CustomerQueue::where('id', $id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch', 'rAddress', 'rCity', 'rLogo', 'id')->where('id', $customerQueue->restAcc_id)->first();
        $customer = CustomerAccount::where('id', $customerQueue->customer_id)->first();

        CustomerQueue::where('id', $id)
        ->update([
            'status' => 'validation',
            'validationDateTime' => date('Y-m-d H:i:s'),
        ]);
        
        if($customer != null){
            $notif = CustomerNotification::create([
                'customer_id' => $customerQueue->customer_id,
                'restAcc_id' => $customerQueue->restAcc_id,
                'notificationType' => "Validation",
                'notificationTitle' => "Please go to the front desk to Confirm your Booking",
                'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
                'notificationStatus' => "Unread",
            ]);
    
    
            $finalImageUrl = "";
            if ($restaurant->rLogo == ""){
                $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
            } else {
                $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
            }
            $to = $customer->deviceToken;
            $notification = array(
                'title' => "$restaurant->rName, $restaurant->rBranch",
                'body' => "Please go to the front desk to Confirm your Booking",
            );
            $data = array(
                'notificationType' => "Validation",
                'notificationId' => $notif->id,
                'notificationRLogo' => $finalImageUrl,
            );
            $this->sendFirebaseNotification($to, $notification, $data);
        }

        Session::flash('validation');
        return redirect('/restaurant/live-transaction/approved-customer/queue/'.$id);
    }


    public function ltCustBookRDecline(Request $request, $id){
        $customerReserve = CustomerReserve::where('id', $id)->first();
        $restaurant = RestaurantAccount::select('id', 'rName', 'rBranch', 'rAddress', 'rCity', 'rLogo')->where('id', $customerReserve->restAcc_id)->first();
        $customer = CustomerAccount::select('deviceToken')->where('id', $customerReserve->customer_id)->first();

        CustomerReserve::where('id', $id)
        ->update([
            'status' => 'declined',
            'declinedReason' => $request->reason,
            'declinedDateTime' => date('Y-m-d H:i:s'),
        ]);

        $notif = CustomerNotification::create([
            'customer_id' => $customerReserve->customer_id,
            'restAcc_id' => $customerReserve->restAcc_id,
            'notificationType' => "Declined",
            'notificationTitle' => "Your Booking has been Declined!",
            'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
            'notificationStatus' => "Unread",
        ]);

        $finalImageUrl = "";
        if ($restaurant->rLogo == ""){
            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
        } else {
            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
        }

        if($customer != null){
            $to = $customer->deviceToken;
            $notification = array(
                'title' => "Your Booking has been Declined!",
                'body' => "$restaurant->rAddress, $restaurant->rCity",
            );
            $data = array(
                'notificationType' => "Declined",
                'notificationId' => $notif->id,
                'notificationRLogo' => $finalImageUrl,
            );
            $this->sendFirebaseNotification($to, $notification, $data);
        }

        Session::flash('declined');
        return redirect('/restaurant/live-transaction/customer-booking/reserve');

    }
    public function ltCustBookRApprove($id){
        $customerReserve = CustomerReserve::where('id', $id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch', 'rAddress', 'rCity', 'id', 'rLogo')->where('id', $customerReserve->restAcc_id)->first();
        $customer = CustomerAccount::select('deviceToken')->where('id', $customerReserve->customer_id)->first();

        CustomerReserve::where('id', $id)
        ->update([
            'status' => 'approved',
            'approvedDateTime' => date('Y-m-d H:i:s'),
        ]);

        $notif = CustomerNotification::create([
            'customer_id' => $customerReserve->customer_id,
            'restAcc_id' => $customerReserve->restAcc_id,
            'notificationType' => "Approved",
            'notificationTitle' => "Your Booking has been Approved",
            'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
            'notificationStatus' => "Unread",
        ]);

        $finalImageUrl = "";
        if ($restaurant->rLogo == ""){
            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
        } else {
            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
        }
        
        if($customer != null){
            $to = $customer->deviceToken;
            $notification = array(
                'title' => "$restaurant->rName, $restaurant->rBranch",
                'body' => "Your Booking has been Approved",
            );
            $data = array(
                'notificationType' => "Approved",
                'notificationId' => $notif->id,
                'notificationRLogo' => $finalImageUrl,
            );
            $this->sendFirebaseNotification($to, $notification, $data);
        }

        Session::flash('approved');
        return redirect('/restaurant/live-transaction/customer-booking/reserve');
    }
    public function ltCustBookQDecline(Request $request, $id){
        $customerQueue = CustomerQueue::where('id', $id)->first();
        $restaurant = RestaurantAccount::select('id', 'rName', 'rBranch', 'rAddress', 'rCity', 'rLogo')->where('id', $customerQueue->restAcc_id)->first();
        $customer = CustomerAccount::select('deviceToken')->where('id', $customerQueue->customer_id)->first();

        CustomerQueue::where('id', $id)
        ->update([
            'status' => 'declined',
            'declinedReason' => $request->reason,
            'declinedDateTime' => date('Y-m-d H:i:s'),
        ]);

        $notif = CustomerNotification::create([
            'customer_id' => $customerQueue->customer_id,
            'restAcc_id' => $customerQueue->restAcc_id,
            'notificationType' => "Declined",
            'notificationTitle' => "Your Booking has been Declined!",
            'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
            'notificationStatus' => "Unread",
        ]);

        $finalImageUrl = "";
        if ($restaurant->rLogo == ""){
            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
        } else {
            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
        }

        if($customer != null){
            $to = $customer->deviceToken;
            $notification = array(
                'title' => "Your Booking has been Declined!",
                'body' => "$restaurant->rAddress, $restaurant->rCity",
            );
            $data = array(
                'notificationType' => "Declined",
                'notificationId' => $notif->id,
                'notificationRLogo' => $finalImageUrl,
            );
            $this->sendFirebaseNotification($to, $notification, $data);
        }


        Session::flash('declined');
        return redirect('/restaurant/live-transaction/customer-booking/queue');
    }
    public function ltCustBookQApprove($id){
        $customerQueue = CustomerQueue::where('id', $id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch', 'rAddress', 'rCity', 'id', 'rLogo')->where('id', $customerQueue->restAcc_id)->first();
        $customer = CustomerAccount::select('deviceToken')->where('id', $customerQueue->customer_id)->first();

        CustomerQueue::where('id', $id)
        ->update([
            'status' => 'approved',
            'approvedDateTime' => date('Y-m-d H:i:s'),
        ]);

        $notif = CustomerNotification::create([
            'customer_id' => $customerQueue->customer_id,
            'restAcc_id' => $customerQueue->restAcc_id,
            'notificationType' => "Approved",
            'notificationTitle' => "Your Booking has been Approved",
            'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
            'notificationStatus' => "Unread",
        ]);


        $finalImageUrl = "";
        if ($restaurant->rLogo == ""){
            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
        } else {
            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
        }
        
        if($customer != null){
            $to = $customer->deviceToken;
            $notification = array(
                'title' => "$restaurant->rName, $restaurant->rBranch",
                'body' => "Your Booking has been Approved",
            );
            $data = array(
                'notificationType' => "Approved",
                'notificationId' => $notif->id,
                'notificationRLogo' => $finalImageUrl,
            );
            $this->sendFirebaseNotification($to, $notification, $data);
        }

        Session::flash('approved');
        return redirect('/restaurant/live-transaction/customer-booking/queue');
    }
    public function deletePromoMechanic($id, $currentPromoId){
        $mechanics = PromoMechanics::where('promo_id', $currentPromoId)->first();

        if($mechanics->id != $id){
            PromoMechanics::where('id', $id)->delete();
        }
        return redirect('/restaurant/manage-restaurant/promo/edit/'.$currentPromoId);
    }
    public function resetPassword(Request $request, $token, $emailAddress){
        $request->validate([
            'newPassword' => 'required|min:6|confirmed',
            'newPassword_confirmation' => 'required',
        ],
        [
            'newPassword.required' => 'New Password is required',
            'newPassword.min' => 'New Password must contain at least 6 characters',
            'newPassword.confirmed' => 'New Password does not match Confirm Password',
            'newPassword_confirmation.required' => 'Confirm Password is required',
        ]);

        Mail::to($emailAddress)->send(new RestaurantPasswordChanged());
        $hashPassword = Hash::make($request->newPassword);
        RestaurantAccount::where('emailAddress', $emailAddress)
        ->update([
            'password' => $hashPassword
        ]);
        RestaurantResetPassword::where('emailAddress', $emailAddress)->where('token', $token)
        ->update([
            'resetStatus' => "Changed"
        ]);

        $request->session()->flash('passwordUpdated');
        return redirect('/restaurant/login');
    
    }
    public function forgotPassword(Request $request){
        $request->validate([
            'emailAddress' => 'required|email',
        ],
        [
            'emailAddress.required' => 'Email Address is required',
            'emailAddress.email' => 'Email Address must be a valid email',
        ]);
        $findEmailAddress = RestaurantAccount::where('emailAddress', $request->emailAddress)->first();
        if($findEmailAddress == null){
            $request->session()->flash('emailNotExist', 'Your email does not exist');
            return redirect('/restaurant/login/forgot-password');
        } else {
            $token = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 12).time().$findEmailAddress->id;
            $details = [
                'link' => env('APP_URL') . '/restaurant/login/reset-password/'.$token.'/'.$request->emailAddress,
            ];
            Mail::to($request->emailAddress)->send(new RestaurantForgotPassword($details));
            RestaurantResetPassword::create([
                'emailAddress' => $request->emailAddress,
                'token' => $token,
                'resetStatus' => 'Pending',
            ]);
            $request->session()->flash('emailSent');
            return redirect('/restaurant/login/forgot-password');
        }
        
    }
    public function addStampCard(Request $request){
        $getDateToday = date("Y-m-d");
        $restAccId = Session::get('loginId');
        
        $request->validate([
            'stampCapacity' => 'required',
            'stampReward'  => 'required',
            'tasks'          => 'required',
            'stampValidity' => 'required',
        ]);
        

        $checkStampExist = StampCard::where('restAcc_id', $restAccId)->latest()->first();
        if($checkStampExist != null){
            if($getDateToday <= $checkStampExist->stampValidity){
                $request->session()->flash('invalid');
                return redirect('/restaurant/manage-restaurant/task-rewards/stamp-card');
            } else {
                StampCard::where('restAcc_id', $restAccId)->delete();
            }
        }

        $getLatestStamp = StampCard::create([
            'restAcc_id' => $restAccId,
            'stampCapacity' => $request->stampCapacity,
            'stampReward_id' => $request->stampReward,
            'stampValidity' => $request->stampValidity,
        ]);

        $storeTasks = array();
        foreach ($request->tasks as $taskId){
            StampCardTasks::create([
                'stampCards_id' => $getLatestStamp->id,
                'restaurantTaskLists_id' => $taskId,
            ]);

            $task = RestaurantTaskList::where('id', $taskId)->first();
            switch($task->taskCode){
                case "SPND":
                    array_push($storeTasks, "Spend ".$task->taskInput." pesos in 1 visit only");
                    break;
                case "BRNG":
                    array_push($storeTasks, "Bring ".$task->taskInput." friends in our store");
                    break;
                case "ORDR":
                    array_push($storeTasks, "Order ".$task->taskInput." add on/s per visit");
                    break;
                case "VST":
                    array_push($storeTasks, "Visit in our store");
                    break;
                case "FDBK":
                    array_push($storeTasks, "Give a feedback/review per visit");
                    break;
                case "PUGC":
                    array_push($storeTasks, "Pay using gcash");
                    break;
                case "LUDN":
                    array_push($storeTasks, "Eat during lunch/dinner hours");
                    break;
                default: 
            }

        }

        $restoReward = RestaurantRewardList::where('id', $request->stampReward)->first();
        $finalReward = "";
        switch($restoReward->rewardCode){
            case "DSCN": 
                $finalReward = "Discount $restoReward->rewardInput% in a Total Bill";
                break;
            case "FRPE": 
                $finalReward = "Free $restoReward->rewardInput person in a group";
                break;
            case "HLF": 
                $finalReward = "Half in the group will be free";
                break;
            case "ALL": 
                $finalReward = "All people in the group will be free";
                break;
            default: 
                $finalReward = "None";
        }
        
        $getLatestStamp2 = RestStampCardHis::create([
            'restAcc_id' => $restAccId,
            'stampCapacity' => $request->stampCapacity,
            'stampReward' => $finalReward,
            'stampValidity' => $request->stampValidity,
        ]);

        foreach($storeTasks as $taskname){
            RestStampTasksHis::create([
                'restStampCardHis_id' => $getLatestStamp2->id,
                'taskName' => $taskname,
            ]);
        }

        $request->session()->flash('added');
        return redirect('/restaurant/manage-restaurant/task-rewards/stamp-card');
    }
    public function editTask3(Request $request){
        $restAccId = Session::get('loginId');
        $getDateToday = date("Y-m-d");

        $checkStampExist = StampCard::where('restAcc_id', $restAccId)->latest()->first();
        if($checkStampExist != null){
            if($getDateToday <= $checkStampExist->stampValidity){
                $request->session()->flash('cantEdit');
                return redirect('/restaurant/manage-restaurant/task-rewards/tasks');
            }
        }

        $taskCode = "ORDR";
        RestaurantTaskList::where('restAcc_id', $restAccId)->where('taskCode', $taskCode)
        ->update([
            'taskInput' => $request->task3
        ]);

        $request->session()->flash('editedTask3');
        return redirect('/restaurant/manage-restaurant/task-rewards/tasks');
    }
    public function editTask2(Request $request){
        $restAccId = Session::get('loginId');
        $getDateToday = date("Y-m-d");

        $checkStampExist = StampCard::where('restAcc_id', $restAccId)->latest()->first();
        if($checkStampExist != null){
            if($getDateToday <= $checkStampExist->stampValidity){
                $request->session()->flash('cantEdit');
                return redirect('/restaurant/manage-restaurant/task-rewards/tasks');
            }
        }

        $taskCode = "BRNG";
        RestaurantTaskList::where('restAcc_id', $restAccId)->where('taskCode', $taskCode)
        ->update([
            'taskInput' => $request->task2
        ]);

        $request->session()->flash('editedTask2');
        return redirect('/restaurant/manage-restaurant/task-rewards/tasks');
    }
    public function editTask1(Request $request){
        $restAccId = Session::get('loginId');
        $getDateToday = date("Y-m-d");

        $checkStampExist = StampCard::where('restAcc_id', $restAccId)->latest()->first();
        if($checkStampExist != null){
            if($getDateToday <= $checkStampExist->stampValidity){
                $request->session()->flash('cantEdit');
                return redirect('/restaurant/manage-restaurant/task-rewards/tasks');
            }
        }

        $taskCode = "SPND";
        RestaurantTaskList::where('restAcc_id', $restAccId)->where('taskCode', $taskCode)
        ->update([
            'taskInput' => $request->task1
        ]);

        $request->session()->flash('editedTask1');
        return redirect('/restaurant/manage-restaurant/task-rewards/tasks');
    }
    public function editReward2(Request $request){
        $restAccId = Session::get('loginId');
        $getDateToday = date("Y-m-d");
        $checkStampExist = StampCard::where('restAcc_id', $restAccId)->latest()->first();

        if($checkStampExist != null){
            if($getDateToday <= $checkStampExist->stampValidity){
                $request->session()->flash('cantEdit');
                return redirect('/restaurant/manage-restaurant/task-rewards/rewards');
            }
        }

        $rewardCode = "FRPE";
        RestaurantRewardList::where('restAcc_id', $restAccId)->where('rewardCode', $rewardCode)
        ->update([
            'rewardInput' => $request->free
        ]);

        $request->session()->flash('editedReward2');
        return redirect('/restaurant/manage-restaurant/task-rewards/rewards');
    }
    public function editReward1(Request $request){
        $restAccId = Session::get('loginId');
        $getDateToday = date("Y-m-d");
        $checkStampExist = StampCard::where('restAcc_id', $restAccId)->latest()->first();

        if($checkStampExist != null){
            if($getDateToday <= $checkStampExist->stampValidity){
                $request->session()->flash('cantEdit');
                return redirect('/restaurant/manage-restaurant/task-rewards/rewards');
            }
        }

        $rewardCode = "DSCN";
        RestaurantRewardList::where('restAcc_id', $restAccId)->where('rewardCode', $rewardCode)
        ->update([
            'rewardInput' => $request->discount
        ]);

        $request->session()->flash('editedReward1');
        return redirect('/restaurant/manage-restaurant/task-rewards/rewards');
    }
    public function editRunaway(Request $request, $id){
        $restAccId = Session::get('loginId');
        if($request->offenseTypeR == "limitBlock"){
            $request->validate([
                'numberOfRunaway' => 'required',
                'offenseTypeR' => 'required',
                'runawayBlockDays' => 'required',
            ]);
        } else {
            $request->validate([
                'numberOfRunaway' => 'required',
                'offenseTypeR' => 'required',
            ]);
        }
        $blockDays = "";
        if($request->offenseTypeR == "noOffense"){
            $blockDays = "0";
        } else if ($request->offenseTypeR == "permanentBlock") {
            $blockDays = "Permanent";
        } else {
            $blockDays = $request->runawayBlockDays;
        }

        Runaway::where('id', $id)->where('restAcc_id', $restAccId)
        ->update([
            'noOfRunaways' => $request->numberOfRunaway,
            'noOfDays' => $request->numberOfDays,
            'blockDays' => $blockDays,
        ]);

        $request->session()->flash('edited');
        return redirect('/restaurant/manage-restaurant/offense/offenses');
    }
    public function editNoShow(Request $request, $id){
        $restAccId = Session::get('loginId');
        if($request->offenseTypeN == "limitBlock"){
            $request->validate([
                'numberOfNoShow' => 'required',
                'offenseTypeN' => 'required',
                'noshowBlockDays' => 'required',
            ]);
        } else {
            $request->validate([
                'numberOfNoShow' => 'required',
                'offenseTypeN' => 'required',
            ]);
        }
        $blockDays = "";
        if($request->offenseTypeN == "noOffense"){
            $blockDays = "0";
        } else if ($request->offenseTypeN == "permanentBlock") {
            $blockDays = "Permanent";
        } else {
            $blockDays = $request->noshowBlockDays;
        }

        NoShow::where('id', $id)->where('restAcc_id', $restAccId)
        ->update([
            'noOfNoShows' => $request->numberOfNoShow,
            'noOfDays' => $request->numberOfDays,
            'blockDays' => $blockDays,
        ]);

        $request->session()->flash('edited');
        return redirect('/restaurant/manage-restaurant/offense/offenses');
    }
    public function editCancelReservation(Request $request, $id){
        $restAccId = Session::get('loginId');
        if($request->offenseType == "limitBlock"){
            $request->validate([
                'numberOfCancellation' => 'required',
                'offenseTypeC' => 'required',
                'cancelBlockDays' => 'required',
            ]);
        } else {
            $request->validate([
                'numberOfCancellation' => 'required',
                'offenseTypeC' => 'required',
            ]);
        }
        $blockDays = "";
        if($request->offenseTypeC == "noOffense"){
            $blockDays = "0";
        } else if ($request->offenseTypeC == "permanentBlock") {
            $blockDays = "Permanent";
        } else {
            $blockDays = $request->cancelBlockDays;
        }

        Cancellation::where('id', $id)->where('restAcc_id', $restAccId)
        ->update([
            'noOfCancellation' => $request->numberOfCancellation,
            'noOfDays' => $request->numberOfDays,
            'blockDays' => $blockDays,
        ]);

        $request->session()->flash('edited');
        return redirect('/restaurant/manage-restaurant/offense/offenses');
    }
    public function editPolicy(Request $request){
        $restAccId = Session::get('loginId');
        Policy::where('id', $request->updatePolicyId)->where('restAcc_id', $restAccId)
                ->update([
                    'policyDesc' => $request->updatePolicyDesc,
                ]);
        $request->session()->flash('edited');
        return redirect('/restaurant/manage-restaurant/offense/policy');
    }
    public function deletePolicy($id){
        Policy::where('id', $id)->delete();
        Session::flash('deleted');
        return redirect('/restaurant/manage-restaurant/offense/policy');
    }
    public function addPolicy(Request $request){
        $restAccId = Session::get('loginId');
        $request->validate([
            'policyDesc' => 'required',
        ]);
        
        Policy::create([
            'restAcc_id' => $restAccId,
            'policyDesc' => $request->policyDesc,
        ]);

        $request->session()->flash('added');
        return redirect('/restaurant/manage-restaurant/offense/policy');
    }
    public function promoDraft($id){
        $restAccId = Session::get('loginId');
        Promo::where('id', $id)->where('restAcc_id', $restAccId)
        ->update([
            'promoPosted' => "Draft",
        ]);
        Session::flash('drafted');
        return redirect('/restaurant/manage-restaurant/promo');
    }
    public function promoPost($id){
        $restAccId = Session::get('loginId');
        Promo::where('id', $id)->where('restAcc_id', $restAccId)
        ->update([
            'promoPosted' => "Posted",
        ]);
        Session::flash('posted');
        return redirect('/restaurant/manage-restaurant/promo');
    }
    public function editTimeLimit(Request $request){
        $restAccId = Session::get('loginId');
        RestaurantAccount::where('id', $restAccId)
        ->update([
            'rTimeLimit' => $request->timeLimit,
        ]);
        $request->session()->flash('edited');
        return redirect('/restaurant/manage-restaurant/time/time-limit');
    }
    public function editUnavailableDates(Request $request){
        $restAccId = Session::get('loginId');
        $unavailableDates = UnavailableDate::select('unavailableDatesDate')->where('restAcc_id', $restAccId)->get();
        $checkDate2 = UnavailableDate::select('unavailableDatesDate')->where('restAcc_id', $restAccId)->where('id', $request->updateUnavailableDateId)->first();
        $restaurant = RestaurantAccount::where('id', $restAccId)->first();

        $checkIfExisting = 0;
        if($checkDate2->unavailableDatesDate != $request->updateDate){
            foreach ($unavailableDates as $unavailableDate){
                if($unavailableDate->unavailableDatesDate == $request->updateDate){
                    if($unavailableDate->id != $request->updateUnavailableDateId){
                        $checkIfExisting++;
                    }
                }
            }
        }

        if($checkIfExisting > 0){
            $request->session()->flash('existing');
            return redirect('/restaurant/manage-restaurant/time/unavailable-dates');
        } else {
            if(strtotime($request->updateDate) <= strtotime(date('Y-m-d'))){
                $request->session()->flash('notLessEqualToday');
                return redirect('/restaurant/manage-restaurant/time/unavailable-dates');
            } else {
                //cancel all reservation
                if($restaurant->status == "Published"){
                    $startDate = $request->updateDate;
                    $startTime = $request->updateTime;
                    $customerReserves = CustomerReserve::where('restAcc_id', $restAccId)
                    ->where(function ($query) {
                        $query->where('status', "pending")
                        ->orWhere('status', "approved");
                    })->get();

                    if(!$customerReserves->isEmpty()){
                        foreach($customerReserves as $customerReserve){
                            $reservedDate = $customerReserve->reserveDate;
                            $reservedTime = $customerReserve->reserveTime;
                            $reservedTimeAdd2 = date('H:i', strtotime($reservedTime) + 60*60*2);

                            if(strtotime($reservedDate) == strtotime($startDate)){
                                if(strtotime($startTime) < strtotime($reservedTimeAdd2)){

                                    $customerAccount = CustomerAccount::where('id', $customerReserve->customer_id)->first();
                                    $finalImageUrl = "";
                                    if ($restaurant->rLogo == ""){
                                        $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                                    } else {
                                        $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                                    }
                            
                                    if($customerAccount != null){
                                        $to = $customerAccount->deviceToken;
                                        $notification = array(
                                            'title' => "$restaurant->rAddress, $restaurant->rCity",
                                            'body' => "Your reservation has been void due to some changes on the schedule. Were sorry for the inconvenience.",
                                        );
                                        $data = array(
                                            'notificationType' => "Schedule Change",
                                            'notificationId' => 0,
                                            'notificationRLogo' => $finalImageUrl,
                                        );
                                        $this->sendFirebaseNotification($to, $notification, $data);
                                    }

                                    CustomerNotification::where('restAcc_id', $restAccId)
                                    ->where('notificationType', "Pending")
                                    ->where('created_at', $customerReserve->created_at)
                                    ->delete();

                                    if($customerReserve->approvedDateTime != null){
                                        CustomerNotification::where('restAcc_id', $restAccId)
                                        ->where('notificationType', "Approved")
                                        ->where('created_at', $customerReserve->approvedDateTime)
                                        ->delete();
                                    }
                                    CustomerReserve::where('id', $customerReserve->id)->delete();
                                }
                            }
                        }
                    }
                }
                UnavailableDate::where('id', $request->updateUnavailableDateId)
                ->update([
                    'unavailableDatesDate' => $request->updateDate,
                    'startTime' => $request->updateTime,
                    'unavailableDatesDesc' => $request->updateDescription,
                ]);
                $request->session()->flash('edited');
                return redirect('/restaurant/manage-restaurant/time/unavailable-dates');
            }
        }
    }
    public function deleteUnavailableDates($id){
        UnavailableDate::where('id', $id)->delete();
        Session::flash('deleted');
        return redirect('/restaurant/manage-restaurant/time/unavailable-dates');
    }
    public function addUnavailableDates(Request $request){
        $restAccId = Session::get('loginId');
        $unavailableDates = UnavailableDate::select('unavailableDatesDate')->where('restAcc_id', $restAccId)->get();
        $restaurant = RestaurantAccount::where('id', $restAccId)->first();

        $checkIfExisting = 0;
        foreach ($unavailableDates as $unavailableDate){
            if($unavailableDate->unavailableDatesDate == $request->date){
                $checkIfExisting++;
            }
        }

        if($checkIfExisting > 0){
            $request->session()->flash('existing');
            return redirect('/restaurant/manage-restaurant/time/unavailable-dates');
        } else {
            if(strtotime($request->date) <= strtotime(date('Y-m-d'))){
                $request->session()->flash('notLessEqualToday');
                return redirect('/restaurant/manage-restaurant/time/unavailable-dates');
            } else {
                //cancel all reservation
                if($restaurant->status == "Published"){
                    $startDate = $request->date;
                    $startTime = $request->time;
                    // $prevDate = date("Y-m-d", strtotime($startDate. " -1 days"));
                    $customerReserves = CustomerReserve::where('restAcc_id', $restAccId)
                    ->where(function ($query) {
                        $query->where('status', "pending")
                        ->orWhere('status', "approved");
                    })->get();

                    if(!$customerReserves->isEmpty()){
                        foreach($customerReserves as $customerReserve){
                            $reservedDate = $customerReserve->reserveDate;
                            $reservedTime = $customerReserve->reserveTime;
                            $reservedTimeAdd2 = date('H:i', strtotime($reservedTime) + 60*60*2);

                            if(strtotime($reservedDate) == strtotime($startDate)){
                                if(strtotime($startTime) < strtotime($reservedTimeAdd2)){

                                    $customerAccount = CustomerAccount::where('id', $customerReserve->customer_id)->first();
                                    $finalImageUrl = "";
                                    if ($restaurant->rLogo == ""){
                                        $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                                    } else {
                                        $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                                    }
                            
                                    if($customerAccount != null){
                                        $to = $customerAccount->deviceToken;
                                        $notification = array(
                                            'title' => "$restaurant->rAddress, $restaurant->rCity",
                                            'body' => "Your reservation has been void due to some changes on the schedule. Were sorry for the inconvenience.",
                                        );
                                        $data = array(
                                            'notificationType' => "Schedule Change",
                                            'notificationId' => 0,
                                            'notificationRLogo' => $finalImageUrl,
                                        );
                                        $this->sendFirebaseNotification($to, $notification, $data);
                                    }

                                    CustomerNotification::where('restAcc_id', $restAccId)
                                    ->where('notificationType', "Pending")
                                    ->where('created_at', $customerReserve->created_at)
                                    ->delete();

                                    if($customerReserve->approvedDateTime != null){
                                        CustomerNotification::where('restAcc_id', $restAccId)
                                        ->where('notificationType', "Approved")
                                        ->where('created_at', $customerReserve->approvedDateTime)
                                        ->delete();
                                    }
                                    CustomerReserve::where('id', $customerReserve->id)->delete();
                                }
                            }
                        }
                    }
                }
                UnavailableDate::create([
                    'restAcc_id' => $restAccId,
                    'unavailableDatesDate' => $request->date,
                    'startTime' => $request->time,
                    'unavailableDatesDesc' => $request->description,
                ]);
                $request->session()->flash('added');
                return redirect('/restaurant/manage-restaurant/time/unavailable-dates');

            }
        }
    }
    public function editStoreHours(Request $request){
        $compareClosingTime = date('H:i', strtotime($request->updateOpeningTime) + 60*60*3);

        if($request->updateDays == null){
            $request->session()->flash('emptyDays');
            return redirect('/restaurant/manage-restaurant/time/store-hours');
        } else if (strtotime($request->updateOpeningTime) == strtotime($request->updateClosingTime)) {
            $request->session()->flash('mustNotTheSame');
            return redirect('/restaurant/manage-restaurant/time/store-hours');
        } else if (strtotime($request->updateOpeningTime) < strtotime("05:00") || strtotime($request->updateOpeningTime) > strtotime("23:00")) {
            $request->session()->flash('openingTimeExceed');
            return redirect('/restaurant/manage-restaurant/time/store-hours');
        } else if (strtotime($compareClosingTime) >= strtotime("00:00") && strtotime($compareClosingTime) <= strtotime("03:00")){
            if (strtotime($request->updateClosingTime) > strtotime("04:00") && strtotime($request->updateClosingTime) <= strtotime("23:59")){
                $request->session()->flash('closingTimeExceed2');
                return redirect('/restaurant/manage-restaurant/time/store-hours');
            } else if (strtotime($request->updateClosingTime) < strtotime($compareClosingTime)){
                $request->session()->flash('closingTimeExceed2');
                return redirect('/restaurant/manage-restaurant/time/store-hours');
            } else {}
        } else if (strtotime($compareClosingTime) <= strtotime("23:59") && strtotime($compareClosingTime) > strtotime("05:00")){
            if(strtotime($request->updateClosingTime) > strtotime("04:00") && strtotime($request->updateClosingTime) < strtotime($compareClosingTime)){
                $request->session()->flash('closingTimeExceed2');
                return redirect('/restaurant/manage-restaurant/time/store-hours');
            } else {}
        } else {}

        $daysToString = "";
        if(count($request->updateDays) == 1){
            $daysToString = $request->updateDays[0];
        } else {
            for ($i=0; $i<count($request->updateDays); $i++){
                if($i == 0){
                    $daysToString = $request->updateDays[$i].',';
                } else if ($i == count($request->updateDays)-1){
                    $daysToString = $daysToString.$request->updateDays[$i];
                } else {
                    $daysToString = $daysToString.$request->updateDays[$i].',';
                }
            }
        }
        StoreHour::where('id', $request->storeId)
        ->update([
            'openingTime' => $request->updateOpeningTime,
            'closingTime' => $request->updateClosingTime,
            'days' => $daysToString,
        ]);
        
        $request->session()->flash('edited');
        return redirect('/restaurant/manage-restaurant/time/store-hours');
    }
    public function deleteStoreHours($id){
        $restAccId = Session::get('loginId');
        $getType = StoreHour::select('type')->where('id', $id)->where('restAcc_id', $restAccId)->first();
        if($getType->type == "Permanent"){
            Session::flash('cantDelete');
            return redirect('/restaurant/manage-restaurant/time/store-hours');
        }
        StoreHour::where('id', $id)->delete();
        Session::flash('deleted');
        return redirect('/restaurant/manage-restaurant/time/store-hours');
    }
    public function addStoreHours(Request $request){
        $restAccId = Session::get('loginId');
        $compareClosingTime = date('H:i', strtotime($request->openingTime) + 60*60*3);

        if($request->days == null){
            $request->session()->flash('emptyDays');
            return redirect('/restaurant/manage-restaurant/time/store-hours');
        } else if (strtotime($request->openingTime) == strtotime($request->closingTime)) {
            $request->session()->flash('mustNotTheSame');
            return redirect('/restaurant/manage-restaurant/time/store-hours');
        } else if (strtotime($request->openingTime) < strtotime("05:00") || strtotime($request->openingTime) > strtotime("23:00")) {
            $request->session()->flash('openingTimeExceed');
            return redirect('/restaurant/manage-restaurant/time/store-hours');
        } else if (strtotime($compareClosingTime) >= strtotime("00:00") && strtotime($compareClosingTime) <= strtotime("03:00")){
            if (strtotime($request->closingTime) > strtotime("04:00") && strtotime($request->closingTime) <= strtotime("23:59")){
                $request->session()->flash('closingTimeExceed2');
                return redirect('/restaurant/manage-restaurant/time/store-hours');
            } else if (strtotime($request->closingTime) < strtotime($compareClosingTime)){
                $request->session()->flash('closingTimeExceed2');
                return redirect('/restaurant/manage-restaurant/time/store-hours');
            } else {}
        } else if (strtotime($compareClosingTime) <= strtotime("23:59") && strtotime($compareClosingTime) > strtotime("05:00")){
            if(strtotime($request->closingTime) > strtotime("04:00") && strtotime($request->closingTime) < strtotime($compareClosingTime)){
                $request->session()->flash('closingTimeExceed2');
                return redirect('/restaurant/manage-restaurant/time/store-hours');
            } else {}
        } else {}

        $daysToString = "";
        if(count($request->days) == 1){
            $daysToString = $request->days[0];
        } else {
            for ($i=0; $i<count($request->days); $i++){
                if($i == 0){
                    $daysToString = $request->days[$i].',';
                } else if ($i == count($request->days)-1){
                    $daysToString = $daysToString.$request->days[$i];
                } else {
                    $daysToString = $daysToString.$request->days[$i].',';
                }
            }
        }

        StoreHour::create([
            'restAcc_id' => $restAccId,
            'openingTime' => $request->openingTime,
            'closingTime' => $request->closingTime,
            'days' => $daysToString,
            'type' => "Draft",
        ]);
        
        $request->session()->flash('added');
        return redirect('/restaurant/manage-restaurant/time/store-hours');
    }
    public function deletePromo($id){
        $restAccId = Session::get('loginId');
        $promo = Promo::where('id', '=', $id)
                            ->where('restAcc_Id', '=', $restAccId)
                            ->first();
        File::delete(public_path('uploads/restaurantAccounts/promo/'.$restAccId.'/'.$promo->promoImage));
        Promo::where('id', $id)->delete();
        Session::flash('deleted');
        return redirect('/restaurant/manage-restaurant/promo');
    }
    public function editPromo(Request $request, $id){
        $restAccId = Session::get('loginId');
        $promo = Promo::where('id', $id)->where('restAcc_id', $restAccId)->first();

        if($request->promoImage == null){
            $request->validate([
                'promoTitle' => 'required',
                'promoDescription' => 'required',
                'promoMechanics.*' => 'required',
                'promoStartDate' => 'required|date',
                'promoEndDate' => 'required|date',
            ],
            [
                'promoTitle.required' => "Title is required",
                'promoDescription.required' => "Description is required",
                'promoMechanics.*.required' => "Mechanics is required",
                'promoStartDate.required' => "Start Date is required",
                'promoStartDate.date' => "Start Date must be date",
                'promoEndDate.required' => "End Date is required",
                'promoEndDate.date' => "End Date must be date",
            ]);

            Promo::where('id', $id)->where('restAcc_id', $restAccId)->update([
                'promoTitle' => $request->promoTitle,
                'promoDescription' => $request->promoDescription,
                'promoStartDate' => $request->promoStartDate,
                'promoEndDate' => $request->promoEndDate,
            ]);

            PromoMechanics::where('promo_id', $id)->delete();
        
            foreach ($request->promoMechanics as $promoMechanic){
                if($promoMechanic != null){
                    PromoMechanics::create([
                        'promo_id' => $id,
                        'promoMechanic' => $promoMechanic,
                    ]);
                }
            }
            
            if($request->promoMechanicsEdit != null){
                foreach ($request->promoMechanicsEdit as $promoMechanicEdit){
                    if($promoMechanicEdit != null){
                        PromoMechanics::create([
                            'promo_id' => $id,
                            'promoMechanic' => $promoMechanicEdit,
                        ]);
                    }
                }
            }

        } else {
            $request->validate([
                'promoTitle' => 'required',
                'promoDescription' => 'required',
                'promoMechanics.*' => 'required',
                'promoStartDate' => 'required|date',
                'promoEndDate' => 'required|date',
                'promoImage' => 'required|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'promoTitle.required' => "Title is required",
                'promoDescription.required' => "Description is required",
                'promoMechanics.*.required' => "Mechanics is required",
                'promoStartDate.required' => "Start Date is required",
                'promoStartDate.date' => "Start Date must be date",
                'promoEndDate.required' => "End Date is required",
                'promoEndDate.date' => "End Date must be date",
                'promoImage.required' => 'Image is required',
                'promoImage.mimes' => 'Image must be in jpeg, png and jpg format',
                'promoImage.max' => 'Image must not be greater than 2mb',
            ]);
            File::delete(public_path('uploads/restaurantAccounts/promo/'.$restAccId.'/'.$promo->promoImage));
            $promoImageName = time().'.'.$request->promoImage->extension();
            $request->promoImage->move(public_path('uploads/restaurantAccounts/promo/'.$restAccId), $promoImageName);
            
            Promo::where('id', $id)->where('restAcc_id', $restAccId)->update([
                'promoTitle' => $request->promoTitle,
                'promoDescription' => $request->promoDescription,
                'promoStartDate' => $request->promoStartDate,
                'promoEndDate' => $request->promoEndDate,
                'promoImage' => $promoImageName,
            ]);


            PromoMechanics::where('promo_id', $id)->delete();
        
            foreach ($request->promoMechanics as $promoMechanic){
                if($promoMechanic != null){
                    PromoMechanics::create([
                        'promo_id' => $id,
                        'promoMechanic' => $promoMechanic,
                    ]);
                }
            }
            
            if($request->promoMechanicsEdit != null){
                foreach ($request->promoMechanicsEdit as $promoMechanicEdit){
                    if($promoMechanicEdit != null){
                        PromoMechanics::create([
                            'promo_id' => $id,
                            'promoMechanic' => $promoMechanicEdit,
                        ]);
                    }
                }
            }

        }
        $request->session()->flash('edited');
        return redirect('/restaurant/manage-restaurant/promo/edit/'.$id);
    }
    public function addPromo(Request $request){
        $id = Session::get('loginId');

        $request->validate([
            'promoTitle' => 'required',
            'promoDescription' => 'required',
            'promoStartDate' => 'required|date',
            'promoEndDate' => 'required|date',
            'promoMechanics.0' => 'required',
            'promoImage' => 'required|mimes:jpeg,png,jpg|max:2048',
        ],
        [
            'promoTitle.required' => "Title is required",
            'promoDescription.required' => "Description is required",
            'promoStartDate.required' => "Start Date is required",
            'promoStartDate.date' => "Start Date must be date",
            'promoEndDate.required' => "End Date is required",
            'promoEndDate.date' => "End Date must be date",
            'promoMechanics.0.required' => "Mechanics is required",
            'promoImage.required' => 'Image is required',
            'promoImage.mimes' => 'Image must be in jpeg, png and jpg format',
            'promoImage.max' => 'Image must not be greater than 2mb',
        ]);

        $promoImageName = time().'.'.$request->promoImage->extension();

        $result = Promo::create([
            'restAcc_id' => $id,
            'promoPosted' => "Draft",
            'promoTitle' => $request->promoTitle,
            'promoDescription' => $request->promoDescription,
            'promoStartDate' => $request->promoStartDate,
            'promoEndDate' => $request->promoEndDate,
            'promoImage' => $promoImageName,
        ]);
        
        foreach ($request->promoMechanics as $promoMechanic){
            if($promoMechanic != null){
                PromoMechanics::create([
                    'promo_id' => $result->id,
                    'promoMechanic' => $promoMechanic,
                ]);
            }
        }

        $request->promoImage->move(public_path('uploads/restaurantAccounts/promo/'.$id), $promoImageName);

        $request->session()->flash('added');
        return redirect('/restaurant/manage-restaurant/promo');
    }
    public function updateRestaurantGcashQr(Request $request){
        $restAccId = Session::get('loginId');
        $getrGcashLogo = RestaurantAccount::select('rGcashQrCodeImage')->where('id', $restAccId)->first();

        $request->validate([
            'restaurantGcashQr' => 'required|mimes:jpeg,png,jpg|max:2048',
        ],
        [
            'restaurantGcashQr.required' => 'Gcash Qr Code is required',
            'restaurantGcashQr.mimes' => 'Gcash Qr Code must be in jpeg, png and jpg format',
            'restaurantGcashQr.max' => 'Gcash Qr Code must not be greater than 2mb',
        ]);
        
        if($getrGcashLogo->rGcashQrCodeImage != "" || $getrGcashLogo->rGcashQrCodeImage != null){
            File::delete(public_path('uploads/restaurantAccounts/gcashQr/'.$restAccId.'/'.$getrGcashLogo->rGcashQrCodeImage));
        }
        $gcashLogo = time().'.'.$request->restaurantGcashQr->extension();
        $request->restaurantGcashQr->move(public_path('uploads/restaurantAccounts/gcashQr/'.$restAccId), $gcashLogo);

        RestaurantAccount::where('id', $restAccId)
        ->update([
            'rGcashQrCodeImage' => $gcashLogo,
        ]);

        $request->session()->flash('gcashQrUpdated');
        return redirect('/restaurant/manage-restaurant/about/restaurant-information');
    }
    public function updateRestaurantLogo(Request $request){
        $restAccId = Session::get('loginId');
        $getrLogo = RestaurantAccount::select('rLogo')->where('id', $restAccId)->first();

        $request->validate([
            'restaurantLogo' => 'required|mimes:jpeg,png,jpg|max:2048',
        ],
        [
            'restaurantLogo.required' => 'Logo is required',
            'restaurantLogo.mimes' => 'Logo must be in jpeg, png and jpg format',
            'restaurantLogo.max' => 'Logo must not be greater than 2mb',
        ]);

        if($getrLogo->rLogo != "" || $getrLogo->rLogo != null){
            File::delete(public_path('uploads/restaurantAccounts/logo/'.$restAccId.'/'.$getrLogo->rLogo));
        }
        $restaurantLogo = time().'.'.$request->restaurantLogo->extension();
        $request->restaurantLogo->move(public_path('uploads/restaurantAccounts/logo/'.$restAccId), $restaurantLogo);

        RestaurantAccount::where('id', $restAccId)
        ->update([
            'rLogo' => $restaurantLogo,
        ]);

        $request->session()->flash('logoUpdated');
        return redirect('/restaurant/manage-restaurant/about/restaurant-information');
    }
    public function updateRestaurantTables(Request $request){
        $restAccId = Session::get('loginId');

        $request->validate([
            'rNumberOfTables' => 'required|numeric|min:1',
            'rCapacityPerTable' => 'required|numeric|min:2',
        ],
        [
            'rNumberOfTables.required' => 'Number of Tables is required',
            'rNumberOfTables.numeric' => 'Number of Tables must be number only',
            'rNumberOfTables.min' => 'Number of Tables must be greater than 1',
            'rCapacityPerTable.required' => 'Capacity per Table is required',
            'rCapacityPerTable.numeric' => 'Capacity per Table must be number only',
            'rCapacityPerTable.min' => 'Capacity per Table must be greater than 2',
        ]);

        $restaurant = RestaurantAccount::where('id', $restAccId)->first();

        if($restaurant->status == "Published"){

            if($this->checkTodaySched() == "Closed Now"){
                
                RestaurantAccount::where('id', $restAccId)
                ->update([
                    'rNumberOfTables' => $request->rNumberOfTables,
                    'rCapacityPerTable' => $request->rCapacityPerTable,
                ]);

                $customerReserves = CustomerReserve::where('restAcc_id', $restAccId)
                ->where('status', '!=', 'cancelled')
                ->where('status', '!=', 'declined')
                ->where('status', '!=', 'noShow')
                ->where('status', '!=', 'runaway')
                ->where('status', '!=', 'completed')
                ->get();

                if(!$customerReserves->isEmpty()){
                    foreach($customerReserves as $customerReserve){
                        $customerAccount = CustomerAccount::where('id', $customerReserve->customer_id)->first();

                        $finalImageUrl = "";
                        if ($restaurant->rLogo == ""){
                            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                        } else {
                            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                        }
                
                        if($customerAccount != null){
                            $to = $customerAccount->deviceToken;
                            $notification = array(
                                'title' => "$restaurant->rAddress, $restaurant->rCity",
                                'body' => "Your reservation has been void due to changes of tables. You can reserve again anytime sorry for the inconvenience",
                            );
                            $data = array(
                                'notificationType' => "Tables Updated",
                                'notificationId' => 0,
                                'notificationRLogo' => $finalImageUrl,
                            );
                            $this->sendFirebaseNotification($to, $notification, $data);
                        }
                    }
                }

                CustomerReserve::where('restAcc_id', $restAccId)
                ->where('status', '!=', 'cancelled')
                ->where('status', '!=', 'declined')
                ->where('status', '!=', 'noShow')
                ->where('status', '!=', 'runaway')
                ->where('status', '!=', 'completed')
                ->delete();
                
                $request->session()->flash('tablesUpdated');
                return redirect('/restaurant/manage-restaurant/about/restaurant-information');
            } else {
                $request->session()->flash('storeStillopen');
                return redirect('/restaurant/manage-restaurant/about/restaurant-information');
            }

        } else {
            RestaurantAccount::where('id', $restAccId)
            ->update([
                'rNumberOfTables' => $request->rNumberOfTables,
                'rCapacityPerTable' => $request->rCapacityPerTable,
            ]);
    
            $request->session()->flash('tablesUpdated');
            return redirect('/restaurant/manage-restaurant/about/restaurant-information');
        }
    }
    public function updateRestaurantRadius(Request $request){
        $restAccId = Session::get('loginId');

        $request->validate([
            'rRadius' => 'required|numeric|min:100',
        ],
        [
            'rRadius.required' => 'Radius is required',
            'rRadius.numeric' => 'Radius must be number only',
            'rRadius.min' => 'Radius must be greater than 100',
        ]);

        RestaurantAccount::where('id', $restAccId)
        ->update([
            'rRadius' => $request->rRadius,
        ]);

        $request->session()->flash('radiusUpdated');
        return redirect('/restaurant/manage-restaurant/about/restaurant-information');

    }
    public function updateRestaurantPassword(Request $request){
        $restAccId = Session::get('loginId');
        $account = RestaurantAccount::select('password')->where('id', $restAccId)->first();

        if(!Hash::check($request->oldPassword, $account->password)){
            $request->session()->flash('currentPassNotMatch');
            return redirect('/restaurant/manage-restaurant/about/restaurant-information');
        }

        $request->validate([
            'oldPassword' => 'required',
            'newPassword' => 'required|min:6|same:confirmPassword',
            'confirmPassword' => 'required',
        ],
        [
            'oldPassword.required' => 'Current Password is required',
            'newPassword.same' => 'New Password does not match Confirm Password',
            'newPassword.min' => 'New Password must contain at least 6 characters',
            'newPassword.same' => 'New Password does not match Confirm Password',
            'confirmPassword.required' => 'Confirm Password is required',
        ]);

        RestaurantAccount::where('id', $restAccId)
        ->update([
            'password' => Hash::make($request->newPassword)
        ]);

        $request->session()->flash('passwordUpdated');
        return redirect('/restaurant/manage-restaurant/about/restaurant-information');
    }
    public function updateRestaurantUsername(Request $request){
        $restAccId = Session::get('loginId');
        $account = RestaurantAccount::select('username')->where('id', $restAccId)->first();
        if($account->username == $request->username){
            $request->session()->flash('usernameEqual');
            return redirect('/restaurant/manage-restaurant/about/restaurant-information');
        }

        $request->validate([
            'username' => 'required|min:6|regex:/^\S*$/u|unique:restaurant_accounts,emailAddress',
        ], 
        [
            'username.required' => 'Username is required',
            'username.min' => 'Username must contain at least 6 characters',
            'username.regex' => 'Username must not contain any spaces',
            'username.unique' => 'Username is taken',
        ]);

        RestaurantAccount::where('id', $restAccId)
        ->update([
            'username' => $request->username,
        ]);

        $request->session()->flash('usernameUpdated');
        return redirect('/restaurant/manage-restaurant/about/restaurant-information');
    }
    public function resendEmailVerification($emailAddress, $fname, $lname){
        $restAccId = Session::get('loginId');
        $details = [
            'link' => env('APP_URL') . '/restaurant/email-verification/'.$restAccId.'/update',
            'applicantName' => $fname.' '.$lname,
            'status' => 'resend'
        ];
        Mail::to($emailAddress)->send(new RestaurantUpdateEmail($details));

        Session::flash('resendEmailVerificationSent');
        return redirect('/restaurant/manage-restaurant/about/restaurant-information');
    }
    public function updateEmailAddress(Request $request){
        $restAccId = Session::get('loginId');
        $account = RestaurantAccount::select('emailAddress', 'verified', 'fname', 'lname')->where('id', $restAccId)->first();
        if($account->emailAddress == $request->emailAddress){
            $request->session()->flash('emailAddressEqual');
            return redirect('/restaurant/manage-restaurant/about/restaurant-information');
        }

        $request->validate([
            'emailAddress' => 'required|email|unique:restaurant_accounts,emailAddress',
        ], 
        [
            'emailAddress.required' => 'Email Address is required',
            'emailAddress.email' => 'Email Address must be a valid email',
            'emailAddress.unique' => 'Email Address has already taken',
        ]);

        if($account->verified == "No"){
            $request->session()->flash('emailAddressNotVerified');
            return redirect('/restaurant/manage-restaurant/about/restaurant-information');
        } else {

            $details = [
                'link' => env('APP_URL') . '/restaurant/email-verification/'.$restAccId.'/update',
                'applicantName' => $account->fname.' '.$account->lname,
                'status' => 'changedEmail'
            ];
            Mail::to($request->emailAddress)->send(new RestaurantUpdateEmail($details));

            RestaurantAccount::where('id', $restAccId)
            ->update([
                'verified' => "No",
                'emailAddress' => $request->emailAddress,
            ]);

            $request->session()->flash('emailAddressUpdated');
            return redirect('/restaurant/manage-restaurant/about/restaurant-information');
        }

    }
    public function updateRestaurantContact(Request $request){
        $restAccId = Session::get('loginId');
        $request->validate([
            'contactNumber' => 'required|digits:11',
            'landlineNumber' => 'nullable|digits:8',
        ], 
        [
            'contactNumber.required' => 'Contact Number is required',
            'contactNumber.digits' => 'Contact Number must be 11 digits',
            'landlineNumber.digits' => 'Landline Number must be 8 digits',
        ]);

        RestaurantAccount::where('id', $restAccId)
        ->update([
            'contactNumber' => $request->contactNumber,
            'landlineNumber' => $request->landlineNumber,
        ]);

        $request->session()->flash('contactUpdated');
        return redirect('/restaurant/manage-restaurant/about/restaurant-information');
    }
    public function editPost(Request $request, $id){
        $restAccId = Session::get('loginId');
        
        $request->validate([
            'postImage' => 'mimes:jpeg,png,jpg|max:2048',
        ],
        [
            'postImage.mimes' => 'Image be in jpeg, png and jpg format',
            'postImage.max' => 'Image not be greater than 2mb',
        ]);

        $post = Post::where('id', $id)->where('restAcc_id', $restAccId)->first();
        if($request->postImage == null){
            Post::where('id', $id)
            ->where('restAcc_id', $restAccId)
            ->update([
                'postDesc' => $request->postDesc
            ]);
        } else {
            File::delete(public_path('uploads/restaurantAccounts/post/'.$restAccId.'/'.$post->postImage));
            $postImageName = time().'.'.$request->postImage->extension();
            $request->postImage->move(public_path('uploads/restaurantAccounts/post/'.$restAccId), $postImageName);
            Post::where('id', $id)
            ->where('restAcc_id', $restAccId)
            ->update([
                'postDesc' => $request->postDesc,
                'postImage' => $postImageName,
            ]);
        }
        $request->session()->flash('edited');
        return redirect('/restaurant/manage-restaurant/about/restaurant-post/edit/'.$id);
    }
    public function deletePost($id){
        $restAccId = Session::get('loginId');
        $post = Post::where('id', '=', $id)
                            ->where('restAcc_Id', '=', $restAccId)
                            ->first();
        File::delete(public_path('uploads/restaurantAccounts/post/'.$restAccId.'/'.$post->postImage));
        Post::where('id', $id)->delete();
        Session::flash('deleted');
        return redirect('/restaurant/manage-restaurant/about/restaurant-post');
    }
    public function addPost(Request $request){
        $restAccId = Session::get('loginId');
        $request->validate([
            'postImage' => 'required|mimes:jpeg,png,jpg|max:2048',
        ],
        [
            'postImage.required' => 'Image is required',
            'postImage.mimes' => 'Image must be in jpeg, png and jpg format',
            'postImage.max' => 'Image must not be greater than 2mb',
        ]);
        
        $postImage = time().'.'.$request->postImage->extension();
        $request->postImage->move(public_path('uploads/restaurantAccounts/post/'.$restAccId), $postImage);

        Post::create([
            'restAcc_id' => $restAccId,
            'postDesc' => $request->postDesc,
            'postImage' => $postImage,
        ]);

        $request->session()->flash('added');
        return redirect('/restaurant/manage-restaurant/about/restaurant-post');
    }
    public function deleteOrderSet($id){
        $restAccId = Session::get('loginId');
        $restaurant = RestaurantAccount::where('id', $restAccId)->first();
        $orderSet = OrderSet::where('id', $id)->first();

        if($restaurant->status == "Unpublished" || $orderSet->status == "Hidden"){
            File::delete(public_path('uploads/restaurantAccounts/orderSet/'.$restAccId.'/'.$orderSet->orderSetImage));
            OrderSet::where('id', $id)->delete();
            Session::flash('deleted');
            return redirect('/restaurant/manage-restaurant/food-menu/order-set');
        } else {
            if($this->checkTodaySched() == "Closed Now"){
                $customerReserves = CustomerReserve::where('restAcc_id', $restAccId)
                ->where('orderSet_id', $id)
                ->where(function ($query) {
                    $query->where('status', "pending")
                    ->orWhere('status', "approved");
                })
                ->get();

                if(!$customerReserves->isEmpty()){
                    foreach($customerReserves as $customerReserve){
                        $customerAccount = CustomerAccount::where('id', $customerReserve->customer_id)->first();
                
                        $finalImageUrl = "";
                        if ($restaurant->rLogo == ""){
                            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                        } else {
                            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                        }
                
                        if($customerAccount != null){
                            $to = $customerAccount->deviceToken;
                            $notification = array(
                                'title' => "$restaurant->rAddress, $restaurant->rCity",
                                'body' => "Your reservation has been void due to some changes on your order. Were sorry for the inconvenience.",
                            );
                            $data = array(
                                'notificationType' => "Order Set Change",
                                'notificationId' => 0,
                                'notificationRLogo' => $finalImageUrl,
                            );
                            $this->sendFirebaseNotification($to, $notification, $data);
                        }

                        CustomerNotification::where('restAcc_id', $restAccId)
                        ->where('notificationType', "Pending")
                        ->where('created_at', $customerReserve->created_at)
                        ->delete();

                        if($customerReserve->approvedDateTime != null){
                            CustomerNotification::where('restAcc_id', $restAccId)
                            ->where('notificationType', "Approved")
                            ->where('created_at', $customerReserve->approvedDateTime)
                            ->delete();
                        }

                        CustomerReserve::where('id', $customerReserve->id)->delete();
                    }
                }

                File::delete(public_path('uploads/restaurantAccounts/orderSet/'.$restAccId.'/'.$orderSet->orderSetImage));
                OrderSet::where('id', $id)->delete();
                Session::flash('deleted');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set');
            } else {
                Session::flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
            }
        }
    }
    public function editOrderSet(Request $request, $id){
        $restAccId = Session::get('loginId');
        $orderSetData = OrderSet::where('id', $id)->where('restAcc_id', $restAccId)->first();
        $restaurant = RestaurantAccount::select('status')->where('id', $restAccId)->first();


        if($restaurant->status == "Unpublished" || $orderSetData->status == "Hidden"){
            $request->validate([
                'foodName' => 'required',
                'foodTagline' => 'required',
                'foodDesc' => 'required',
                'foodPrice' => 'required',
            ]);
            if($request->foodImage == null){
                OrderSet::where('id', $id)
                ->where('restAcc_id', $restAccId)
                ->update([
                    'orderSetName' => $request->foodName,
                    'orderSetTagline' => $request->foodTagline,
                    'orderSetDescription' => $request->foodDesc,
                    'orderSetPrice' => $request->foodPrice,
                ]);
            } else {
                File::delete(public_path('uploads/restaurantAccounts/orderSet/'.$restAccId.'/'.$orderSetData->orderSetImage));
                $foodImageName = time().'.'.$request->foodImage->extension();
                $request->foodImage->move(public_path('uploads/restaurantAccounts/orderSet/'.$restAccId), $foodImageName);
                OrderSet::where('id', $id)
                ->where('restAcc_id', $restAccId)
                ->update([
                    'orderSetName' => $request->foodName,
                    'orderSetTagline' => $request->foodTagline,
                    'orderSetDescription' => $request->foodDesc,
                    'orderSetPrice' => $request->foodPrice,
                    'orderSetImage' => $foodImageName,
                ]);
            }
            $request->session()->flash('edited');
            return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/edit/'.$id);
        } else {
            if($this->checkTodaySched() == "Closed Now"){
                
                $request->validate([
                    'foodName' => 'required',
                    'foodTagline' => 'required',
                    'foodDesc' => 'required',
                    'foodPrice' => 'required',
                ]);
                if($request->foodImage == null){
                    OrderSet::where('id', $id)
                    ->where('restAcc_id', $restAccId)
                    ->update([
                        'orderSetName' => $request->foodName,
                        'orderSetTagline' => $request->foodTagline,
                        'orderSetDescription' => $request->foodDesc,
                        'orderSetPrice' => $request->foodPrice,
                    ]);
                } else {
                    File::delete(public_path('uploads/restaurantAccounts/orderSet/'.$restAccId.'/'.$orderSetData->orderSetImage));
                    $foodImageName = time().'.'.$request->foodImage->extension();
                    $request->foodImage->move(public_path('uploads/restaurantAccounts/orderSet/'.$restAccId), $foodImageName);
                    OrderSet::where('id', $id)
                    ->where('restAcc_id', $restAccId)
                    ->update([
                        'orderSetName' => $request->foodName,
                        'orderSetTagline' => $request->foodTagline,
                        'orderSetDescription' => $request->foodDesc,
                        'orderSetPrice' => $request->foodPrice,
                        'orderSetImage' => $foodImageName,
                    ]);
                }

                $customerReserves = CustomerReserve::where('restAcc_id', $restAccId)
                ->where('orderSet_id', $id)
                ->where(function ($query) {
                    $query->where('status', "pending")
                    ->orWhere('status', "approved");
                })
                ->get();

                if(!$customerReserves->isEmpty()){
                    foreach($customerReserves as $customerReserve){
                        $customerAccount = CustomerAccount::where('id', $customerReserve->customer_id)->first();
                
                        $finalImageUrl = "";
                        if ($restaurant->rLogo == ""){
                            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                        } else {
                            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                        }
                
                        if($customerAccount != null){
                            $to = $customerAccount->deviceToken;
                            $notification = array(
                                'title' => "$restaurant->rAddress, $restaurant->rCity",
                                'body' => "Your reservation has been void due to some changes on your order. Were sorry for the inconvenience.",
                            );
                            $data = array(
                                'notificationType' => "Order Set Change",
                                'notificationId' => 0,
                                'notificationRLogo' => $finalImageUrl,
                            );
                            $this->sendFirebaseNotification($to, $notification, $data);
                        }

                        CustomerNotification::where('restAcc_id', $restAccId)
                        ->where('notificationType', "Pending")
                        ->where('created_at', $customerReserve->created_at)
                        ->delete();

                        if($customerReserve->approvedDateTime != null){
                            CustomerNotification::where('restAcc_id', $restAccId)
                            ->where('notificationType', "Approved")
                            ->where('created_at', $customerReserve->approvedDateTime)
                            ->delete();
                        }

                        CustomerReserve::where('id', $customerReserve->id)->delete();
                    }
                }

                $request->session()->flash('edited');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/edit/'.$id);
            } else {
                $request->session()->flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/edit/'.$id);
            }
        }

        
    }
    public function orderSetFoodItemDelete($orderSetid, $orderSetFoodItemId){
        $restAccId = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAccId)->first();
        $orderSet = OrderSet::where('id', $orderSetid)->first();

        if($restaurant->status == "Unpublished" || $orderSet->status == "Hidden"){
            OrderSetFoodItem::where('id', $orderSetFoodItemId)->delete();
            Session::flash('deletedFoodItem');
            return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$orderSetid);
        } else {
            if($this->checkTodaySched() == "Closed Now"){
                OrderSetFoodItem::where('id', $orderSetFoodItemId)->delete();
                Session::flash('deletedFoodItem');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$orderSetid);
            } else {
                Session::flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$orderSetid);
            }
        }
    }
    public function orderSetFoodSetDelete($orderSetid, $orderSetFoodSetId){
        $restAccId = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAccId)->first();
        $orderSet = OrderSet::where('id', $orderSetid)->first();

        if($restaurant->status == "Unpublished" || $orderSet->status == "Hidden"){
            OrderSetFoodSet::where('id', $orderSetFoodSetId)->delete();
            Session::flash('deletedFoodSet');
            return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$orderSetid);
        } else {
            if($this->checkTodaySched() == "Closed Now"){
                OrderSetFoodSet::where('id', $orderSetFoodSetId)->delete();
                Session::flash('deletedFoodSet');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$orderSetid);
            } else {
                Session::flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$orderSetid);
            }
        }
    }
    public function orderSetAddFoodItem(Request $request, $id){
        $restAccId = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAccId)->first();
        $orderSet = OrderSet::where('id', $id)->first();

        if($restaurant->status == "Unpublished" || $orderSet->status == "Hidden"){
            $checkIfExisting = 0;
            $checkIfExisting2 = 0;
            $checkLive = 0;
            $orderSetDatas = OrderSetFoodSet::where('orderSet_id', $id)->get();

            if(empty($request->foodItem)){
                $request->session()->flash('emptyFoodItem');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
            } else {
                foreach ($request->foodItem as $foodItemId) {
                    $data = OrderSetFoodItem::where('orderSet_id', '=', $id)
                                        ->where('foodItem_id', '=', $foodItemId)
                                        ->get();
                    
                    foreach ($orderSetDatas as $orderSetData){
                        $foodSetDatas = FoodSetItem::where('foodSet_id', $orderSetData->foodSet_id)->get();
                        foreach ($foodSetDatas as $foodSetData){
                            if ($foodItemId == $foodSetData->foodItem_id){
                                $checkLive++;
                            }
                        }
                    }

                    if(!$data->isEmpty()){
                        $checkIfExisting++;
                    } else {
                        if($checkLive == 0){
                            OrderSetFoodItem::create([
                                'orderSet_id' => $id,
                                'foodItem_id' => $foodItemId
                            ]);
                        } else {
                            $checkIfExisting2++;
                        }
                    }
                    $checkLive = 0;
                }
                // Exapmle 5 items
                if($checkIfExisting == 0 && $checkIfExisting2 == 0){ // 0, 0 
                    $request->session()->flash('addedFoodItem');
                } else if ($checkIfExisting == sizeOf($request->foodItem) && $checkIfExisting2 == 0) { // 5, 0 
                    $request->session()->flash('allExistingFoodItem');
                } else if ($checkIfExisting == 0 && $checkIfExisting2 == sizeOf($request->foodItem)) { // 0, 5
                    $request->session()->flash('allExistingFoodSets');
                } else if ($checkIfExisting > 0 && $checkIfExisting < sizeOf($request->foodItem) && $checkIfExisting2 == 0) { // 1-4, 0
                    $request->session()->flash('someExistInFoodItem');
                } else if ($checkIfExisting == 0 && $checkIfExisting2 > 0 && $checkIfExisting2 < sizeOf($request->foodItem)) { // 0, 1-4
                    $request->session()->flash('someExistInFoodSet');
                } else if ($checkIfExisting > 0 && $checkIfExisting < sizeOf($request->foodItem) && 
                        $checkIfExisting2 > 0 && $checkIfExisting2 < sizeOf($request->foodItem) &&
                        ($checkIfExisting + $checkIfExisting2) == sizeOf($request->foodItem)) { // 1-4, 1-4 == 5
                    $request->session()->flash('allExistInFoodSetAndFoodItem');
                } else {
                    $request->session()->flash('someExistInFoodSetAndFoodItem');
                }
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
            }
        } else {
            if($this->checkTodaySched() == "Closed Now"){
                $checkIfExisting = 0;
                $checkIfExisting2 = 0;
                $checkLive = 0;
                $orderSetDatas = OrderSetFoodSet::where('orderSet_id', $id)->get();

                if(empty($request->foodItem)){
                    $request->session()->flash('emptyFoodItem');
                    return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
                } else {
                    foreach ($request->foodItem as $foodItemId) {
                        $data = OrderSetFoodItem::where('orderSet_id', '=', $id)
                                            ->where('foodItem_id', '=', $foodItemId)
                                            ->get();
                        
                        foreach ($orderSetDatas as $orderSetData){
                            $foodSetDatas = FoodSetItem::where('foodSet_id', $orderSetData->foodSet_id)->get();
                            foreach ($foodSetDatas as $foodSetData){
                                if ($foodItemId == $foodSetData->foodItem_id){
                                    $checkLive++;
                                }
                            }
                        }

                        if(!$data->isEmpty()){
                            $checkIfExisting++;
                        } else {
                            if($checkLive == 0){
                                OrderSetFoodItem::create([
                                    'orderSet_id' => $id,
                                    'foodItem_id' => $foodItemId
                                ]);
                            } else {
                                $checkIfExisting2++;
                            }
                        }
                        $checkLive = 0;
                    }
                    // Exapmle 5 items
                    if($checkIfExisting == 0 && $checkIfExisting2 == 0){ // 0, 0 
                        $request->session()->flash('addedFoodItem');
                    } else if ($checkIfExisting == sizeOf($request->foodItem) && $checkIfExisting2 == 0) { // 5, 0 
                        $request->session()->flash('allExistingFoodItem');
                    } else if ($checkIfExisting == 0 && $checkIfExisting2 == sizeOf($request->foodItem)) { // 0, 5
                        $request->session()->flash('allExistingFoodSets');
                    } else if ($checkIfExisting > 0 && $checkIfExisting < sizeOf($request->foodItem) && $checkIfExisting2 == 0) { // 1-4, 0
                        $request->session()->flash('someExistInFoodItem');
                    } else if ($checkIfExisting == 0 && $checkIfExisting2 > 0 && $checkIfExisting2 < sizeOf($request->foodItem)) { // 0, 1-4
                        $request->session()->flash('someExistInFoodSet');
                    } else if ($checkIfExisting > 0 && $checkIfExisting < sizeOf($request->foodItem) && 
                            $checkIfExisting2 > 0 && $checkIfExisting2 < sizeOf($request->foodItem) &&
                            ($checkIfExisting + $checkIfExisting2) == sizeOf($request->foodItem)) { // 1-4, 1-4 == 5
                        $request->session()->flash('allExistInFoodSetAndFoodItem');
                    } else {
                        $request->session()->flash('someExistInFoodSetAndFoodItem');
                    }
                    return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
                }
            } else {
                $request->session()->flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
            }
        }


        
    }
    public function orderSetAddFoodSet(Request $request, $id){
        $restAccId = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAccId)->first();
        $orderSet = OrderSet::where('id', $id)->first();

        if($restaurant->status == "Unpublished" || $orderSet->status == "Hidden"){
            $checkIfExisting = 0;
            if(empty($request->foodSet)){
                $request->session()->flash('emptyFoodSet');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
            } else {
                foreach ($request->foodSet as $foodSetId) {
                    $data = OrderSetFoodSet::where('orderSet_id', '=', $id)
                                        ->where('foodSet_id', '=', $foodSetId)
                                        ->get();
                    if(!$data->isEmpty()){
                        $checkIfExisting++;
                    } else {
                        OrderSetFoodSet::create([
                            'orderSet_id' => $id,
                            'foodSet_id' => $foodSetId
                        ]);
                    }
                }
                if($checkIfExisting == 0){
                    $request->session()->flash('addedFoodSet');
                } else if ($checkIfExisting > 0 && $checkIfExisting < sizeOf($request->foodSet)) {
                    $request->session()->flash('someAreExistingFoodSet');
                } else {
                    $request->session()->flash('allExistingFoodSet');
                }
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
            }
        } else {
            if($this->checkTodaySched() == "Closed Now"){
                $checkIfExisting = 0;
                if(empty($request->foodSet)){
                    $request->session()->flash('emptyFoodSet');
                    return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
                } else {
                    foreach ($request->foodSet as $foodSetId) {
                        $data = OrderSetFoodSet::where('orderSet_id', '=', $id)
                                            ->where('foodSet_id', '=', $foodSetId)
                                            ->get();
                        if(!$data->isEmpty()){
                            $checkIfExisting++;
                        } else {
                            OrderSetFoodSet::create([
                                'orderSet_id' => $id,
                                'foodSet_id' => $foodSetId
                            ]);
                        }
                    }
                    if($checkIfExisting == 0){
                        $request->session()->flash('addedFoodSet');
                    } else if ($checkIfExisting > 0 && $checkIfExisting < sizeOf($request->foodSet)) {
                        $request->session()->flash('someAreExistingFoodSet');
                    } else {
                        $request->session()->flash('allExistingFoodSet');
                    }
                    return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
                }
            } else {
                $request->session()->flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$id);
            }
        }
        
    }
    public function addOrderSet(Request $request){
        $id = Session::get('loginId');
        $request->validate([
            'foodName' => 'required',
            'foodTagline' => 'required',
            'foodDesc' => 'required',
            'foodPrice' => 'required',
            'foodImage' => 'required',
        ]);

        $foodImageName = time().'.'.$request->foodImage->extension();
        $request->foodImage->move(public_path('uploads/restaurantAccounts/orderSet/'.$id), $foodImageName);

        OrderSet::create([
            'restAcc_id' => $id,
            'status' => "Visible",
            'available' => "Yes",
            'orderSetName' => $request->foodName,
            'orderSetTagline' => $request->foodTagline,
            'orderSetDescription' => $request->foodDesc,
            'orderSetPrice' => $request->foodPrice,
            'orderSetImage' => $foodImageName,
        ]);
        $request->session()->flash('added');
        return redirect('/restaurant/manage-restaurant/food-menu/order-set');
    }
    public function editFoodSet(Request $request, $id){
        $restAccId = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAccId)->first();
        $foodSet = FoodSet::where('id', $id)->first();

        if($restaurant->status == "Unpublished" || $foodSet->status == "Hidden"){
            $foodSetData = FoodSet::where('id', $id)->where('restAcc_id', $restAccId)->first();
            $request->validate([
                'foodName' => 'required',
                'foodDesc' => 'required',
                'foodPrice' => 'required',
            ]);
            if($request->foodImage == null){
                FoodSet::where('id', $id)
                ->where('restAcc_id', $restAccId)
                ->update([
                    'foodSetName' => $request->foodName,
                    'foodSetDescription' => $request->foodDesc,
                    'foodSetPrice' => $request->foodPrice,
                ]);
            } else {
                File::delete(public_path('uploads/restaurantAccounts/foodSet/'.$restAccId.'/'.$foodSetData->foodSetImage));
                $foodImageName = time().'.'.$request->foodImage->extension();
                $request->foodImage->move(public_path('uploads/restaurantAccounts/foodSet/'.$restAccId), $foodImageName);
                FoodSet::where('id', $id)
                ->where('restAcc_id', $restAccId)
                ->update([
                    'foodSetName' => $request->foodName,
                    'foodSetDescription' => $request->foodDesc,
                    'foodSetPrice' => $request->foodPrice,
                    'foodSetImage' => $foodImageName,
                ]);
            }
            $request->session()->flash('edited');
            return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/edit/'.$id);
        } else {
            if($this->checkTodaySched() == "Closed Now"){
                $foodSetData = FoodSet::where('id', $id)->where('restAcc_id', $restAccId)->first();
                $request->validate([
                    'foodName' => 'required',
                    'foodDesc' => 'required',
                    'foodPrice' => 'required',
                ]);
                if($request->foodImage == null){
                    FoodSet::where('id', $id)
                    ->where('restAcc_id', $restAccId)
                    ->update([
                        'foodSetName' => $request->foodName,
                        'foodSetDescription' => $request->foodDesc,
                        'foodSetPrice' => $request->foodPrice,
                    ]);
                } else {
                    File::delete(public_path('uploads/restaurantAccounts/foodSet/'.$restAccId.'/'.$foodSetData->foodSetImage));
                    $foodImageName = time().'.'.$request->foodImage->extension();
                    $request->foodImage->move(public_path('uploads/restaurantAccounts/foodSet/'.$restAccId), $foodImageName);
                    FoodSet::where('id', $id)
                    ->where('restAcc_id', $restAccId)
                    ->update([
                        'foodSetName' => $request->foodName,
                        'foodSetDescription' => $request->foodDesc,
                        'foodSetPrice' => $request->foodPrice,
                        'foodSetImage' => $foodImageName,
                    ]);
                }
                $request->session()->flash('edited');
                return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/edit/'.$id);
            } else {
                $request->session()->flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/edit/'.$id);
            }
        }
        
    }
    public function editFoodItem(Request $request, $id){
        $restAccId = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAccId)->first();
        $foodItem = FoodItem::where('id', $id)->first();
        
        if($restaurant->status == "Unpublished" || $foodItem->status == "Hidden"){
            $foodItemData = FoodItem::where('id', $id)->where('restAcc_id', $restAccId)->first();
            if($request->foodItemImage == null){
                $request->validate([
                    'foodName' => 'required',
                    'foodDesc' => 'required',
                    'foodPrice' => 'required|numeric|min:0',
                ],
                [
                    'foodName.required' => 'Name is required',
                    'foodDesc.required' => 'Description is required',
                    'foodPrice.required' => 'Price is required',
                    'foodPrice.numeric' => 'Price must be number only',
                    'foodPrice.min' => 'Price must not be less than 0',
                ]);
                FoodItem::where('id', $id)
                ->where('restAcc_id', $restAccId)
                ->update([
                    'foodItemName' => $request->foodName,
                    'foodItemDescription' => $request->foodDesc,
                    'foodItemPrice' => $request->foodPrice,
                ]);
                $request->session()->flash('edited');
                return redirect('/restaurant/manage-restaurant/food-menu/food-item/edit/'.$id);
            } else {
                $request->validate([
                    'foodName' => 'required',
                    'foodDesc' => 'required',
                    'foodPrice' => 'required|numeric|min:0',
                    'foodItemImage' => 'required|mimes:jpeg,png,jpg|max:2048',
                ],
                [
                    'foodName.required' => 'Name is required',
                    'foodDesc.required' => 'Description is required',
                    'foodPrice.required' => 'Price is required',
                    'foodPrice.numeric' => 'Price must be number only',
                    'foodPrice.min' => 'Price must not be less than 0',
                    'foodItemImage.required' => 'Image is required',
                    'foodItemImage.mimes' => 'Image must be in jpeg, png and jpg format',
                    'foodItemImage.max' => 'Image must not be greater than 2mb',
                ]);
                File::delete(public_path('uploads/restaurantAccounts/foodItem/'.$restAccId.'/'.$foodItemData->foodItemImage));
                $foodImageName = time().'.'.$request->foodItemImage->extension();
                $request->foodItemImage->move(public_path('uploads/restaurantAccounts/foodItem/'.$restAccId), $foodImageName);
                FoodItem::where('id', $id)
                ->where('restAcc_id', $restAccId)
                ->update([
                    'foodItemName' => $request->foodName,
                    'foodItemDescription' => $request->foodDesc,
                    'foodItemPrice' => $request->foodPrice,
                    'foodItemImage' => $foodImageName,
                ]);
                $request->session()->flash('edited');
                return redirect('/restaurant/manage-restaurant/food-menu/food-item/edit/'.$id);
            }
        } else {
            if($this->checkTodaySched() == "Closed Now"){
                $foodItemData = FoodItem::where('id', $id)->where('restAcc_id', $restAccId)->first();
                if($request->foodItemImage == null){
                    $request->validate([
                        'foodName' => 'required',
                        'foodDesc' => 'required',
                        'foodPrice' => 'required|numeric|min:0',
                    ],
                    [
                        'foodName.required' => 'Name is required',
                        'foodDesc.required' => 'Description is required',
                        'foodPrice.required' => 'Price is required',
                        'foodPrice.numeric' => 'Price must be number only',
                        'foodPrice.min' => 'Price must not be less than 0',
                    ]);
                    FoodItem::where('id', $id)
                    ->where('restAcc_id', $restAccId)
                    ->update([
                        'foodItemName' => $request->foodName,
                        'foodItemDescription' => $request->foodDesc,
                        'foodItemPrice' => $request->foodPrice,
                    ]);
                    $request->session()->flash('edited');
                    return redirect('/restaurant/manage-restaurant/food-menu/food-item/edit/'.$id);
                } else {
                    $request->validate([
                        'foodName' => 'required',
                        'foodDesc' => 'required',
                        'foodPrice' => 'required|numeric|min:0',
                        'foodItemImage' => 'required|mimes:jpeg,png,jpg|max:2048',
                    ],
                    [
                        'foodName.required' => 'Name is required',
                        'foodDesc.required' => 'Description is required',
                        'foodPrice.required' => 'Price is required',
                        'foodPrice.numeric' => 'Price must be number only',
                        'foodPrice.min' => 'Price must not be less than 0',
                        'foodItemImage.required' => 'Image is required',
                        'foodItemImage.mimes' => 'Image must be in jpeg, png and jpg format',
                        'foodItemImage.max' => 'Image must not be greater than 2mb',
                    ]);
                    File::delete(public_path('uploads/restaurantAccounts/foodItem/'.$restAccId.'/'.$foodItemData->foodItemImage));
                    $foodImageName = time().'.'.$request->foodItemImage->extension();
                    $request->foodItemImage->move(public_path('uploads/restaurantAccounts/foodItem/'.$restAccId), $foodImageName);
                    FoodItem::where('id', $id)
                    ->where('restAcc_id', $restAccId)
                    ->update([
                        'foodItemName' => $request->foodName,
                        'foodItemDescription' => $request->foodDesc,
                        'foodItemPrice' => $request->foodPrice,
                        'foodItemImage' => $foodImageName,
                    ]);
                    $request->session()->flash('edited');
                    return redirect('/restaurant/manage-restaurant/food-menu/food-item/edit/'.$id);
                }
            } else {
                Session::flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/food-item/edit/'.$id);
            }
        }
        
    }
    public function deleteFoodSet($id){
        $restAccId = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAccId)->first();
        $foodSet = FoodSet::where('id', $id)->first();

        if($restaurant->status == "Unpublished" || $foodSet->status == "Hidden"){
            File::delete(public_path('uploads/restaurantAccounts/foodSet/'.$restAccId.'/'.$foodSet->foodSetImage));
            FoodSet::where('id', $id)->delete();
            Session::flash('deleted');
            return redirect('/restaurant/manage-restaurant/food-menu/food-set');
        } else {
            if($this->checkTodaySched() == "Closed Now"){
                File::delete(public_path('uploads/restaurantAccounts/foodSet/'.$restAccId.'/'.$foodSet->foodSetImage));
                FoodSet::where('id', $id)->delete();
                Session::flash('deleted');
                return redirect('/restaurant/manage-restaurant/food-menu/food-set');
            } else {
                Session::flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$id);
            }
        }
    }
    public function foodSetItemDelete($foodSetid, $foodSetItemId){
        $restAccId = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAccId)->first();
        $foodSet = FoodSet::where('id', $foodSetid)->first();

        if($restaurant->status == "Unpublished" || $foodSet->status == "Hidden"){
            FoodSetItem::where('id', $foodSetItemId)->delete();
            Session::flash('deleted');
            return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$foodSetid);
        } else {
            if($this->checkTodaySched() == "Closed Now"){ 
                FoodSetItem::where('id', $foodSetItemId)->delete();
                Session::flash('deleted');
                return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$foodSetid);
            } else {
                Session::flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$foodSetid);
            }
        }
    }
    public function addItemToFoodSet(Request $request){
        $restAccId = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAccId)->first();
        $checkIfExisting = 0;
        $foodSet = FoodSet::where('id', $request->foodSetId)->first();

        if($restaurant->status == "Unpublished" || $foodSet->status == "Hidden"){
            if(empty($request->foodItem)){
                $request->session()->flash('empty');
                return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$request->foodSetId);
            } else {
                foreach ($request->foodItem as $foodItemId) {
                    $data = FoodSetItem::where('foodSet_id', $request->foodSetId)->where('foodItem_id', $foodItemId)->first();
                    if($data != null){
                        $checkIfExisting++;
                    } else {
                        $checkOrderSets = OrderSet::select('id')->where('restAcc_id', $restAccId)->get();
                        foreach ($checkOrderSets as $checkOrderSet){
                            $checkOrderSetItems = OrderSetFoodItem::where('orderSet_id', $checkOrderSet->id)->get();
                            foreach ($checkOrderSetItems as $checkOrderSetItem){
                                if($checkOrderSetItem->foodItem_id == $foodItemId){
                                    OrderSetFoodItem::where('id', $checkOrderSetItem->id)->delete();
                                }
                            }
                        }
                        FoodSetItem::create([
                            'foodSet_id' => $request->foodSetId,
                            'foodItem_id' => $foodItemId
                        ]);
                    }
                }
                if($checkIfExisting == sizeOf($request->foodItem)){
                    $request->session()->flash('allExisting');
                } else if ($checkIfExisting > 0 && $checkIfExisting < sizeOf($request->foodItem)) {
                    $request->session()->flash('someExisting');
                } else {
                    $request->session()->flash('added');
                }
                return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$request->foodSetId);
            }
        } else {
            if($this->checkTodaySched() == "Closed Now"){
                if(empty($request->foodItem)){
                    $request->session()->flash('empty');
                    return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$request->foodSetId);
                } else {
                    foreach ($request->foodItem as $foodItemId) {
                        $data = FoodSetItem::where('foodSet_id', $request->foodSetId)->where('foodItem_id', $foodItemId)->first();
                        if($data != null){
                            $checkIfExisting++;
                        } else {
                            $checkOrderSets = OrderSet::select('id')->where('restAcc_id', $restAccId)->get();
                            foreach ($checkOrderSets as $checkOrderSet){
                                $checkOrderSetItems = OrderSetFoodItem::where('orderSet_id', $checkOrderSet->id)->get();
                                foreach ($checkOrderSetItems as $checkOrderSetItem){
                                    if($checkOrderSetItem->foodItem_id == $foodItemId){
                                        OrderSetFoodItem::where('id', $checkOrderSetItem->id)->delete();
                                    }
                                }
                            }
                            FoodSetItem::create([
                                'foodSet_id' => $request->foodSetId,
                                'foodItem_id' => $foodItemId
                            ]);
                        }
                    }
                    if($checkIfExisting == sizeOf($request->foodItem)){
                        $request->session()->flash('allExisting');
                    } else if ($checkIfExisting > 0 && $checkIfExisting < sizeOf($request->foodItem)) {
                        $request->session()->flash('someExisting');
                    } else {
                        $request->session()->flash('added');
                    }
                    return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$request->foodSetId);
                }
            } else {
                $request->session()->flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$request->foodSetId);
            }
        }
        
    }
    public function addFoodSet(Request $request){
        $id = Session::get('loginId');
        $request->validate([
            'foodName' => 'required',
            'foodDesc' => 'required',
            'foodPrice' => 'required',
            'foodImage' => 'required',
        ]);

        $foodImageName = time().'.'.$request->foodImage->extension();
        $request->foodImage->move(public_path('uploads/restaurantAccounts/foodSet/'.$id), $foodImageName);

        FoodSet::create([
            'restAcc_id' => $id,
            'status' => "Visible",
            'available' => "Yes",
            'foodSetName' => $request->foodName,
            'foodSetDescription' => $request->foodDesc,
            'foodSetPrice' => $request->foodPrice,
            'foodSetImage' => $foodImageName,
        ]);
        $request->session()->flash('added', "Item Added");
        return redirect('/restaurant/manage-restaurant/food-menu/food-set');
    }
    public function deleteFoodItem($id){
        $restAccId = Session::get('loginId');
        $restaurant = RestaurantAccount::select('status')->where('id', $restAccId)->first();
        $foodItem = FoodItem::where('id', $id)->first();

        if($restaurant->status == "Unpublished" || $foodItem->status == "Hidden"){
            $foodItem = FoodItem::where('id', $id)->where('restAcc_Id', $restAccId)->first();
            File::delete(public_path('uploads/restaurantAccounts/foodItem/'.$restAccId.'/'.$foodItem->foodItemImage));
            FoodItem::where('id', $id)->delete();
            Session::flash('deleted');
            return redirect('/restaurant/manage-restaurant/food-menu/food-item');
        } else {
            if($this->checkTodaySched() == "Closed Now"){
                $foodItem = FoodItem::where('id', $id)->where('restAcc_Id', $restAccId)->first();
                File::delete(public_path('uploads/restaurantAccounts/foodItem/'.$restAccId.'/'.$foodItem->foodItemImage));
                FoodItem::where('id', $id)->delete();
                Session::flash('deleted');
                return redirect('/restaurant/manage-restaurant/food-menu/food-item');
            } else {
                Session::flash('stillOpenHours');
                return redirect('/restaurant/manage-restaurant/food-menu/food-item');
            }
        }
    }
    public function addFoodItem(Request $request){
        $id = Session::get('loginId');
        $request->validate([
            'foodName' => 'required',
            'foodDesc' => 'required',
            'foodPrice' => 'required',
            'foodImage' => 'required',
        ]);

        $foodImageName = time().'.'.$request->foodImage->extension();
        $request->foodImage->move(public_path('uploads/restaurantAccounts/foodItem/'.$id), $foodImageName);

        FoodItem::create([
            'restAcc_id' => $id,
            'status' => "Visible",
            'available' => "Yes",
            'foodItemName' => $request->foodName,
            'foodItemDescription' => $request->foodDesc,
            'foodItemPrice' => $request->foodPrice,
            'foodItemImage' => $foodImageName,
        ]);
        $request->session()->flash('added', "Item Added");
        return redirect('/restaurant/manage-restaurant/food-menu/food-item');
    }
    public function logout(){
        Session::flush();
        return redirect('/restaurant/login');
    }
    public function login(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $account = RestaurantAccount::where('username', '=', $request->username)->first();
        if(empty($account)){
            $request->session()->flash('invalidPassword', "Incorrect Username and Password");
            return redirect('/restaurant/login');
        } else {
            if(Hash::check($request->password, $account->password)){
                $request->session()->put('userType', 'restaurant');
                $request->session()->put('loginId', $account->id);
                return redirect('/restaurant/dashboard');
            } else {
                $request->session()->flash('invalidPassword', "Incorrect Username and Password");
                return redirect('/restaurant/login');
            }
        }
    }

    public function verifyEmail($id, $status){
        $data = RestaurantAccount::select('emailAddress', 'fname', 'lname', 'verified')->where('id', $id)->first();
        if($data->verified == "No"){
            $details = [
                'status' => $status,
                'link' => env('APP_URL') . '/restaurant/login',
                'applicantName' => $data->fname.' '.$data->lname,
            ];
            Mail::to($data->emailAddress)->send(new RestaurantVerified($details));
            RestaurantAccount::where('id', $id)->update(['verified' => "Yes"]);
            return redirect('/restaurant/login');
        } else {
            return redirect('/restaurant/login');
        }
    }
}
