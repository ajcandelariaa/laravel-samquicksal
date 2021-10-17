<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerAccount;
use App\Models\FoodItem;
use App\Models\FoodSet;
use App\Models\FoodSetItem;
use App\Models\OrderSet;
use App\Models\OrderSetFoodItem;
use App\Models\OrderSetFoodSet;
use App\Models\Post;
use App\Models\Promo;
use App\Models\RestaurantAccount;
use App\Models\RestaurantRewardList;
use App\Models\RestaurantTaskList;
use App\Models\StampCard;
use App\Models\StampCardTasks;
use App\Models\StoreHour;
use App\Models\UnavailableDate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\RestaurantFormAppreciation;

class CustomerController extends Controller
{
    // public $RESTAURANT_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/logo";
    // public $CUSTOMER_IMAGE_PATH = "http://192.168.1.53:8000/uploads/customerAccounts/logo";
    // public $ACCOUNT_NO_IMAGE_PATH = "http://192.168.1.53:8000/images";
    // public $POST_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/post";
    // public $PROMO_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/promo";
    // public $ORDER_SET_IMAGE_PATH = "http://192.168.1.53:8000/uploads/restaurantAccounts/orderSet";

    
    public $CUSTOMER_IMAGE_PATH = "https://www.samquicksal.com/uploads/customerAccounts/logo";
    public $ACCOUNT_NO_IMAGE_PATH = "https://www.samquicksal.com/images";
    public $POST_IMAGE_PATH = "https://www.samquicksal.com/uploads/restaurantAccounts/post";
    public $PROMO_IMAGE_PATH = "https://www.samquicksal.com/uploads/restaurantAccounts/promo";
    public $ORDER_SET_IMAGE_PATH = "https://www.samquicksal.com/uploads/restaurantAccounts/orderSet";

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
                            $currentTime = date("h:i");
                            if($currentTime < $restaurantStoreHour->openingTime || $currentTime > $restaurantStoreHour->closingTime){
                                $rSchedule = "Closed Now";
                            } else {
                                $openingTime = date("g:i a", strtotime($restaurantStoreHour->openingTime));
                                $closingTime = date("g:i a", strtotime($restaurantStoreHour->closingTime));
                                $rSchedule = "Open today at ".$currentTime." to ".$closingTime;
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
        $account = RestaurantAccount::select('rName', 'rAddress', 'rLogo', 'rNumberOfTables')->where('id', $id)->first();
        $posts = Post::where('restAcc_id', $id)->get();

        $storePosts = array();
        foreach($posts as $post){
            array_push($storePosts, [
                'image' => $this->POST_IMAGE_PATH."/".$id."/".$post->postImage,
                'description' => $post->postDesc,
            ]);
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


        $finalImageUrl = "";
        if ($account->rLogo == ""){
            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/resto-default.png';
        } else {
            $finalImageUrl = $this->RESTAURANT_IMAGE_PATH.'/'.$id.'/'. $account->rLogo;
        }

        return response()->json([
            'rName' => $account->rName,
            'rAddress' => $account->rAddress,
            'rImage' => $finalImageUrl,
            'rSchedule' => $finalSchedule,
            'rTableCapacity' => $account->rNumberOfTables,
            'rTableStatus' => 5,
            'rReservedTables' => 3,
            'rNumberOfPeople' => 16,
            'rNumberOfQueues' => 6,
            'rPosts' => $storePosts,
        ]);
    }
    // -------RESTAURANT ABOUT BY ID------------ //
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
            switch ($orderdate[1]) {
                case '1':
                    $month = "January";
                    break;
                case '2':
                    $month = "February";
                    break;
                case '3':
                    $month = "March";
                    break;
                case '4':
                    $month = "April";
                    break;
                case '5':
                    $month = "May";
                    break;
                case '6':
                    $month = "June";
                    break;
                case '7':
                    $month = "July";
                    break;
                case '8':
                    $month = "August";
                    break;
                case '9':
                    $month = "September";
                    break;
                case '10':
                    $month = "October";
                    break;
                case '11':
                    $month = "November";
                    break;
                case '12':
                    $month = "December";
                    break;
                default:
                    $month = "";
                    break;
            }
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
        
        if($promos == null){
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
                    'foodSetName' => "Others",
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
    



    // -------LOGIN CUSTOMER------------ //
    public function loginCustomer(Request $request){
        $request->validate([
            'emailAddress' => 'required',
            'password' => 'required',
        ]);
        $account = CustomerAccount::where('emailAddress', $request->emailAddress)->first();
        if(empty($account)){
            return response()->json([
                'id' => null,
                'status' => "Account does not exist",
            ]);
        } else {
            if(Hash::check($request->password, $account->password)){
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
        ]);

        $existingAccount = CustomerAccount::where('emailAddress', $request->emailAddress)->first();
        
        if($existingAccount != null){
            return response()->json([
                'id' => null,
                'status' => "Email Address is already existing",
            ]);
        } else {
            $customer = CustomerAccount::create([
                'name' => $request->name,
                'emailAddress' => $request->emailAddress,
                'emailAddressVerified' => "No",
                'contactNumber' => $request->contactNumber,
                'contactNumberVerified' => "No",
                'password' => Hash::make($request->password),
                'profileImage' => "",
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
            

            $details = [
                'applicantName' => $request->name,
            ];

            Mail::to($request->emailAddress)->send(new RestaurantFormAppreciation($details));

            return response()->json([
                'id' => $customer->id,
                'status' => "Registered Successfully",
            ]);
        }
    }
    // -------GET CUSTOMER HOMEPAGE INFO------------ //
    public function getCustomerHomepageInfo($id){
        $account = CustomerAccount::select('name', 'emailAddress', 'profileImage')->where('id', $id)->first();

        $finalImageUrl = "";
        if($account->profileImage == ""){
            $finalImageUrl = $this->ACCOUNT_NO_IMAGE_PATH.'/user-default.png';
        } else {
            $finalImageUrl = $this->CUSTOMER_IMAGE_PATH.'/'.$id.'/'. $account->profileImage;
        }

        return response()->json([
            'name' => $account->name,
            'emailAddress' => $account->emailAddress,
            'profileImage' => $finalImageUrl,
            'status' => "onGoing",
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
}
