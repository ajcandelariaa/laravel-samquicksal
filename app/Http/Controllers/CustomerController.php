<?php

namespace App\Http\Controllers;

use App\Mail\CustomerForgotPassword;
use App\Mail\CustomerPasswordChanged;
use App\Models\Post;
use App\Models\Promo;
use App\Models\FoodSet;
use App\Models\FoodItem;
use App\Models\OrderSet;
use App\Models\StampCard;
use App\Models\StoreHour;
use App\Models\FoodSetItem;
use Illuminate\Http\Request;
use App\Models\PromoMechanics;
use App\Models\StampCardTasks;
use Illuminate\Support\Carbon;
use App\Models\CustomerAccount;
use App\Models\OrderSetFoodSet;
use App\Models\UnavailableDate;
use App\Models\OrderSetFoodItem;
use App\Models\CustomerStampCard;
use App\Models\RestaurantAccount;
use App\Models\RestaurantTaskList;
use App\Models\RestaurantRewardList;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\RestaurantFormAppreciation;
use App\Models\CustomerLOrder;
use App\Models\CustomerNotification;
use App\Models\CustomerOrdering;
use App\Models\CustomerQrAccess;
use App\Models\CustomerQueue;
use App\Models\CustomerReserve;
use App\Models\CustomerResetPassword;
use App\Models\Policy;
use Illuminate\Contracts\Cache\Store;

