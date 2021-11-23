<?php

namespace App\Http\Controllers;

use App\Mail\CustomerEmailVerified;
use App\Models\Post;
use App\Models\Promo;
use App\Models\Policy;
use App\Models\FoodSet;
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
use App\Models\CustRestoRating;
use App\Models\OrderSetFoodSet;
use App\Models\UnavailableDate;
use App\Models\CustomerLRequest;
use App\Models\CustomerOrdering;
use App\Models\CustomerQrAccess;
use App\Models\OrderSetFoodItem;
use App\Mail\CustomerEVerifyLink;
use App\Models\CustomerStampCard;
use App\Models\CustomerTasksDone;
use App\Models\RestaurantAccount;
use App\Models\CustomerStampTasks;
use App\Models\RestaurantTaskList;
use App\Mail\CustomerForgotPassword;
use App\Models\CustomerNotification;
use App\Models\RestaurantRewardList;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerPasswordChanged;
use App\Models\CustomerResetPassword;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    // public $RESTAURANT_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/logo";
    // public $ACCOUNT_NO_IMAGE_PATH = "http://192.168.1.53:8000/images";
    // public $CUSTOMER_IMAGE_PATH = "http://192.168.1.53:8000/uploads/customerAccounts/logo";
    // public $POST_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/post";
    // public $PROMO_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/promo";
    // public $ORDER_SET_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/orderSet";
    // public $FOOD_SET_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/foodSet";
    // public $FOOD_ITEM_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/foodItem";
    // public $RESTAURANT_GCASH_QR_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/gcashQr";
    
    public $RESTAURANT_IMAGE_PATH = "https://www.samquicksal.com/uploads/restaurantAccounts/logo";
    public $ACCOUNT_NO_IMAGE_PATH = "https://www.samquicksal.com/images";
    public $CUSTOMER_IMAGE_PATH = "https://www.samquicksal.com/uploads/customerAccounts/logo";
    public $POST_IMAGE_PATH = "https://www.samquicksal.com/uploads/restaurantAccounts/post";
    public $PROMO_IMAGE_PATH = "https://www.samquicksal.com/uploads/restaurantAccounts/promo";
    public $ORDER_SET_IMAGE_PATH = "https://www.samquicksal.com/uploads/restaurantAccounts/orderSet";
    public $FOOD_SET_IMAGE_PATH = "https://www.samquicksal.com/uploads/restaurantAccounts/foodSet";
    public $FOOD_ITEM_IMAGE_PATH = "https://www.samquicksal.com/uploads/restaurantAccounts/foodItem";
    public $RESTAURANT_GCASH_QR_IMAGE_PATH = "https://www.samquicksal.com/uploads/restaurantAccounts/gcashQr";


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

    public function createBlockNotif(
        $customerId, 
        $restAccId, 
        $notifTitle, 
        $notifDesc, 
        $custDeviceToken, 
        $notif2Title, 
        $notif2Desc, 
        $finalImageUrl){
        
        $notif = CustomerNotification::create([
            'customer_id' => $customerId,
            'restAcc_id' => $restAccId,
            'notificationType' => "Blocked",
            'notificationTitle' => $notifTitle,
            'notificationDescription' => $notifDesc,
            'notificationStatus' => "Unread",
        ]);

        if($custDeviceToken != null){
            $to = $custDeviceToken;
            $notification = array(
                'title' => $notif2Title,
                'body' => $notif2Desc,
            );
            $data = array(
                'notificationType' => "Blocked",
                'notificationId' => $notif->id,
                'notificationRLogo' => $finalImageUrl,
            );
            $this->sendFirebaseNotification($to, $notification, $data);
        }
    }










    public function customers(){
        $account = CustomerAccount::all();
        return response()->json([$account]);
    }
    public function restaurants(){
        $account = RestaurantAccount::all();
        return response()->json([$account]);
    }




    // RESTAURANT ROUTES
    // -------LIST OF RESTAURANTS------------ //
    public function getListOfRestaurants($keyword){
        $finalData = array();

        if($keyword == "" || $keyword == null || $keyword == " "){
            $restaurants = RestaurantAccount::select('id', 'rName', 'rCity', 'rBranch', 'rLogo')->where('status', "Published")->get();
        } else {
            $searchText = '%'.$keyword.'%';
            $restaurants = RestaurantAccount::select('id', 'rName', 'rCity', 'rBranch', 'rLogo')
            ->where('status', "Published")
            ->where(function ($query) use ($searchText) {
                $query->where('rName', 'LIKE', $searchText)
                    ->orWhere('rCity', 'LIKE', $searchText)
                    ->orWhere('rBranch', 'LIKE', $searchText);
            })->get();
        }
        
        if(!$restaurants->isEmpty()){
            foreach ($restaurants as $restaurant){

                $rSchedule = $this->checkTodaySched($restaurant->id);
    
                $finalImageUrl = "";
                if ($restaurant->rLogo == ""){
                    $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                } else {
                    $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                }
    
                array_push($finalData, [
                    'id' => $restaurant->id,
                    'rName' => $restaurant->rName,
                    'rAddress' => "$restaurant->rBranch, $restaurant->rCity",
                    'rLogo' => $finalImageUrl,
                    'rSchedule' => $rSchedule,
                ]);
            }
        } else {
            $finalData = null;
        }
        return response()->json($finalData);
    }
    // -------RESTAURANT ABOUT BY ID------------ //
    public function getRestaurantAboutInfo($id){
        $account = RestaurantAccount::select('rName', 'rAddress', 'rLogo', 'rNumberOfTables', 'rCity', 'rBranch')->where('id', $id)->first();
        $posts = Post::where('restAcc_id', $id)->get();

        $storePosts = array();
        if(!$posts->isEmpty()){
            foreach($posts as $post){
                array_push($storePosts, [
                    'image' => $this->POST_IMAGE_PATH."/".$id."/".$post->postImage,
                    'description' => $post->postDesc,
                ]);
            }
        } else {
            $storePosts = null;
        }


        $storePolicy = array();
        $policies = Policy::where('restAcc_id', $id)->get();
        if(!$policies->isEmpty()){
            foreach ($policies as $policie){
                array_push($storePolicy, [
                    'policy' => $policie->policyDesc,
                ]);
            }
        } else {
            $storePolicy = null;
        }

        $storeUnavailableDate = array();
        $unavailableDates = UnavailableDate::where('restAcc_id', $id)->orderBy('unavailableDatesDate', 'ASC')->get();
        if(!$unavailableDates->isEmpty()){
            foreach($unavailableDates as $unavailableDate){
                $orderdate1 = explode('-', $unavailableDate->unavailableDatesDate);
                $month1 = $this->convertMonths($orderdate1[1]);
                $year1 = $orderdate1[0];
                $day1  = $orderdate1[2];
    
                array_push($storeUnavailableDate, [
                    'date' => "$month1 $day1, $year1",
                    'description' => $unavailableDate->unavailableDatesDesc,
                    'startTime' => date("g:i a", strtotime($unavailableDate->startTime)),
                ]);
            }
        } else {
            $storeUnavailableDate = null;
        }

        $finalScheduleTemp = array();
        $storeHours = StoreHour::where('restAcc_id', $id)->get();
        foreach ($storeHours as $storeHour){
            $openingTime = date("g:i a", strtotime($storeHour->openingTime));
            $closingTime = date("g:i a", strtotime($storeHour->closingTime));
            foreach (explode(",", $storeHour->days) as $day){
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
                array_push($finalScheduleTemp, [
                    'Day' => $this->convertDays($day),
                    'Opening' => $openingTime,
                    'Closing' => $closingTime,
                    'num' => $num,
                ]);
            }
        }

        usort($finalScheduleTemp, function($a, $b) {
            return ($a['num']) - ($b['num']);
        });

        $finalSchedule = array();
        foreach($finalScheduleTemp as $tempSched){
            array_push($finalSchedule, [
                'Day' => $tempSched['Day'],
                'Opening' => $tempSched['Opening'],
                'Closing' => $tempSched['Closing'],
            ]);
        }

        $finalImageUrl = "";
        if ($account->rLogo == ""){
            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
        } else {
            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$id.'/'. $account->rLogo;
        }

        $getDateToday = date("Y-m-d");
        $countCurrentTables = 0;
        $countNumberOfPeople = 0;
        $customerOrderings = CustomerOrdering::where('restAcc_id', $id)->where('status', 'eating')->get();
        if(!$customerOrderings->isEmpty()){
            foreach($customerOrderings as $customerOrdering){
                if($customerOrdering->custBookType == "queue"){
                    $customerQueue = CustomerQueue::select('numberOfPersons', 'numberOfTables')->where('id', $customerOrdering->custBook_id)->first();
                    $countCurrentTables += $customerQueue->numberOfTables;
                    $countNumberOfPeople += $customerQueue->numberOfPersons;
                } else {
                    $customerReserve = CustomerReserve::select('numberOfPersons', 'numberOfTables')->where('id', $customerOrdering->custBook_id)->first();
                    $countCurrentTables += $customerReserve->numberOfTables;
                    $countNumberOfPeople += $customerReserve->numberOfPersons;
                }
            }
        }

        $countQueueTables = CustomerQueue::select('numberOfTables')
        ->where('restAcc_id', $id)
        ->where('status', 'approved')
        ->where('queueDate', $getDateToday)
        ->sum('numberOfTables');
        
        $countReserveTables = CustomerReserve::select('numberOfTables')
        ->where('restAcc_id', $id)
        ->where('status', 'approved')
        ->where('reserveDate', $getDateToday)
        ->sum('numberOfTables');

        return response()->json([
            'rName' => $account->rName,
            'rAddress' => "$account->rAddress, $account->rBranch, $account->rCity",
            'rImage' => $finalImageUrl,
            'rSchedule' => $finalSchedule,
            'rTableCapacity' => $account->rNumberOfTables,
            'rTableStatus' => $countCurrentTables,
            'rReservedTables' => $countReserveTables,
            'rNumberOfPeople' => $countNumberOfPeople,
            'rNumberOfQueues' => $countQueueTables,
            'rPosts' => $storePosts,
            'rPolicy' => $storePolicy,
            'rUnavailableD' => $storeUnavailableDate
        ]);
    }
    // -------RESTAURANT ABOUT BY ID------------ //
    public function getRestaurantsPromoDetailInfo($promoId, $restaurantId){
        $finalMechanics = array();
        $promo = Promo::where('id', $promoId)->first();
        $mechanics = PromoMechanics::where('promo_id', $promoId)->get();
        
        foreach($mechanics as $mechanic){
            array_push($finalMechanics, [
                'mechanic' => $mechanic->promoMechanic,
            ]);
        }

        $orderdate1 = explode('-', $promo->promoStartDate);
        $month1 = $this->convertMonths($orderdate1[1]);
        $year1 = $orderdate1[0];
        $day1  = $orderdate1[2];

        $orderdate2 = explode('-', $promo->promoEndDate);
        $month2 = $this->convertMonths($orderdate2[1]);
        $year2 = $orderdate2[0];
        $day2  = $orderdate2[2];
        
        return response()->json([
            'promoTitle' => $promo->promoTitle,
            'promoDescription' => $promo->promoDescription,
            'promoStartDate' => $month1." ".$day1.", ".$year1,
            'promoEndDate' => $month2." ".$day2.", ".$year2,
            'promoImage' => $this->PROMO_IMAGE_PATH."/".$restaurantId."/".$promo->promoImage,
            'promoMechanics' => $finalMechanics,
        ]);

    }
    public function getRestaurantsRewardsInfo($id){
        $finalStampTasks = array();
        $getDateToday = date("Y-m-d");
        $stamp = StampCard::where('restAcc_id', $id)->latest()->first();
        if($stamp != null){
            if($getDateToday > $stamp->stampValidity){
                $finalStampReward = null;
                $finalValidity = null;
                $finalStampTasks = null;
                $finalStampCapacity = null;
            } else {
                $stampReward = RestaurantRewardList::where('restAcc_id', $id)->where('id', $stamp->stampReward_id)->first();
                $stampTasks = StampCardTasks::where('stampCards_id', $stamp->id)->get();
                foreach($stampTasks as $stampTask){
                    $task = RestaurantTaskList::where('restAcc_id', $id)->where('id', $stampTask->restaurantTaskLists_id)->first();
                    if($task->taskCode == "SPND"){
                        array_push($finalStampTasks, [
                            'task' => "Spend ".$task->taskInput." pesos in 1 visit only"
                        ]);
                    } else if($task->taskCode == "BRNG"){
                        array_push($finalStampTasks, [
                            'task' => "Bring ".$task->taskInput." friends in our store"
                        ]);
                    } else if($task->taskCode == "ORDR"){
                        array_push($finalStampTasks, [
                            'task' => "Order ".$task->taskInput." add on/s per visit"
                        ]);
                    } else {
                        array_push($finalStampTasks, [
                            'task' => $task->taskDescription
                        ]);
                    }
                }
        
                $finalStampReward = "";
                if($stampReward->rewardCode == "DSCN"){
                    $finalStampReward = "Discount ".$stampReward->rewardInput."% in a Total Bill";
                } else if($stampReward->rewardCode == "FRPE"){
                    $finalStampReward = "Free ".$stampReward->rewardInput." person in a group";
                } else {
                    $finalStampReward = $stampReward->rewardDescription;
                }
        
                $orderdate = explode('-', $stamp->stampValidity);
                $month = $this->convertMonths($orderdate[1]);
                $year = $orderdate[0];
                $day  = $orderdate[2];
                $finalValidity = "Valid Until: ".$month." ".$day.", ".$year;
                $finalStampCapacity = $stamp->stampCapacity;
            }
        } else {
            $finalStampReward = null;
            $finalValidity = null;
            $finalStampTasks = null;
            $finalStampCapacity = null;
        }
        

        $finalPromos = array();
        $promos = Promo::select('id', 'promoTitle', 'promoImage')->where('restAcc_id', $id)->where('promoPosted', "Posted")->get();
        
        if(!$promos->isEmpty()){
            foreach ($promos as $promo){
                array_push($finalPromos,[
                    'promoId' => $promo->id,
                    'promoName' => $promo->promoTitle,
                    'promoImage' => $this->PROMO_IMAGE_PATH."/".$id."/".$promo->promoImage,
                ]);
            }
        } else {
            $finalPromos = null;
        }

        return response()->json([
            'stampReward' => $finalStampReward,
            'stampValidity' => $finalValidity,
            'stampCapacity' => $finalStampCapacity,
            'stampTasks' => $finalStampTasks,
            'promos' => $finalPromos,
        ]);
    }
    public function getRestaurantsMenuInfo($id, $cust_id){
        $orderSets = OrderSet::where('restAcc_id', $id)->where('status', "Visible")->get();
        $getDateToday = date('Y-m-d');
        $getDateTimeToday = date('Y-m-d H:i:s');

        
        $finalData = array();
        if(!$orderSets->isEmpty()){
            foreach ($orderSets as $orderSet){
                $foodSets = array();
                $orderSetFoodSets = OrderSetFoodSet::where('orderSet_id', $orderSet->id)->get();
                $orderSetFoodItems = OrderSetFoodItem::where('orderSet_id', $orderSet->id)->get();
    
                // GET FOOD SETS
                if(!$orderSetFoodSets->isEmpty()){
                    foreach($orderSetFoodSets as $orderSetFoodSet){
                        $foodItems = array();
                        $foodSetName = FoodSet::where('id', $orderSetFoodSet->foodSet_id)->where('restAcc_id', $id)->where('status', "Visible")->first();
                        if($foodSetName != null){
                            $foodSetItems = FoodSetItem::where('foodSet_id', $foodSetName->id)->get();
                            foreach($foodSetItems as $foodSetItem){
                                $foodItemName = FoodItem::where('id', $foodSetItem->foodItem_id)->where('restAcc_id', $id)->where('status', "Visible")->first();
                                if($foodItemName != null){
                                    array_push($foodItems, $foodItemName->foodItemName);
                                }
                            }
                            if($foodItems != null){
                                array_push($foodSets, [
                                    'foodSetName' => $foodSetName->foodSetName,
                                    'foodItem' => $foodItems,
                                ]);
                            }
                        }
                    }
                }
                
                // GET OTHER FOOD SET (FOOD ITEMS)
                if(!$orderSetFoodItems->isEmpty()){
                    $foodItems = array();
                    foreach($orderSetFoodItems as $orderSetFoodItem){
                        $foodItemName = FoodItem::where('id', $orderSetFoodItem->foodItem_id)->where('restAcc_id', $id)->where('status', "Visible")->first();
                        if($foodItemName != null){
                            array_push($foodItems, $foodItemName->foodItemName);
                        }
                    }
                    if($foodItems != null){
                        array_push($foodSets, [
                            'foodSetName' => "Add-on/s (Not Free)",
                            'foodItem' => $foodItems,
                        ]);
                    }
                }
    
                array_push($finalData, [
                    'orderSetName' => $orderSet->orderSetName,
                    'orderSetTagline' => $orderSet->orderSetTagline,
                    'orderSetDescription' => $orderSet->orderSetDescription,
                    'orderSetPrice' => "Price: ".$orderSet->orderSetPrice,
                    'orderSetImage' => $this->ORDER_SET_IMAGE_PATH."/".$id."/".$orderSet->orderSetImage,
                    'foodSet' => $foodSets,
                ]);
            }
        }

        // CHECKING IF CAN QUEUE OR BOOK
        /*
            0. check kung verified
            1. check kung nakablock
            2. check kung nakaqueue /  reserve
            3. check kung may qr access
            4. check schedule 
        */

        $description = "";
        $status = "";

        $customerQueue = CustomerQueue::where('customer_id', $cust_id)
        ->where('queueDate', $getDateToday)
        ->where('status', '!=', 'cancelled')
        ->where('status', '!=', 'declined')
        ->where('status', '!=', 'noShow')
        ->where('status', '!=', 'runaway')
        ->where('status', '!=', 'completed')
        ->orderBy('created_at', 'DESC')
        ->first();

        $customerReserve = CustomerReserve::where('customer_id', $cust_id)
        ->where('status', '!=', 'cancelled')
        ->where('status', '!=', 'declined')
        ->where('status', '!=', 'noShow')
        ->where('status', '!=', 'runaway')
        ->where('status', '!=', 'completed')
        ->orderBy('created_at', 'DESC')
        ->first();

        $custMainOff = CustOffenseMain::where('customer_id', $cust_id)
        ->where('restAcc_id', $id)
        ->orderBy('created_at', 'DESC')
        ->first();

        $customerQrAccess = CustomerQrAccess::where('subCust_id', $cust_id)
        ->orderBy('created_at', 'DESC')
        ->where('status', 'pending')
        ->orWhere('status', 'approved')
        ->first();

        $customer = CustomerAccount::where('id', $cust_id)->first();

        if($customer->emailAddressVerified == "No"){
            $description = "You need to verify your email address first.";
            $status = "onGoing";
        } else if ($customerQueue != null){
            $description = "You cannot book as of now because you are currently queue.";
            $status = "onGoing";
        } else if ($customerReserve != null){
            $description = "You cannot book as of now because you are currently reserve.";
            $status = "onGoing";
        } else if ($customerQrAccess != null) {
            $customerOrdering = CustomerOrdering::where('id', $customerQrAccess->custOrdering_id)->where('status', "eating")->first();
            if($customerQrAccess->status == "pending"){
                if($customerOrdering != null){
                    $description = "You cannot book as of now because you currently requesting access to your friends Ordering QR.";
                    $status = "onGoing";
                }
            } else {
                if($customerOrdering != null){
                    $description = "You cannot book as of now because you currently have an access to your friend that are currently booked.";
                    $status = "onGoing";
                }
            }
        } else if ($custMainOff != null){
            if($custMainOff->offenseDaysBlock == "Permanent" && $custMainOff->offenseValidity != null){
                $description = "You cannot book to this restaurant because you are currently blocked";
                $status = "onGoing";
            } else {
                if(strtotime($custMainOff->offenseValidity) >= strtotime($getDateTimeToday)){
                    $description = "You cannot book to this restaurant because you are currently blocked";
                    $status = "onGoing";
                }
            }
        } else {}

        if($status == ""){
            $rSchedule = $this->checkTodaySched($id);
            if($rSchedule == "Closed Now"){
                $description = "You cannot queue as of now wait till the restaurant is open.";
                $status = "closed";
            }
        }

        return response()->json([
            'description' => $description,
            'status' => $status,
            'menu' => $finalData,
        ]);
    }
    public function getListOfPromos(){
        $finalData = array();

        $promos = Promo::orderBy('id', 'DESC')->where('promoPosted', "Posted")->get();

        if(!$promos->isEmpty()){
            foreach ($promos as $promo){
                $restaurantInfo = RestaurantAccount::select('id', 'rLogo', 'rAddress', 'rBranch', 'rCity')->where('id', $promo->restAcc_id)->where('status', "Published")->first();
                $finalImageUrl = "";
                if ($restaurantInfo->rLogo == ""){
                    $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                } else {
                    $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurantInfo->id.'/'. $restaurantInfo->rLogo;
                }
                
    
                $orderdate1 = explode('-', $promo->promoStartDate);
                $month1 = $this->convertMonths($orderdate1[1]);
                $year1 = $orderdate1[0];
                $day1  = $orderdate1[2];
    
                $orderdate2 = explode('-', $promo->promoEndDate);
                $month2 = $this->convertMonths($orderdate2[1]);
                $year2 = $orderdate2[0];
                $day2  = $orderdate2[2];
            
                array_push($finalData, [
                    'restaurantImage' => $finalImageUrl,
                    'restaurantAddress' => "$restaurantInfo->rAddress, $restaurantInfo->rBranch, $restaurantInfo->rCity",
                    'restaurantId' => $restaurantInfo->id,
                    'promoId' => $promo->id,
                    'promoTitle' => $promo->promoTitle,
                    'promoStartDate' => $month1." ".$day1.", ".$year1,
                    'promoEndDate' => $month2." ".$day2.", ".$year2,
                ]);
            }
        } else {
            $finalData = null;
        }
        return response()->json($finalData);
    }
    public function getRestaurantChooseOrderSet($id, $custId){
        $finalData = array();
        $finalRewardStatus = "";
        $finalRewardType = "";
        $finalRewardInput = 0;

        $restaurant = RestaurantAccount::select('rName', 'rTimeLimit', 'rCapacityPerTable')->where('id', $id)->first();
        $orderSets = OrderSet::where('restAcc_id', $id)->where('status', "Visible")->get();
        $getStamp = StampCard::select('stampValidity', 'stampReward_id')->where('restAcc_id', $id)->first();

        if($getStamp == null){
            $finalRewardStatus = "Incomplete";
            $finalRewardType = "";
            $finalRewardInput = 0;
        } else {
            $getReward = RestaurantRewardList::where('restAcc_id', $id)->where('id', $getStamp->stampReward_id)->first();

            $checkIfComplete = CustomerStampCard::where('customer_id', $custId)
                            ->where('restAcc_id', $id)
                            ->where('stampValidity', $getStamp->stampValidity)
                            ->where('claimed', "No")
                            ->where('status', "Complete")
                            ->first();
            if($checkIfComplete == null){
                $finalRewardStatus = "Incomplete";
                $finalRewardType = "";
                $finalRewardInput = 0;
            } else {
                $finalRewardStatus = "Complete";
                $finalRewardType = $getReward->rewardCode;
                $finalRewardInput = $getReward->rewardInput;
            }
        }
        
        if(!$orderSets->isEmpty()){
            foreach ($orderSets as $orderSet){
                array_push($finalData, [
                    'orderSetId' => $orderSet->id,
                    'orderSetPrice' => $orderSet->orderSetPrice,
                    'orderSetName' => $orderSet->orderSetName,
                    'orderSetTagline' => $orderSet->orderSetTagline,
                    'orderSetImage' => $this->ORDER_SET_IMAGE_PATH."/".$id."/".$orderSet->orderSetImage,
                    'orderSetAvailable' => $orderSet->available,
                ]);
            }
        }
        
        return response()->json([
            'restaurantName' => $restaurant->rName,
            'rTimeLimit' => $restaurant->rTimeLimit,
            'rCapacityPerTable' => $restaurant->rCapacityPerTable,
            'rewardStatus' => $finalRewardStatus,
            'rewardType' => $finalRewardType,
            'rewardInput' => $finalRewardInput,
            'orderSets' => $finalData,
        ]);
    }
    public function getReservationDateAndTimeForm($rest_id){
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
        $unavailableDates = UnavailableDate::where('restAcc_id', $rest_id)->get();
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
        $restaurantStoreHours = StoreHour::where('restAcc_id', $rest_id)->get();
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

        return response()->json([
            'modDaysBFRAD' => $modDaysBFRAD2,
            'modDatesBFRAD' => $modDatesBFRAD2,
            'storeTrueDates' => $storeTrueDates2,
            'modTimeBFRAT' => $modTimeBFRAT2,
        ]);
    }
    

















    // -------LOGIN CUSTOMER------------ //
    public function loginCustomer(Request $request){
        $request->validate([
            'emailAddress' => 'required',
            'password' => 'required',
            'deviceToken' => 'required',
        ]);
        $account = CustomerAccount::where('emailAddress', $request->emailAddress)->first();
        if(empty($account)){
            return response()->json([
                'id' => null,
                'status' => "Account does not exist",
            ]);
        } else {
            if(Hash::check($request->password, $account->password)){
                CustomerAccount::where('id', $account->id)
                ->update([
                    'deviceToken' => $request->deviceToken
                ]);
                return response()->json([
                    'id' => $account->id,
                    'status' => "Login Successfully",
                ]);
            } else {
                return response()->json([
                    'id' => null,
                    'status' => "Account does not exist",
                ]);
            }
        }
    }
    // -------REGISTER CUSTOMER------------ //
    public function registerCustomer(Request $request){
        $request->validate([
            'name' => 'required',
            'emailAddress' => 'required',
            'contactNumber' => 'required',
            'password' => 'required',
            'deviceToken' => 'required',
        ]);

        $existingAccount = CustomerAccount::where('emailAddress', $request->emailAddress)->first();
        
        if($existingAccount != null){
            return response()->json([
                'id' => null,
                'status' => "Email Address is already existing",
            ]);
        } else {
            
            // $details = [
            //     'applicantName' => $request->name,
            // ];

            // Mail::to($request->emailAddress)->send(new RestaurantFormAppreciation($details));
            

            $customer = CustomerAccount::create([
                'name' => $request->name,
                'emailAddress' => $request->emailAddress,
                'emailAddressVerified' => "No",
                'contactNumber' => $request->contactNumber,
                'contactNumberVerified' => "No",
                'password' => Hash::make($request->password),
                'deviceToken' => $request->deviceToken,
            ]);
            
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
            
            $notif = CustomerNotification::create([
                'customer_id' => $customer->id,
                'restAcc_id' => 0,
                'notificationType' => "New Account",
                'notificationTitle' => "Welcome to Samquicksal!",
                'notificationDescription' => "Samquicksal",
                'notificationStatus' => "Unread",
            ]);

            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/samquicksalLogo.png';
            
            if($customer != null){
                $to = $customer->deviceToken;
                $notification = array(
                    'title' => "Hi $customer->name, Welcome to Samquicksal!",
                    'body' => "Are you ready to Dine-in? You can do it anytime! Just find the restaurant that you want to eat then your’re good to go! Don’t forget to give them a rating! Thank you and enjoy using our app.",
                );
                $data = array(
                    'notificationType' => "New Account",
                    'notificationId' => $notif->id,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }

            return response()->json([
                'id' => $customer->id,
                'status' => "Registered Successfully",
            ]);
        }
    }
    // -------GET CUSTOMER HOMEPAGE INFO------------ //
    public function getCustomerHomepageInfo($id){
        $getDateToday = date("Y-m-d");
        $account = CustomerAccount::select('name', 'emailAddress', 'profileImage')->where('id', $id)->first();
        
        $customerQueue = CustomerQueue::where('customer_id', $id)
        ->where('queueDate', $getDateToday)
        ->where('status', '!=', 'cancelled')
        ->where('status', '!=', 'declined')
        ->where('status', '!=', 'noShow')
        ->where('status', '!=', 'runaway')
        ->orderBy('created_at', 'DESC')
        ->first();

        $customerReserve = CustomerReserve::where('customer_id', $id)
        ->where('status', '!=', 'cancelled')
        ->where('status', '!=', 'declined')
        ->where('status', '!=', 'noShow')
        ->where('status', '!=', 'runaway')
        ->orderBy('created_at', 'DESC')
        ->first();

        if($customerQueue != null && $customerQueue->checkoutStatus != "completed"){
            if($customerQueue->checkoutStatus == null){
                if($customerQueue->status == "eating"){
                    $status = "eating";
                } else if ($customerQueue->status == "pending" 
                            || $customerQueue->status == "approved" 
                            || $customerQueue->status == "validation"
                            || $customerQueue->status == "tableSettingUp") {
                    $status = "onGoing";
                } else {
                    $status = "none";
                }
            } else {
                if($customerQueue->checkoutStatus == "gcashCheckout" || $customerQueue->checkoutStatus == "gcashIncorretFormat"){
                    $status = "gcashCheckout";
                } else if($customerQueue->checkoutStatus == "gcashCheckoutValidation" 
                || $customerQueue->checkoutStatus == "cashCheckoutValidation" || $customerQueue->checkoutStatus == "gcashInsufficientAmount"){
                    $status = "checkoutValidation";
                } else if($customerQueue->checkoutStatus == "customerFeedback"){
                    $status = "customerFeedback";
                } else {
                    $status = "none";
                }
            }
            
        } else if ($customerReserve != null && $customerReserve->checkoutStatus != "completed") { 
            if($customerReserve->checkoutStatus == null){
                if($customerReserve->status == "eating"){
                    $status = "eating";
                } else if ($customerReserve->status == "pending" 
                            || $customerReserve->status == "approved" 
                            || $customerReserve->status == "validation"
                            || $customerReserve->status == "tableSettingUp") {
                    $status = "onGoing";
                } else {
                    $status = "none";
                }
            } else {
                if($customerReserve->checkoutStatus == "gcashCheckout" || $customerReserve->checkoutStatus == "gcashIncorretFormat"){
                    $status = "gcashCheckout";
                } else if($customerReserve->checkoutStatus == "gcashCheckoutValidation" 
                || $customerReserve->checkoutStatus == "cashCheckoutValidation" || $customerReserve->checkoutStatus == "gcashInsufficientAmount"){
                    $status = "checkoutValidation";
                } else if($customerReserve->checkoutStatus == "customerFeedback"){
                    $status = "customerFeedback";
                } else {
                    $status = "none";
                }
            }
        } else {
            $status = "none";
        }

        $finalImageUrl = "";
        if($account->profileImage == null){
            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/user-default.png';
        } else {
            $finalImageUrl = $this->CUSTOMER_IMAGE_PATH.'/'.$id.'/'. $account->profileImage;
        }

        return response()->json([
            'name' => $account->name,
            'emailAddress' => $account->emailAddress,
            'profileImage' => $finalImageUrl,
            'status' => $status,
        ]);
    }
    public function getCustomerAccountInfo($id){
        $account = CustomerAccount::where('id', $id)->first();

        $finalImageUrl = "";
        if($account->profileImage == ""){
            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/user-default.png';
        } else {
            $finalImageUrl = $this->CUSTOMER_IMAGE_PATH.'/'.$id.'/'. $account->profileImage;
        }

        return response()->json([
            'name' => $account->name,
            'emailAddress' => $account->emailAddress,
            'emailAddressVerified' => $account->emailAddressVerified,
            'contactNumber' => $account->contactNumber,
            'contactNumberVerified' => $account->contactNumberVerified,
            'password' => $account->password,
            'profileImage' => $finalImageUrl,
        ]);

    }
    public function submitQueueForm(Request $request){
        $restaurant = RestaurantAccount::select('id', 'rName', 'rBranch', 'rAddress', 'rCity', 'rLogo')->where('id', $request->restAcc_id)->first();
        $customer = CustomerAccount::select('deviceToken')->where('id', $request->customer_id)->first();
        $orderSet = OrderSet::where('id', $request->orderSet_id)->first();

        CustomerQueue::create([
            'customer_id' => $request->customer_id,
            'restAcc_id' => $request->restAcc_id,
            'orderSet_id' => $request->orderSet_id,
            'orderSetName' => $orderSet->orderSetName,
            'orderSetPrice' => $orderSet->orderSetPrice,
            'status' => "pending",
            'numberOfPersons' => $request->numberOfPersons,
            'numberOfTables' => $request->numberOfTables,
            'hoursOfStay' => $request->hoursOfStay,
            'numberOfChildren' => $request->numberOfChildren,
            'numberOfPwd' => $request->numberOfPwd,
            'totalPwdChild' => $request->totalPwdChild,
            'notes' => $request->notes,
            'rewardStatus' => $request->rewardStatus,
            'rewardType' => $request->rewardType,
            'rewardInput' => $request->rewardInput,
            'rewardClaimed' => $request->rewardClaimed,
            'totalPrice' => $request->totalPrice,
            'childrenDiscount' => 0,
            'additionalDiscount' => 0.00,
            'promoDiscount' => 0.00,
            'offenseCharges' => 0.00,
            'queueDate' => date("Y-m-d"),
        ]);

        $notif = CustomerNotification::create([
            'customer_id' => $request->customer_id,
            'restAcc_id' => $request->restAcc_id,
            'notificationType' => "Pending",
            'notificationTitle' => "You have booked at $restaurant->rName $restaurant->rBranch",
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
                'body' => "Thank your for booking with us, please wait for your form to be validated!",
            );
            $data = array(
                'notificationType' => "Pending",
                'notificationId' => $notif->id,
                'notificationRLogo' => $finalImageUrl,
            );
            $this->sendFirebaseNotification($to, $notification, $data);
        }

        return response()->json([
            'status' => "Success",
        ]);
    }
    public function submitReserveForm(Request $request){
        $restaurant = RestaurantAccount::select('id', 'rName', 'rBranch', 'rAddress', 'rCity', 'rLogo')->where('id', $request->restAcc_id)->first();
        $customer = CustomerAccount::select('deviceToken')->where('id', $request->customer_id)->first();
        $orderSet = OrderSet::where('id', $request->orderSet_id)->first();

        CustomerReserve::create([
            'customer_id' => $request->customer_id,
            'restAcc_id' => $request->restAcc_id,
            'orderSet_id' => $request->orderSet_id,
            'orderSetName' => $orderSet->orderSetName,
            'orderSetPrice' => $orderSet->orderSetPrice,
            'status' => "pending",
            'reserveDate' => $request->date,
            'reserveTime' => $request->time,
            'numberOfPersons' => $request->numberOfPersons,
            'numberOfTables' => $request->numberOfTables,
            'hoursOfStay' => $request->hoursOfStay,
            'numberOfChildren' => $request->numberOfChildren,
            'numberOfPwd' => $request->numberOfPwd,
            'totalPwdChild' => $request->totalPwdChild,
            'notes' => $request->notes,
            'rewardStatus' => $request->rewardStatus,
            'rewardType' => $request->rewardType,
            'rewardInput' => $request->rewardInput,
            'rewardClaimed' => $request->rewardClaimed,
            'totalPrice' => $request->totalPrice,
            'childrenDiscount' => 0,
            'additionalDiscount' => 0.00,
            'promoDiscount' => 0.00,
            'offenseCharges' => 0.00,
        ]);

        $notif = CustomerNotification::create([
            'customer_id' => $request->customer_id,
            'restAcc_id' => $request->restAcc_id,
            'notificationType' => "Pending",
            'notificationTitle' => "You have booked at $restaurant->rName $restaurant->rBranch",
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
                'body' => "Thank your for booking with us, please wait for your form to be validated!",
            );
            $data = array(
                'notificationType' => "Pending",
                'notificationId' => $notif->id,
                'notificationRLogo' => $finalImageUrl,
            );
            $this->sendFirebaseNotification($to, $notification, $data);
        }

        return response()->json([
            'status' => "Success",
        ]);
    }
    public function getCustomerNotification($id){
        $notifications = CustomerNotification::where('customer_id', $id)->orderBy('id', 'DESC')->get();
        $finalData = array();

        foreach($notifications as $notification){
            $finalImageUrl = "";
            $currentTime = Carbon::now();
            

            if($notification->restAcc_id == 0){
                $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/samquicksalLogo.png';
            } else {
                $getRLogo = RestaurantAccount::select('rLogo')->where('id', $notification->restAcc_id)->first();

                if($getRLogo->rLogo == null){
                    $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                } else {
                    $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$notification->restAcc_id.'/'.$getRLogo->rLogo;
                }
            }

            $rAppstartTime = Carbon::parse($notification->created_at);
            $rAppDiffSeconds = $currentTime->diffInSeconds($rAppstartTime);
            $rAppDiffMinutes = $currentTime->diffInMinutes($rAppstartTime);
            $rAppDiffHours = $currentTime->diffInHours($rAppstartTime);
            $rAppDiffDays = $currentTime->diffInDays($rAppstartTime);

            if($rAppDiffDays > 0){
                if ($rAppDiffDays == 1){
                    $rAppDiffTime = "1 day ago";
                } else {
                    $rAppDiffTime = "$rAppDiffDays days ago";
                }
            } else if ($rAppDiffHours > 0){
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

            $clickable = "Yes";

            //Validation
            //validationDateTime
            //validation
            if($notification->notificationType == "Validation"){
                $customerQueue = CustomerQueue::select('status')->where('customer_id', $notification->customer_id)
                ->where('restAcc_id', $notification->restAcc_id)
                ->where('validationDateTime', $notification->created_at)
                ->first();
                
                $customerReserve = CustomerReserve::select('status')->where('customer_id', $notification->customer_id)
                ->where('restAcc_id', $notification->restAcc_id)
                ->where('validationDateTime', $notification->created_at)
                ->first();

                if($customerQueue != null){
                    if($customerQueue->status != "validation"){
                        $clickable = "No";
                    }
                } else if ($customerReserve != null){
                    if($customerReserve->status != "validation"){
                        $clickable = "No";
                    }
                } else {}
            }

            //Table Setting Up
            //tableSettingDateTime
            //tableSettingUp
            if($notification->notificationType == "Table Setting Up"){
                $customerQueue = CustomerQueue::select('status')->where('customer_id', $notification->customer_id)
                ->where('restAcc_id', $notification->restAcc_id)
                ->where('tableSettingDateTime', $notification->created_at)
                ->first();

                $customerReserve = CustomerReserve::select('status')->where('customer_id', $notification->customer_id)
                ->where('restAcc_id', $notification->restAcc_id)
                ->where('tableSettingDateTime', $notification->created_at)
                ->first();

                if($customerQueue != null){
                    if($customerQueue->status != "tableSettingUp"){
                        $clickable = "No";
                    }
                } else if ($customerReserve != null){
                    if($customerReserve->status != "tableSettingUp"){
                        $clickable = "No";
                    }
                } else {}
            }

            
            //Table is Ready
            //eatingDateTime
            //eating
            if($notification->notificationType == "Table is Ready"){
                $customerQueue = CustomerQueue::select('status')->where('customer_id', $notification->customer_id)
                ->where('restAcc_id', $notification->restAcc_id)
                ->where('eatingDateTime', $notification->created_at)
                ->first();
                
                
                $customerReserve = CustomerReserve::select('status')->where('customer_id', $notification->customer_id)
                ->where('restAcc_id', $notification->restAcc_id)
                ->where('eatingDateTime', $notification->created_at)
                ->first();

                if($customerQueue != null){
                    if($customerQueue->status != "eating"){
                        $clickable = "No";
                    }
                } else if ($customerReserve != null){
                    if($customerReserve->status != "eating"){
                        $clickable = "No";
                    }
                } else {}
            }

            array_push($finalData,[
                'id' => $notification->id,
                'notificationType' => $notification->notificationType,
                'notificationImage' => $finalImageUrl,
                'notificationTitle' => $notification->notificationTitle,
                'notificationDescription' => $notification->notificationDescription,
                'notificationStatus' => $notification->notificationStatus,
                'notificationTime' => $rAppDiffTime,
                'clickable' => $clickable,
            ]);
        }

        return response()->json($finalData);
    }
    public function getCustomerLiveStatus($id){
        $getDateToday = date("Y-m-d");
        $currentTime = Carbon::now();
        $customerQueue = CustomerQueue::where('customer_id', $id)
        ->where('queueDate', $getDateToday)
        ->where('status', '!=', 'cancelled')
        ->where('status', '!=', 'declined')
        ->where('status', '!=', 'noShow')
        ->where('status', '!=', 'runaway')
        ->where('status', '!=', 'completed')
        ->orderBy('created_at', 'DESC')
        ->first();


        $customerReserve = CustomerReserve::where('customer_id', $id)
        ->where('status', '!=', 'cancelled')
        ->where('status', '!=', 'declined')
        ->where('status', '!=', 'noShow')
        ->where('status', '!=', 'runaway')
        ->where('status', '!=', 'completed')
        ->orderBy('created_at', 'DESC')
        ->first();

        if($customerQueue != null){
            if($customerQueue->status == "pending"){
                $rAppstartTime = Carbon::parse($customerQueue->created_at);
                $rAppDiffSeconds = $currentTime->diffInSeconds($rAppstartTime);
                $rAppDiffMinutes = $currentTime->diffInMinutes($rAppstartTime);

                $rAppDiffMinutes = 15 - $rAppDiffMinutes;
                $rAppDiffSeconds = (15 * 60) - $rAppDiffSeconds;

                $liveStatusDescription = "Cancellation is void when time exceeds 15 minutes";
                if ($rAppDiffMinutes > 1){
                    $rAppDiffTime = $rAppDiffMinutes;
                    $rAppDiffTimeLabel = "minutes";
                } else if ($rAppDiffMinutes == 1) {
                    if ($rAppDiffSeconds == 1){
                        $rAppDiffTime = 1;
                        $rAppDiffTimeLabel = "second";
                    } else {
                        $rAppDiffTime = $rAppDiffSeconds;
                        $rAppDiffTimeLabel = "seconds";
                    }
                } else {
                    $rAppDiffTime = 0;
                    $rAppDiffTimeLabel = "second";
                    $liveStatusDescription = "You can no longer cancel. Please wait for the approval of your queueing.";
                }
                return response()->json([
                    'liveStatusHeader' => "Cancellation Time",
                    'liveStatusBody' => null,
                    'liveStatusNumber' => $rAppDiffTime,
                    'liveStatusNumberDesc' => $rAppDiffTimeLabel,
                    'liveStatusDescription' => $liveStatusDescription,
                    'liveStatusBookType' => "queue",
                ]);
            } else if ($customerQueue->status == "approved"){
                $countCustomersApproved = CustomerQueue::where('status', "approved")
                
                ->where('queueDate', $getDateToday)
                ->orderBy('totalPwdChild', 'DESC')
                ->orderBy('approvedDateTime', 'ASC')
                ->get();

                $finalQueueNumber = 0;
                $queueNumber = 0;
                foreach ($countCustomersApproved as $countCustomerApproved){
                    $queueNumber++;
                    if($countCustomerApproved->customer_id == $id){
                        $finalQueueNumber = $queueNumber;

                        $endTime = Carbon::now();
                        $rAppstartTime = Carbon::parse($countCustomerApproved->approvedDateTime);
                        $rAppDiffMinutes = $endTime->diffInMinutes($rAppstartTime);
        
                        if($rAppDiffMinutes >= 5) {
                            $cancellable = "yes";
                            $desc = "Hey you’ve been in queue for a while, there is no table available yet. If you’d like to look for another restaurant you may do so.";
                        } else {
                            $cancellable = "no";
                            $desc = "The queue number will serve as your number in the line. This will update from time to time. Your queue number may change due to prioritize PWD/Senior Citizen and Children below 7 years old.";
                        }
                    }
                }
                
                return response()->json([
                    'liveStatusHeader' => "Queue Number",
                    'liveStatusBody' => $cancellable,
                    'liveStatusNumber' => $finalQueueNumber,
                    'liveStatusNumberDesc' => null,
                    'liveStatusDescription' => $desc,
                    'liveStatusBookType' => "queue",
                ]);
            } else if ($customerQueue->status == "validation") {
                $rAppstartTime = Carbon::parse($customerQueue->validationDateTime);
                $rAppDiffSeconds = $currentTime->diffInSeconds($rAppstartTime);
                $rAppDiffMinutes = $currentTime->diffInMinutes($rAppstartTime);

                $rAppDiffMinutes = 15 - $rAppDiffMinutes;
                $rAppDiffSeconds = (15 * 60) - $rAppDiffSeconds;

                $liveStatusDescription = "Please be at the front desk within the 15 minutes time limit to confirm and validate your booking. If you do not show up within the given time, your book will be void.";

                if ($rAppDiffMinutes > 1){
                    $rAppDiffTime = $rAppDiffMinutes;
                    $rAppDiffTimeLabel = "minutes";
                } else if ($rAppDiffMinutes == 1) {
                    if ($rAppDiffSeconds == 1){
                        $rAppDiffTime = 1;
                        $rAppDiffTimeLabel = "second";
                    } else {
                        $rAppDiffTime = $rAppDiffSeconds;
                        $rAppDiffTimeLabel = "seconds";
                    }
                } else {
                    $rAppDiffTime = 0;
                    $rAppDiffTimeLabel = "second";
                }
                return response()->json([
                    'liveStatusHeader' => "Confirmation Time",
                    'liveStatusBody' => null,
                    'liveStatusNumber' => $rAppDiffTime,
                    'liveStatusNumberDesc' => $rAppDiffTimeLabel,
                    'liveStatusDescription' => $liveStatusDescription,
                    'liveStatusBookType' => "queue",
                ]);
            } else if ($customerQueue->status == "tableSettingUp") {
                return response()->json([
                    'liveStatusHeader' => "Note",
                    'liveStatusBody' => "Your Table is Now Setting Up",
                    'liveStatusNumber' => null,
                    'liveStatusNumberDesc' => null,
                    'liveStatusDescription' => "Your table is now setting up, please wait until you can access the ordering part",
                    'liveStatusBookType' => "queue",
                ]);
            } else {
                return response()->json([
                    'liveStatusHeader' => null,
                    'liveStatusBody' => null,
                    'liveStatusNumber' => null,
                    'liveStatusNumberDesc' => null,
                    'liveStatusDescription' => null,
                    'liveStatusBookType' => null,
                ]);
            }
        } else if ($customerReserve != null) { 
            if($customerReserve->status == "pending"){
                $rAppstartTime = Carbon::parse($customerReserve->created_at);
                $rAppDiffSeconds = $currentTime->diffInSeconds($rAppstartTime);
                $rAppDiffMinutes = $currentTime->diffInMinutes($rAppstartTime);

                $rAppDiffMinutes = 15 - $rAppDiffMinutes;
                $rAppDiffSeconds = (15 * 60) - $rAppDiffSeconds;

                $liveStatusDescription = "Cancellation is void when time exceeds 15 minutes";
                if ($rAppDiffMinutes > 1){
                    $rAppDiffTime = $rAppDiffMinutes;
                    $rAppDiffTimeLabel = "minutes";
                } else if ($rAppDiffMinutes == 1) {
                    if ($rAppDiffSeconds == 1){
                        $rAppDiffTime = 1;
                        $rAppDiffTimeLabel = "second";
                    } else {
                        $rAppDiffTime = $rAppDiffSeconds;
                        $rAppDiffTimeLabel = "seconds";
                    }
                } else {
                    $rAppDiffTime = 0;
                    $rAppDiffTimeLabel = "second";
                    $liveStatusDescription = "You can no longer cancel. Please wait for the approval of your reservation.";
                }
                return response()->json([
                    'liveStatusHeader' => "Cancellation Time",
                    'liveStatusBody' => null,
                    'liveStatusNumber' => $rAppDiffTime,
                    'liveStatusNumberDesc' => $rAppDiffTimeLabel,
                    'liveStatusDescription' => $liveStatusDescription,
                    'liveStatusBookType' => "reserve",
                ]);
            } else if ($customerReserve->status == "approved"){

                $dateTime = date("Y-m-d H:i:s", strtotime("$customerReserve->reserveDate $customerReserve->reserveTime"));
                $endTime = Carbon::parse($dateTime);
                $currentTime = Carbon::now();

                $rAppDiffSeconds = $endTime->diffInSeconds($currentTime);
                $rAppDiffMinutes = $endTime->diffInMinutes($currentTime);
                $rAppDiffHours = $endTime->diffInHours($currentTime);
                $rAppDiffDays = $endTime->diffInDays($currentTime);

                $cancellable = "no";
                $desc = "Please be informed that your reservation is near.";
                if($rAppDiffDays > 0){
                    $cancellable = "yes";
                    $desc = "You may no longer cancel a day before your reservation date.";
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
                
                return response()->json([
                    'liveStatusHeader' => "Reservation Status",
                    'liveStatusBody' => $cancellable,
                    'liveStatusNumber' => $rAppDiffTime,
                    'liveStatusNumberDesc' => $rAppDiffTimeLabel,
                    'liveStatusDescription' => $desc,
                    'liveStatusBookType' => "reserve",
                ]);

            } else if ($customerReserve->status == "validation") {
                $rAppstartTime = Carbon::parse($customerReserve->validationDateTime);
                $rAppDiffSeconds = $currentTime->diffInSeconds($rAppstartTime);
                $rAppDiffMinutes = $currentTime->diffInMinutes($rAppstartTime);

                $rAppDiffMinutes = 15 - $rAppDiffMinutes;
                $rAppDiffSeconds = (15 * 60) - $rAppDiffSeconds;

                $liveStatusDescription = "Please be at the front desk within the 15 minutes time limit to confirm and validate your booking. If you do not show up within the given time, your book will be void.";

                if ($rAppDiffMinutes > 1){
                    $rAppDiffTime = $rAppDiffMinutes;
                    $rAppDiffTimeLabel = "minutes";
                } else if ($rAppDiffMinutes == 1) {
                    if ($rAppDiffSeconds == 1){
                        $rAppDiffTime = 1;
                        $rAppDiffTimeLabel = "second";
                    } else {
                        $rAppDiffTime = $rAppDiffSeconds;
                        $rAppDiffTimeLabel = "seconds";
                    }
                } else {
                    $rAppDiffTime = 0;
                    $rAppDiffTimeLabel = "second";
                }
                return response()->json([
                    'liveStatusHeader' => "Confirmation Time",
                    'liveStatusBody' => null,
                    'liveStatusNumber' => $rAppDiffTime,
                    'liveStatusNumberDesc' => $rAppDiffTimeLabel,
                    'liveStatusDescription' => $liveStatusDescription,
                    'liveStatusBookType' => "reserve",
                ]);
            } else if ($customerReserve->status == "tableSettingUp") {
                return response()->json([
                    'liveStatusHeader' => "Note",
                    'liveStatusBody' => "Your Table is Now Setting Up",
                    'liveStatusNumber' => null,
                    'liveStatusNumberDesc' => null,
                    'liveStatusDescription' => "Your table is now setting up, please wait until you can access the ordering part",
                    'liveStatusBookType' => "reserve",
                ]);
            } else {
                return response()->json([
                    'liveStatusHeader' => null,
                    'liveStatusBody' => null,
                    'liveStatusNumber' => null,
                    'liveStatusNumberDesc' => null,
                    'liveStatusDescription' => null,
                    'liveStatusBookType' => null,
                ]);
            }
        } else {
            return response()->json([
                'liveStatusHeader' => null,
                'liveStatusBody' => null,
                'liveStatusNumber' => null,
                'liveStatusNumberDesc' => null,
                'liveStatusDescription' => null,
                'liveStatusBookType' => null,
            ]);
        }

    }
    public function cancelCustomerBooking($id){
        $getDateToday = date("Y-m-d");
        $getDateTimeToday = date('Y-m-d H:i:s');
        $customerQueue = CustomerQueue::where('customer_id', $id)
        ->where('queueDate', $getDateToday)
        ->where('status', 'pending')
        ->orWhere('status', 'approved')
        ->orderBy('created_at', 'DESC')
        ->first();

        $customerReserve = CustomerReserve::where('customer_id', $id)
        ->where('status', 'pending')
        ->orWhere('status', 'approved')
        ->orderBy('created_at', 'DESC')
        ->first();

        if($customerQueue != null){
            $restaurant = RestaurantAccount::select('id', 'rAddress', 'rCity', 'rLogo', 'rName', 'rBranch')->where('id', $customerQueue->restAcc_id)->first();
            $customer = CustomerAccount::select('deviceToken')->where('id', $id)->first();
    
            CustomerQueue::where('id', $customerQueue->id)
            ->update([
                'status' => 'cancelled',
                'cancelDateTime' => date('Y-m-d H:i:s'),
            ]);

            // CHECK IF THERES LATEST CANCEL OFFENSE
            $mainOffense = CustOffenseMain::where('customer_id', $customerQueue->customer_id)
            ->where('restAcc_id', $restaurant->id)
            ->where('offenseType', "cancellation")
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
                                            'offenseType' => "cancellation",
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
                                            'offenseType' => "cancellation",
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
                                        'offenseType' => "cancellation",
                                        'totalOffense' => 1,
                                        'offenseCapacity' => $restOffense->noOfCancellation,
                                        'offenseDaysBlock' => $restOffense->blockDays,
                                    ]);
                                    $custMainOffenseId = $createMainOffense->id;
                                }
                                
                                //FOR BLOCKED NOTIFICATION
                                $customerId = $customerQueue->customer_id;
                                $restAccId = $customerQueue->restAcc_id;
                                $notifTitle = "You have been blocked due to number of cancellation";
                                $notifDesc = "$restaurant->rAddress, $restaurant->rCity";
                                $finalImageUrl = "";
                                if ($restaurant->rLogo == ""){
                                    $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                                } else {
                                    $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                                }
                                $custDeviceToken  = $customer->deviceToken;
                                $notif2Title = "$restaurant->rAddress, $restaurant->rCity";
                                $notif2Desc = "You have been blocked due to number of cancellation";
                                $this->createBlockNotif($customerId, $restAccId, $notifTitle, $notifDesc, $custDeviceToken, $notif2Title, $notif2Desc, $finalImageUrl);
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
                            
                            //FOR BLOCKED NOTIFICATION
                            $customerId = $customerQueue->customer_id;
                            $restAccId = $customerQueue->restAcc_id;
                            $notifTitle = "You have been blocked due to number of cancellation";
                            $notifDesc = "$restaurant->rAddress, $restaurant->rCity";
                            $finalImageUrl = "";
                            if ($restaurant->rLogo == ""){
                                $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                            } else {
                                $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                            }
                            $custDeviceToken  = $customer->deviceToken;
                            $notif2Title = "$restaurant->rAddress, $restaurant->rCity";
                            $notif2Desc = "You have been blocked due to number of cancellation";
                            $this->createBlockNotif($customerId, $restAccId, $notifTitle, $notifDesc, $custDeviceToken, $notif2Title, $notif2Desc, $finalImageUrl);
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
                                'offenseType' => "cancellation",
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
                                'offenseType' => "cancellation",
                                'totalOffense' => 1,
                                'offenseCapacity' => $restOffense->noOfCancellation,
                                'offenseDaysBlock' => $restOffense->blockDays,
                                'offenseValidity' => $finalOffenseValidity,
                            ]);
                            $custMainOffenseId = $createMainOffense->id;
                        }
                        
                        //FOR BLOCKED NOTIFICATION
                        $customerId = $customerQueue->customer_id;
                        $restAccId = $customerQueue->restAcc_id;
                        $notifTitle = "You have been blocked due to number of cancellation";
                        $notifDesc = "$restaurant->rAddress, $restaurant->rCity";
                        $finalImageUrl = "";
                        if ($restaurant->rLogo == ""){
                            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                        } else {
                            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                        }
                        $custDeviceToken  = $customer->deviceToken;
                        $notif2Title = "$restaurant->rAddress, $restaurant->rCity";
                        $notif2Desc = "You have been blocked due to number of cancellation";
                        $this->createBlockNotif($customerId, $restAccId, $notifTitle, $notifDesc, $custDeviceToken, $notif2Title, $notif2Desc, $finalImageUrl);
                    } else {
                        //kung yung noOfcanccellation ay lagpas sa isa then create na tayo tas di pa to block
                        $createMainOffense = CustOffenseMain::create([
                            'customer_id' => $customerQueue->customer_id,
                            'restAcc_id' => $restaurant->id,
                            'offenseType' => "cancellation",
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
                        'offenseType' => "cancellation",
                    ]);
                }
            }
            
    
            $notif = CustomerNotification::create([
                'customer_id' => $customerQueue->customer_id,
                'restAcc_id' => $customerQueue->restAcc_id,
                'notificationType' => "Cancelled",
                'notificationTitle' => "You have Cancelled your Booking!",
                'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
                'notificationStatus' => "Unread",
            ]);
    
            $finalImageUrl = "";
            if ($restaurant->rLogo == ""){
                $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
            } else {
                $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
            }
    
            
            if($customer != null && $customer->deviceToken != null){
                $to = $customer->deviceToken;
                $notification = array(
                    'title' => "$restaurant->rName, $restaurant->rBranch",
                    'body' => "You have Cancelled your Booking, Thank you and we hope to see you again!",
                );
                $data = array(
                    'notificationType' => "Cancelled",
                    'notificationId' => $notif->id,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }
        } else {
            $restaurant = RestaurantAccount::select('id', 'rAddress', 'rCity', 'rLogo', 'rName', 'rBranch')->where('id', $customerReserve->restAcc_id)->first();
            $customer = CustomerAccount::select('deviceToken')->where('id', $id)->first();
    
            CustomerReserve::where('id', $customerReserve->id)
            ->update([
                'status' => 'cancelled',
                'cancelDateTime' => date('Y-m-d H:i:s'),
            ]);


            // CHECK IF THERES LATEST CANCEL OFFENSE
            $mainOffense = CustOffenseMain::where('customer_id', $customerReserve->customer_id)
            ->where('restAcc_id', $restaurant->id)
            ->where('offenseType', "cancellation")
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
                                            'offenseType' => "cancellation",
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
                                            'offenseType' => "cancellation",
                                            'totalOffense' => 1,
                                            'offenseCapacity' => $restOffense->noOfCancellation,
                                            'offenseDaysBlock' => $restOffense->blockDays,
                                            'offenseValidity' => $finalOffenseValidity,
                                        ]);
                                        $custMainOffenseId = $createMainOffense->id;
                                    }
                                    

                                    //FOR BLOCKED NOTIFICATION
                                    $customerId = $customerReserve->customer_id;
                                    $restAccId = $customerReserve->restAcc_id;
                                    $notifTitle = "You have been blocked due to number of cancellation";
                                    $notifDesc = "$restaurant->rAddress, $restaurant->rCity";
                                    $finalImageUrl = "";
                                    if ($restaurant->rLogo == ""){
                                        $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                                    } else {
                                        $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                                    }
                                    $custDeviceToken  = $customer->deviceToken;
                                    $notif2Title = "$restaurant->rAddress, $restaurant->rCity";
                                    $notif2Desc = "You have been blocked due to number of cancellation";
                                    $this->createBlockNotif($customerId, $restAccId, $notifTitle, $notifDesc, $custDeviceToken, $notif2Title, $notif2Desc, $finalImageUrl);

                                } else {
                                    //kung yung noOfcanccellation ay lagpas sa isa then create na tayo tas di pa to block
                                    $createMainOffense = CustOffenseMain::create([
                                        'customer_id' => $customerReserve->customer_id,
                                        'restAcc_id' => $restaurant->id,
                                        'offenseType' => "cancellation",
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
                            

                            //FOR BLOCKED NOTIFICATION
                            $customerId = $customerReserve->customer_id;
                            $restAccId = $customerReserve->restAcc_id;
                            $notifTitle = "You have been blocked due to number of cancellation";
                            $notifDesc = "$restaurant->rAddress, $restaurant->rCity";
                            $finalImageUrl = "";
                            if ($restaurant->rLogo == ""){
                                $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                            } else {
                                $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                            }
                            $custDeviceToken  = $customer->deviceToken;
                            $notif2Title = "$restaurant->rAddress, $restaurant->rCity";
                            $notif2Desc = "You have been blocked due to number of cancellation";
                            $this->createBlockNotif($customerId, $restAccId, $notifTitle, $notifDesc, $custDeviceToken, $notif2Title, $notif2Desc, $finalImageUrl);

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
                                'offenseType' => "cancellation",
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
                                'offenseType' => "cancellation",
                                'totalOffense' => 1,
                                'offenseCapacity' => $restOffense->noOfCancellation,
                                'offenseDaysBlock' => $restOffense->blockDays,
                                'offenseValidity' => $finalOffenseValidity,
                            ]);
                            $custMainOffenseId = $createMainOffense->id;
                        }
                        
                        //FOR BLOCKED NOTIFICATION
                        $customerId = $customerReserve->customer_id;
                        $restAccId = $customerReserve->restAcc_id;
                        $notifTitle = "You have been blocked due to number of cancellation";
                        $notifDesc = "$restaurant->rAddress, $restaurant->rCity";
                        $finalImageUrl = "";
                        if ($restaurant->rLogo == ""){
                            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                        } else {
                            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                        }
                        $custDeviceToken  = $customer->deviceToken;
                        $notif2Title = "$restaurant->rAddress, $restaurant->rCity";
                        $notif2Desc = "You have been blocked due to number of cancellation";
                        $this->createBlockNotif($customerId, $restAccId, $notifTitle, $notifDesc, $custDeviceToken, $notif2Title, $notif2Desc, $finalImageUrl);

                    } else {
                        //kung yung noOfcanccellation ay lagpas sa isa then create na tayo tas di pa to block
                        $createMainOffense = CustOffenseMain::create([
                            'customer_id' => $customerReserve->customer_id,
                            'restAcc_id' => $restaurant->id,
                            'offenseType' => "cancellation",
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
                        'offenseType' => "cancellation",
                    ]);
                }
            }


    
            $notif = CustomerNotification::create([
                'customer_id' => $customerReserve->customer_id,
                'restAcc_id' => $customerReserve->restAcc_id,
                'notificationType' => "Cancelled",
                'notificationTitle' => "You have Cancelled your Booking!",
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
                    'body' => "You have Cancelled your Booking, Thank you and we hope to see you again!",
                );
                $data = array(
                    'notificationType' => "Cancelled",
                    'notificationId' => $notif->id,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }
        }


        return response()->json([
            'status' => "Success"
        ]);
    }

    public function getNotificationPending($cust_id, $notif_id){
        $notification = CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $notification->restAcc_id)->first();

        CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)
        ->update([
            'notificationStatus' => "Read"
        ]);
        
        $customerQueue = CustomerQueue::where('customer_id', $cust_id)
        ->where('restAcc_id', $notification->restAcc_id)
        ->where('created_at', $notification->created_at)
        ->first();
        
        $customerReserve = CustomerReserve::where('customer_id', $cust_id)
        ->where('restAcc_id', $notification->restAcc_id)
        ->where('created_at', $notification->created_at)
        ->first();

        if($customerQueue != null){

            if($customerQueue->status == "pending"){
                $viewable = "yes";
            } else {
                $viewable = "no";
            }
    
            return response()->json([
                'orderName' => $customerQueue->orderSetName,
                'numberOfPersons' => $customerQueue->numberOfPersons,
                'numberOfTables' => $customerQueue->numberOfTables,
                'hoursOfStay' => $customerQueue->hoursOfStay,
                'numberOfChildren' => $customerQueue->numberOfChildren,
                'numberOfPwd' => $customerQueue->numberOfPwd,
                'note' => $customerQueue->notes,
                'restaurant' => "$restaurant->rName $restaurant->rBranch",
                'date' => null,
                'time' => null,
                'statusViewable' => $viewable,
            ]);
        } else {

            if($customerReserve->status == "pending"){
                $viewable = "yes";
            } else {
                $viewable = "no";
            }

            $bookDate = explode('-', $customerReserve->reserveDate);
            $year = $bookDate[0];
            $month = $this->convertMonths($bookDate[1]);
            $day  = $bookDate[2];
    
            return response()->json([
                'orderName' => $customerReserve->orderSetName,
                'numberOfPersons' => $customerReserve->numberOfPersons,
                'numberOfTables' => $customerReserve->numberOfTables,
                'hoursOfStay' => $customerReserve->hoursOfStay,
                'numberOfChildren' => $customerReserve->numberOfChildren,
                'numberOfPwd' => $customerReserve->numberOfPwd,
                'note' => $customerReserve->notes,
                'restaurant' => "$restaurant->rName $restaurant->rBranch",
                'date' => "$month $day, $year",
                'time' => $customerReserve->reserveTime,
                'statusViewable' => $viewable,
            ]);
        }
    }
    public function getNotificationCancelled($cust_id, $notif_id){
        $notification = CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $notification->restAcc_id)->first();

        CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)
        ->update([
            'notificationStatus' => "Read"
        ]);

        CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)
        ->update([
            'notificationStatus' => "Read"
        ]);
        
        $customerQueue = CustomerQueue::where('customer_id', $cust_id)
        ->where('restAcc_id', $notification->restAcc_id)
        ->where('cancelDateTime', $notification->created_at)
        ->first();

        $customerReserve = CustomerReserve::where('customer_id', $cust_id)
        ->where('restAcc_id', $notification->restAcc_id)
        ->where('cancelDateTime', $notification->created_at)
        ->first();

        if($customerQueue != null){
            return response()->json([
                'cancelReason' => $customerQueue->cancelReason,
                'restaurant' => "$restaurant->rName $restaurant->rBranch",
            ]);
        } else {
            return response()->json([
                'cancelReason' => $customerReserve->cancelReason,
                'restaurant' => "$restaurant->rName $restaurant->rBranch",
            ]);
        }
    }
    public function getNotificationDeclined($cust_id, $notif_id){
        $notification = CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $notification->restAcc_id)->first();
        
        $customerQueue = CustomerQueue::where('customer_id', $cust_id)
        ->where('restAcc_id', $notification->restAcc_id)
        ->where('declinedDateTime', $notification->created_at)
        ->first();
        
        $customerReserve = CustomerReserve::where('customer_id', $cust_id)
        ->where('restAcc_id', $notification->restAcc_id)
        ->where('declinedDateTime', $notification->created_at)
        ->first();

        if($customerQueue != null){
            return response()->json([
                'declinedReason' => $customerQueue->declinedReason,
                'restaurant' => "$restaurant->rName $restaurant->rBranch",
            ]);
        } else {
            return response()->json([
                'declinedReason' => $customerReserve->declinedReason,
                'restaurant' => "$restaurant->rName $restaurant->rBranch",
            ]);
        }
    }
    public function getNotificationApproved($cust_id, $notif_id){
        $notification = CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $notification->restAcc_id)->first();

        CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)
        ->update([
            'notificationStatus' => "Read"
        ]);
        
        $customerQueue = CustomerQueue::where('customer_id', $cust_id)
        ->where('restAcc_id', $notification->restAcc_id)
        ->where('approvedDateTime', $notification->created_at)
        ->first();
        
        $customerReserve = CustomerReserve::where('customer_id', $cust_id)
        ->where('restAcc_id', $notification->restAcc_id)
        ->where('approvedDateTime', $notification->created_at)
        ->first();

        if($customerQueue != null){
            if($customerQueue->status == "approved"){
                $viewable = "yes";
            } else {
                $viewable = "no";
            }
        } else {
            if($customerReserve->status == "approved"){
                $viewable = "yes";
            } else {
                $viewable = "no";
            }
        }

        
        return response()->json([
            'restaurant' => "$restaurant->rName $restaurant->rBranch",
            'statusViewable' => $viewable,
        ]);
    }
    public function getNotificationNoShow($cust_id, $notif_id){
        $notification = CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $notification->restAcc_id)->first();

        CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)
        ->update([
            'notificationStatus' => "Read"
        ]);

        CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)
        ->update([
            'notificationStatus' => "Read"
        ]);

        return response()->json([
            'restaurant' => "$restaurant->rName $restaurant->rBranch",
        ]);
    }
    public function getNotificationRunaway($cust_id, $notif_id){
        $notification = CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $notification->restAcc_id)->first();
        
        return response()->json([
            'restaurant' => "$restaurant->rName $restaurant->rBranch",
        ]);
    }
    public function getNotificationGeofencing($cust_id, $notif_id){
        $promos = array();
        $tasks = array();
        $orderSets = array();
        $notification = CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)->first();
        $restaurant = RestaurantAccount::select('id', 'rName', 'rBranch', 'rAddress', 'rCity', 'rLogo')->where('id', $notification->restAcc_id)->first();

        CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)
        ->update([
            'notificationStatus' => "Read"
        ]);

        $getPromos = Promo::where('restAcc_id', $restaurant->id)->where('promoPosted', 'Posted')->get();
        if($getPromos->isEmpty()){
            $promos = null;
        } else {
            foreach($getPromos as $getPromo){
                array_push($promos, [
                    'promoImage' => $this->PROMO_IMAGE_PATH."/".$restaurant->id."/".$getPromo->promoImage,
                    'promoTitle' => $getPromo->promoTitle,
                    'promoDescription' => $getPromo->promoDescription,
                ]);
            }
        }

        $stampCard = StampCard::where('restAcc_id', $restaurant->id)->first();
        if($stampCard == null){
            $stampDiscount = null;
            $stampValidity = null;
            $stampCapacity = null;
            $tasks = null;
        } else {
            $reward = RestaurantRewardList::where('id', $stampCard->stampReward_id)->where('restAcc_id', $restaurant->id)->first();
            switch($reward->rewardCode){
                case "DSCN": 
                    $finalReward = "Discount $reward->rewardInput% in a Total Bill";
                    break;
                case "FRPE": 
                    $finalReward = "Free $reward->rewardInput person in a group";
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

            $bookDate = explode('-', $stampCard->stampValidity);
            $year = $bookDate[0];
            $month = $this->convertMonths($bookDate[1]);
            $day  = $bookDate[2];

            $stampDiscount = $finalReward;
            $stampValidity = "Valid Until: $month $day, $year";
            $stampCapacity = "$stampCard->stampCapacity";

            $getStampTasks = StampCardTasks::where('stampCards_id', $stampCard->id)->get();
            foreach($getStampTasks as $getStampTask){
                $getTasks = RestaurantTaskList::where('id', $getStampTask->restaurantTaskLists_id)->where('restAcc_id', $restaurant->id)->first();
                switch($getTasks->taskCode){
                    case "SPND":
                        array_push($tasks, "Spend ".$getTasks->taskInput." pesos in 1 visit only");
                        break;
                    case "BRNG":
                        array_push($tasks, "Bring ".$getTasks->taskInput." friends in our store");
                        break;
                    case "ORDR":
                        array_push($tasks, "Order ".$getTasks->taskInput." add on/s per visit");
                        break;
                    case "VST":
                        array_push($tasks, "Visit in our store");
                        break;
                    case "FDBK":
                        array_push($tasks, "Give a feedback/review per visit");
                        break;
                    case "PUGC":
                        array_push($tasks, "Pay using gcash");
                        break;
                    case "LUDN":
                        array_push($tasks, "Eat during lunch/dinner hours");
                        break;
                    default: 
                }
            }

        }

        $getOrderSets = OrderSet::where('restAcc_id', $restaurant->id)->where('status', "Visible")->get();
        if($getOrderSets->isEmpty()){
            $orderSets = null;
        } else {
            foreach($getOrderSets as $getOrderSet){
                array_push($orderSets, [
                    'orderSetName' => $getOrderSet->orderSetName,
                    'orderSetTagline' => $getOrderSet->orderSetTagline,
                    'orderSetDescription' => $getOrderSet->orderSetDescription,
                    'orderSetPrice' => (number_format($getOrderSet->orderSetPrice, 2, '.')),
                    'orderSetImage' => $this->ORDER_SET_IMAGE_PATH."/".$restaurant->id."/".$getOrderSet->orderSetImage,
                ]);
            }
        }



        $finalImageUrl = "";
        if ($restaurant->rLogo == ""){
            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
        } else {
            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
        }

        return response()->json([
            'restAcc_id' => $restaurant->id,
            'rName' => $restaurant->rName,
            'rBranch' => $restaurant->rBranch,
            'rAddress' => "$restaurant->rAddress, $restaurant->rCity",
            'rLogo' => $finalImageUrl,
            'promos' => $promos,
            'stampDiscount' => $stampDiscount,
            'stampValidity' => $stampValidity,
            'stampCapacity' => $stampCapacity,
            'stampTasks' => $tasks,
            'orderSets' => $orderSets,
        ]);
    }
    public function getNotificationBlocked($cust_id, $notif_id){
        $notification = CustomerNotification::where('id', $notif_id)->where('notificationType', "Blocked")->where('customer_id', $cust_id)->first();


        CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)
        ->update([
            'notificationStatus' => "Read"
        ]);

        $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $notification->restAcc_id)->first();
        $description = "";
        $description2 = "";
        $offenseHist = array();

        $custOffenseMain = CustOffenseMain::where('updated_at', $notification->created_at)->first();

        $custOffenseEaches = CustOffenseEach::where('custOffMain_id', $custOffenseMain->id)->get();
        foreach($custOffenseEaches as $custOffenseEach){

            $offenseEachdate = explode('-', date('Y-m-d', strtotime($custOffenseEach->created_at)));
            $month = $this->convertMonths($offenseEachdate[1]);
            $year = $offenseEachdate[0];
            $day  = $offenseEachdate[2];

            $offenseEachTime = date('g:i a', strtotime($custOffenseEach->created_at));

            $dateTime = "$month $day, $year - $offenseEachTime";

            $book_type = "Reserve";
            if($custOffenseEach->book_type == "queue"){
                $book_type = "Queue";
            }
            
            array_push($offenseHist, [
                'book_type' => $book_type,
                'dateTime' => $dateTime,
            ]);
        }

        $offenseMaindate = explode('-', date('Y-m-d', strtotime($custOffenseMain->offenseValidity)));
        $month2 = $this->convertMonths($offenseMaindate[1]);
        $year2 = $offenseMaindate[0];
        $day2  = $offenseMaindate[2];

        $offenseMainTime = date('g:i a', strtotime($custOffenseMain->offenseValidity));

        $dateTime2 = "$month2 $day2, $year2 at $offenseMainTime";

        switch($custOffenseMain->offenseType){
            case "cancellation":
                $description = "You have been blocked due to number of Cancellations.";
                if($custOffenseMain->offenseDaysBlock == "Permanent"){
                    $description2 = "You cannot queue or reserve anymore on our store due to permanent block.";
                } else {
                    $description2 = "You will be unblocked from booking after $custOffenseMain->offenseDaysBlock day/s. The exact date will be on $dateTime2.";
                }
                break;
            case "runaway":
                $description = "You have been blocked due to number of Runaways.";
                if($custOffenseMain->offenseDaysBlock == "Permanent"){
                    $description2 = "You cannot queue or reserve anymore on our store due to permanent block.";
                } else {
                    $description2 = "You will be unblocked from booking after $custOffenseMain->offenseDaysBlock day/s. The exact date will be on $dateTime2.";
                }
                break;
            case "noshow":
                $description = "You have been blocked due to number of No Shows.";
                if($custOffenseMain->offenseDaysBlock == "Permanent"){
                    $description2 = "You cannot queue or reserve anymore on our store due to permanent block.";
                } else {
                    $description2 = "You will be unblocked from booking after $custOffenseMain->offenseDaysBlock day/s. The exact date will be on $dateTime2.";
                }
                break;
        }
        
        return response()->json([
            'restaurant' => "$restaurant->rName $restaurant->rBranch",
            'description' => $description,
            'description2' => $description2,
            'offenseHist' => $offenseHist,
            'offenseType' => $custOffenseMain->offenseType,
        ]);
    }



    public function updateDeviceToken(Request $request){
        $request->validate([
            'cust_id' => 'required',
            'deviceToken' => 'required',
        ]);

        CustomerAccount::where('id', $request->cust_id)
        ->update([
            'deviceToken' => $request->deviceToken,
        ]);

        return response()->json([
            'status' => 'success',
        ]);
    }
    public function forgotPassword(Request $request){
        $request->validate([
            'emailAddress' => 'required|email',
        ]);
        $findEmailAddress = CustomerAccount::where('emailAddress', $request->emailAddress)->first();
        if($findEmailAddress == null){
            return response()->json([
                'status' => "emailNotExist",
            ]);
        } else {
            $token = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 12).time().$findEmailAddress->id;
            $details = [
                'link' => env('APP_URL') . '/customer/forgot-password/'.$token.'/'.$request->emailAddress,
            ];
            Mail::to($request->emailAddress)->send(new CustomerForgotPassword($details));
            CustomerResetPassword::create([
                'emailAddress' => $request->emailAddress,
                'token' => $token,
                'resetStatus' => 'Pending',
            ]);
            
            $notif = CustomerNotification::create([
                'customer_id' => $findEmailAddress->id,
                'restAcc_id' => 0,
                'notificationType' => "Forgot Password",
                'notificationTitle' => "Your password reset link has been sent to your email",
                'notificationDescription' => "Samquicksal",
                'notificationStatus' => "Unread",
            ]);

            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/samquicksalLogo.png';
            
            if($findEmailAddress != null){
                $to = $findEmailAddress->deviceToken;
                $notification = array(
                    'title' => "Forgot Password",
                    'body' => "Your password reset link has been sent to your email",
                );
                $data = array(
                    'notificationType' => "Forgot Password",
                    'notificationId' => $notif->id,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }
            
            return response()->json([
                'status' => "success",
            ]);
        }
    }

    public function resetPasswordView($token, $emailAddress){
        $checkIfExist = CustomerResetPassword::where('token', $token)->where('emailAddress', $emailAddress)->where('resetStatus', 'Pending')->first();
        if($checkIfExist == null){
            abort(404);
        } else {
            return view('customer.resetPassword',[
                'token' => $token,
                'emailAddress' => $emailAddress,
            ]);
        }
    }
    public function resetPassword(Request $request, $token, $emailAddress){
        $findEmailAddress = CustomerAccount::where('emailAddress', $request->emailAddress)->first();
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

        Mail::to($emailAddress)->send(new CustomerPasswordChanged());
        $hashPassword = Hash::make($request->newPassword);
        CustomerAccount::where('emailAddress', $emailAddress)
        ->update([
            'password' => $hashPassword
        ]);
        CustomerResetPassword::where('emailAddress', $emailAddress)->where('token', $token)
        ->update([
            'resetStatus' => "Changed"
        ]);
        
        $notif = CustomerNotification::create([
            'customer_id' => $findEmailAddress->id,
            'restAcc_id' => 0,
            'notificationType' => "Password Changed",
            'notificationTitle' => "Your Password has been changed successfully",
            'notificationDescription' => "Samquicksal",
            'notificationStatus' => "Unread",
        ]);

        $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/samquicksalLogo.png';
            
        if($findEmailAddress != null){
            $to = $findEmailAddress->deviceToken;
            $notification = array(
                'title' => "Password Changed",
                'body' => "Your Password has been changed successfully, you can now login again",
            );
            $data = array(
                'notificationType' => "Password Changed",
                'notificationId' => $notif->id,
                'notificationRLogo' => $finalImageUrl,
            );
            $this->sendFirebaseNotification($to, $notification, $data);
        }

        $request->session()->flash('passwordUpdated');
        return redirect('/');
    }

    public function getBookingHistory($cust_id){
        $storeBookingHistory = array();

        $customerQueues = CustomerQueue::where('customer_id', $cust_id)
        ->where(function ($query) {
            $query->where('status', "declined")
            ->orWhere('status', "cancelled")
            ->orWhere('status', "noshow")
            ->orWhere('status', "runaway")
            ->orWhere('status', "completed");
        })->get();
        
        $customerReserves = CustomerReserve::where('customer_id', $cust_id)
        ->where(function ($query) {
            $query->where('status', "declined")
            ->orWhere('status', "cancelled")
            ->orWhere('status', "noshow")
            ->orWhere('status', "runaway")
            ->orWhere('status', "completed");
        })->get();

        if(!$customerQueues->isEmpty()){
            foreach($customerQueues as $customerQueue){
                $restaurant = RestaurantAccount::select('id', 'rLogo', 'rName', 'rAddress', 'rCity')->where('id', $customerQueue->restAcc_id)->first();
                
                if ($restaurant->rLogo == ""){
                    $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                } else {
                    $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                }

                array_push($storeBookingHistory, [
                    'bookId' => $customerQueue->id,
                    'rLogo' => $finalImageUrl,
                    'rName' => $restaurant->rName,
                    'rAddress' => "$restaurant->rAddress, $restaurant->rCity",
                    "bookDate" => "$customerQueue->queueDate",
                    "bookStatus" => $customerQueue->status,
                    "bookType" => "queue",
                    "created_at" => $customerQueue->created_at,
                ]);
            }
        }

        if(!$customerReserves->isEmpty()){
            foreach($customerReserves as $customerReserve){
                $restaurant = RestaurantAccount::select('id', 'rLogo', 'rName', 'rAddress', 'rCity')->where('id', $customerReserve->restAcc_id)->first();
                
                if ($restaurant->rLogo == ""){
                    $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                } else {
                    $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                }

                array_push($storeBookingHistory, [
                    'bookId' => $customerReserve->id,
                    'rLogo' => $finalImageUrl,
                    'rName' => $restaurant->rName,
                    'rAddress' => "$restaurant->rAddress, $restaurant->rCity",
                    "bookDate" => "$customerReserve->reserveDate",
                    "bookStatus" => $customerReserve->status,
                    "bookType" => "reserve",
                    "created_at" => $customerReserve->created_at,
                ]);
            }
        }
        
        if($storeBookingHistory == null){
            return response()->json($storeBookingHistory);
        } else {

            usort($storeBookingHistory, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            $finalStoreBookingHistory = array();
            foreach($storeBookingHistory as $temp){
                $bookDate = explode('-', $temp['bookDate']);
                $year = $bookDate[0];
                $month = $this->convertMonths($bookDate[1]);
                $day  = $bookDate[2];

                array_push($finalStoreBookingHistory, [
                    'bookId' => $temp['bookId'],
                    'rLogo' => $temp['rLogo'],
                    'rName' => $temp['rName'],
                    'rAddress' => $temp['rAddress'],
                    "bookDate" => "$month $day, $year",
                    "bookStatus" => $temp['bookStatus'],
                    "bookType" => $temp['bookType'],
                ]); 
            }
            return response()->json($finalStoreBookingHistory);
        }
    }


    // BOOKING DETAILS
    public function getBookingHistoryCancelled(Request $request){
        if($request->book_type == "queue"){
            $customerQueue = CustomerQueue::where('id', $request->book_id)->where('customer_id', $request->cust_id)->first();
            $restaurant = RestaurantAccount::select('rName', 'rAddress', 'rBranch', 'rCity')->where('id', $customerQueue->restAcc_id)->first();
            
            $bookDate = explode('-', $customerQueue->queueDate);
            $year = $bookDate[0];
            $month = $this->convertMonths($bookDate[1]);
            $day  = $bookDate[2];
            $bookTime = date("g:i a", strtotime($customerQueue->created_at->toTimeString()));

            if($customerQueue->status == "cancelled"){
                $finalReason = ($customerQueue->cancelReason) ? "$customerQueue->cancelReason" : "None";
            } else if ($customerQueue->status == "declined"){
                $finalReason = ($customerQueue->declinedReason) ? "$customerQueue->declinedReason" : "None";
            } else {
                $finalReason = "None";
            }

            $finalReward = "None";
            if($customerQueue->rewardStatus == "Complete"){
                switch($customerQueue->rewardType){
                    case "DSCN": 
                        $finalReward = "Discount $customerQueue->rewardInput% in a Total Bill";
                        break;
                    case "FRPE": 
                        $finalReward = "Free $customerQueue->rewardInput person in a group";
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
            }

            

            return response()->json([
                'bookDate' => "$month $day, $year",
                'bookTime' => $bookTime,
                'reserveDate' => null,
                'reserveTime' => null,
                'rName' => $restaurant->rName,
                'rAddress' => "$restaurant->rAddress, $restaurant->rBranch, $restaurant->rCity",
                'orderName' => $customerQueue->orderSetName,
                'hoursOfStay' => "$customerQueue->hoursOfStay hours",
                'numberOfPwd' => $customerQueue->numberOfPwd,
                'numberOfChildren' => $customerQueue->totalPwdChild,
                'reward' => $finalReward,
                'rewardClaimed' => ($customerQueue->rewardClaimed) ? $customerQueue->rewardClaimed : "N/A",
                'bookingType' => "Queue",
                'notes' => ($customerQueue->notes) ? "$customerQueue->notes" : "None",
                'reason' => $finalReason,
            ]);
        } else {
            $customerReserve = CustomerReserve::where('id', $request->book_id)->where('customer_id', $request->cust_id)->first();
            $restaurant = RestaurantAccount::select('rName', 'rAddress', 'rBranch', 'rCity')->where('id', $customerReserve->restAcc_id)->first();
            
            $bookDate = explode('-', date("Y-m-d", strtotime($customerReserve->created_at->toTimeString())));
            $year = $bookDate[0];
            $month = $this->convertMonths($bookDate[1]);
            $day  = $bookDate[2];
            $bookTime = date("g:i a", strtotime($customerReserve->created_at->toTimeString()));

            if($customerReserve->status == "cancelled"){
                $finalReason = ($customerReserve->cancelReason) ? "$customerReserve->cancelReason" : "None";
            } else if ($customerReserve->status == "declined"){
                $finalReason = ($customerReserve->declinedReason) ? "$customerReserve->declinedReason" : "None";
            } else {
                $finalReason = "None";
            }

            $finalReward = "None";
            if($customerReserve->rewardStatus == "Complete"){
                switch($customerReserve->rewardType){
                    case "DSCN": 
                        $finalReward = "Discount $customerReserve->rewardInput% in a Total Bill";
                        break;
                    case "FRPE": 
                        $finalReward = "Free $customerReserve->rewardInput person in a group";
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
            }

            $reserveDate = explode('-', $customerReserve->reserveDate);
            $year2 = $reserveDate[0];
            $month2 = $this->convertMonths($reserveDate[1]);
            $day2  = $reserveDate[2];
            $reserveTime = date("g:i a", strtotime($customerReserve->reserveTime));

            return response()->json([
                'bookDate' => "$month $day, $year",
                'bookTime' => $bookTime,
                'reserveDate' => "$month2 $day2, $year2",
                'reserveTime' => $reserveTime,
                'rName' => $restaurant->rName,
                'rAddress' => "$restaurant->rAddress, $restaurant->rBranch, $restaurant->rCity",
                'orderName' => $customerReserve->orderSetName,
                'hoursOfStay' => "$customerReserve->hoursOfStay hours",
                'numberOfPwd' => $customerReserve->numberOfPwd,
                'numberOfChildren' => $customerReserve->totalPwdChild,
                'reward' => $finalReward,
                'rewardClaimed' => ($customerReserve->rewardClaimed) ? $customerReserve->rewardClaimed : "N/A",
                'bookingType' => "Reserve",
                'notes' => ($customerReserve->notes) ? "$customerReserve->notes" : "None",
                'reason' => $finalReason,
            ]);
        }
    }
    public function getOrderingFoodSets($cust_id){
        $customerQueue = CustomerQueue::where('customer_id', $cust_id)->where('status', 'eating')->first();
        $customerReserve = CustomerReserve::where('customer_id', $cust_id)->where('status', 'eating')->first();

        $finalFoodSets = array();
        $orderSetId = null;
        $restAccId = null;

        if($customerQueue != null){
            $restAccId = $customerQueue->restAcc_id;
            $orderSetId = $customerQueue->orderSet_id;
            $foodSets = OrderSetFoodSet::where('orderSet_id', $customerQueue->orderSet_id)->get();
            $foodItems = OrderSetFoodItem::where('orderSet_id', $customerQueue->orderSet_id)->get();
            $countFoodItems = OrderSetFoodItem::where('orderSet_id', $customerQueue->orderSet_id)->count();

            foreach($foodSets as $foodSet){
                $getFoodSet = FoodSet::where('id', $foodSet->foodSet_id)->where('status', "Visible")->first();
                if($getFoodSet != null){
                    array_push($finalFoodSets, [
                        'foodSetId' => $foodSet->foodSet_id,
                        'foodSetImage' => $this->FOOD_SET_IMAGE_PATH.'/'.$getFoodSet->restAcc_id.'/'. $getFoodSet->foodSetImage,
                        'foodSetName' => $getFoodSet->foodSetName,
                        'foodSetDescription' => $getFoodSet->foodSetDescription,
                        'foodSetAvailable' => $getFoodSet->available,
                    ]);
                }
            }
            
            if(!$foodItems->isEmpty()){
                $count = 0;

                foreach($foodItems as $foodItem){
                    $food = FoodItem::where('id', $foodItem->foodItem_id)->where('status', 'Hidden')->first();
                    if($food != null){
                        $count++;
                    }
                }

                if($count != $countFoodItems){
                    array_push($finalFoodSets, [
                        'foodSetId' => 0,
                        'foodSetImage' => $this->ACCOUNT_NO_IMAGE_PATH."/add_ons_img.png",
                        'foodSetName' => "Add Ons",
                        'foodSetDescription' => "Includes extra charges on your total bill",
                        'foodSetAvailable' => "Yes",
                    ]);
                }

            }
        } else if($customerReserve != null){
            $restAccId = $customerReserve->restAcc_id;
            $orderSetId = $customerReserve->orderSet_id;
            $foodSets = OrderSetFoodSet::where('orderSet_id', $customerReserve->orderSet_id)->get();
            $foodItems = OrderSetFoodItem::where('orderSet_id', $customerReserve->orderSet_id)->get();
            $countFoodItems = OrderSetFoodItem::where('orderSet_id', $customerReserve->orderSet_id)->count();
            
            foreach($foodSets as $foodSet){
                $getFoodSet = FoodSet::where('id', $foodSet->foodSet_id)->where('status', "Visible")->first();
                if($getFoodSet != null){
                    array_push($finalFoodSets, [
                        'foodSetId' => $foodSet->foodSet_id,
                        'foodSetImage' => $this->FOOD_SET_IMAGE_PATH.'/'.$getFoodSet->restAcc_id.'/'. $getFoodSet->foodSetImage,
                        'foodSetName' => $getFoodSet->foodSetName,
                        'foodSetDescription' => $getFoodSet->foodSetDescription,
                        'foodSetAvailable' => $getFoodSet->available,
                    ]);
                }
            }
            
            if(!$foodItems->isEmpty()){
                $count = 0;

                foreach($foodItems as $foodItem){
                    $food = FoodItem::where('id', $foodItem->foodItem_id)->where('status', 'Hidden')->first();
                    if($food != null){
                        $count++;
                    }
                }
                
                if($count != $countFoodItems){
                    array_push($finalFoodSets, [
                        'foodSetId' => 0,
                        'foodSetImage' => $this->ACCOUNT_NO_IMAGE_PATH."/add_ons_img.png",
                        'foodSetName' => "Add Ons",
                        'foodSetDescription' => "Add ons contain price",
                        'foodSetAvailable' => "Yes",
                    ]);
                }
            }
        } else { }
        return response()->json([
            'restAccId' => $restAccId,
            'orderSetId' => $orderSetId,
            'foodSets' => $finalFoodSets,
        ]);
    }
    public function getOrderingFoodItems($restAcc_id, $orderSet_id, $foodSet_id){
        $finalFoodItems = array();
        if($foodSet_id == 0){
            $foodItems = OrderSetFoodItem::where('orderSet_id', $orderSet_id)->get();
            if(!$foodItems->isEmpty()){
                foreach ($foodItems as $foodItem){
                    $food = FoodItem::where('id', $foodItem->foodItem_id)->where('restAcc_id', $restAcc_id)->where('status', "Visible")->first();
                    if($food != null){
                        array_push($finalFoodItems, [
                            'foodItemId' => $food->id,
                            'foodItemName' => $food->foodItemName,
                            'foodItemDescription' => $food->foodItemDescription,
                            'foodItemPrice' => "$food->foodItemPrice",
                            'foodItemImage' => $this->FOOD_ITEM_IMAGE_PATH.'/'.$restAcc_id.'/'. $food->foodItemImage,
                            'foodItemType' => "not free",
                            'foodItemAvailable' => $food->available
                        ]);
                    }
                }
            }
        } else {
            $foodItems = FoodSetItem::where('foodSet_id', $foodSet_id)->get();
            if(!$foodItems->isEmpty()){
                foreach ($foodItems as $foodItem){
                    $food = FoodItem::where('id', $foodItem->foodItem_id)->where('restAcc_id', $restAcc_id)->where('status', "Visible")->first();
                    if($food != null){
                        array_push($finalFoodItems, [
                            'foodItemId' => $food->id,
                            'foodItemName' => $food->foodItemName,
                            'foodItemDescription' => $food->foodItemDescription,
                            'foodItemPrice' => "0.00",
                            'foodItemImage' => $this->FOOD_ITEM_IMAGE_PATH.'/'.$restAcc_id.'/'. $food->foodItemImage,
                            'foodItemType' => "free",
                            'foodItemAvailable' => $food->available
                        ]);
                    }
                }
            }
        }

        return response()->json($finalFoodItems);
    }
    public function orderingAddFoodItem(Request $request){
        $finalStatus = "Error";
        $customerQueue = CustomerQueue::where('customer_id', $request->cust_id)->where('status', 'eating')->first();
        $customerReserve = CustomerReserve::where('customer_id', $request->cust_id)->where('status', 'eating')->first();

        if($customerQueue != null){
             $customerOrdering = CustomerOrdering::where('custBook_id', $customerQueue->id)->where('custBookType', 'queue')->first();
             $tableNumbers = explode(',', $customerOrdering->tableNumbers);
             $mainTable =  $tableNumbers[0];

             $countQuantity = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)
             ->where('tableNumber', $mainTable)
             ->where('orderDone', "Processing")
             ->sum('quantity');

             if($countQuantity >= 5){
                $finalStatus = "Maximum Quantity Reached";
             } else {
                $customerOrderCount = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)
                ->where('tableNumber', $mainTable)
                ->where('orderDone', "Processing")
                ->where('foodItem_id', $request->foodItemId)
                ->first();

                if($customerOrderCount == null){
                    CustomerLOrder::create([
                        'custOrdering_id' => $customerOrdering->id,
                        'cust_id' => $request->cust_id,
                        'tableNumber' => $mainTable,
                        'foodItem_id' => $request->foodItemId,
                        'foodItemName' => $request->foodName,
                        'quantity' => 1,
                        'price' => $request->foodPrice,
                        'orderDone' => "Processing",
                    ]);
                    $finalStatus = "Item Added";
                } else {
                    CustomerLOrder::where('custOrdering_id', $customerOrdering->id)
                    ->where('tableNumber', $mainTable)
                    ->where('orderDone', "Processing")
                    ->where('foodItem_id', $request->foodItemId)
                    ->update([
                        'quantity' => $customerOrderCount->quantity + 1,
                        'price' => ($customerOrderCount->price / $customerOrderCount->quantity) * ($customerOrderCount->quantity + 1),
                    ]);
                    $finalStatus = "Item Added";
                }
             }
        } else if ($customerReserve != null){
            $customerOrdering = CustomerOrdering::where('custBook_id', $customerReserve->id)->where('custBookType', 'reserve')->first();
            $tableNumbers = explode(',', $customerOrdering->tableNumbers);
            $mainTable =  $tableNumbers[0];

            $countQuantity = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)
            ->where('tableNumber', $mainTable)
            ->where('orderDone', "Processing")
            ->sum('quantity');

            if($countQuantity >= 5){
               $finalStatus = "Maximum Quantity Reached";
            } else {
               $customerOrderCount = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)
               ->where('tableNumber', $mainTable)
               ->where('orderDone', "Processing")
               ->where('foodItem_id', $request->foodItemId)
               ->first();

               if($customerOrderCount == null){
                   CustomerLOrder::create([
                       'custOrdering_id' => $customerOrdering->id,
                       'cust_id' => $request->cust_id,
                       'tableNumber' => $mainTable,
                       'foodItem_id' => $request->foodItemId,
                       'foodItemName' => $request->foodName,
                       'quantity' => 1,
                       'price' => $request->foodPrice,
                       'orderDone' => "Processing",
                   ]);
                   $finalStatus = "Item Added";
               } else {
                   CustomerLOrder::where('custOrdering_id', $customerOrdering->id)
                   ->where('tableNumber', $mainTable)
                   ->where('orderDone', "Processing")
                   ->where('foodItem_id', $request->foodItemId)
                   ->update([
                       'quantity' => $customerOrderCount->quantity + 1,
                       'price' => ($customerOrderCount->price / $customerOrderCount->quantity) * ($customerOrderCount->quantity + 1),
                   ]);
                   $finalStatus = "Item Added";
               }
            }
        } else {
            $customerQrAccess = CustomerQrAccess::where('subCust_id', $request->cust_id)->where('status', "approved")->latest()->first();

            if($customerQrAccess != null){
                $countQuantity = CustomerLOrder::where('custOrdering_id', $customerQrAccess->custOrdering_id)
                ->where('tableNumber', $customerQrAccess->tableNumber)
                ->where('orderDone', "Processing")
                ->sum('quantity');
    
                if($countQuantity >= 5){
                    $finalStatus = "Maximum Quantity Reached";
                 } else {
                    $customerOrderCount = CustomerLOrder::where('custOrdering_id', $customerQrAccess->custOrdering_id)
                    ->where('tableNumber', $customerQrAccess->tableNumber)
                    ->where('orderDone', "Processing")
                    ->where('foodItem_id', $request->foodItemId)
                    ->first();
    
                    if($customerOrderCount == null){
                        $tableNumber = $customerQrAccess->tableNumber;
                        CustomerLOrder::create([
                            'custOrdering_id' => $customerQrAccess->custOrdering_id,
                            'cust_id' => $request->cust_id,
                            'tableNumber' => $tableNumber,
                            'foodItem_id' => $request->foodItemId,
                            'foodItemName' => $request->foodName,
                            'quantity' => 1,
                            'price' => $request->foodPrice,
                            'orderDone' => "Processing",
                        ]);
                        $finalStatus = "Item Added";
                    } else {
                        CustomerLOrder::where('custOrdering_id', $customerQrAccess->custOrdering_id)
                        ->where('tableNumber', $customerQrAccess->tableNumber)
                        ->where('orderDone', "Processing")
                        ->where('foodItem_id', $request->foodItemId)
                        ->update([
                            'quantity' => $customerOrderCount->quantity + 1,
                            'price' => ($customerOrderCount->price / $customerOrderCount->quantity) * ($customerOrderCount->quantity + 1),
                        ]);
                        $finalStatus = "Item Added";
                    }
                 }
            }
            
            
        }

        return response()->json([
            'status' => "$finalStatus"
        ]);
    }
    public function getOrderingOrders($cust_id){
        $customerQueue = CustomerQueue::where('customer_id', $cust_id)->where('status', 'eating')->first();
        $customerReserve = CustomerReserve::where('customer_id', $cust_id)->where('status', 'eating')->first();
        $customerQrAccess = CustomerQrAccess::where('subCust_id', $cust_id)->where('status', 'approved')->latest()->first();
        $finalCustomerOrders = array();

        if($customerQueue != null){
            $customerOrdering = CustomerOrdering::where('custBook_id', $customerQueue->id)->where('custBookType', 'queue')->first();
            $customerOrders = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)
            ->where('cust_id', $cust_id)
            ->where('orderDone', "Processing")
            ->get();

            if(!$customerOrders->isEmpty()){
                foreach ($customerOrders as $customerOrder){
                    array_push($finalCustomerOrders, [
                        'custLOrder_id' => $customerOrder->id,
                        'foodItemName' => $customerOrder->foodItemName,
                        'quantity' => $customerOrder->quantity,
                        'price' => $customerOrder->price,
                    ]);
                }
            } else {
                $finalCustomerOrders = null;
            }
        } else if ($customerReserve != null) {
            $customerOrdering = CustomerOrdering::where('custBook_id', $customerReserve->id)->where('custBookType', 'reserve')->first();
            $customerOrders = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)
            ->where('cust_id', $cust_id)
            ->where('orderDone', "Processing")
            ->get();

            if(!$customerOrders->isEmpty()){
                foreach ($customerOrders as $customerOrder){
                    array_push($finalCustomerOrders, [
                        'custLOrder_id' => $customerOrder->id,
                        'foodItemName' => $customerOrder->foodItemName,
                        'quantity' => $customerOrder->quantity,
                        'price' => $customerOrder->price,
                    ]);
                }
            } else {
                $finalCustomerOrders = null;
            }
        } else if ($customerQrAccess != null){
            $customerOrdering = CustomerOrdering::where('id', $customerQrAccess->custOrdering_id)->first();

            if($customerOrdering != null){
                $customerOrders = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)
                ->where('cust_id', $cust_id)
                ->where('orderDone', "Processing")
                ->get();
    
                if(!$customerOrders->isEmpty()){
                    foreach ($customerOrders as $customerOrder){
                        array_push($finalCustomerOrders, [
                            'custLOrder_id' => $customerOrder->id,
                            'foodItemName' => $customerOrder->foodItemName,
                            'quantity' => $customerOrder->quantity,
                            'price' => $customerOrder->price,
                        ]);
                    }
                } else {
                    $finalCustomerOrders = null;
                }
            } else {
                $finalCustomerOrders = null;
            }
        } else {
            $finalCustomerOrders = null;
        }

        return response()->json($finalCustomerOrders);
    }
    public function orderingUpdateFoodItem(Request $request){
        $finalStatus = null;
        if($request->updateType == "delete"){
            CustomerLOrder::where('id', $request->custLOrder_id)->delete();
            $finalStatus = "Item Removed";
        } else if ($request->updateType == "add"){
            $getCustomerId = CustomerLOrder::where('id', $request->custLOrder_id)->first();
            $countQuantity = CustomerLOrder::where('cust_id', $getCustomerId->cust_id)->where('orderDone', "Processing")->sum('quantity');
            if($countQuantity >= 5){
                $finalStatus = "Maximum Quantity Reached";
            } else {
                CustomerLOrder::where('id', $request->custLOrder_id)
                ->where('orderDone', "Processing")
                ->update([
                    'quantity' => $getCustomerId->quantity + 1,
                    'price' => ($getCustomerId->price / $getCustomerId->quantity) * ($getCustomerId->quantity + 1),
                ]);
                $finalStatus = "Quantity Updated";
            }
        } else {
            $getCustomerId = CustomerLOrder::where('id', $request->custLOrder_id)->first();
            if($getCustomerId->quantity == 1){
                $finalStatus = "Minimum quantity cant be less than 1";
            } else {
                CustomerLOrder::where('id', $request->custLOrder_id)->where('orderDone', "Processing")
                ->update([
                    'quantity' => $getCustomerId->quantity - 1,
                    'price' => ($getCustomerId->price / $getCustomerId->quantity) * ($getCustomerId->quantity - 1),
                ]);
                $finalStatus = "Quantity Updated";
            }
        }
        return response()->json([
            'status' => "$finalStatus",
        ]);
    }
    public function orderingSubmitOrders($cust_id){
        CustomerLOrder::where('cust_id', $cust_id)->where('orderDone', "Processing")
        ->update([
            'orderDone' => "No",
            'orderSubmitDT' => date('Y-m-d H:i:s'),
        ]);
        
        return response()->json([
            'status' => "Order Submitted, Kindly wait for your order. Thanks!",
        ]);
    }
    public function getOrderingAssistHist($cust_id){
        $finalAssistance = array();
        $customerQueue = CustomerQueue::where('customer_id', $cust_id)->where('status', 'eating')->first();
        $customerReserve = CustomerReserve::where('customer_id', $cust_id)->where('status', 'eating')->first();
        $customerQrAccess = CustomerQrAccess::where('subCust_id', $cust_id)->where('status', 'approved')->latest()->first();

        if($customerQueue != null){
            $customerOrdering = CustomerOrdering::where('custBook_id', $customerQueue->id)->where('custBookType', "queue")->first();
            $customerRequests = CustomerLRequest::where('custOrdering_id', $customerOrdering->id)->where('cust_id', $cust_id)->get();

            if($customerRequests->isEmpty()){
                $finalAssistance = null;
            } else {
                foreach($customerRequests as $customerRequest){
                    $time = date("g:i A", strtotime($customerRequest->created_at->format('H:i')));
                    array_push($finalAssistance, [
                        'requestType' => $customerRequest->request,
                        'requestTime' => $time,
                    ]);
                }
            }

        } else if ($customerReserve != null){
            $customerOrdering = CustomerOrdering::where('custBook_id', $customerReserve->id)->where('custBookType', "reserve")->first();
            $customerRequests = CustomerLRequest::where('custOrdering_id', $customerOrdering->id)->where('cust_id', $cust_id)->get();
            
            if($customerRequests->isEmpty()){
                $finalAssistance = null;
            } else {
                foreach($customerRequests as $customerRequest){
                    $time = date("g:i A", strtotime($customerRequest->created_at->format('H:i')));
                    array_push($finalAssistance, [
                        'requestType' => $customerRequest->request,
                        'requestTime' => $time,
                    ]);
                }
            }
        } else if($customerQrAccess != null){
            $customerOrdering = CustomerOrdering::where('id', $customerQrAccess->custOrdering_id)->first();
            $customerRequests = CustomerLRequest::where('custOrdering_id', $customerOrdering->id)->where('cust_id', $cust_id)->get();
            
            if($customerRequests->isEmpty()){
                $finalAssistance = null;
            } else {
                foreach($customerRequests as $customerRequest){
                    $time = date("g:i A", strtotime($customerRequest->created_at->format('H:i')));
                    array_push($finalAssistance, [
                        'requestType' => $customerRequest->request,
                        'requestTime' => $time,
                    ]);
                }
            }
        } else {
            $finalAssistance = null;
        }
        
        return response()->json($finalAssistance);
    }

    public function orderingAddAssistance(Request $request){
        $finalStatus = null;
        $customerQueue = CustomerQueue::where('customer_id', $request->cust_id)->where('status', 'eating')->first();
        $customerReserve = CustomerReserve::where('customer_id', $request->cust_id)->where('status', 'eating')->first();
        $customerQrAccess = CustomerQrAccess::where('subCust_id', $request->cust_id)->where('status', 'approved')->latest()->first();

        if($customerQueue != null){
            $customerOrdering = CustomerOrdering::where('custBook_id', $customerQueue->id)->where('custBookType', 'queue')->first();
            $tableNumbers = explode(',', $customerOrdering->tableNumbers);
            $mainTable =  $tableNumbers[0];
            
            $countRequests = CustomerLRequest::where('custOrdering_id', $customerOrdering->id)
            ->where('tableNumber', $mainTable)
            ->where('cust_id', $request->cust_id)
            ->where('requestDone', "No")->count();

            if($countRequests >= 3){
                $checkRequests = CustomerLRequest::where('custOrdering_id', $customerOrdering->id)
                ->where('tableNumber', $mainTable)
                ->where('cust_id', $request->cust_id)
                ->where('requestDone', "No")
                ->orderBy('requestSubmitDT', "DESC")->limit(3)->get();

                foreach($checkRequests as $checkRequest){
                    $lastRequest = $checkRequest->requestSubmitDT;
                }

                $endTime = Carbon::now();
                $rAppstartTime = Carbon::parse($lastRequest);
                $rAppDiffMinutes = $endTime->diffInMinutes($rAppstartTime);
                if ($rAppDiffMinutes >= 0){
                    if ($rAppDiffMinutes == 1){
                        $rAppDiffTime = 1;
                    } else {
                        $rAppDiffTime = "$rAppDiffMinutes";
                    }
                }

                if($rAppDiffTime <= 5){
                    $finalStatus = "You have requested too many times, please try again after 5 mins (3 request only per 5 mins)";
                } else {
                    $tableNumber = explode(',', $customerOrdering->tableNumbers);
    
                    CustomerLRequest::create([
                        'custOrdering_id' => $customerOrdering->id,
                        'cust_id' => $request->cust_id,
                        'tableNumber' => $mainTable,
                        'request' => $request->requestType,
                        'requestDone' => "No",
                        'requestSubmitDT' => date('Y-m-d H:i:s'),
                    ]);
                    $finalStatus = "Request sent! Kindly wait for the staff to come to your table. Thank you!";
                }
            } else {

                CustomerLRequest::create([
                    'custOrdering_id' => $customerOrdering->id,
                    'cust_id' => $request->cust_id,
                    'tableNumber' => $mainTable,
                    'request' => $request->requestType,
                    'requestDone' => "No",
                    'requestSubmitDT' => date('Y-m-d H:i:s'),
                ]);
                $finalStatus = "Request sent! Kindly wait for the staff to come to your table. Thank you!";
            }
        } else if ($customerReserve != null){
            $customerOrdering = CustomerOrdering::where('custBook_id', $customerReserve->id)->where('custBookType', 'reserve')->first();
            $tableNumbers = explode(',', $customerOrdering->tableNumbers);
            $mainTable =  $tableNumbers[0];

            $countRequests = CustomerLRequest::where('custOrdering_id', $customerOrdering->id)
            ->where('tableNumber', $mainTable)
            ->where('cust_id', $request->cust_id)
            ->where('requestDone', "No")->count();

            if($countRequests >= 3){
                $checkRequests = CustomerLRequest::where('custOrdering_id', $customerOrdering->id)
                ->where('tableNumber', $mainTable)
                ->where('cust_id', $request->cust_id)
                ->where('requestDone', "No")
                ->orderBy('requestSubmitDT', "DESC")->limit(3)->get();

                foreach($checkRequests as $checkRequest){
                    $lastRequest = $checkRequest->requestSubmitDT;
                }

                $endTime = Carbon::now();
                $rAppstartTime = Carbon::parse($lastRequest);
                $rAppDiffMinutes = $endTime->diffInMinutes($rAppstartTime);
                if ($rAppDiffMinutes >= 0){
                    if ($rAppDiffMinutes == 1){
                        $rAppDiffTime = 1;
                    } else {
                        $rAppDiffTime = "$rAppDiffMinutes";
                    }
                }

                if($rAppDiffTime <= 5){
                    $finalStatus = "You have requested too many times, please try again after 5 mins (3 request only per 5 mins)";
                } else {
                    CustomerLRequest::create([
                        'custOrdering_id' => $customerOrdering->id,
                        'cust_id' => $request->cust_id,
                        'tableNumber' => $mainTable,
                        'request' => $request->requestType,
                        'requestDone' => "No",
                        'requestSubmitDT' => date('Y-m-d H:i:s'),
                    ]);
                    $finalStatus = "Request sent! Kindly wait for the staff to come to your table. Thank you!";
                }
            } else {
                CustomerLRequest::create([
                    'custOrdering_id' => $customerOrdering->id,
                    'cust_id' => $request->cust_id,
                    'tableNumber' => $mainTable,
                    'request' => $request->requestType,
                    'requestDone' => "No",
                    'requestSubmitDT' => date('Y-m-d H:i:s'),
                ]);
                $finalStatus = "Request sent! Kindly wait for the staff to come to your table. Thank you!";
            }
        } else if($customerQrAccess != null){
            $customerOrdering = CustomerOrdering::where('id', $customerQrAccess->custOrdering_id)->first();
            $mainTable =  $customerQrAccess->tableNumber;

            $countRequests = CustomerLRequest::where('custOrdering_id', $customerOrdering->id)
            ->where('tableNumber', $mainTable)
            ->where('cust_id', $request->cust_id)
            ->where('requestDone', "No")->count();

            if($countRequests >= 3){
                $checkRequests = CustomerLRequest::where('custOrdering_id', $customerOrdering->id)
                ->where('tableNumber', $mainTable)
                ->where('cust_id', $request->cust_id)
                ->where('requestDone', "No")
                ->orderBy('requestSubmitDT', "DESC")->limit(3)->get();

                foreach($checkRequests as $checkRequest){
                    $lastRequest = $checkRequest->requestSubmitDT;
                }

                $endTime = Carbon::now();
                $rAppstartTime = Carbon::parse($lastRequest);
                $rAppDiffMinutes = $endTime->diffInMinutes($rAppstartTime);
                if ($rAppDiffMinutes >= 0){
                    if ($rAppDiffMinutes == 1){
                        $rAppDiffTime = 1;
                    } else {
                        $rAppDiffTime = "$rAppDiffMinutes";
                    }
                }

                if($rAppDiffTime <= 5){
                    $finalStatus = "You have requested too many times, please try again after 5 mins (3 request only per 5 mins)";
                } else {
                    $tableNumber = explode(',', $customerOrdering->tableNumbers);
    
                    CustomerLRequest::create([
                        'custOrdering_id' => $customerOrdering->id,
                        'cust_id' => $request->cust_id,
                        'tableNumber' => $mainTable,
                        'request' => $request->requestType,
                        'requestDone' => "No",
                        'requestSubmitDT' => date('Y-m-d H:i:s'),
                    ]);
                    $finalStatus = "Request sent! Kindly wait for the staff to come to your table. Thank you!";
                }
            } else {

                CustomerLRequest::create([
                    'custOrdering_id' => $customerOrdering->id,
                    'cust_id' => $request->cust_id,
                    'tableNumber' => $mainTable,
                    'request' => $request->requestType,
                    'requestDone' => "No",
                    'requestSubmitDT' => date('Y-m-d H:i:s'),
                ]);
                $finalStatus = "Request sent! Kindly wait for the staff to come to your table. Thank you!";
            }

        } else {
            $finalStatus = "Error";
        }

        return response()->json([
            'status' => $finalStatus,
        ]);
    }
    public function getOrderingBill($cust_id){
        $customerQueue = CustomerQueue::where('customer_id', $cust_id)->where('status', 'eating')->first();
        $customerReserve = CustomerReserve::where('customer_id', $cust_id)->where('status', 'eating')->first();

        if($customerQueue != null){
            $finalOrders = array();
            $orderSet = OrderSet::where('id', $customerQueue->orderSet_id)->first();
            $customerOrdering = CustomerOrdering::where('custBook_id', $customerQueue->id)->where('custBookType', 'queue')->first();
            $orders = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', "Yes")->get();

            $orderTotalPrice = 0.0;
            foreach ($orders as $order){
                $orderTotalPrice += $order->price;
                array_push($finalOrders, [
                    'foodItemName' => $order->foodItemName,
                    'quantity' => $order->quantity,
                    'price' => (number_format($order->price, 2, '.'))
                ]);
            }

            $finalReward = "None";
            $rewardDiscount = 0.00;
            if($customerQueue->rewardStatus == "Complete" && $customerQueue->rewardClaimed == "Yes"){
                switch($customerQueue->rewardType){
                    case "DSCN": 
                        $finalReward = "Discount $customerQueue->rewardInput% in a Total Bill";
                        $rewardDiscount = ($customerQueue->numberOfPersons * $orderSet->orderSetPrice) * ($customerQueue->rewardInput / 100);
                        break;
                    case "FRPE": 
                        $finalReward = "Free $customerQueue->rewardInput person in a group";
                        $rewardDiscount = $orderSet->orderSetPrice * $customerQueue->rewardInput;
                        break;
                    case "HLF": 
                        $finalReward = "Half in the group will be free";
                        $rewardDiscount = ($customerQueue->numberOfPersons * $orderSet->orderSetPrice) / 2;
                        break;
                    case "ALL": 
                        $finalReward = "All people in the group will be free";
                        $rewardDiscount = $customerQueue->numberOfPersons * $orderSet->orderSetPrice;
                        break;
                    default: 
                        $finalReward = "None";
                }
            }

            $seniorDiscount = $orderSet->orderSetPrice * ($customerQueue->numberOfPwd * 0.2);
            $childrenDiscount = $orderSet->orderSetPrice * ($customerQueue->numberOfChildren * ($customerQueue->childrenDiscount / 100));
            $subTotal = ($customerQueue->numberOfPersons * $orderSet->orderSetPrice) + $orderTotalPrice;

            $totalPrice = $subTotal - (
                $rewardDiscount + 
                $seniorDiscount + 
                $childrenDiscount + 
                $customerQueue->additionalDiscount + 
                $customerQueue->promoDiscount
            ) + $customerQueue->offenseCharges;

            // return response()->json($customerOrdering);
            return response()->json([
                'orderSetName' => $orderSet->orderSetName,
                'numberOfPersons' => $customerQueue->numberOfPersons,
                'orderSetPriceTotal' => (number_format($customerQueue->numberOfPersons * $orderSet->orderSetPrice, 2, '.')),
                'orders' => $finalOrders,
                'subtotal' => (number_format($subTotal, 2, '.')),
                'numberOfPwd' => $customerQueue->numberOfPwd,
                'pwdDiscount' =>  (number_format($seniorDiscount, 2, '.')),
                'childrenPercentage' => $customerQueue->childrenDiscount,
                'numberOfChildren' => $customerQueue->numberOfChildren,
                'childrenDiscount' => (number_format($childrenDiscount, 2, '.')),
                'promoDiscount' => (number_format($customerQueue->promoDiscount, 2, '.')),
                'additionalDiscount' => (number_format($customerQueue->additionalDiscount, 2, '.')),
                'rewardName' => $finalReward,
                'rewardDiscount' => (number_format($rewardDiscount, 2, '.')),
                'offenseCharge' => (number_format($customerQueue->offenseCharges, 2, '.')),
                'totalPrice' => (number_format($totalPrice, 2, '.')),
            ]);
        } else if ($customerReserve != null){
            $finalOrders = array();
            $orderSet = OrderSet::where('id', $customerReserve->orderSet_id)->first();
            $customerOrdering = CustomerOrdering::where('custBook_id', $customerReserve->id)->where('custBookType', 'reserve')->first();
            $orders = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', "Yes")->get();

            $orderTotalPrice = 0.0;
            foreach ($orders as $order){
                $orderTotalPrice += $order->price;
                array_push($finalOrders, [
                    'foodItemName' => $order->foodItemName,
                    'quantity' => $order->quantity,
                    'price' => (number_format($order->price, 2, '.'))
                ]);
            }

            $finalReward = "None";
            $rewardDiscount = 0.00;
            if($customerReserve->rewardStatus == "Complete" && $customerReserve->rewardClaimed == "Yes"){
                switch($customerReserve->rewardType){
                    case "DSCN": 
                        $finalReward = "Discount $customerReserve->rewardInput% in a Total Bill";
                        $rewardDiscount = ($customerReserve->numberOfPersons * $orderSet->orderSetPrice) * ($customerReserve->rewardInput / 100);
                        break;
                    case "FRPE": 
                        $finalReward = "Free $customerReserve->rewardInput person in a group";
                        $rewardDiscount = $orderSet->orderSetPrice * $customerReserve->rewardInput;
                        break;
                    case "HLF": 
                        $finalReward = "Half in the group will be free";
                        $rewardDiscount = ($customerReserve->numberOfPersons * $orderSet->orderSetPrice) / 2;
                        break;
                    case "ALL": 
                        $finalReward = "All people in the group will be free";
                        $rewardDiscount = $customerReserve->numberOfPersons * $orderSet->orderSetPrice;
                        break;
                    default: 
                        $finalReward = "None";
                }
            }

            $seniorDiscount = $orderSet->orderSetPrice * ($customerReserve->numberOfPwd * 0.2);
            $childrenDiscount = $orderSet->orderSetPrice * ($customerReserve->numberOfChildren * ($customerReserve->childrenDiscount / 100));
            $subTotal = ($customerReserve->numberOfPersons * $orderSet->orderSetPrice) + $orderTotalPrice;

            $totalPrice = $subTotal - (
                $rewardDiscount + 
                $seniorDiscount + 
                $childrenDiscount + 
                $customerReserve->additionalDiscount + 
                $customerReserve->promoDiscount
            ) + $customerReserve->offenseCharges;

            return response()->json([
                'orderSetName' => $orderSet->orderSetName,
                'numberOfPersons' => $customerReserve->numberOfPersons,
                'orderSetPriceTotal' => (number_format($customerReserve->numberOfPersons * $orderSet->orderSetPrice, 2, '.')),
                'orders' => $finalOrders,
                'subtotal' => (number_format($subTotal, 2, '.')),
                'numberOfPwd' => $customerReserve->numberOfPwd,
                'pwdDiscount' =>  (number_format($seniorDiscount, 2, '.')),
                'childrenPercentage' => $customerReserve->childrenDiscount,
                'numberOfChildren' => $customerReserve->numberOfChildren,
                'childrenDiscount' => (number_format($childrenDiscount, 2, '.')),
                'promoDiscount' => (number_format($customerReserve->promoDiscount, 2, '.')),
                'additionalDiscount' => (number_format($customerReserve->additionalDiscount, 2, '.')),
                'rewardName' => $finalReward,
                'rewardDiscount' => (number_format($rewardDiscount, 2, '.')),
                'offenseCharge' => (number_format($customerReserve->offenseCharges, 2, '.')),
                'totalPrice' => (number_format($totalPrice, 2, '.')),
            ]);
        } else {
            $finalStatus = "Error";
        }
    }

    public function orderingCheckout(Request $request){
        $finalStatus = null;
        $customerQueue = CustomerQueue::where('customer_id', $request->cust_id)->where('status', 'eating')->first();
        $customerReserve = CustomerReserve::where('customer_id', $request->cust_id)->where('status', 'eating')->first();

        
        if($customerQueue != null){
            $customerOrdering = CustomerOrdering::where('custBook_id', $customerQueue->id)->where('custBookType', 'queue')->first();
            $tableNumber = explode(',', $customerOrdering->tableNumbers);

            if($request->requestType == "Cash Payment"){
                CustomerQueue::where('id', $customerOrdering->custBook_id)
                ->update([
                    'checkoutStatus' => 'cashCheckoutValidation',
                ]);
                $finalStatus = "Cash Payment";
            } else {
                CustomerQueue::where('id', $customerOrdering->custBook_id)
                ->update([
                    'checkoutStatus' => 'gcashCheckout',
                ]);
                $finalStatus = "GCash Payment";
            }

            CustomerLRequest::create([
                'custOrdering_id' => $customerOrdering->id,
                'cust_id' => $request->cust_id,
                'tableNumber' => $tableNumber[0],
                'request' => $request->requestType,
                'requestDone' => "No",
                'requestSubmitDT' => date('Y-m-d H:i:s'),
            ]);
            
            $customerQrAccess = CustomerQrAccess::where('custOrdering_id', $customerOrdering->id)->get();
            if(!$customerQrAccess->isEmpty()){
                foreach ($customerQrAccess as $customerQrA){
                    if($customerQrA->status == "pending"){
                        CustomerQrAccess::where('id', $customerQrA->id)
                        ->update([
                            'status' => "ignored"
                        ]);
                    } else if ($customerQrA->status == "approved"){
                        $customer = CustomerAccount::where('id', $customerQrA->subCust_id)->first();
                        $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/samquicksalLogo.png';
                        if($customer != null){
                            $to = $customer->deviceToken;
                            $notification = array(
                                'title' => "Hi $customer->name, thank you for ordering together with your friends!",
                                'body' => "You will not access it anymore because your friend did checkout already. Thank you and have a nice day!",
                            );
                            $data = array(
                                'notificationType' => "QR Access Complete",
                                'notificationId' => 0,
                                'notificationRLogo' => $finalImageUrl,
                            );
                            $this->sendFirebaseNotification($to, $notification, $data);
                        }
                        
                        CustomerQrAccess::where('id', $customerQrA->id)
                        ->update([
                            'status' => "completed"
                        ]);
                    } else {}
                }
            }
        } else if ($customerReserve != null){
            $customerOrdering = CustomerOrdering::where('custBook_id', $customerReserve->id)->where('custBookType', 'reserve')->first();

            $tableNumber = explode(',', $customerOrdering->tableNumbers);

            if($request->requestType == "Cash Payment"){
                CustomerReserve::where('id', $customerOrdering->custBook_id)
                ->update([
                    'checkoutStatus' => 'cashCheckoutValidation',
                ]);
                $finalStatus = "Cash Payment";
            } else {
                CustomerReserve::where('id', $customerOrdering->custBook_id)
                ->update([
                    'checkoutStatus' => 'gcashCheckout',
                ]);
                $finalStatus = "GCash Payment";
            }

            CustomerLRequest::create([
                'custOrdering_id' => $customerOrdering->id,
                'cust_id' => $request->cust_id,
                'tableNumber' => $tableNumber[0],
                'request' => $request->requestType,
                'requestDone' => "No",
                'requestSubmitDT' => date('Y-m-d H:i:s'),
            ]);
            
            $customerQrAccess = CustomerQrAccess::where('custOrdering_id', $customerOrdering->id)->get();
            if(!$customerQrAccess->isEmpty()){
                foreach ($customerQrAccess as $customerQrA){
                    if($customerQrA->status == "pending"){
                        CustomerQrAccess::where('id', $customerQrA->id)
                        ->update([
                            'status' => "ignored"
                        ]);
                    } else if ($customerQrA->status == "approved"){
                        $customer = CustomerAccount::where('id', $customerQrA->subCust_id)->first();
                        $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/samquicksalLogo.png';
                        if($customer != null){
                            $to = $customer->deviceToken;
                            $notification = array(
                                'title' => "Hi $customer->name, thank you for ordering together with your friends!",
                                'body' => "You will not access it anymore because your friend did checkout already. Thank you and have a nice day!",
                            );
                            $data = array(
                                'notificationType' => "QR Access Complete",
                                'notificationId' => 0,
                                'notificationRLogo' => $finalImageUrl,
                            );
                            $this->sendFirebaseNotification($to, $notification, $data);
                        }
                        
                        CustomerQrAccess::where('id', $customerQrA->id)
                        ->update([
                            'status' => "completed"
                        ]);
                    } else {}
                }
            }
        } else {
            $finalStatus = "Error";
        }

        return response()->json([
            'status' => $finalStatus
        ]);
    }
    public function getOrderingCheckoutStatus($cust_id){
        $finalStatus = null;
        $customerQueue = CustomerQueue::where('customer_id', $cust_id)->where('status', 'eating')
        ->where(function ($query) {
            $query->where('checkoutStatus', "cashCheckoutValidation")
            ->orWhere('checkoutStatus', "gcashCheckoutValidation")
            ->orWhere('checkoutStatus', "gcashInsufficientAmount");
        })->first();

        $customerReserve = CustomerReserve::where('customer_id', $cust_id)->where('status', 'eating')
        ->where(function ($query) {
            $query->where('checkoutStatus', "cashCheckoutValidation")
            ->orWhere('checkoutStatus', "gcashCheckoutValidation")
            ->orWhere('checkoutStatus', "gcashInsufficientAmount");
        })->first();


        if($customerQueue != null){
            $finalStatus = $customerQueue->checkoutStatus;
        } else if ($customerReserve != null){
            $finalStatus = $customerReserve->checkoutStatus;
        } else {
            $finalStatus = "Error";
        }

        return response()->json([
            'status' => $finalStatus
        ]);
    }
    public function getOrderingGCashStatus($cust_id){
        $finalAmount = null;
        $finalRestGCQr = null;
        $finalStatus = null;
        $book_id = null;
        $book_type = null;

        $customerQueue = CustomerQueue::where('customer_id', $cust_id)->where('status', 'eating')
        ->where(function ($query) {
            $query->where('checkoutStatus', "gcashCheckout")
            ->orWhere('checkoutStatus', "gcashIncorretFormat");
        })->first();

        $customerReserve = CustomerReserve::where('customer_id', $cust_id)->where('status', 'eating')
        ->where(function ($query) {
            $query->where('checkoutStatus', "gcashCheckout")
            ->orWhere('checkoutStatus', "gcashIncorretFormat");
        })->first();

        if($customerQueue != null){
            $restaurant = RestaurantAccount::select('rGcashQrCodeImage', 'id')->where('id', $customerQueue->restAcc_id)->first();
            $finalRestGCQr = $this->RESTAURANT_GCASH_QR_IMAGE_PATH."/".$restaurant->id."/".$restaurant->rGcashQrCodeImage;
            $finalStatus = $customerQueue->checkoutStatus;
            $book_id = $customerQueue->id;
            $book_type = "queue";

            $customerOrdering = CustomerOrdering::where('custBook_id', $customerQueue->id)
            ->where('restAcc_id', $customerQueue->restAcc_id)
            ->where('custBookType', 'queue')
            ->first();

            $customerOrders = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', 'Yes')->get();
            $addOns = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', 'Yes')->sum('price');
            $subTotal = ($customerQueue->orderSetPrice * $customerQueue->numberOfPersons) + $addOns;

            $rewardDiscount = null;
            if($customerQueue->rewardStatus == "Complete" && $customerQueue->rewardClaimed == "Yes"){
                switch($customerQueue->rewardType){
                    case "DSCN": 
                        $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) * ($customerQueue->rewardInput / 100);
                        break;
                    case "FRPE": 
                        $rewardDiscount = $customerQueue->orderSetPrice * $customerQueue->rewardInput;
                        break;
                    case "HLF": 
                        $rewardDiscount = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) / 2;
                        break;
                    case "ALL": 
                        $rewardDiscount = $customerQueue->numberOfPersons * $customerQueue->orderSetPrice;
                        break;
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

            $finalAmount = (number_format($totalPrice, 2, '.'));

        } else if($customerReserve != null){
            $restaurant = RestaurantAccount::select('rGcashQrCodeImage', 'id')->where('id', $customerReserve->restAcc_id)->first();
            $finalRestGCQr = $this->RESTAURANT_GCASH_QR_IMAGE_PATH."/".$restaurant->id."/".$restaurant->rGcashQrCodeImage;
            $finalStatus = $customerReserve->checkoutStatus;
            $book_id = $customerReserve->id;
            $book_type = "reserve";

            $customerOrdering = CustomerOrdering::where('custBook_id', $customerReserve->id)
            ->where('restAcc_id', $customerReserve->restAcc_id)
            ->where('custBookType', 'reserve')
            ->first();

            $customerOrders = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', 'Yes')->get();
            $addOns = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', 'Yes')->sum('price');
            $subTotal = ($customerReserve->orderSetPrice * $customerReserve->numberOfPersons) + $addOns;

            $rewardDiscount = null;
            if($customerReserve->rewardStatus == "Complete" && $customerReserve->rewardClaimed == "Yes"){
                switch($customerReserve->rewardType){
                    case "DSCN": 
                        $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) * ($customerReserve->rewardInput / 100);
                        break;
                    case "FRPE": 
                        $rewardDiscount = $customerReserve->orderSetPrice * $customerReserve->rewardInput;
                        break;
                    case "HLF": 
                        $rewardDiscount = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) / 2;
                        break;
                    case "ALL": 
                        $rewardDiscount = $customerReserve->numberOfPersons * $customerReserve->orderSetPrice;
                        break;
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

            $finalAmount = (number_format($totalPrice, 2, '.'));
        } else {}

        return response()->json([
            'amount' => $finalAmount,
            'restGCashQr' => $finalRestGCQr,
            'status' => $finalStatus,
            'filename' => $restaurant->rGcashQrCodeImage,
            'book_id' => $book_id,
            'book_type' => $book_type,
        ]);
    }
    public function getOrderingRatingFeedback($cust_id){
        $finalStatus = null;

        $customerQueue = CustomerQueue::where('customer_id', $cust_id)->where('status', 'completed')->where('checkoutStatus', "customerFeedback")->first();
        $customerReserve = CustomerReserve::where('customer_id', $cust_id)->where('status', 'completed')->where('checkoutStatus', "customerFeedback")->first();

        if($customerQueue != null){
            $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $customerQueue->restAcc_id)->first();
            $finalStatus = "$restaurant->rName, $restaurant->rBranch";
        } else {
            $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $customerReserve->restAcc_id)->first();
            $finalStatus = "$restaurant->rName, $restaurant->rBranch";
        }

        return response()->json([
            'rName' => $finalStatus
        ]);
    }
    public function ratingFeedbackSubmit(Request $request){
        $getDateToday = date("Y-m-d");
        $customerQueue = CustomerQueue::where('customer_id', $request->cust_id)->where('status', 'completed')->where('checkoutStatus', "customerFeedback")->first();
        $customerReserve = CustomerReserve::where('customer_id', $request->cust_id)->where('status', 'completed')->where('checkoutStatus', "customerFeedback")->first();
        
        if($request->type == "not now"){
            if($customerQueue != null){
                CustomerQueue::where('customer_id', $request->cust_id)->where('status', 'completed')->where('checkoutStatus', "customerFeedback")
                ->update([
                    'checkoutStatus' => "completed"
                ]);
            } else {
                CustomerReserve::where('customer_id', $request->cust_id)->where('status', 'completed')->where('checkoutStatus', "customerFeedback")
                ->update([
                    'checkoutStatus' => "completed"
                ]);
            }
        } else {
            if($customerQueue != null){
                $customerOrdering = CustomerOrdering::where('custBook_id', $customerQueue->id)->first();
                $restaurant = RestaurantAccount::where('id', $customerQueue->restAcc_id)->first();
                $stampCard = StampCard::where('restAcc_id', $customerQueue->restAcc_id)->latest()->first();

                if($stampCard != null){
                    if($getDateToday <= $stampCard->stampValidity){
                        $custStampCard = CustomerStampCard::where('customer_id', $customerQueue->customer_id)
                        ->where('restAcc_id', $restaurant->id)
                        ->where('stampValidity', $stampCard->stampValidity)
                        ->latest()
                        ->first();

                        if($customerQueue->rewardClaimed == "Yes"){
                            CustomerStampCard::where('id', $custStampCard->id)
                            ->update([
                                'claimed' => "Yes",
                            ]);
                        } else {
                            $storeTasks = array();
                            $storeDoneTasks = array();
    
                            if($custStampCard != null){
                                $stampCardTasks = StampCardTasks::where('stampCards_id', $stampCard->id)->get();
                                
                                foreach($stampCardTasks as $stampCardTask){
                                    $task = RestaurantTaskList::where('id', $stampCardTask->restaurantTaskLists_id)->first();
                                    if($task->taskCode == "FDBK"){
                                        array_push($storeDoneTasks, "Give a feedback/review per visit");
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
                                $stampCardTasks = StampCardTasks::where('stampCards_id', $stampCard->id)->get();
                                foreach($stampCardTasks as $stampCardTask){
                                    $task = RestaurantTaskList::where('id', $stampCardTask->restaurantTaskLists_id)->first();
                                    if($task->taskCode == "FDBK"){
                                        array_push($storeTasks, "Give a feedback/review per visit");
                                        array_push($storeDoneTasks, "Give a feedback/review per visit");
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
                                $status = "Incomplete";
                                if(sizeof($storeDoneTasks) >= $stampCard->stampCapacity){
                                    $finalStampCapac = $stampCard->stampCapacity;
                                    $status = "Complete";
                                }
    
                                $custStampCardNew = CustomerStampCard::create([
                                    'customer_id' => $customerQueue->customer_id,
                                    'restAcc_id' => $customerQueue->restAcc_id,
                                    'status' => $status,
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

                CustRestoRating::create([
                    'customer_id' => $customerQueue->customer_id,
                    'restAcc_id' => $customerQueue->restAcc_id,
                    'custOrdering_id' => $customerOrdering->id,
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                    'anonymous' => $request->anonymous,
                ]);

                CustomerQueue::where('customer_id', $request->cust_id)->where('status', 'completed')->where('checkoutStatus', "customerFeedback")
                ->update([
                    'checkoutStatus' => "completed",
                ]);
            } else {
                $customerOrdering = CustomerOrdering::where('custBook_id', $customerReserve->id)->first();
                $restaurant = RestaurantAccount::where('id', $customerReserve->restAcc_id)->first();
                $stampCard = StampCard::where('restAcc_id', $customerReserve->restAcc_id)->first();

                if($stampCard != null){
                    if($getDateToday <= $stampCard->stampValidity){
                        $custStampCard = CustomerStampCard::where('customer_id', $customerReserve->customer_id)
                        ->where('restAcc_id', $restaurant->id)
                        ->where('stampValidity', $stampCard->stampValidity)
                        ->where('claimed', "No")
                        ->latest()
                        ->first();

                        if(!$custStampCard != null){
                            if($customerReserve->rewardClaimed == "Yes"){
                                CustomerStampCard::where('id', $custStampCard->id)
                                ->update([
                                    'claimed' => "Yes",
                                ]);
                            } else {
                                $storeTasks = array();
                                $storeDoneTasks = array();
    
                                if($custStampCard != null){
                                    $stampCardTasks = StampCardTasks::where('stampCards_id', $stampCard->id)->get();
                                    
                                    foreach($stampCardTasks as $stampCardTask){
                                        $task = RestaurantTaskList::where('id', $stampCardTask->restaurantTaskLists_id)->first();
                                        if($task->taskCode == "FDBK"){
                                            array_push($storeDoneTasks, "Give a feedback/review per visit");
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
                                    $stampCardTasks = StampCardTasks::where('stampCards_id', $stampCard->id)->get();
                                    foreach($stampCardTasks as $stampCardTask){
                                        $task = RestaurantTaskList::where('id', $stampCardTask->restaurantTaskLists_id)->first();
                                        if($task->taskCode == "FDBK"){
                                            array_push($storeTasks, "Give a feedback/review per visit");
                                            array_push($storeDoneTasks, "Give a feedback/review per visit");
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
                                    $status = "Incomplete";
                                    if(sizeof($storeDoneTasks) >= $stampCard->stampCapacity){
                                        $finalStampCapac = $stampCard->stampCapacity;
                                        $status = "Complete";
                                    }
    
                                    $custStampCardNew = CustomerStampCard::create([
                                        'customer_id' => $customerReserve->customer_id,
                                        'restAcc_id' => $customerReserve->restAcc_id,
                                        'status' => $status,
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

                CustRestoRating::create([
                    'customer_id' => $customerReserve->customer_id,
                    'restAcc_id' => $customerReserve->restAcc_id,
                    'custOrdering_id' => $customerOrdering->id,
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                    'anonymous' => $request->anonymous,
                ]);

                CustomerReserve::where('customer_id', $request->cust_id)->where('status', 'completed')->where('checkoutStatus', "customerFeedback")
                ->update([
                    'checkoutStatus' => "completed",
                ]);
            }
        }

        return response()->json([
            'status' => "sucess"
        ]);
    }

    public function getStampCards($cust_id){
        $finalStampCards = array();

        $customerStamps = CustomerStampCard::where('customer_id', $cust_id)->orderBy('id', "DESC")->get();
        if(!$customerStamps->isEmpty()){
            foreach($customerStamps as $customerStamp){
                $restaurant = RestaurantAccount::select('id', 'rLogo', 'rName', 'rBranch')->where('id', $customerStamp->restAcc_id)->first();

                $finalImageUrl = "";
                if ($restaurant->rLogo == ""){
                    $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                } else {
                    $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                }

                $validity = explode('-', $customerStamp->stampValidity);
                $month = $this->convertMonths($validity[1]);
                $year = $validity[0];
                $day = $validity[2];

                array_push($finalStampCards, [
                    'stamp_id' => $customerStamp->id,
                    'rLogo' => $finalImageUrl,
                    'stampReward' => $customerStamp->stampReward,
                    'rAddress' => "$restaurant->rName, $restaurant->rBranch",
                    'stampValidity' => "Valid until: $month $day, $year",
                    'stampClaimed' => $customerStamp->claimed,
                    'currentNumStamp' => $customerStamp->currentStamp,
                    'stampCapacity' => $customerStamp->stampCapacity,
                ]);
            }
        } else {
            $finalStampCards = null;
        }
        return response()->json($finalStampCards);
    }

    public function getStampCardDetails($stamp_id){
        $finalStampTasks = array();
        $finalStampDoneTasks = array();

        $stampCard = CustomerStampCard::where('id', $stamp_id)->first();
        $stampTasks = CustomerStampTasks::where('customerStampCard_id', $stamp_id)->get();
        foreach($stampTasks as $stampTask){
            array_push($finalStampTasks, $stampTask->taskName);
        }

        $stampDoneTasks = CustomerTasksDone::where('customerStampCard_id', $stamp_id)->get();
        foreach($stampDoneTasks as $stampDoneTask){
            
            $stampDoneTaskDate = explode('-', $stampDoneTask->taskAccomplishDate);
            $month = $this->convertMonths($stampDoneTaskDate[1]);
            $year = $stampDoneTaskDate[0];
            $day = $stampDoneTaskDate[2];

            array_push($finalStampDoneTasks, [
                'taskName' => $stampDoneTask->taskName,
                'taskDate' => "$month $day, $year",
            ]);
        }


        $validity = explode('-', $stampCard->stampValidity);
        $month = $this->convertMonths($validity[1]);
        $year = $validity[0];
        $day = $validity[2];

        return response()->json([
            'stampReward' => $stampCard->stampReward,
            'stampValidity' => "Valid until: $month $day, $year",
            'stampTasks' => $finalStampTasks,
            'stampDoneTasks' => $finalStampDoneTasks,
        ]);
    }
    public function getBookingHistoryComplete(Request $request){
        if($request->book_type == "queue"){
            $customerQueue = CustomerQueue::where('id', $request->book_id)->where('customer_id', $request->cust_id)->first();
            $restaurant = RestaurantAccount::select('rName', 'rAddress', 'rBranch', 'rCity')->where('id', $customerQueue->restAcc_id)->first();

            $bookDate = explode('-', $customerQueue->queueDate);
            $year = $bookDate[0];
            $month = $this->convertMonths($bookDate[1]);
            $day  = $bookDate[2];
            $checkIn = date("g:i a", strtotime($customerQueue->eatingDateTime));
            $checkOut = date("g:i a", strtotime($customerQueue->completeDateTime));

            $customerOrdering = CustomerOrdering::where('custBook_id', $customerQueue->id)->where('custBookType', "queue")->first();

            $finalOrders = array();
            $orders = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->get();

            $orderTotalPrice = 0.0;
            foreach ($orders as $order){
                $orderTotalPrice += $order->price;
                array_push($finalOrders, [
                    'foodItemName' => $order->foodItemName,
                    'quantity' => $order->quantity,
                    'price' => (number_format($order->price, 2, '.'))
                ]);
            }

            $finalReward = "None";
            $rewardDiscount = 0.00;
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
            $subTotal = ($customerQueue->numberOfPersons * $customerQueue->orderSetPrice) + $orderTotalPrice;

            $totalPrice = $subTotal - (
                $rewardDiscount + 
                $seniorDiscount + 
                $childrenDiscount + 
                $customerQueue->additionalDiscount + 
                $customerQueue->promoDiscount
            ) + $customerQueue->offenseCharges;

            return response()->json([
                'bookDate' => "$month $day, $year",
                'checkIn' => $checkIn,
                'checkOut' => $checkOut,
                'rName' => $restaurant->rName,
                'rAddress' => "$restaurant->rAddress, $restaurant->rBranch, $restaurant->rCity",
                'tableNumber' => $customerOrdering->tableNumbers,
                'numberOfPersons' => $customerQueue->numberOfPersons,
                'bookingType' => "Queue",

                'orderSetName' => $customerQueue->orderSetName,
                'numberOfPersons' => $customerQueue->numberOfPersons,
                'orderSetPriceTotal' => (number_format($customerQueue->numberOfPersons * $customerQueue->orderSetPrice, 2, '.')),
                'orders' => $finalOrders,
                'subtotal' => (number_format($subTotal, 2, '.')),
                'numberOfPwd' => $customerQueue->numberOfPwd,
                'pwdDiscount' =>  (number_format($seniorDiscount, 2, '.')),
                'childrenPercentage' => $customerQueue->childrenDiscount,
                'numberOfChildren' => $customerQueue->numberOfChildren,
                'childrenDiscount' => (number_format($childrenDiscount, 2, '.')),
                'promoDiscount' => (number_format($customerQueue->promoDiscount, 2, '.')),
                'additionalDiscount' => (number_format($customerQueue->additionalDiscount, 2, '.')),
                'rewardName' => $finalReward,
                'rewardDiscount' => (number_format($rewardDiscount, 2, '.')),
                'offenseCharge' => (number_format($customerQueue->offenseCharges, 2, '.')),
                'totalPrice' => (number_format($totalPrice, 2, '.')),
            ]);
        } else {
            $customerReserve = CustomerReserve::where('id', $request->book_id)->where('customer_id', $request->cust_id)->first();
            $restaurant = RestaurantAccount::select('rName', 'rAddress', 'rBranch', 'rCity')->where('id', $customerReserve->restAcc_id)->first();

            $bookDate = explode('-', $customerReserve->reserveDate);
            $year = $bookDate[0];
            $month = $this->convertMonths($bookDate[1]);
            $day  = $bookDate[2];
            $checkIn = date("g:i a", strtotime($customerReserve->eatingDateTime));
            $checkOut = date("g:i a", strtotime($customerReserve->completeDateTime));

            $customerOrdering = CustomerOrdering::where('custBook_id', $customerReserve->id)->where('custBookType', "reserve")->first();

            $finalOrders = array();
            $orders = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->get();

            $orderTotalPrice = 0.0;
            foreach ($orders as $order){
                $orderTotalPrice += $order->price;
                array_push($finalOrders, [
                    'foodItemName' => $order->foodItemName,
                    'quantity' => $order->quantity,
                    'price' => (number_format($order->price, 2, '.'))
                ]);
            }

            $finalReward = "None";
            $rewardDiscount = 0.00;
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
            $subTotal = ($customerReserve->numberOfPersons * $customerReserve->orderSetPrice) + $orderTotalPrice;

            $totalPrice = $subTotal - (
                $rewardDiscount + 
                $seniorDiscount + 
                $childrenDiscount + 
                $customerReserve->additionalDiscount + 
                $customerReserve->promoDiscount +
                $customerReserve->offenseCharges 
            );

            return response()->json([
                'bookDate' => "$month $day, $year",
                'checkIn' => $checkIn,
                'checkOut' => $checkOut,
                'rName' => $restaurant->rName,
                'rAddress' => "$restaurant->rAddress, $restaurant->rBranch, $restaurant->rCity",
                'tableNumber' => $customerOrdering->tableNumbers,
                'numberOfPersons' => $customerReserve->numberOfPersons,
                'bookingType' => "Reserve",

                'orderSetName' => $customerReserve->orderSetName,
                'numberOfPersons' => $customerReserve->numberOfPersons,
                'orderSetPriceTotal' => (number_format($customerReserve->numberOfPersons * $customerReserve->orderSetPrice, 2, '.')),
                'orders' => $finalOrders,
                'subtotal' => (number_format($subTotal, 2, '.')),
                'numberOfPwd' => $customerReserve->numberOfPwd,
                'pwdDiscount' =>  (number_format($seniorDiscount, 2, '.')),
                'childrenPercentage' => $customerReserve->childrenDiscount,
                'numberOfChildren' => $customerReserve->numberOfChildren,
                'childrenDiscount' => (number_format($childrenDiscount, 2, '.')),
                'promoDiscount' => (number_format($customerReserve->promoDiscount, 2, '.')),
                'additionalDiscount' => (number_format($customerReserve->additionalDiscount, 2, '.')),
                'rewardName' => $finalReward,
                'rewardDiscount' => (number_format($rewardDiscount, 2, '.')),
                'offenseCharge' => (number_format($customerReserve->offenseCharges, 2, '.')),
                'totalPrice' => (number_format($totalPrice, 2, '.')),
            ]);
        }
    }
    public function getRestaurantsReviews($rest_id){
        $finalCustReviews = array();

        $reviews = CustRestoRating::where('restAcc_id', $rest_id)->orderBy('id', "DESC")->get();
        $countReviews = 0;
        $sumRating = 0.0;
        $averageRating = 0.0;
        if(!$reviews->isEmpty()){
            foreach($reviews as $review){
                $countReviews++;
                $sumRating += $review->rating;
                $date = strtotime($review->created_at);
                if($review->anonymous == "Yes"){
                    array_push($finalCustReviews, [
                        'custImage' => $this->ACCOUNT_NO_IMAGE_PATH.'/user-default.png',
                        'custName' => "Anonymous",
                        'custRating' => $review->rating,
                        'custComment' => $review->comment,
                        'custRateDate' => date('M j ', $date),
                    ]);
                } else {
                    $customer = CustomerAccount::where('id', $review->customer_id)->first();
    
                    $finalImageUrl = "";
                    if($customer->profileImage == null){
                        $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/user-default.png';
                    } else {
                        $finalImageUrl = $this->CUSTOMER_IMAGE_PATH.'/'.$customer->id.'/'. $customer->profileImage;
                    }
    
                    array_push($finalCustReviews, [
                        'custImage' => $finalImageUrl,
                        'custName' => $customer->name,
                        'custRating' => $review->rating,
                        'custComment' => $review->comment,
                        'custRateDate' => date('M j ', $date),
                    ]);
                }
            }
            $averageRating = $sumRating / $countReviews;
        } else {
            $finalCustReviews = null;
        }

        return response()->json([
            'averageRating' => (number_format($averageRating, 1, '.')),
            'countReviews' => $countReviews,
            'custReviews' => $finalCustReviews,
        ]);
    }
    public function getListOfRatedRestaurants(){
        $finalRatedRestaurants = array();

        $restaurants = RestaurantAccount::select('id', 'rName', 'rCity', 'rBranch', 'rLogo')
        ->where('status', "Published")
        ->get();

        if(!$restaurants->isEmpty()){
            $count = 1;
            foreach($restaurants as $restaurant){
                if($count <= 5){
                    $finalImageUrl = "";
                    if ($restaurant->rLogo == ""){
                        $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                    } else {
                        $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                    }
        
                    $restoRatings = CustRestoRating::where('restAcc_id', $restaurant->id)->get();
                    if(!$restoRatings->isEmpty()){
                        $countReviews = 0;
                        $sumRating = 0.0;
        
                        foreach($restoRatings as $restoRating){
                            $countReviews++;
                            $sumRating += $restoRating->rating;
                        }
        
                        $averageRating = $sumRating / $countReviews;
                        array_push($finalRatedRestaurants, [
                            'restId' => $restaurant->id, 
                            'rName' => $restaurant->rName, 
                            'rAddress' => "$restaurant->rBranch, $restaurant->rCity",
                            'rLogo' => $finalImageUrl, 
                            'rRating' => (number_format($averageRating, 1, '.')),
                            'rCountReview' => "$countReviews",
                        ]);
                    } else {
                        array_push($finalRatedRestaurants, [
                            'restId' => $restaurant->id, 
                            'rName' => $restaurant->rName, 
                            'rAddress' => "$restaurant->rBranch, $restaurant->rCity",
                            'rLogo' => $finalImageUrl, 
                            'rRating' => "0.0", 
                            'rCountReview' => "0",
                        ]);
                    }
                    $count++;
                }
            }
    
            usort($finalRatedRestaurants, function($a, $b) {
                return strtotime($b['rRating']) - strtotime($a['rRating']);
            });
        }
        
        return response()->json($finalRatedRestaurants);
    }
    public function geofenceNotifyCustomer(Request $request){
        $getDateToday = date('Y-m-d');
        $cust_id = $request->cust_id;
        $cust_lat = $request->cust_lat;
        $cust_long = $request->cust_long;

        $finalResult = array();
        if($cust_lat != $cust_long){
            $restaurants = RestaurantAccount::select('id', 'rName', 'rAddress', 'rBranch', 'rLogo', 'rLatitudeLoc', 'rLongitudeLoc', 'rRadius')->where('status', "Published")->get();
            $storeGeo = array();
            foreach ($restaurants as $restaurant){
                $rest_lat = $restaurant->rLatitudeLoc;
                $rest_long = $restaurant->rLongitudeLoc;
                if ($rest_lat != $rest_long) {
                    //calculate distance
                    $meters = 0;
                    $R = 6371;
                    $lat = $rest_lat - $cust_lat;
                    $long = $rest_long - $cust_long;

                    $dLat = deg2rad($lat);
                    $dLong = deg2rad($long);

                    $a = 
                        sin($dLat/2) * sin($dLat/2) +
                        cos(deg2rad($cust_lat)) * cos(deg2rad($rest_lat)) *
                        sin($dLong/2) * sin($dLong/2);
                    
                    $c = 2 * atan2(sqrt($a), sqrt(1-$a));

                    $DistanceKM = $R * $c;
                    $DistanceMeter = $DistanceKM * 1000;

                    //i-check kung pasok
                    $CONST_RADIUS = $restaurant->rRadius;
                    if($CONST_RADIUS >= $DistanceMeter){

                        $finalImageUrl = "";
                        if ($restaurant->rLogo == ""){
                            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                        } else {
                            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                        }

                        array_push($storeGeo , [
                            'rest_id' => $restaurant->id,
                            'rName' => $restaurant->rName,
                            'rAddress' => "$restaurant->rAddress, $restaurant->rBranch",
                            'rBranch' => $restaurant->rBranch,
                            'rLogo' => $finalImageUrl,
                            'DistanceMeter' => intval($DistanceMeter),
                        ]);
                    }

                }
            }

            foreach($storeGeo as $existingNotif){
                $notifcations = CustomerNotification::where('customer_id', $cust_id)
                ->where('restAcc_id', $existingNotif['rest_id'])
                ->where('notificationType', 'Geofencing')
                ->orderBy('created_at', 'DESC')
                ->first();

                if($notifcations != null){
                    //existing na yung type ng ganong notif
                    $notifDate = explode(' ', $notifcations->created_at);

                    //check kung nag notif na ngayon araw
                    if($notifDate[0] != $getDateToday){
                        array_push($finalResult, [
                            'rest_id' => $existingNotif['rest_id'],
                            'rName' => $existingNotif['rName'],
                            'rAddress' => $existingNotif['rAddress'],
                            'rLogo' => $existingNotif['rLogo'],
                            'rBranch' => $existingNotif['rBranch'],
                        ]);
                    }
                } else {
                    //wala pang notif na ganon
                    array_push($finalResult, [
                        'rest_id' => $existingNotif['rest_id'],
                        'rName' => $existingNotif['rName'],
                        'rAddress' => $existingNotif['rAddress'],
                        'rLogo' => $existingNotif['rLogo'],
                        'rBranch' => $existingNotif['rBranch'],
                    ]);
                }
            }

            foreach($finalResult as $custNotif){
                $notif = CustomerNotification::create([
                    'customer_id' => $cust_id,
                    'restAcc_id' => $custNotif['rest_id'],
                    'notificationType' => "Geofencing",
                    'notificationTitle' => $custNotif['rName'] . " is near you! See the latest promos or menu",
                    'notificationDescription' => $custNotif['rAddress'],
                    'notificationStatus' => "Unread",
                ]);

                $customer = CustomerAccount::where('id', $cust_id)->first();
        
                if($customer != null){
                    $to = $customer->deviceToken;
                    $notification = array(
                        'title' => $custNotif['rName'].', '.$custNotif['rBranch'],
                        'body' => "It seems like you are near to our restaurant",
                    );
                    $data = array(
                        'notificationType' => "Geofencing",
                        'notificationId' => $notif->id,
                        'notificationRLogo' => $custNotif['rLogo'],
                    );
                    $this->sendFirebaseNotification($to, $notification, $data);
                }
            }
        } else {
            $finalResult = null;
        }
        return response()->json([
            'status' => "Success",
        ]);
    }

    public function getListOfNearbyRestaurants(Request $request){
        $cust_lat = $request->cust_lat;
        $cust_long = $request->cust_long;
        $CONST_RADIUS = 10000000000;
        $finalResult = array();


        if($cust_lat != $cust_long){
            $restaurants = RestaurantAccount::select('id', 'rName', 'rBranch', 'rAddress', 'rCity', 'rLogo', 'rLatitudeLoc', 'rLongitudeLoc')->where('status', "Published")->get();
            $storeGeo = array();

            foreach ($restaurants as $restaurant){
                $rest_lat = $restaurant->rLatitudeLoc;
                $rest_long = $restaurant->rLongitudeLoc;

                if ($rest_lat != $rest_long) {
                    //calculate distance
                    $meters = 0;
                    $R = 6371;
                    $lat = $rest_lat - $cust_lat;
                    $long = $rest_long - $cust_long;

                    $dLat = deg2rad($lat);
                    $dLong = deg2rad($long);

                    $a = 
                        sin($dLat/2) * sin($dLat/2) +
                        cos(deg2rad($cust_lat)) * cos(deg2rad($rest_lat)) *
                        sin($dLong/2) * sin($dLong/2);
                    
                    $c = 2 * atan2(sqrt($a), sqrt(1-$a));

                    $DistanceKM = $R * $c;
                    $DistanceMeter = $DistanceKM * 1000;

                    // 10,000 meters = 10km
                    if($CONST_RADIUS >= $DistanceMeter){
                        $finalImageUrl = "";
                        if ($restaurant->rLogo == ""){
                            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                        } else {
                            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
                        }
                        $tempDistance = $DistanceMeter;
                        $finalDistance = "";
                        if($DistanceKM >= 1){
                            $finalDistance = (number_format($DistanceKM, 1, '.'))." km away";
                        } else {
                            $finalDistance = (number_format($DistanceMeter, 1, '.'))." m away";
                        }

                        $rSchedule = $this->checkTodaySched($restaurant->id);

                        array_push($storeGeo , [
                            'rest_id' => $restaurant->id,
                            'rName' => $restaurant->rName,
                            'rAddress' => "$restaurant->rBranch, $restaurant->rCity",
                            'rLogo' => $finalImageUrl,
                            'rSchedule' => $rSchedule,
                            'distance' => $finalDistance,
                            'tempDistance' => $tempDistance,
                        ]);
                    }
                }
            }

            usort($storeGeo, function($a, $b) {
                return ($a['tempDistance']) - ($b['tempDistance']);
            });

            foreach($storeGeo as $temp){
                array_push($finalResult, [
                    'rest_id' => $temp['rest_id'],
                    'rName' => $temp['rName'],
                    'rAddress' => $temp['rAddress'],
                    'rLogo' => $temp['rLogo'],
                    'rSchedule' => $temp['rSchedule'],
                    'distance' => $temp['distance'],
                ]);
            }
            
        }
        if($storeGeo == null){
            $finalResult = null;
        }
        return response()->json($finalResult);
    }

    public function getNotificationCompleted($cust_id, $notif_id){
        $notification = CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)->where('notificationType', "Complete")->first();

        CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)
        ->update([
            'notificationStatus' => "Read"
        ]);
        $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $notification->restAcc_id)->first();
        
        $customerQueue = CustomerQueue::where('status', 'completed')->where('completeDateTime', $notification->created_at)->first();
        $customerReserve = CustomerReserve::where('status', 'completed')->where('completeDateTime', $notification->created_at)->first();

        $countTasks = 0;
        $stampCard_id = 0;
        $storeTasks = array();
        if($customerQueue != null){
            $getTasksDones = CustomerTasksDone::where('booking_id', $customerQueue->id)->where('booking_type', "queue")->get();
            if(!$getTasksDones->isEmpty()){
                $stampCard_id = $getTasksDones[0]->customerStampCard_id;
                foreach($getTasksDones as $getTasksDone){
                    array_push($storeTasks, $getTasksDone->taskName);
                    $countTasks++;
                }
            }

            $finalBookType = "queue";
            $finalBookId = $customerQueue->id;
        } else {
            $getTasksDones = CustomerTasksDone::where('booking_id', $customerReserve->id)->where('booking_type', "reserve")->get();
            if(!$getTasksDones->isEmpty()){
                $stampCard_id = $getTasksDones[0]->customerStampCard_id;
                foreach($getTasksDones as $getTasksDone){
                    array_push($storeTasks, $getTasksDone->taskName);
                    $countTasks++;
                }
            }

            $finalBookType = "reserve";
            $finalBookId = $customerReserve->id;
        }
        
        return response()->json([
            'restaurant' => "$restaurant->rName $restaurant->rBranch",
            'finalBookType' => $finalBookType,
            'finalBookId' => $finalBookId,
            'stampCardId' => $stampCard_id,
            'tasks' => $storeTasks,
            'countTasks' => $countTasks
        ]);
    }

    public function customerScanQr($cust_id, $request_cust_id){
        $subCustStatus = "none";

        $qrReport = explode(',', $request_cust_id);

        if($qrReport[0] == "s@mquicks@l"){
            $request_cust_id = $qrReport[1];

            // SUB CUST STATUS
            $customerQueueSub = CustomerQueue::where('customer_id', $cust_id)
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'declined')
            ->where('status', '!=', 'noShow')
            ->where('status', '!=', 'runaway')
            ->where('status', '!=', 'completed')
            ->orderBy('created_at', 'DESC')
            ->first();
    
            $customerReserveSub = CustomerReserve::where('customer_id', $cust_id)
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'declined')
            ->where('status', '!=', 'noShow')
            ->where('status', '!=', 'runaway')
            ->where('status', '!=', 'completed')
            ->orderBy('created_at', 'DESC')
            ->first();
    
            if($customerQueueSub != null){
                if($customerQueueSub->status == "eating"){
                    $subCustStatus = "onGoing";
                } else if ($customerQueueSub->status == "pending" 
                            || $customerQueueSub->status == "approved" 
                            || $customerQueueSub->status == "validation"
                            || $customerQueueSub->status == "tableSettingUp") {
                    $subCustStatus = "onGoing";
                } else {
                    $subCustStatus = "none";
                }
            } else if ($customerReserveSub != null) { 
                if($customerReserveSub->status == "eating"){
                    $subCustStatus = "onGoing";
                } else if ($customerReserveSub->status == "pending" 
                            || $customerReserveSub->status == "approved" 
                            || $customerReserveSub->status == "validation"
                            || $customerReserveSub->status == "tableSettingUp") {
                    $subCustStatus = "onGoing";
                } else {
                    $subCustStatus = "none";
                }
            } else {
                $subCustStatus = "none";
            }
    
    
            if($subCustStatus == "onGoing"){
                return response()->json([
                    'custId' => null,
                    'custImage' => null,
                    'custName' => null,
                    'custEAddress' => null,
                    'custCNumber' => null,
                    'custJDate' => null,
                    'bdRName' => null,
                    'bdRAddres' => null,
                    'bdOrderName' => null,
                    'bdBookType' => null,
                    'bdAccessSlot' => null,
                    'reqStatus' => "Ongoing Status",
                ]);
            } else {
                // MAIN CUST STATUS
                $customer = CustomerAccount::where('id', $request_cust_id)->first();
                $customerQueue = CustomerQueue::where('customer_id', $request_cust_id)->where('status', "eating")->first();
                $customerReserve = CustomerReserve::where('customer_id', $request_cust_id)->where('status', "eating")->first();
    
                if($customer != null){
                    $userSinceDate = explode('-', date('Y-m-d', strtotime($customer->created_at)));
                    $month2 = $this->convertMonths($userSinceDate[1]);
                    $year2 = $userSinceDate[0];
                    $day2  = $userSinceDate[2];
                    $finalImageUrl = "";
                    if($customer->profileImage == null){
                        $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/user-default.png';
                    } else {
                        $finalImageUrl = $this->CUSTOMER_IMAGE_PATH.'/'.$request_cust_id.'/'. $customer->profileImage;
                    }
    
                    if($customerQueue != null){
                        $restaurant = RestaurantAccount::select('id', 'rName', 'rAddress', 'rCity')->where('id', $customerQueue->restAcc_id)->first();
                        $orderSet = OrderSet::where('id', $customerQueue->orderSet_id)->where('restAcc_id', $restaurant->id)->first();
                        $customerOrdering = CustomerOrdering::where('custBook_id', $customerQueue->id)
                        ->where('custBookType', "queue")
                        ->where('status', "eating")
                        ->first();
        
                        if($customerOrdering->availableQrAccess >= 1){
                            $checkAccess = CustomerQrAccess::where('mainCust_id', $request_cust_id)
                            ->where('subCust_id', $cust_id)
                            ->where('custOrdering_id', $customerOrdering->id)
                            ->latest()
                            ->first();
        
                            if($checkAccess == null){
                                return response()->json([
                                    'custId' => $request_cust_id,
                                    'custImage' => $finalImageUrl,
                                    'custName' => $customer->name,
                                    'custEAddress' => $customer->emailAddress,
                                    'custCNumber' => $customer->contactNumber,
                                    'custJDate' => "User since: $month2 $day2, $year2",
                                    'bdRName' => $restaurant->rName,
                                    'bdRAddres' => "$restaurant->rAddress, $restaurant->rCity",
                                    'bdOrderName' => $orderSet->orderSetName,
                                    'bdBookType' => "Queue",
                                    'bdAccessSlot' => $customerOrdering->availableQrAccess,
                                    'reqStatus' => "Success",
                                ]);
                            } else {
                                if($checkAccess->status == "declined") {
                                    return response()->json([
                                        'custId' => $request_cust_id,
                                        'custImage' => $finalImageUrl,
                                        'custName' => $customer->name,
                                        'custEAddress' => $customer->emailAddress,
                                        'custCNumber' => $customer->contactNumber,
                                        'custJDate' => "User since: $month2 $day2, $year2",
                                        'bdRName' => $restaurant->rName,
                                        'bdRAddres' => "$restaurant->rAddress, $restaurant->rCity",
                                        'bdOrderName' => $orderSet->orderSetName,
                                        'bdBookType' => "Queue",
                                        'bdAccessSlot' => $customerOrdering->availableQrAccess,
                                        'reqStatus' => "Success",
                                    ]);
                                } else if($checkAccess->status == "approved") {
                                    return response()->json([
                                        'custId' => $request_cust_id,
                                        'custImage' => $finalImageUrl,
                                        'custName' => $customer->name,
                                        'custEAddress' => $customer->emailAddress,
                                        'custCNumber' => $customer->contactNumber,
                                        'custJDate' => "User since: $month2 $day2, $year2",
                                        'bdRName' => $restaurant->rName,
                                        'bdRAddres' => "$restaurant->rAddress, $restaurant->rCity",
                                        'bdOrderName' => $orderSet->orderSetName,
                                        'bdBookType' => "Queue",
                                        'bdAccessSlot' => $customerOrdering->availableQrAccess,
                                        'reqStatus' => "Approved",
                                    ]);
                                } else {
                                    return response()->json([
                                        'custId' => $request_cust_id,
                                        'custImage' => $finalImageUrl,
                                        'custName' => $customer->name,
                                        'custEAddress' => $customer->emailAddress,
                                        'custCNumber' => $customer->contactNumber,
                                        'custJDate' => "User since: $month2 $day2, $year2",
                                        'bdRName' => $restaurant->rName,
                                        'bdRAddres' => "$restaurant->rAddress, $restaurant->rCity",
                                        'bdOrderName' => $orderSet->orderSetName,
                                        'bdBookType' => "Queue",
                                        'bdAccessSlot' => $customerOrdering->availableQrAccess,
                                        'reqStatus' => "Pending",
                                    ]);
                                }
                            }
                        } else {
                            return response()->json([
                                'custId' => $request_cust_id,
                                'custImage' => $finalImageUrl,
                                'custName' => $customer->name,
                                'custEAddress' => $customer->emailAddress,
                                'custCNumber' => $customer->contactNumber,
                                'custJDate' => "User since: $month2 $day2, $year2",
                                'bdRName' => $restaurant->rName,
                                'bdRAddres' => "$restaurant->rAddress, $restaurant->rCity",
                                'bdOrderName' => $orderSet->orderSetName,
                                'bdBookType' => "Queue",
                                'bdAccessSlot' => 0,
                                'reqStatus' => "Full Slot",
                            ]);
                        }
                    } else if($customerReserve != null){
                        $restaurant = RestaurantAccount::select('id', 'rName', 'rAddress', 'rCity')->where('id', $customerReserve->restAcc_id)->first();
                        $orderSet = OrderSet::where('id', $customerReserve->orderSet_id)->where('restAcc_id', $restaurant->id)->first();
                        $customerOrdering = CustomerOrdering::where('custBook_id', $customerReserve->id
                        )->where('custBookType', "queue")
                        ->where('status', "eating")
                        ->first();
        
                        if($customerOrdering->availableQrAccess >= 1){
                            $checkAccess = CustomerQrAccess::where('mainCust_id', $request_cust_id)
                            ->where('subCust_id', $cust_id)
                            ->where('custOrdering_id', $customerOrdering->id)
                            ->first();
        
                            if($checkAccess == null){
                                return response()->json([
                                    'custId' => $request_cust_id,
                                    'custImage' => $finalImageUrl,
                                    'custName' => $customer->name,
                                    'custEAddress' => $customer->emailAddress,
                                    'custCNumber' => $customer->contactNumber,
                                    'custJDate' => "User since: $month2 $day2, $year2",
                                    'bdRName' => $restaurant->rName,
                                    'bdRAddres' => "$restaurant->rAddress, $restaurant->rCity",
                                    'bdOrderName' => $orderSet->orderSetName,
                                    'bdBookType' => "Reserve",
                                    'bdAccessSlot' => $customerOrdering->availableQrAccess,
                                    'reqStatus' => "Success",
                                ]);
                            } else {
                                return response()->json([
                                    'custId' => $request_cust_id,
                                    'custImage' => $finalImageUrl,
                                    'custName' => $customer->name,
                                    'custEAddress' => $customer->emailAddress,
                                    'custCNumber' => $customer->contactNumber,
                                    'custJDate' => "User since: $month2 $day2, $year2",
                                    'bdRName' => $restaurant->rName,
                                    'bdRAddres' => "$restaurant->rAddress, $restaurant->rCity",
                                    'bdOrderName' => $orderSet->orderSetName,
                                    'bdBookType' => "Reserve",
                                    'bdAccessSlot' => $customerOrdering->availableQrAccess,
                                    'reqStatus' => "Pending",
                                ]);
                            }
        
                        } else {
                            return response()->json([
                                'custId' => $request_cust_id,
                                'custImage' => $finalImageUrl,
                                'custName' => $customer->name,
                                'custEAddress' => $customer->emailAddress,
                                'custCNumber' => $customer->contactNumber,
                                'custJDate' => "User since: $month2 $day2, $year2",
                                'bdRName' => $restaurant->rName,
                                'bdRAddres' => "$restaurant->rAddress, $restaurant->rCity",
                                'bdOrderName' => $orderSet->orderSetName,
                                'bdBookType' => "Reserve",
                                'bdAccessSlot' => 0,
                                'reqStatus' => "Full Slot",
                            ]);
                        }
                    } else {
                        return response()->json([
                            'custId' => null,
                            'custImage' => null,
                            'custName' => null,
                            'custEAddress' => null,
                            'custCNumber' => null,
                            'custJDate' => null,
                            'bdRName' => null,
                            'bdRAddres' => null,
                            'bdOrderName' => null,
                            'bdBookType' => null,
                            'bdAccessSlot' => null,
                            'reqStatus' => "Invalid Access",
                        ]);
                    }
                }
            }
        } else {
            return response()->json([
                'custId' => null,
                'custImage' => null,
                'custName' => null,
                'custEAddress' => null,
                'custCNumber' => null,
                'custJDate' => null,
                'bdRName' => null,
                'bdRAddres' => null,
                'bdOrderName' => null,
                'bdBookType' => null,
                'bdAccessSlot' => null,
                'reqStatus' => "Invalid Access",
            ]);
        }
        
    }

    public function orderingRequestAccess($cust_id, $request_cust_id){
        $finalStatus = "";
        $customerQueue = CustomerQueue::where('customer_id', $request_cust_id)->where('status', "eating")->first();
        $customerReserve = CustomerReserve::where('customer_id', $request_cust_id)->where('status', "eating")->first();
        
        if($customerQueue != null){
            $customer = CustomerAccount::where('id', $request_cust_id)->first();

            $customerOrdering = CustomerOrdering::where('custBook_id', $customerQueue->id)
            ->where('custBookType', "queue")
            ->where('status', "eating")
            ->first();

            CustomerQrAccess::create([
                'custOrdering_id' =>  $customerOrdering->id,
                'mainCust_id' => $request_cust_id,
                'subCust_id' => $cust_id,
                'tableNumber' => "0",
                'status' => "pending",
            ]);

            $notif = CustomerNotification::create([
                'customer_id' => $customer->id,
                'restAcc_id' => 0,
                'notificationType' => "QR Request",
                'notificationTitle' => "Someone has scanned your QR Code. Please review it now.",
                'notificationDescription' => "Samquicksal",
                'notificationStatus' => "Unread",
            ]);

            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/samquicksalLogo.png';
            if($customer != null){
                $to = $customer->deviceToken;
                $notification = array(
                    'title' => "Hi $customer->name, Someone has scanned your QR Code!",
                    'body' => "Please review it now.",
                );
                $data = array(
                    'notificationType' => "QR Request",
                    'notificationId' => $notif->id,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }


            $customerSub = CustomerAccount::where('id', $cust_id)->first();
            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/samquicksalLogo.png';
            if($customerSub != null){
                $to2 = $customerSub->deviceToken;
                $notification2 = array(
                    'title' => "Hi $customerSub->name, Your request has been sent",
                    'body' => "Please wait for your friend to validate your request. Thank you for your patience.",
                );
                $data2 = array(
                    'notificationType' => "QR Request Sub",
                    'notificationId' => 0,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to2, $notification2, $data2);
            }

            $finalStatus = "success";
        } else if($customerReserve != null){
            $customer = CustomerAccount::where('id', $request_cust_id)->first();
            $customerOrdering = CustomerOrdering::where('custBook_id', $customerReserve->id)
            ->where('custBookType', "queue")
            ->where('status', "eating")
            ->first();

            CustomerQrAccess::create([
                'custOrdering_id' =>  $customerOrdering->id,
                'mainCust_id' => $request_cust_id,
                'subCust_id' => $cust_id,
                'tableNumber' => "0",
                'status' => "pending",
            ]);

            $notif = CustomerNotification::create([
                'customer_id' => $customer->id,
                'restAcc_id' => 0,
                'notificationType' => "QR Request",
                'notificationTitle' => "Someone has scanned your QR Code. Please review it now.",
                'notificationDescription' => "Samquicksal",
                'notificationStatus' => "Unread",
            ]);

            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/samquicksalLogo.png';
            if($customer != null){
                $to = $customer->deviceToken;
                $notification = array(
                    'title' => "Hi $customer->name, Someone has scanned your QR Code!",
                    'body' => "Please review it now.",
                );
                $data = array(
                    'notificationType' => "QR Request",
                    'notificationId' => $notif->id,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }

            $customerSub = CustomerAccount::where('id', $cust_id)->first();
            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/samquicksalLogo.png';
            if($customerSub != null){
                $to2 = $customerSub->deviceToken;
                $notification2 = array(
                    'title' => "Hi $customerSub->name, Your request has been sent",
                    'body' => "Please wait for your friend to validate your request. Thank you for your patience.",
                );
                $data2 = array(
                    'notificationType' => "QR Request Sub",
                    'notificationId' => 0,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to2, $notification2, $data2);
            }
            $finalStatus = "success";
        } else {
            $finalStatus = "error";
        }
        
        return response()->json([
            'status' => $finalStatus,
        ]);
    }
    public function getNotificationQrValidate($cust_id, $notif_id){
        $customerNotification = CustomerNotification::where('id', $notif_id)
        ->where('customer_id', $cust_id)
        ->where('notificationType', "QR Request")
        ->first();

        CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)
        ->update([
            'notificationStatus' => "Read"
        ]);

        $customerQrAccess = CustomerQrAccess::where('mainCust_id', $cust_id)
        ->where('created_at', $customerNotification->created_at)
        ->first();
        
        $customerSub = CustomerAccount::where('id', $customerQrAccess->subCust_id)->first();
        $reqDate = explode('-', date("Y-m-d", strtotime($customerQrAccess->created_at)));
        $year = $reqDate[0];
        $month = $this->convertMonths($reqDate[1]);
        $day  = $reqDate[2];
        $reqTime = date("g:i a", strtotime($customerQrAccess->created_at->toTimeString()));

        if($customerQrAccess->status == "pending"){
            $customerOrdering = CustomerOrdering::where('id', $customerQrAccess->custOrdering_id)->where('status', "eating")->first();
            if($customerOrdering == null){
                return response()->json([
                    'custQrAcces_id' => $customerQrAccess->id,
                    'custName' => $customerSub->name,
                    'custEAddress' => $customerSub->emailAddress,
                    'custENumber' => $customerSub->contactNumber,
                    'scannedStatus' => $customerQrAccess->status,
                    'scanReqDate' => "$month $day, $year",
                    'scanReqTime' => $reqTime,
                    'tableNumbers' => null,
                ]);
            } else {
                if($customerOrdering->availableQrAccess >= 1){
                    $availTableNumbers = array();
                    $tableNumbers = array();
                    $tableNumbers = explode(',', $customerOrdering->tableNumbers);
                    $getExTableNums = CustomerQrAccess::where('mainCust_id', $cust_id)
                    ->where('custOrdering_id', $customerOrdering->id)
                    ->where('status', "approved")
                    ->get();
    
                    if($getExTableNums->isEmpty()){
                       for($i=0; $i<sizeOf($tableNumbers); $i++){
                           if($i!=0){
                                array_push($availTableNumbers, $tableNumbers[$i]); 
                           }
                       }
                    } else {
                        foreach($getExTableNums as $getExTableNum){
                            $exTableNum = $getExTableNum->tableNumber;
                            for($i=0; $i<sizeOf($tableNumbers); $i++){
                                if($i!=0){
                                    if($tableNumbers[$i] != $exTableNum){
                                        array_push($availTableNumbers, $tableNumbers[$i]); 
                                    }
                                }
                            }
                        }
                    }
                    return response()->json([
                        'custQrAcces_id' => $customerQrAccess->id,
                        'custName' => $customerSub->name,
                        'custEAddress' => $customerSub->emailAddress,
                        'custENumber' => $customerSub->contactNumber,
                        'scannedStatus' => $customerQrAccess->status,
                        'scanReqDate' => "$month $day, $year",
                        'scanReqTime' => $reqTime,
                        'tableNumbers' => $availTableNumbers,
                    ]);
                } else {
                    return response()->json([
                        'custQrAcces_id' => $customerQrAccess->id,
                        'custName' => $customerSub->name,
                        'custEAddress' => $customerSub->emailAddress,
                        'custENumber' => $customerSub->contactNumber,
                        'scannedStatus' => $customerQrAccess->status,
                        'scanReqDate' => "$month $day, $year",
                        'scanReqTime' => $reqTime,
                        'tableNumbers' => null,
                    ]);
                }
            }
        } else {
            return response()->json([
                'custQrAcces_id' => $customerQrAccess->id,
                'custName' => $customerSub->name,
                'custEAddress' => $customerSub->emailAddress,
                'custENumber' => $customerSub->contactNumber,
                'scannedStatus' => $customerQrAccess->status,
                'scanReqDate' => "$month $day, $year",
                'scanReqTime' => $reqTime,
                'tableNumbers' => null,
            ]);
        }
    }

    public function orderingRequestAppDec(Request $request){
        $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/samquicksalLogo.png';
        $finalStatus = "Error";

        if($request->reqType == "declined"){
            $customerQrAccess = CustomerQrAccess::where('id', $request->custQrAcces_id)->first();
            CustomerQrAccess::where('id', $request->custQrAcces_id)
            ->update([
                'status' => "declined",
            ]);
    
            $customerSub = CustomerAccount::where('id', $customerQrAccess->subCust_id)->first();
            $notif = CustomerNotification::create([
                'customer_id' => $customerSub->id,
                'restAcc_id' => 0,
                'notificationType' => "QR Declined",
                'notificationTitle' => "Your request has been declined",
                'notificationDescription' => "Samquicksal",
                'notificationStatus' => "Unread",
            ]);
            if($customerSub != null){
                $to = $customerSub->deviceToken;
                $notification = array(
                    'title' => "Hi $customerSub->name, your request has been declined",
                    'body' => "You can request again anytime as long as you have the qr code of your friend",
                );
                $data = array(
                    'notificationType' => "QR Declined",
                    'notificationId' => $notif->id,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }


            $customerMain = CustomerAccount::where('id', $customerQrAccess->mainCust_id)->first();
            if($customerMain != null){
                $to = $customerMain->deviceToken;
                $notification = array(
                    'title' => "Customer Request Declined",
                    'body' => "The next time this customer requested again you can ignore it so that this customer cannot spam request",
                );
                $data = array(
                    'notificationType' => "QR Declined Main",
                    'notificationId' => 0,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }
            $finalStatus = "Request Declined";
        } else {
            $customerQrAccess = CustomerQrAccess::where('id', $request->custQrAcces_id)->first();
            $customerOrdering = CustomerOrdering::where('id', $customerQrAccess->custOrdering_id)->first();

            CustomerOrdering::where('id', $customerQrAccess->custOrdering_id)
            ->update([
                'availableQrAccess' => ($customerOrdering->availableQrAccess - 1)
            ]);

            CustomerQrAccess::where('id', $request->custQrAcces_id)
            ->update([
                'status' => "approved",
                'tableNumber' => $request->tableNumber,
                'approvedDateTime' => date("Y-m-d H:i:s"),
            ]);
    
            $customerSub = CustomerAccount::where('id', $customerQrAccess->subCust_id)->first();
            $notif = CustomerNotification::create([
                'customer_id' => $customerSub->id,
                'restAcc_id' => 0,
                'notificationType' => "QR Approved",
                'notificationTitle' => "Your request has been approved! You can now order together with your friend",
                'notificationDescription' => "Samquicksal",
                'notificationStatus' => "Unread",
            ]);
            if($customerSub != null){
                $to = $customerSub->deviceToken;
                $notification = array(
                    'title' => "Hi $customerSub->name, your request has been approved",
                    'body' => "You can now order together with your friend",
                );
                $data = array(
                    'notificationType' => "QR Approved",
                    'notificationId' => $notif->id,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }


            $customerMain = CustomerAccount::where('id', $customerQrAccess->mainCust_id)->first();
            if($customerMain != null){
                $to = $customerMain->deviceToken;
                $notification = array(
                    'title' => "Customer Request Approved",
                    'body' => "This customer can now order the your order but cannot checkout",
                );
                $data = array(
                    'notificationType' => "QR Approved Main",
                    'notificationId' => 0,
                    'notificationRLogo' => $finalImageUrl,
                );
                $this->sendFirebaseNotification($to, $notification, $data);
            }
            $finalStatus = "Request Approved";
        }
        return response()->json([
            'status' => $finalStatus,
        ]);
    }

    public function getNotificationQrApproved($cust_id, $notif_id){
        //check kung may access pa ba sa customer access
        $notification = CustomerNotification::where('id', $notif_id)->first();
        $customerQrAccess = CustomerQrAccess::where('subCust_id', $cust_id)->where('approvedDateTime', $notification->created_at)->first();

        CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)
        ->update([
            'notificationStatus' => "Read"
        ]);

        if($customerQrAccess != null){
            $customer = CustomerAccount::where('id', $customerQrAccess->mainCust_id)->first();
            if($customerQrAccess->status == "approved"){
                return response()->json([
                    'custQrAcces_id' => $customerQrAccess->id,
                    'custName' => $customer->name,
                    'custEAddress' => $customer->emailAddress,
                    'custENumber' => $customer->contactNumber,
                    'tableNumber' => $customerQrAccess->tableNumber,
                    'status' => "clickable",
                ]);
            } else {
                return response()->json([
                    'custQrAcces_id' => $customerQrAccess->id,
                    'custName' => $customer->name,
                    'custEAddress' => $customer->emailAddress,
                    'custENumber' => $customer->contactNumber,
                    'tableNumber' => $customerQrAccess->tableNumber,
                    'status' => "invalid",
                ]);
            }
        } else {
            return response()->json([
                'custQrAcces_id' => null,
                'custName' => null,
                'custEAddress' => null,
                'custENumber' => null,
                'tableNumber' => null,
                'status' => "invalid",
            ]);
        }
    }

    public function orderingCheckCustAccess($cust_id){
        $customerQrAccess = CustomerQrAccess::where('subCust_id', $cust_id)->where('status', 'approved')->latest()->first();
        if($customerQrAccess != null){
            $customerOrdering = CustomerOrdering::where('id', $customerQrAccess->custOrdering_id)->where('status', "eating")->first();
            if($customerOrdering != null){
                return response()->json([
                    'mainCust_id' => $customerQrAccess->mainCust_id,
                    'status' => "subCustomer",
                ]);
            } else {
                return response()->json([
                    'mainCust_id' => null,
                    'status' => "mainCustomer",
                ]);
            }
        } else {
            return response()->json([
                'mainCust_id' => null,
                'status' => "mainCustomer",
            ]);
        }
    }



    public function gcashUploadReceipt(){
        $finalStatus = "";
        $params = $_REQUEST;
        $cust_id = strval($params['cust_id']);
        $book_id = strval($params['book_id']);
        $book_type = strval($params['book_type']);
        $extension = pathinfo($_FILES['gCashReceipt']['name'], PATHINFO_EXTENSION);
        $filename = time().'.'.$extension;
        $target_file = 'uploads/customerAccounts/gcashQr/'.$cust_id.'/'.$filename;

        if($book_type == "queue"){
            $customerQueue = CustomerQueue::where('id', $book_id)->first();

            if($customerQueue->gcashCheckoutReceipt != null){
                File::delete(public_path('uploads/customerAccounts/gcashQr/'.$cust_id.'/'.$customerQueue->gcashCheckoutReceipt));
            }
            
            move_uploaded_file($_FILES['gCashReceipt']['tmp_name'], $target_file);
            
            CustomerQueue::where('id', $book_id)
            ->update([
                'gcashCheckoutReceipt' => $filename,
                'checkoutStatus' => "gcashCheckoutValidation",
            ]);

        } else {
            $customerReserve = CustomerReserve::where('id', $book_id)->first();

            if($customerReserve->gcashCheckoutReceipt != null){
                File::delete(public_path('uploads/customerAccounts/gcashQr/'.$cust_id.'/'.$customerReserve->gcashCheckoutReceipt));
            }
            
            move_uploaded_file($_FILES['gCashReceipt']['tmp_name'], $target_file);
            
            CustomerReserve::where('id', $book_id)
            ->update([
                'gcashCheckoutReceipt' => $filename,
                'checkoutStatus' => "gcashCheckoutValidation",
            ]);
        }

        return response()->json([
            'status' => "Success"
        ]);
    }
    public function custUpdateSInfo(Request $request){
        $status = "Error Updating Info";
        if($request->infoType == "Name"){
            CustomerAccount::where('id', $request->cust_id)
            ->update([
                'name' => $request->input
            ]);
            $status = "nameUpdated";
        } else if ($request->infoType == "Email Address"){
            $customer = CustomerAccount::where('id', $request->cust_id)->first();
            if($customer->emailAddressVerified == "No"){
                $status = "Please Verify your email first before changing";
            } else {
                $custCheckEmail = CustomerAccount::where('emailAddress', $request->input)->first();
                if($custCheckEmail != null){
                    $status = "Email is already taken";
                } else {
                    CustomerAccount::where('id', $request->cust_id)
                    ->update([
                        'emailAddress' => $request->input,
                        'emailAddressVerified' => "No"
                    ]);
                    $status = "emailUpdated";
                }
            }
        } else if ($request->infoType == "Contact Number"){
            CustomerAccount::where('id', $request->cust_id)
            ->update([
                'contactNumber' => $request->input
            ]);
            $status = "numberUpdated";
        } else {}

        return response()->json([
            'status' => $status
        ]);
    }
    public function custUpdatePass(Request $request){
        $status = "Error Updating Info";
        $customer = CustomerAccount::where('id', $request->cust_id)->first();

        if(!Hash::check($request->oldPassword, $customer->password)){
            $status = "Current Password does not match Old Password";
        } else {
            CustomerAccount::where('id', $request->cust_id)
            ->update([
                'password' => Hash::make($request->newPassword)
            ]);
            $status = "passwordUpdated";
        }

        return response()->json([
            'status' => $status
        ]);
    }
    public function custUpdateUImage(){
        $params = $_REQUEST;
        $cust_id = strval($params['cust_id']);
        $extension = pathinfo($_FILES['userProfile']['name'], PATHINFO_EXTENSION);
        $filename = time().'.'.$extension;
        $target_file = 'uploads/customerAccounts/logo/'.$cust_id.'/'.$filename;

        $customer = CustomerAccount::where('id', $cust_id)->first();
        if($customer->profileImage != null){
            File::delete(public_path('uploads/customerAccounts/logo/'.$cust_id.'/'.$customer->profileImage));
        }
        
        move_uploaded_file($_FILES['userProfile']['tmp_name'], $target_file);

        CustomerAccount::where('id', $cust_id)
        ->update([
            'profileImage' => $filename
        ]);

        return response()->json([
            'status' => "success"
        ]);
    }
    public function custVerEmailLink($cust_id){
        $customer = CustomerAccount::where('id', $cust_id)->first();

        if($customer != null){
            $details = [
                'link' => env('APP_URL') . '/customer/verify-email/'.$cust_id,
                'applicantName' => $customer->name,
            ];
    
            Mail::to($customer->emailAddress)->send(new CustomerEVerifyLink($details));
    
            return response()->json([
                'status' => "success"
            ]);
        }
    }
    public function custVerifyEmail($cust_id){
        $customer = CustomerAccount::where('id', $cust_id)->first();

        if($customer != null){
            if($customer->emailAddressVerified == "Yes"){
                Session::flash('alreadyVerified');
                return redirect('/');
            } else {
                CustomerAccount::where('id', $cust_id)->update(['emailAddressVerified' => "Yes"]);
                
                $details = [
                    'applicantName' => $customer->name,
                ];

                Mail::to($customer->emailAddress)->send(new CustomerEmailVerified($details));
                Session::flash('emailVerified');
                return redirect('/');
            }
        }
    }
}
