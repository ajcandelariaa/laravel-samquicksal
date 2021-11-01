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
use App\Models\CustomerAccount;
use App\Models\CustomerReserve;
use App\Models\OrderSetFoodSet;
use App\Models\UnavailableDate;
use App\Mail\RestaurantVerified;
use App\Models\CustomerLRequest;
use App\Models\CustomerOrdering;
use App\Models\OrderSetFoodItem;
use App\Models\RestaurantAccount;
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
use App\Models\CustomerQrAccess;

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





    // RENDER VIEWS
    public function ltCustOrderOSPartView($id){
        $restAcc_id = Session::get('loginId');
        $getDateToday = date('Y-m-d');
        $customerOrdering = CustomerOrdering::where('id', $id)
        ->where('restAcc_id', $restAcc_id)
        ->where('status', 'eating')
        ->where('orderingDate', $getDateToday)
        ->first();

        if($customerOrdering->custBookType == "queue"){
            $customerQueue = CustomerQueue::where('id', $customerOrdering->custBook_id)
            ->where('restAcc_id', $restAcc_id)
            ->first();

            $orderSet = OrderSet::where('id', $customerQueue->orderSet_id)
            ->where('restAcc_id', $restAcc_id)
            ->first();

            
            $orderSetName = $orderSet->orderSetName;
            $customerBook = $customerQueue;

            $customer = CustomerAccount::select('profileImage', 'id')->where('id', $customerQueue->customer_id)->first();
            
        } else {
            $customerReserve = CustomerReserve::where('id', $customerOrdering->custBook_id)
            ->where('restAcc_id', $restAcc_id)
            ->first();

            $orderSet = OrderSet::where('id', $customerReserve->orderSet_id)
            ->where('restAcc_id', $restAcc_id)
            ->first();

            $orderSetName = $orderSet->orderSetName;
            $customerBook = $customerReserve;

            $customer = CustomerAccount::select('profileImage', 'id')->where('id', $customerReserve->customer_id)->first();
        }

        $customerOrders = CustomerLOrder::where('custOrdering_id', $id)->where('orderDone', 'Yes')->get();
        $customerRequests = CustomerLRequest::where('custOrdering_id', $id)->where('requestDone', 'Yes')->get();

        $endTime = Carbon::now();
        $customerTOrders = CustomerLOrder::select('created_at')->where('custOrdering_id', $id)->latest('created_at')->first();
        $customerTRequests = CustomerLRequest::select('created_at')->where('custOrdering_id', $id)->latest('created_at')->first();


        if($customerTOrders != null){
            $rAppstartTime = Carbon::parse($customerTOrders->created_at);
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
            $rAppstartTime2 = Carbon::parse($customerTRequests->created_at);
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

        $addOns = CustomerLOrder::where('custOrdering_id', $id)->where('orderDone', 'Yes')->count('price');
        $subTotal = ($orderSet->orderSetPrice * $customerBook->numberOfPersons) + $addOns;

        $finalReward = "None";
        $rewardDiscount = 0.00;
        if($customerBook->rewardStatus == "Complete"){
            switch($customerBook->rewardType){
                case "DSCN": 
                    $finalReward = "Discount $customerBook->rewardInput% in a Total Bill";
                    $rewardDiscount = ($customerBook->numberOfPersons * $orderSet->orderSetPrice) * ($customerBook->rewardInput / 100);
                    break;
                case "FRPE": 
                    $finalReward = "Free $customerBook->rewardInput person in a group";
                    $rewardDiscount = $orderSet->orderSetPrice * $customerBook->rewardInput;
                    break;
                case "HLF": 
                    $finalReward = "Half in the group will be free";
                    $rewardDiscount = ($customerBook->numberOfPersons * $orderSet->orderSetPrice) / 2;
                    break;
                case "ALL": 
                    $finalReward = "All people in the group will be free";
                    $rewardDiscount = $customerBook->numberOfPersons * $orderSet->orderSetPrice;
                    break;
                default: 
                    $finalReward = "None";
            }
        }

        $seniorDiscount = $orderSet->orderSetPrice * ($customerBook->numberOfPwd * 0.2);
        $childrenDiscount = $orderSet->orderSetPrice * ($customerBook->numberOfChildren * ($customerBook->childrenDiscount / 100));
        $totalPrice = $subTotal - (
            $rewardDiscount + 
            $seniorDiscount + 
            $childrenDiscount + 
            $customerBook->additionalDiscount + 
            $customerBook->promoDiscount +
            $customerBook->offenseCharges 
        );
        
        return view('restaurant.liveTransactions.customerOrdering.custOrderSum', [
            'customerOrdering' => $customerOrdering,
            'customerOrders' => $customerOrders,
            'customerRequests' => $customerRequests,
            'order' => $orderSetName,
            'orderSet' => $orderSet,
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
        ->where('accessDate', $getDateToday)
        ->get();

        $finalCustomerAccess = array();
        if($customerQrAccess->isEmpty()){
            foreach($customerQrAccess as $customerQr){
                $customer = CustomerAccount::where('id', $customerQr->subCust_id)->first();

                $userSinceDate = explode('-', date('Y-m-d', strtotime($customer->created_at)));
                $month2 = $this->convertMonths($userSinceDate[1]);
                $year2 = $userSinceDate[0];
                $day2  = $userSinceDate[2];

                array_push($finalCustomerAccess, [
                    'custName' => $customer->name,
                    'tableNumber' => $customerQr->tableNumber,
                    'userSince' => "$month2 $day2, $year2",
                ]);
            }
        }
        
        if($customerOrdering->custBookType == "queue"){
            $customerQueue = CustomerQueue::select('orderSet_id', 'customer_id')
            ->where('id', $customerOrdering->custBook_id)
            ->where('restAcc_id', $restAcc_id)
            ->first();

            $orderSet = OrderSet::select('orderSetName')
            ->where('id', $customerQueue->orderSet_id)
            ->where('restAcc_id', $restAcc_id)
            ->first();
            
            $orderSetName = $orderSet->orderSetName;

            $customer = CustomerAccount::select('profileImage', 'id')->where('id', $customerQueue->customer_id)->first();
            
        } else {
            $customerReserve = CustomerReserve::select('orderSet_id', 'customer_id')
            ->where('id', $customerOrdering->custBook_id)
            ->where('restAcc_id', $restAcc_id)
            ->first();

            $orderSet = OrderSet::select('orderSetName')
            ->where('id', $customerReserve->orderSet_id
            )->where('restAcc_id', $restAcc_id)
            ->first();

            $orderSetName = $orderSet->orderSetName;

            $customer = CustomerAccount::select('profileImage', 'id')->where('id', $customerReserve->customer_id)->first();
        }

        $customerOrders = CustomerLOrder::where('custOrdering_id', $id)->where('orderDone', 'No')->get();
        $customerRequests = CustomerLRequest::where('custOrdering_id', $id)->where('requestDone', 'No')->get();

        $endTime = Carbon::now();
        $customerTOrders = CustomerLOrder::select('created_at')->where('custOrdering_id', $id)->latest('created_at')->first();
        $customerTRequests = CustomerLRequest::select('created_at')->where('custOrdering_id', $id)->latest('created_at')->first();

        if($customerTOrders != null){
            $rAppstartTime = Carbon::parse($customerTOrders->created_at);
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
            $rAppstartTime2 = Carbon::parse($customerTRequests->created_at);
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

        return view('restaurant.liveTransactions.customerOrdering.custOrderReq', [
            'customerOrdering' => $customerOrdering,
            'customerOrders' => $customerOrders,
            'customerRequests' => $customerRequests,
            'order' => $orderSetName,
            'rAppDiffTime' => $rAppDiffTime,
            'rAppDiffTime2' => $rAppDiffTime2,
            'customer' => $customer,
            'finalCustomerAccess' => $finalCustomerAccess,
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
                if($customerQueue->eatingDateTime != null){
                    $starTime = $customerQueue->eatingDateTime;
                    $endTime = date(Carbon::parse($customerQueue->eatingDateTime)->addHours($customerQueue->hoursOfStay));
                    $startCountDown = "yes";
                } else {
                    $starTime = $customerQueue->eatingDateTime;
                    $endTime = date(Carbon::parse($customerQueue->eatingDateTime)->addHours($customerQueue->hoursOfStay));
                    $startCountDown = "no";
                }
            } else {
                $customerReserve = CustomerReserve::select('eatingDateTime', 'hoursOfStay')->where('id', $eatingCustomer->custBook_id)->first();
                if($customerReserve->eatingDateTime != null){
                    $starTime = $customerReserve->eatingDateTime;
                    $endTime = date(Carbon::parse($customerReserve->eatingDateTime)->addHours($customerReserve->hoursOfStay));
                    $startCountDown = "yes";
                } else {
                    $starTime = $customerReserve->eatingDateTime;
                    $endTime = date(Carbon::parse($customerReserve->eatingDateTime)->addHours($customerReserve->hoursOfStay));
                    $startCountDown = "no";
                }
            }

            if(sizeOf($customerTableNumbers) == 1){
                array_push($customers, [
                    'custOrdering_id' => $eatingCustomer->id,
                    'tableNumber' => $eatingCustomer->tableNumbers,
                    'countOrders' => $countOrders,
                    'countRequests' => $countRequests,
                    'grantedAccess' => $eatingCustomer->grantedAccess,
                    'starTime' => $starTime,
                    'endTime' => $endTime,
                    'startCountDown' => $startCountDown,
                ]);
            } else if (sizeOf($customerTableNumbers) > 1){
                foreach ($customerTableNumbers as $customerTableNumber){
                    array_push($customers, [
                        'custOrdering_id' => $eatingCustomer->id,
                        'tableNumber' => $customerTableNumber,
                        'countOrders' => $countOrders,
                        'countRequests' => $countRequests,
                        'grantedAccess' => $eatingCustomer->grantedAccess,
                        'starTime' => $starTime,
                        'endTime' => $endTime,
                        'startCountDown' => $startCountDown,
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
                        'starTime' => $customer['starTime'],
                        'endTime' => $customer['endTime'],
                        'startCountDown' => $customer['startCountDown'],
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
                    'starTime' => "",
                    'endTime' => "",
                    'startCountDown' => "",
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
        $orderSets = OrderSet::where('restAcc_id', $restAcc_id)->get();


        return view('restaurant.liveTransactions.approvedCustomer.addWalkin', [
            'rTimeLimit' => $restaurant->rTimeLimit,
            'rCapacityPerTable' => $restaurant->rCapacityPerTable,
            'orderSets' => $orderSets,
        ]);
    }
    public function ltAppCustRParticularView($id){
        return view('restaurant.liveTransactions.approvedCustomer.reserveView');
    }
    public function ltAppCustRListView(){
        return view('restaurant.liveTransactions.approvedCustomer.reserveList');
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
            $orderSet = OrderSet::where('id', $customerQueue->orderSet_id)->first();
    
            $countQueues = CustomerQueue::where('restAcc_id', $restAcc_id)
                        ->where('status', 'approved')
                        ->where('customer_id', $customerQueue->customer_id)
                        ->count();
    
            $countCancelled = CustomerQueue::where('restAcc_id', $restAcc_id)
                        ->where('status', 'cancelled')
                        ->where('customer_id', $customerQueue->customer_id)
                        ->count();
    
            $countNoShow = CustomerQueue::where('restAcc_id', $restAcc_id)
                        ->where('status', 'noShow')
                        ->where('customer_id', $customerQueue->customer_id)
                        ->count();
    
            $countRunaway = CustomerQueue::where('restAcc_id', $restAcc_id)
                        ->where('status', 'runaway')
                        ->where('customer_id', $customerQueue->customer_id)
                        ->count();
    
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
            if($customerQueue->rewardStatus == "Complete"){
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
                'customerInfo' => $customerInfo,
                'countQueues' => $countQueues,
                'countCancelled' => $countCancelled,
                'countNoShow' => $countNoShow,
                'countRunaway' => $countRunaway,
                'bookTime' => $bookTime,
                'bookDate' => $month1." ".$day1.", ".$year1,
                'userSinceDate' => $month2." ".$day2.", ".$year2,
                'finalReward' => $finalReward,
                'orderSet' => $orderSet,
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
        return view('restaurant.liveTransactions.customerBooking.reserveView');
    }
    public function ltCustBookRListView(){
        return view('restaurant.liveTransactions.customerBooking.reserveList');
    }
    public function ltCustBookQParticularView($id){
        $restAcc_id = Session::get('loginId');
        $customerQueue = CustomerQueue::where('id', $id)->where('restAcc_id', $restAcc_id)->where('status', 'pending')->first();
        $customerInfo = CustomerAccount::where('id', $customerQueue->customer_id)->first();
        $orderSet = OrderSet::where('id', $customerQueue->orderSet_id)->first();

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

        $countQueues = CustomerQueue::where('restAcc_id', $restAcc_id)
                    ->where('status', 'approved')
                    ->where('customer_id', $customerQueue->customer_id)
                    ->count();

        $countCancelled = CustomerQueue::where('restAcc_id', $restAcc_id)
                    ->where('status', 'cancelled')
                    ->where('customer_id', $customerQueue->customer_id)
                    ->count();

        $countNoShow = CustomerQueue::where('restAcc_id', $restAcc_id)
                    ->where('status', 'noShow')
                    ->where('customer_id', $customerQueue->customer_id)
                    ->count();

        $countRunaway = CustomerQueue::where('restAcc_id', $restAcc_id)
                    ->where('status', 'runaway')
                    ->where('customer_id', $customerQueue->customer_id)
                    ->count();

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
        if($customerQueue->rewardStatus == "Complete"){
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
        $childrenDiscount = $orderSet->orderSetPrice * $customerQueue->numberOfChildren;

        
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
            'orderSet' => $orderSet,
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
    public function runawayView(){
        $id = Session::get('loginId');
        $runaway = Runaway::where('restAcc_id', $id)->first();
        return view('restaurant.manageRestaurant.offense.runaway',[
            'title' => 'Manage Offense',
            'runaway' => $runaway
        ]);
    }
    public function noShowView(){
        $id = Session::get('loginId');
        $noShow = NoShow::where('restAcc_id', $id)->first();
        return view('restaurant.manageRestaurant.offense.noShow',[
            'title' => 'Manage Offense',
            'noShow' => $noShow
        ]);
    }
    public function cancelReservationView(){
        $id = Session::get('loginId');
        $cancellation = Cancellation::where('restAcc_id', $id)->first();
        return view('restaurant.manageRestaurant.offense.cancelReservation',[
            'title' => 'Manage Offense',
            'cancellation' => $cancellation
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
        $resAccid = Session::get('loginId');
        $stamp = StampCard::where('restAcc_id', $resAccid)->first();
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
        $orderSet = OrderSet::where('restAcc_Id', '=', $resAccid)
                            ->where('id', '=', $id)
                            ->first();
        $foodItems = FoodItem::where('restAcc_id', '=', $resAccid)->get();
        $foodSets = FoodSet::where('restAcc_id', $resAccid)->get();
        $orderSetFoodItems = OrderSetFoodItem::where('orderSet_id', '=', $id)->get();
        $orderSetFoodSets = OrderSetFoodSet::where('orderSet_id', '=', $id)->get();
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
        $foodSet = FoodSet::where('restAcc_id', '=', $resAccid)
                            ->where('id', '=', $id)
                            ->first();
        return view('restaurant.manageRestaurant.foodMenu.foodSetEdit',[
            'foodSet' => $foodSet,
            'title' => 'Manage Food Menu',
            'resAccid' => $resAccid
        ]);
    }
    public function editFoodItemView($id){
        $resAccid = Session::get('loginId');
        $foodItem = FoodItem::where('restAcc_id', '=', $resAccid)
                            ->where('id', '=', $id)
                            ->first();
        return view('restaurant.manageRestaurant.foodMenu.foodItemEdit',[
            'foodItem' => $foodItem,
            'title' => 'Manage Food Menu',
            'resAccid' => $resAccid
        ]);
    }
    public function foodSetDetailView($id){
        $resAccid = Session::get('loginId');
        $foodSet = FoodSet::where('restAcc_Id', '=', $resAccid)
                            ->where('id', '=', $id)
                            ->first();
        $foodItems = FoodItem::where('restAcc_id', '=', $resAccid)->get();
        $foodSetItems = FoodSetItem::where('foodSet_id', '=', $id)->get();
        return view('restaurant.manageRestaurant.foodMenu.foodSetDetail',[
            'foodSet' => $foodSet,
            'title' => 'Manage Food Menu',
            'id' => $resAccid,
            'foodItems' => $foodItems,
            'foodSetItems' => $foodSetItems
        ]);
    }
    public function manageRestaurantChecklistView(){
        $id = Session::get('loginId');
        $account = RestaurantAccount::where('id', '=', $id)->first();
        return view('restaurant.manageRestaurant.checkList.checkList',[
            'account' => $account,
            'title' => 'Manage Checklist'
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
                                  ->orWhere('orderSetTagline', 'LIKE', $searchText)
                                  ->orWhere('orderSetDescription', 'LIKE', $searchText)
                                  ->orWhere('orderSetPrice', 'LIKE', $searchText);
                        })
                        ->paginate(4);
            $orderSets->appends($request->all());
            return view('restaurant.manageRestaurant.foodMenu.orderSet',[
                'orderSets' => $orderSets,
                'title' => 'Manage Food Menu',
                'id' => $id
            ]);
        } else {
            $orderSets = OrderSet::where('restAcc_Id', '=', $id)->paginate(2);
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
                                  ->orWhere('foodSetDescription', 'LIKE', $searchText)
                                  ->orWhere('foodSetPrice', 'LIKE', $searchText);
                        })
                        ->paginate(4);
            $foodSets->appends($request->all());
            return view('restaurant.manageRestaurant.foodMenu.foodSet',[
                'foodSets' => $foodSets,
                'title' => 'Manage Food Menu',
                'id' => $id
            ]);
        } else {
            $foodSets = FoodSet::where('restAcc_Id', '=', $id)->paginate(2);
            return view('restaurant.manageRestaurant.foodMenu.foodSet',[
                'foodSets' => $foodSets,
                'title' => 'Manage Food Menu',
                'id' => $id
            ]);
        }
    }
    public function manageRestaurantFoodItemView(Request $request){
        $id = Session::get('loginId');
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $searchText = '%'.$_GET['q'].'%';
            $foodItems = FoodItem::where('restAcc_Id', $id)
                        ->where(function ($query) use ($searchText) {
                            $query->where('foodItemName', 'LIKE', $searchText)
                                  ->orWhere('foodItemDescription', 'LIKE', $searchText)
                                  ->orWhere('foodItemPrice', 'LIKE', $searchText);
                        })
                        ->orderBy('id', 'DESC')
                        ->paginate(10);
            $foodItems->appends($request->all());
            return view('restaurant.manageRestaurant.foodMenu.foodItem',[
                'foodItems' => $foodItems,
                'title' => 'Manage Food Menu',
                'id' => $id
            ]);
        } else {
            $foodItems = FoodItem::where('restAcc_Id', $id)->orderBy('id', 'DESC')->paginate(10);
            return view('restaurant.manageRestaurant.foodMenu.foodItem',[
                'foodItems' => $foodItems,
                'title' => 'Manage Food Menu',
                'id' => $id
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
            'landlineNumber' => 'numeric|min:8',
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
        return redirect('/restaurant/register2');
    }
    public function registerView2(){
        return view('restaurant.register2');
    }
    public function registerView(){
        return view('restaurant.register');
    }
    public function loginView(){
        return view('restaurant.login');
    }







    

    










    // RENDER LOGICS
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
        if($customerOrder->custBookType == "queue"){
            $customer = CustomerQueue::where('id', $customerOrder->custBook_id)->first();
            CustomerQueue::where('id', $customerOrder->custBook_id)
            ->update([
                'status' => 'eating'
            ]);
        } else {
            $customer = CustomerReserve::where('id', $customerOrder->custBook_id)->first();
            CustomerReserve::where('id', $customerOrder->custBook_id)
            ->update([
                'status' => 'eating'
            ]);
        }
        CustomerOrdering::where('id', $id)->where('orderingDate', $getDateToday)
        ->update([
            'grantedAccess' => "Yes"
        ]);
        
        CustomerNotification::create([
            'customer_id' => $customer->customer_id,
            'restAcc_id' => $restAcc_id,
            'notificationType' => "Table is Ready",
            'notificationTitle' => "Your table is now Ready. Enjoy your meal!",
            'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
            'notificationStatus' => "Unread",
        ]);
        Session::flash('grantedAccess');
        return redirect('/restaurant/live-transaction/customer-ordering/list/'.$id.'/order-request');
    }
    public function ltAppCustQAddWalkIn(Request $request){
        $restAcc_id = Session::get('loginId');

        $request->validate([
            'walkInName' => 'required',
            'walkInPersons' => 'required',
        ],
        [
            'walkInName.required' => 'Name is required',
            'walkInPersons.required' => 'No. of Persons is required',
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
    }
    public function ltAppCustRAdmit($id){
    }
    public function ltAppCustRNoShow($id){
        
    }
    public function ltAppCustQAdmit(Request $request, $id){
        $restAcc_id = Session::get('loginId');
        $request->validate([
            'tableNumbers' => 'required',
        ]);
        $getDateToday = date('Y-m-d');

        $restaurant = RestaurantAccount::select('rNumberOfTables')->where('id', $restAcc_id)->first();
        $customerQueue = CustomerQueue::where('id', $id)->where('restAcc_id', $restAcc_id)->where('queueDate', $getDateToday)->first();
        
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
                            $customerQueue = CustomerQueue::where('id', $id)->first();
                            $restaurant = RestaurantAccount::select('rName', 'rBranch', 'rAddress', 'rCity')->where('id', $customerQueue->restAcc_id)->first();
                            
                            if($customerQueue->name == null){
                                $customer = CustomerAccount::select('name')->where('id', $customerQueue->customer_id)->first();
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

                            CustomerNotification::create([
                                'customer_id' => $customerQueue->customer_id,
                                'restAcc_id' => $customerQueue->restAcc_id,
                                'notificationType' => "Table Setting Up",
                                'notificationTitle' => "Your Table is Setting up",
                                'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
                                'notificationStatus' => "Unread",
                            ]);
                            
                            $request->session()->flash('admitted');
                            return redirect('/restaurant/live-transaction/approved-customer/queue');
                        }
                    }
                }
            }
        }
        
    }
    public function ltAppCustQNoShow($id){
        $customerQueue = CustomerQueue::where('id', $id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch', 'rAddress', 'rCity')->where('id', $customerQueue->restAcc_id)->first();

        CustomerQueue::where('id', $id)
        ->update([
            'status' => 'noShow',
        ]);

        CustomerNotification::create([
            'customer_id' => $customerQueue->customer_id,
            'restAcc_id' => $customerQueue->restAcc_id,
            'notificationType' => "No Show",
            'notificationTitle' => "Your Booking is labelled as No Show",
            'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
            'notificationStatus' => "Unread",
        ]);

        Session::flash('noShow');
        return redirect('/restaurant/live-transaction/approved_customer/queue');
    }
    public function ltAppCustQValidate($id){
        $customerQueue = CustomerQueue::where('id', $id)->first();
        $restaurant = RestaurantAccount::select('rName', 'rBranch', 'rAddress', 'rCity')->where('id', $customerQueue->restAcc_id)->first();

        CustomerQueue::where('id', $id)
        ->update([
            'status' => 'validation',
            'validationDateTime' => date('Y-m-d H:i:s'),
        ]);

        CustomerNotification::create([
            'customer_id' => $customerQueue->customer_id,
            'restAcc_id' => $customerQueue->restAcc_id,
            'notificationType' => "Validation",
            'notificationTitle' => "Please go to the front desk to Confirm your Booking ",
            'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
            'notificationStatus' => "Unread",
        ]);


        Session::flash('validation');
        return redirect('/restaurant/live-transaction/approved-customer/queue/'.$id);
    }


    public function ltCustBookRDecline($id){
        
    }
    public function ltCustBookRApprove($id){
        
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
        $restaurant = RestaurantAccount::select('rName', 'rBranch', 'rAddress', 'rCity')->where('id', $customerQueue->restAcc_id)->first();

        CustomerQueue::where('id', $id)
        ->update([
            'status' => 'approved',
            'approvedDateTime' => date('Y-m-d H:i:s'),
        ]);

        CustomerNotification::create([
            'customer_id' => $customerQueue->customer_id,
            'restAcc_id' => $customerQueue->restAcc_id,
            'notificationType' => "Approved",
            'notificationTitle' => "Your Booking has been Approved",
            'notificationDescription' => "$restaurant->rAddress, $restaurant->rCity",
            'notificationStatus' => "Unread",
        ]);


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
        $restAccId = Session::get('loginId');
        $checkStampExist = StampCard::where('restAcc_id', $restAccId)->first();

        if($checkStampExist != null){
            $request->session()->flash('invalid');
            return redirect('/restaurant/manage-restaurant/task-rewards/stamp-card');
        }

        $request->validate([
            'stampCapacity' => 'required',
            'stampReward'  => 'required',
            'tasks'          => 'required',
            'stampValidity' => 'required',
        ]);

        StampCard::create([
            'restAcc_id' => $restAccId,
            'stampCapacity' => $request->stampCapacity,
            'stampReward_id' => $request->stampReward,
            'stampValidity' => $request->stampValidity,
        ]);

        $getLatestStamp = StampCard::where('restAcc_id', $restAccId)->first();

        foreach ($request->tasks as $taskId){
            StampCardTasks::create([
                'stampCards_id' => $getLatestStamp->id,
                'restaurantTaskLists_id' => $taskId,
            ]);
        }
        $request->session()->flash('added');
        return redirect('/restaurant/manage-restaurant/task-rewards/stamp-card');
    }
    public function editTask3(Request $request){
        $restAccId = Session::get('loginId');

        $checkStampExist = StampCard::where('restAcc_id', $restAccId)->first();
        if($checkStampExist != null){
            $request->session()->flash('cantEdit');
            return redirect('/restaurant/manage-restaurant/task-rewards/tasks');
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

        $checkStampExist = StampCard::where('restAcc_id', $restAccId)->first();
        if($checkStampExist != null){
            $request->session()->flash('cantEdit');
            return redirect('/restaurant/manage-restaurant/task-rewards/tasks');
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

        $checkStampExist = StampCard::where('restAcc_id', $restAccId)->first();
        if($checkStampExist != null){
            $request->session()->flash('cantEdit');
            return redirect('/restaurant/manage-restaurant/task-rewards/tasks');
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

        $checkStampExist = StampCard::where('restAcc_id', $restAccId)->first();
        if($checkStampExist != null){
            $request->session()->flash('cantEdit');
            return redirect('/restaurant/manage-restaurant/task-rewards/rewards');
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

        $checkStampExist = StampCard::where('restAcc_id', $restAccId)->first();
        if($checkStampExist != null){
            $request->session()->flash('cantEdit');
            return redirect('/restaurant/manage-restaurant/task-rewards/rewards');
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
        if($request->offenseType == "limitBlock"){
            $request->validate([
                'numberOfRunaway' => 'required',
                'numberOfDays' => 'required',
                'offenseType' => 'required',
                'cancelBlockDays' => 'required',
            ]);
        } else {
            $request->validate([
                'numberOfRunaway' => 'required',
                'numberOfDays' => 'required',
                'offenseType' => 'required',
            ]);
        }
        $blockDays = "";
        if($request->offenseType == "noOffense"){
            $blockDays = "0";
        } else if ($request->offenseType == "permanentBlock") {
            $blockDays = "Permanent";
        } else {
            $blockDays = $request->cancelBlockDays;
        }

        Runaway::where('id', $id)->where('restAcc_id', $restAccId)
        ->update([
            'noOfRunaways' => $request->numberOfRunaway,
            'noOfDays' => $request->numberOfDays,
            'blockDays' => $blockDays,
        ]);

        $request->session()->flash('edited');
        return redirect('/restaurant/manage-restaurant/offense/runaway');
    }
    public function editNoShow(Request $request, $id){
        $restAccId = Session::get('loginId');
        if($request->offenseType == "limitBlock"){
            $request->validate([
                'numberOfNoShow' => 'required',
                'numberOfDays' => 'required',
                'offenseType' => 'required',
                'cancelBlockDays' => 'required',
            ]);
        } else {
            $request->validate([
                'numberOfNoShow' => 'required',
                'numberOfDays' => 'required',
                'offenseType' => 'required',
            ]);
        }
        $blockDays = "";
        if($request->offenseType == "noOffense"){
            $blockDays = "0";
        } else if ($request->offenseType == "permanentBlock") {
            $blockDays = "Permanent";
        } else {
            $blockDays = $request->cancelBlockDays;
        }

        NoShow::where('id', $id)->where('restAcc_id', $restAccId)
        ->update([
            'noOfNoShows' => $request->numberOfNoShow,
            'noOfDays' => $request->numberOfDays,
            'blockDays' => $blockDays,
        ]);

        $request->session()->flash('edited');
        return redirect('/restaurant/manage-restaurant/offense/no-show');
    }
    public function editCancelReservation(Request $request, $id){
        $restAccId = Session::get('loginId');
        if($request->offenseType == "limitBlock"){
            $request->validate([
                'numberOfCancellation' => 'required',
                'numberOfDays' => 'required',
                'offenseType' => 'required',
                'cancelBlockDays' => 'required',
            ]);
        } else {
            $request->validate([
                'numberOfCancellation' => 'required',
                'numberOfDays' => 'required',
                'offenseType' => 'required',
            ]);
        }
        $blockDays = "";
        if($request->offenseType == "noOffense"){
            $blockDays = "0";
        } else if ($request->offenseType == "permanentBlock") {
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
        return redirect('/restaurant/manage-restaurant/offense/cancel-reservation');
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
            UnavailableDate::where('id', $request->updateUnavailableDateId)
            ->update([
                'unavailableDatesDate' => $request->updateDate,
                'unavailableDatesDesc' => $request->updateDescription,
            ]);
            $request->session()->flash('edited');
            return redirect('/restaurant/manage-restaurant/time/unavailable-dates');
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
            UnavailableDate::create([
                'restAcc_id' => $restAccId,
                'unavailableDatesDate' => $request->date,
                'unavailableDatesDesc' => $request->description,
            ]);
            $request->session()->flash('added');
            return redirect('/restaurant/manage-restaurant/time/unavailable-dates');
        }
    }
    public function editStoreHours(Request $request){
        if($request->updateDays == null){
            $request->session()->flash('emptyDays');
            return redirect('/restaurant/manage-restaurant/time/store-hours');
        } 
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
        if($request->days == null){
            $request->session()->flash('emptyDays');
            return redirect('/restaurant/manage-restaurant/time/store-hours');
        } 
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
            'rCapacityPerTable' => 'required|numeric|min:1',
        ],
        [
            'rNumberOfTables.required' => 'Number of Tables is required',
            'rNumberOfTables.numeric' => 'Number of Tables must be number only',
            'rNumberOfTables.min' => 'Number of Tables must be greater than zero',
            'rCapacityPerTable.required' => 'Capacity per Table is required',
            'rCapacityPerTable.numeric' => 'Capacity per Table must be number only',
            'rCapacityPerTable.min' => 'Capacity per Table must be greater than zero',
        ]);

        RestaurantAccount::where('id', $restAccId)
        ->update([
            'rNumberOfTables' => $request->rNumberOfTables,
            'rCapacityPerTable' => $request->rCapacityPerTable,
        ]);

        $request->session()->flash('tablesUpdated');
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
            'landlineNumber' => 'digits:8',
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
        $orderSet = OrderSet::where('id', '=', $id)
                            ->where('restAcc_Id', '=', $restAccId)
                            ->first();
        File::delete(public_path('uploads/restaurantAccounts/orderSet/'.$restAccId.'/'.$orderSet->orderSetImage));
        OrderSet::where('id', $id)->delete();
        Session::flash('deleted');
        return redirect('/restaurant/manage-restaurant/food-menu/order-set');
    }
    public function editOrderSet(Request $request, $id){
        $restAccId = Session::get('loginId');
        $orderSetData = OrderSet::where('id', $id)
                                ->where('restAcc_id', $restAccId)
                                ->first();
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
    }
    public function orderSetFoodItemDelete($orderSetid, $orderSetFoodItemId){
        OrderSetFoodItem::where('id', $orderSetFoodItemId)->delete();
        Session::flash('deletedFoodItem');
        return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$orderSetid);
    }
    public function orderSetFoodSetDelete($orderSetid, $orderSetFoodSetId){
        OrderSetFoodSet::where('id', $orderSetFoodSetId)->delete();
        Session::flash('deletedFoodSet');
        return redirect('/restaurant/manage-restaurant/food-menu/order-set/detail/'.$orderSetid);
    }
    public function orderSetAddFoodItem(Request $request, $id){
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
    }
    public function orderSetAddFoodSet(Request $request, $id){
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
        $foodSetData = FoodSet::where('id', $id)
                                ->where('restAcc_id', $restAccId)
                                ->first();
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
    }
    public function editFoodItem(Request $request, $id){
        $restAccId = Session::get('loginId');
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
    }
    public function deleteFoodSet($id){
        $restAccId = Session::get('loginId');
        $foodSet = FoodSet::where('id', '=', $id)
                            ->where('restAcc_Id', '=', $restAccId)
                            ->first();
        File::delete(public_path('uploads/restaurantAccounts/foodSet/'.$restAccId.'/'.$foodSet->foodSetImage));
        FoodSet::where('id', $id)->delete();
        Session::flash('deleted');
        return redirect('/restaurant/manage-restaurant/food-menu/food-set');
    }
    public function foodSetItemDelete($foodSetid, $foodSetItemId){
        FoodSetItem::where('id', $foodSetItemId)->delete();
        Session::flash('deleted');
        return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$foodSetid);
    }
    public function addItemToFoodSet(Request $request){
        $restAccId = Session::get('loginId');
        $checkIfExisting = 0;
        $checkIfExsiting2 = 0;
        if(empty($request->foodItem)){
            $request->session()->flash('empty');
            return redirect('/restaurant/manage-restaurant/food-menu/food-set/detail/'.$request->foodSetId);
        } else {
            $countFoodItem = sizeOf($request->foodItem);
            foreach ($request->foodItem as $foodItemId) {
                $data = FoodSetItem::where('foodSet_id', '=', $request->foodSetId)
                                    ->where('foodItem_id', '=', $foodItemId)
                                    ->get();
                if(!$data->isEmpty()){
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
        $foodItem = FoodItem::where('id', '=', $id)
                            ->where('restAcc_Id', '=', $restAccId)
                            ->first();
        File::delete(public_path('uploads/restaurantAccounts/foodItem/'.$restAccId.'/'.$foodItem->foodItemImage));
        FoodItem::where('id', $id)->delete();
        Session::flash('deleted', "Food Item is Deleted");
        return redirect('/restaurant/manage-restaurant/food-menu/food-item');
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