class CustomerController extends Controller
{
    public $RESTAURANT_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/logo";
    public $ACCOUNT_NO_IMAGE_PATH = "http://192.168.1.53:8000/images";
    public $CUSTOMER_IMAGE_PATH = "http://192.168.1.53:8000/uploads/customerAccounts/logo";
    public $POST_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/post";
    public $PROMO_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/promo";
    public $ORDER_SET_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/orderSet";
    public $FOOD_SET_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/foodSet";
    public $FOOD_ITEM_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/foodItem";

    
    // public $RESTAURANT_IMAGE_PATH = "https://www.samquicksal.com/uploads/customerAccounts/logo";
    // public $CUSTOMER_IMAGE_PATH = "https://www.samquicksal.com/uploads/customerAccounts/logo";
    // public $ACCOUNT_NO_IMAGE_PATH = "https://www.samquicksal.com/images";
    // public $POST_IMAGE_PATH = "https://www.samquicksal.com/uploads/restaurantAccounts/post";
    // public $PROMO_IMAGE_PATH = "https://www.samquicksal.com/uploads/restaurantAccounts/promo";
    // public $ORDER_SET_IMAGE_PATH = "https://www.samquicksal.com/uploads/restaurantAccounts/orderSet";
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
    public function getListOfRestaurants(){
        $finalData = array();
        $restaurants = RestaurantAccount::select('id', 'rName', 'rAddress', 'rLogo')->get();
        foreach ($restaurants as $restaurant){
            $getAvailability = "";
            $rSchedule = "";

            $restaurantUnavailableDates = UnavailableDate::select('unavailableDatesDate')->where('restAcc_id', $restaurant->id)->get();
            foreach ($restaurantUnavailableDates as $restaurantUnavailableDate) {
                if($restaurantUnavailableDate->unavailableDatesDate == date("Y-m-d")){
                    $getAvailability = "Closed";
                } else {
                    $getAvailability = "Open";
                }
            }

            if($getAvailability == "Closed"){
                $rSchedule = "Closed Now";
            } else {
                $restaurantStoreHours = StoreHour::where('restAcc_id', $restaurant->id)->get();
                foreach ($restaurantStoreHours as $restaurantStoreHour){
                    foreach (explode(",", $restaurantStoreHour->days) as $day){
                        if($this->convertDays($day) == date('l')){
                            $currentTime = date("H:i");
                            if($currentTime < $restaurantStoreHour->openingTime || $currentTime > $restaurantStoreHour->closingTime){
                                $rSchedule = "Closed Now";
                            } else {
                                $openingTime = date("g:i a", strtotime($restaurantStoreHour->openingTime));
                                $closingTime = date("g:i a", strtotime($restaurantStoreHour->closingTime));
                                $rSchedule = "Open today at ".$openingTime." to ".$closingTime;
                            }
                        }
                    }
                }
            }
            if($rSchedule == ""){
                $rSchedule = "Closed Now";
            }

            $finalImageUrl = "";
            if ($restaurant->rLogo == ""){$finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
                
            } else {
                $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$restaurant->id.'/'. $restaurant->rLogo;
            }

            array_push($finalData, [
                'id' => $restaurant->id,
                'rName' => $restaurant->rName,
                'rAddress' => $restaurant->rAddress,
                'rLogo' => $finalImageUrl,
                'rSchedule' => $rSchedule
            ]);
        }
        return response()->json($finalData);
    }
    // -------RESTAURANT ABOUT BY ID------------ //
    public function getRestaurantAboutInfo($id){
        $account = RestaurantAccount::select('rName', 'rAddress', 'rLogo', 'rNumberOfTables', 'rCity', 'rBranch')->where('id', $id)->first();
        $posts = Post::where('restAcc_id', $id)->get();

        $storePosts = array();
        foreach($posts as $post){
            array_push($storePosts, [
                'image' => $this->POST_IMAGE_PATH."/".$id."/".$post->postImage,
                'description' => $post->postDesc,
            ]);
        }

        $storePolicy = array();
        $policies = Policy::where('restAcc_id', $id)->get();
        if($policies->isEmpty()){
            array_push($storePolicy,[
                'policy' => "We do not have any policy as of now"
            ]);
        } else {
            foreach ($policies as $policie){
                array_push($storePolicy, [
                    'policy' => $policie->policyDesc,
                ]);
            }
        }

        $finalSchedule = array();
        $storeHours = StoreHour::where('restAcc_id', $id)->get();
        foreach ($storeHours as $storeHour){
            $openingTime = date("g:i a", strtotime($storeHour->openingTime));
            $closingTime = date("g:i a", strtotime($storeHour->closingTime));
            foreach (explode(",", $storeHour->days) as $day){
                array_push($finalSchedule, [
                    'Day' => $this->convertDays($day),
                    'Opening' => $openingTime,
                    'Closing' => $closingTime,
                ]);
            }
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
        $stamp = StampCard::where('restAcc_id', $id)->first();
        if($stamp != null){
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
        } else {
            $finalStampReward = null;
            $finalValidity = null;
            $finalStampTasks = null;
            $finalStampCapacity = null;
        }
        

        $finalPromos = array();
        $promos = Promo::select('id', 'promoTitle', 'promoImage')->where('restAcc_id', $id)->where('promoPosted', "Posted")->get();
        foreach ($promos as $promo){
            array_push($finalPromos,[
                'promoId' => $promo->id,
                'promoName' => $promo->promoTitle,
                'promoImage' => $this->PROMO_IMAGE_PATH."/".$id."/".$promo->promoImage,
            ]);
        }
        
        if($promos->isEmpty()){
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
    public function getRestaurantsMenuInfo($id){
        $orderSets = OrderSet::where('restAcc_id', $id)->get();

        $finalData = array();

        foreach ($orderSets as $orderSet){
            $foodSets = array();
            $orderSetFoodSets = OrderSetFoodSet::where('orderSet_id', $orderSet->id)->get();
            $orderSetFoodItems = OrderSetFoodItem::where('orderSet_id', $orderSet->id)->get();

            // GET FOOD SETS
            if(!$orderSetFoodSets->isEmpty()){
                foreach($orderSetFoodSets as $orderSetFoodSet){
                    $foodItems = array();
                    $foodSetName = FoodSet::select('foodSetName', 'id')->where('id', $orderSetFoodSet->foodSet_id)->where('restAcc_id', $id)->first();
                    $foodSetItems = FoodSetItem::where('foodSet_id', $foodSetName->id)->get();
                    foreach($foodSetItems as $foodSetItem){
                        $foodItemName = FoodItem::where('id', $foodSetItem->foodItem_id)->where('restAcc_id', $id)->first();
                        array_push($foodItems, $foodItemName->foodItemName);
                    }
                    array_push($foodSets, [
                        'foodSetName' => $foodSetName->foodSetName,
                        'foodItem' => $foodItems,
                    ]);
                }
            }
            
            // GET OTHER FOOD SET (FOOD ITEMS)
            if(!$orderSetFoodItems->isEmpty()){
                $foodItems = array();
                foreach($orderSetFoodItems as $orderSetFoodItem){
                    $foodItemName = FoodItem::where('id', $orderSetFoodItem->foodItem_id)->where('restAcc_id', $id)->first();
                    array_push($foodItems, $foodItemName->foodItemName);
                }
                array_push($foodSets, [
                    'foodSetName' => "Add-on/s (Not Free)",
                    'foodItem' => $foodItems,
                ]);
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

        return response()->json($finalData);
    }
    public function getListOfPromos(){
        $finalData = array();

        $promos = Promo::orderBy('id', 'DESC')->where('promoPosted', "Posted")->get();

        foreach ($promos as $promo){
            $restaurantInfo = RestaurantAccount::select('id', 'rLogo', 'rAddress')->where('id', $promo->restAcc_id)->first();
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
                'restaurantAddress' => $restaurantInfo->rAddress,
                'restaurantId' => $restaurantInfo->id,
                'promoId' => $promo->id,
                'promoTitle' => $promo->promoTitle,
                'promoStartDate' => $month1." ".$day1.", ".$year1,
                'promoEndDate' => $month2." ".$day2.", ".$year2,
            ]);
        }
        return response()->json($finalData);
    }
    public function getRestaurantChooseOrderSet($id, $custId){
        $finalData = array();
        $finalRewardStatus = "";
        $finalRewardType = "";
        $finalRewardInput = "";

        $restaurant = RestaurantAccount::select('rName', 'rTimeLimit', 'rCapacityPerTable')->where('id', $id)->first();
        $orderSets = OrderSet::where('restAcc_id', $id)->get();
        $getStamp = StampCard::select('stampValidity', 'stampReward_id')->where('restAcc_id', $id)->first();

        if($getStamp == null){
            $finalRewardStatus = "Incomplete";
        } else {
            $getReward = RestaurantRewardList::where('restAcc_id', $id)->where('id', $getStamp->stampReward_id)->first();

            $checkIfComplete = CustomerStampCard::where('customer_id', $custId)
                            ->where('restAcc_id', $id)
                            ->where('stampValidity', $getStamp->stampValidity)
                            ->where('claimed', "No")
                            ->where('status', "Complete")->first();
            if($checkIfComplete == null){
                $finalRewardStatus = "Incomplete";
                $finalRewardType = "";
                $finalRewardInput = 0;
            } else {
                $finalRewardType = $getReward->rewardCode;
                $finalRewardInput = $getReward->rewardInput;
                $finalRewardStatus = "Complete";
            }
        }
        
        foreach ($orderSets as $orderSet){
            array_push($finalData, [
                'orderSetId' => $orderSet->id,
                'orderSetPrice' => $orderSet->orderSetPrice,
                'orderSetName' => $orderSet->orderSetName,
                'orderSetTagline' => $orderSet->orderSetTagline,
                'orderSetImage' => $this->ORDER_SET_IMAGE_PATH."/".$id."/".$orderSet->orderSetImage,
            ]);
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
    public function getReservationDateAndTimeForm($id){
        $unavailableDates = UnavailableDate::select('unavailableDatesDate')->where('restAcc_id', $id)->get();
        $currentDate = date('Y-m-d');
        $storeDates = array();

        for($i=0; $i<7; $i++){
            $currentDateTime2 = date('Y-m-d', strtotime($currentDate. ' + 1 days'));
            array_push($storeDates, $currentDateTime2);
            $currentDate = $currentDateTime2;
        }

        if(!$unavailableDates->isEmpty()){
            foreach ($unavailableDates as $unavailableDate){
                if (($key = array_search($unavailableDate->unavailableDatesDate, $storeDates)) !== false) {
                    unset($storeDates[$key]);
                }
            }
        }

        $newStoreDates = array_values($storeDates);
        $storeDays = array();
        foreach ($newStoreDates as $newStoreDate){
            array_push($storeDays, date('l', strtotime($newStoreDate)));
        }

        $finalSchedule = array();
        $storeHours = StoreHour::where('restAcc_id', $id)->get();
        foreach($storeHours as $storeHour){
            $openingTime = date("g:i a", strtotime($storeHour->openingTime));
            $closingTime = date("g:i a", strtotime($storeHour->closingTime));
            foreach (explode(",", $storeHour->days) as $day){
                array_push($finalSchedule, [
                    'Day' => $this->convertDays($day),
                    'Opening' => $openingTime,
                    'Closing' => $closingTime,
                ]);
            }
        }


        return response()->json([
            'storeDates' => $storeDates,
            'newStoreDates' => $newStoreDates,
            'storeDays' => $storeDays,
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
        ->orderBy('created_at', 'DESC')
        ->first();

        //need naten malaman kung eating ba sya para mag redirect sa orderingUI pero kung hindi pa sya eating like ongoing to approve pa lang sya then show livestatus
        if($customerQueue != null){
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

        
        CustomerQueue::create([
            'customer_id' => $request->customer_id,
            'restAcc_id' => $request->restAcc_id,
            'orderSet_id' => $request->orderSet_id,
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
                'body' => "Thank your for booking with us, please wait for your form to validate!",
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

            array_push($finalData,[
                'id' => $notification->id,
                'notificationType' => $notification->notificationType,
                'notificationImage' => $finalImageUrl,
                'notificationTitle' => $notification->notificationTitle,
                'notificationDescription' => $notification->notificationDescription,
                'notificationStatus' => $notification->notificationStatus,
                'notificationTime' => $rAppDiffTime,
            ]);
        }

        return response()->json($finalData);
    }
    public function getCustomerLiveStatus($id){
        $getDateToday = date("Y-m-d");
        $currentTime = Carbon::now();
        $customerQueue = CustomerQueue::where('customer_id', $id)
        ->where('queueDate', $getDateToday)
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
                    $liveStatusDescription = "You can no longer cancel. Please wait for the approval of your booking.";
                }
                return response()->json([
                    'liveStatusHeader' => "Cancellation Time",
                    'liveStatusBody' => null,
                    'liveStatusNumber' => $rAppDiffTime,
                    'liveStatusNumberDesc' => $rAppDiffTimeLabel,
                    'liveStatusDescription' => $liveStatusDescription,
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
                ]);
            } else if ($customerQueue->status == "tableSettingUp") {
                return response()->json([
                    'liveStatusHeader' => "Note",
                    'liveStatusBody' => "Your Table is Now Setting Up",
                    'liveStatusNumber' => null,
                    'liveStatusNumberDesc' => null,
                    'liveStatusDescription' => "Your table is now setting up, please wait until you can access the ordering part",
                ]);
            } else {
                return response()->json([
                    'liveStatusHeader' => null,
                    'liveStatusBody' => null,
                    'liveStatusNumber' => null,
                    'liveStatusNumberDesc' => null,
                    'liveStatusDescription' => null,
                ]);
            }
        } else {
            return response()->json([
                'liveStatusHeader' => null,
                'liveStatusBody' => null,
                'liveStatusNumber' => null,
                'liveStatusNumberDesc' => null,
                'liveStatusDescription' => null,
            ]);
        }

    }
    public function cancelCustomerBooking($id){
        $getDateToday = date("Y-m-d");
        $customerQueue = CustomerQueue::where('customer_id', $id)
        ->where('queueDate', $getDateToday)
        ->where('status', '!=', 'completed')
        ->orderBy('created_at', 'DESC')
        ->first();
        
        $restaurant = RestaurantAccount::select('id', 'rAddress', 'rCity', 'rLogo', 'rName', 'rBranch')->where('id', $customerQueue->restAcc_id)->first();
        $customer = CustomerAccount::select('deviceToken')->where('id', $id)->first();

        CustomerQueue::where('id', $customerQueue->id)
        ->update([
            'status' => 'cancelled',
            'cancelDateTime' => date('Y-m-d H:i:s'),
        ]);

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

        return response()->json([
            'status' => "Success"
        ]);
    }

    public function getNotificationPending($cust_id, $notif_id){
        $notification = CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $notification->restAcc_id)->first();
        
        $customerQueue = CustomerQueue::where('customer_id', $cust_id)
        ->where('restAcc_id', $notification->restAcc_id)
        ->where('created_at', $notification->created_at)
        ->first();

        $orderSet = OrderSet::select('orderSetName')->where('id', $customerQueue->orderSet_id)->first();

        if($customerQueue->status == "pending"){
            $viewable = "yes";
        } else {
            $viewable = "no";
        }

        return response()->json([
            'orderName' => $orderSet->orderSetName,
            'numberOfPersons' => $customerQueue->numberOfPersons,
            'numberOfTables' => $customerQueue->numberOfTables,
            'hoursOfStay' => $customerQueue->hoursOfStay,
            'numberOfChildren' => $customerQueue->numberOfChildren,
            'numberOfPwd' => $customerQueue->numberOfPwd,
            'note' => $customerQueue->notes,
            'restaurant' => "$restaurant->rName $restaurant->rBranch",
            'statusViewable' => $viewable,
        ]);
    }
    public function getNotificationCancelled($cust_id, $notif_id){
        $notification = CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $notification->restAcc_id)->first();
        
        $customerQueue = CustomerQueue::where('customer_id', $cust_id)
        ->where('restAcc_id', $notification->restAcc_id)
        ->where('cancelDateTime', $notification->created_at)
        ->first();

        return response()->json([
            'cancelReason' => $customerQueue->cancelReason,
            'restaurant' => "$restaurant->rName $restaurant->rBranch",
        ]);
    }
    public function getNotificationDeclined($cust_id, $notif_id){
        $notification = CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $notification->restAcc_id)->first();
        
        $customerQueue = CustomerQueue::where('customer_id', $cust_id)
        ->where('restAcc_id', $notification->restAcc_id)
        ->where('declinedDateTime', $notification->created_at)
        ->first();

        return response()->json([
            'declinedReason' => $customerQueue->declinedReason,
            'restaurant' => "$restaurant->rName $restaurant->rBranch",
        ]);
    }
    public function getNotificationApproved($cust_id, $notif_id){
        $notification = CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $notification->restAcc_id)->first();
        
        $customerQueue = CustomerQueue::where('customer_id', $cust_id)
        ->where('restAcc_id', $notification->restAcc_id)
        ->where('approvedDateTime', $notification->created_at)
        ->first();

        if($customerQueue->status == "approved"){
            $viewable = "yes";
        } else {
            $viewable = "no";
        }

        return response()->json([
            'restaurant' => "$restaurant->rName $restaurant->rBranch",
            'statusViewable' => $viewable,
        ]);
    }
    public function getNotificationNoShow($cust_id, $notif_id){
        $notification = CustomerNotification::where('id', $notif_id)->where('customer_id', $cust_id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch')->where('id', $notification->restAcc_id)->first();

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
                $restaurant = RestaurantAccount::select('rLogo', 'rName', 'rAddress', 'rCity')->where('id', $customerReserve->restAcc_id)->first();
                
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
            $orderSet = OrderSet::select('orderSetName')->where('id', $customerQueue->orderSet_id)->where('restAcc_id', $customerQueue->restAcc_id)->first();
            
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
                'rName' => $restaurant->rName,
                'rAddress' => "$restaurant->rAddress, $restaurant->rBranch, $restaurant->rCity",
                'orderName' => $orderSet->orderSetName,
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
            $orderSet = OrderSet::select('orderSetName')->where('id', $customerReserve->orderSet_id)->where('restAcc_id', $customerReserve->restAcc_id)->first();
            
            $bookDate = explode('-', $customerReserve->reserveDate);
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

            return response()->json([
                'bookDate' => "$month $day, $year",
                'bookTime' => $bookTime,
                'rName' => $restaurant->rName,
                'rAddress' => "$restaurant->rAddress, $restaurant->rBranch, $restaurant->rCity",
                'orderName' => $orderSet->orderSetName,
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
            
            foreach($foodSets as $foodSet){
                $getFoodSet = FoodSet::where('id', $foodSet->foodSet_id)->first();
                array_push($finalFoodSets, [
                    'foodSetId' => $foodSet->foodSet_id,
                    'foodSetImage' => $this->FOOD_SET_IMAGE_PATH.'/'.$getFoodSet->restAcc_id.'/'. $getFoodSet->foodSetImage,
                    'foodSetName' => $getFoodSet->foodSetName,
                    'foodSetDescription' => $getFoodSet->foodSetDescription,
                ]);
            }
            
            if(!$foodItems->isEmpty()){
                array_push($finalFoodSets, [
                    'foodSetId' => 0,
                    'foodSetImage' => $this->ACCOUNT_NO_IMAGE_PATH."/add_ons_img.png",
                    'foodSetName' => "Add Ons",
                    'foodSetDescription' => "Add ons contain price",
                ]);
            }
        } else if($customerReserve != null){
            $restAccId = $customerReserve->restAcc_id;
            $orderSetId = $customerReserve->orderSet_id;
            $foodSets = OrderSetFoodSet::where('orderSet_id', $customerReserve->orderSet_id)->get();
            $foodItems = OrderSetFoodItem::where('orderSet_id', $customerReserve->orderSet_id)->get();
            
            foreach($foodSets as $foodSet){
                $getFoodSet = FoodSet::where('id', $foodSet->foodSet_id)->first();
                array_push($finalFoodSets, [
                    'foodSetId' => $foodSet->foodSet_id,
                    'foodSetImage' => $this->FOOD_SET_IMAGE_PATH.'/'.$getFoodSet->restAcc_id.'/'. $getFoodSet->foodSetImage,
                    'foodSetName' => $getFoodSet->foodSetName,
                    'foodSetDescription' => $getFoodSet->foodSetDescription,
                ]);
            }
            
            if(!$foodItems->isEmpty()){
                array_push($finalFoodSets, [
                    'foodSetId' => 0,
                    'foodSetImage' => $this->ACCOUNT_NO_IMAGE_PATH."/add_ons_img.png",
                    'foodSetName' => "Add Ons",
                    'foodSetDescription' => "Add ons contain price",
                ]);
            }
        } else {}
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
            foreach ($foodItems as $foodItem){
                $food = FoodItem::where('id', $foodItem->foodItem_id)->where('restAcc_id', $restAcc_id)->first();
                array_push($finalFoodItems, [
                    'foodItemId' => $food->id,
                    'foodItemName' => $food->foodItemName,
                    'foodItemDescription' => $food->foodItemDescription,
                    'foodItemPrice' => "$food->foodItemPrice",
                    'foodItemImage' => $this->FOOD_ITEM_IMAGE_PATH.'/'.$restAcc_id.'/'. $food->foodItemImage,
                    'foodItemType' => "not free"
                ]);
            }
        } else {
            $foodItems = FoodSetItem::where('foodSet_id', $foodSet_id)->get();
            foreach ($foodItems as $foodItem){
                $food = FoodItem::where('id', $foodItem->foodItem_id)->where('restAcc_id', $restAcc_id)->first();
                array_push($finalFoodItems, [
                    'foodItemId' => $food->id,
                    'foodItemName' => $food->foodItemName,
                    'foodItemDescription' => $food->foodItemDescription,
                    'foodItemPrice' => "0.00",
                    'foodItemImage' => $this->FOOD_ITEM_IMAGE_PATH.'/'.$restAcc_id.'/'. $food->foodItemImage,
                    'foodItemType' => "free"
                ]);
            }
        }

        return response()->json($finalFoodItems);
    }
    public function orderingAddFoodItem(Request $request){
        $customerQueue = CustomerQueue::where('customer_id', $request->cust_id)->where('status', 'eating')->first();
        $customerReserve = CustomerReserve::where('customer_id', $request->cust_id)->where('status', 'eating')->first();

        // $customerQrAccess = CustomerQrAccess::where('subCust_id', $cust_id)->first();

        $finalStatus = "";
        if($customerQueue != null){
             $customerOrdering = CustomerOrdering::where('custBook_id', $customerQueue->id)->first();
             $countQuantity = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', "Processing")->sum('quantity');

             if($countQuantity >= 5){
                $finalStatus = "Item max limit";
             } else {
                $customerOrderCount = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)
                ->where('orderDone', "Processing")
                ->where('foodItem_id', $request->foodItemId)
                ->first();

                if($customerOrderCount == null){
                    $tableNumber = explode(',', $customerOrdering->tableNumbers);
                    CustomerLOrder::create([
                        'custOrdering_id' => $customerOrdering->id,
                        'cust_id' => $request->cust_id,
                        'tableNumber' => $tableNumber[0],
                        'foodItem_id' => $request->foodItemId,
                        'foodItemName' => $request->foodName,
                        'quantity' => 1,
                        'price' => $request->foodPrice,
                        'orderDone' => "Processing",
                    ]);
                    $finalStatus = "Item Added";
                } else {
                    CustomerLOrder::where('custOrdering_id', $customerOrdering->id)
                    ->where('orderDone', "Processing")
                    ->where('foodItem_id', $request->foodItemId)
                    ->update([
                        'quantity' => $customerOrderCount->quantity + 1,
                        'price' => $customerOrderCount->price * 2,
                    ]);
                    $finalStatus = "Item Updated";
                }
             }
             
        } else if ($customerReserve != null){
            $customerOrdering = CustomerOrdering::where('custBook_id', $customerReserve->id)->first();
             $countQuantity = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)->where('orderDone', "Processing")->sum('quantity');

             if($countQuantity >= 5){
                $finalStatus = "Item max limit";
             } else {
                $customerOrderCount = CustomerLOrder::where('custOrdering_id', $customerOrdering->id)
                ->where('orderDone', "Processing")
                ->where('foodItem_id', $request->foodItemId)
                ->first();

                if($customerOrderCount == null){
                    $tableNumber = explode(',', $customerOrdering->tableNumbers);
                    CustomerLOrder::create([
                        'custOrdering_id' => $customerOrdering->id,
                        'cust_id' => $request->cust_id,
                        'tableNumber' => $tableNumber[0],
                        'foodItem_id' => $request->foodItemId,
                        'foodItemName' => $request->foodName,
                        'quantity' => 1,
                        'price' => $request->foodPrice,
                        'orderDone' => "Processing",
                    ]);
                    $finalStatus = "Item Added";
                } else {
                    CustomerLOrder::where('custOrdering_id', $customerOrdering->id)
                    ->where('orderDone', "Processing")
                    ->where('foodItem_id', $request->foodItemId)
                    ->update([
                        'quantity' => $customerOrderCount->quantity + 1,
                        'price' => $customerOrderCount->price * 2,
                    ]);
                    $finalStatus = "Item Updated";
                }
             }
        } else {
            $finalStatus = "Error";
        }

        return response()->json([
            'status' => "$finalStatus"
        ]);
    }
    public function getOrderingOrders($cust_id){
        $customerOrders = CustomerLOrder::where('cust_id', $cust_id)->where('orderDone', "Processing")->get();
        $finalCustomerOrders = array();

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

        return response()->json($finalCustomerOrders);
    }
    
}
