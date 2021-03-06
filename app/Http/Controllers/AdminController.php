<?php

namespace App\Http\Controllers;

use App\Models\NoShow;
use App\Models\Runaway;
use App\Models\StoreHour;
use App\Models\Cancellation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\CustomerAccount;
use App\Models\UnavailableDate;
use App\Models\RestaurantAccount;
use App\Models\RestaurantTaskList;
use Illuminate\Support\Facades\DB;
use App\Mail\RestaurantFormApprove;
use App\Mail\RestaurantFormDecline;
use App\Models\RestaurantApplicant;
use App\Models\RestaurantRewardList;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
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
    public function checkTodaySched($restId){
        $restAcc_id = $restId;
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
    public function loginView(){
        return view('admin.login');
    }
    public function dashboardView(){
        $endTime = Carbon::now();
        $getTime1 = RestaurantApplicant::select('created_at', 'id')->latest()->first();
        $getTime2 = RestaurantAccount::select('created_at')->latest()->first();
        $getTime3 = CustomerAccount::select('created_at')->latest()->first();


        // RESTAURANT APPLICANT DIFF TIME
        if($getTime1 != null){ 
            $rAppstartTime = Carbon::parse($getTime1->created_at);
            $rAppDiffSeconds = $endTime->diffInSeconds($rAppstartTime);
            $rAppDiffMinutes = $endTime->diffInMinutes($rAppstartTime);
            $rAppDiffHours = $endTime->diffInHours($rAppstartTime);
            $rAppDiffDays = $endTime->diffInDays($rAppstartTime);

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
        } else {
            $rAppDiffTime = "No entries";
        }


        // RESTAURANT ACCOUNT DIFF TIME
        if($getTime2 != null){ 
            $rAccstartTime = Carbon::parse($getTime2->created_at);
            $rAccDiffSeconds = $endTime->diffInSeconds($rAccstartTime);
            $rAccDiffMinutes = $endTime->diffInMinutes($rAccstartTime);
            $rAccDiffHours = $endTime->diffInHours($rAccstartTime);
            $rAccDiffDays = $endTime->diffInDays($rAccstartTime);

            if($rAccDiffDays > 0){
                if ($rAccDiffDays == 1){
                    $rAccDiffTime = "1 day ago";
                } else {
                    $rAccDiffTime = "$rAccDiffDays days ago";
                }
            } else if ($rAccDiffHours > 0){
                if ($rAccDiffHours == 1){
                    $rAccDiffTime = "1 hour ago";
                } else {
                    $rAccDiffTime = "$rAccDiffHours hours ago";
                }
            } else if ($rAccDiffMinutes > 0){
                if ($rAccDiffMinutes == 1){
                    $rAccDiffTime = "1 minute ago";
                } else {
                    $rAccDiffTime = "$rAccDiffMinutes minutes ago";
                }
            } else {
                if ($rAccDiffSeconds == 1){
                    $rAccDiffTime = "1 second ago";
                } else {
                    $rAccDiffTime = "$rAccDiffSeconds seconds ago";
                }
            }
        } else {
            $rAccDiffTime = "No entries";
        }


        // CUSTOMER ACCOUNT DIFF TIME
        if($getTime3 != null){ 
            $cAccstartTime = Carbon::parse($getTime3->created_at);
            $cAccDiffSeconds = $endTime->diffInSeconds($cAccstartTime);
            $cAccDiffMinutes = $endTime->diffInMinutes($cAccstartTime);
            $cAccDiffHours = $endTime->diffInHours($cAccstartTime);
            $cAccDiffDays = $endTime->diffInDays($cAccstartTime);

            if($cAccDiffDays > 0){
                if ($cAccDiffDays == 1){
                    $cAccDiffTime = "1 day ago";
                } else {
                    $cAccDiffTime = "$cAccDiffDays days ago";
                }
            } else if ($cAccDiffHours > 0){
                if ($cAccDiffHours == 1){
                    $cAccDiffTime = "1 hour ago";
                } else {
                    $cAccDiffTime = "$cAccDiffHours hours ago";
                }
            } else if ($cAccDiffMinutes > 0){
                if ($cAccDiffMinutes == 1){
                    $cAccDiffTime = "1 minute ago";
                } else {
                    $cAccDiffTime = "$cAccDiffMinutes minutes ago";
                }
            } else {
                if ($cAccDiffSeconds == 1){
                    $cAccDiffTime = "1 second ago";
                } else {
                    $cAccDiffTime = "$cAccDiffSeconds seconds ago";
                }
            }
        } else {
            $cAccDiffTime = "No entries";
        }


        
        $restaurantAccountsCount = RestaurantAccount::count();
        $restaurantApplicantsCount = RestaurantApplicant::count();
        $customerAccountsCount = CustomerAccount::count();
        $approvedCount = RestaurantApplicant::where('status', 'Approved')->count();
        $pendingCount = RestaurantApplicant::where('status', 'Pending')->count();
        $declinedCount = RestaurantApplicant::where('status', 'Declined')->count();
        return view('admin.dashboard', [
            'title' => "Dashboard",
            'restaurantAccountsCount' => $restaurantAccountsCount,
            'restaurantApplicantsCount' => $restaurantApplicantsCount,
            'customerAccountsCount' => $customerAccountsCount,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
            'declinedCount' => $declinedCount,
            'rAppDiffTime' => $rAppDiffTime,
            'rAccDiffTime' => $rAccDiffTime,
            'cAccDiffTime' => $cAccDiffTime,
        ]);
    }
    public function restaurantApplicantsView(Request $request){
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $searchText = '%'.$_GET['q'].'%';
            $data = RestaurantApplicant::where('status', 'LIKE', $searchText)
                        ->orWhere('fname', 'LIKE', $searchText)
                        ->orWhere('lname', 'LIKE', $searchText)
                        ->orWhere('role', 'LIKE', $searchText)
                        ->orWhere('rName', 'LIKE', $searchText)
                        ->orWhere('rState', 'LIKE', $searchText)
                        ->orWhere('rCity', 'LIKE', $searchText)
                        ->paginate(10);
            $data->appends($request->all());
            return view('admin.restaurant-applicants', [
                'applicants' => $data,
                'title' => "Restaurant Applicants"
            ]);
        } else {
            $data = RestaurantApplicant::paginate(10);
            return view('admin.restaurant-applicants', [
                'applicants' => $data,
                'title' => "Restaurant Applicants"
            ]);
        }
    }
    public function customerAccountView($id){
        $account = CustomerAccount::where('id', $id)->first();
        
        $userDate = explode('-', date("Y-m-d", strtotime($account->created_at)));
        $month = $this->convertMonths($userDate[1]);
        $year = $userDate[0];
        $day  = $userDate[2];



        return view('admin.customer-account', [
            'account' => $account,
            'title' => "Customer Account View",
            'userJoined' => "$month $day, $year",
        ]);
    }
    public function restaurantApplicantView($id){
        $applicant = RestaurantApplicant::where('id', $id)->first();
        return view('admin.restaurant-applicant', [
            'applicant' => $applicant,
            'title' => "Restaurant Applicant View"
        ]);
    }
    
    public function restaurantApplicantApprovedView($id){
        $applicant = RestaurantApplicant::where('id', $id)->first();
        return view('admin.restaurant-applicant-approve', [
            'applicant' => $applicant,
            'title' => "Restaurant Applicant View"
        ]);
    }
    
    public function restaurantAccountsView(Request $request){
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $searchText = '%'.$_GET['q'].'%';
            $data = RestaurantAccount::where('status', 'LIKE', $searchText)
                        ->orWhere('rName', 'LIKE', $searchText)
                        ->orWhere('rBranch', 'LIKE', $searchText)
                        ->orWhere('rState', 'LIKE', $searchText)
                        ->orWhere('rCity', 'LIKE', $searchText)
                        ->orWhere('rCountry', 'LIKE', $searchText)
                        ->paginate(10);
            $data->appends($request->all());
            return view('admin.restaurant-accounts', [
                'accounts' => $data,
                'title' => "Restaurant Owners"
            ]);
        } else {
            $data = RestaurantAccount::paginate(10);
            return view('admin.restaurant-accounts', [
                'accounts' => $data,
                'title' => "Restaurant Owners"
            ]);
        }
    }
    public function restaurantAccountView($id){
        $account = RestaurantAccount::where('id', $id)->first();
        $storeHours = StoreHour::where('restAcc_id', '=', $id)->get();
        $existingDays = array();
        foreach ($storeHours as $storeHour){
            foreach (explode(",", $storeHour->days) as $day){
                array_push($existingDays, $day);
            }
        }

        $totalNumberOfChairs = ($account->r2seater * 2) + 
        ($account->r3seater * 3) + 
        ($account->r4seater * 4) + 
        ($account->r5seater * 5) + 
        ($account->r6seater * 6) + 
        ($account->r7seater * 7) + 
        ($account->r8seater * 8) + 
        ($account->r9seater * 9) + 
        ($account->r10seater * 10);

        $schedToday = $this->checkTodaySched($id);

        return view('admin.restaurant-account', [
            'account' => $account,
            'storeHours' => $storeHours,
            'existingDays' => $existingDays,
            'totalNumberOfChairs' => $totalNumberOfChairs,
            'title' => "Restaurant Account View",
            'schedToday' => $schedToday,
        ]);
    }
    public function customerAccountsView(Request $request){
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $searchText = '%'.$_GET['q'].'%';
            $data = CustomerAccount::where('name', 'LIKE', $searchText)
                        ->orWhere('emailAddress', 'LIKE', $searchText)
                        ->orWhere('contactNumber', 'LIKE', $searchText)
                        ->paginate(10);
            $data->appends($request->all());
            return view('admin.customer-accounts', [
                'accounts' => $data,
                'title' => "Customers"
            ]);
        } else {
            $data = CustomerAccount::paginate(10);
            return view('admin.customer-accounts', [
                'accounts' => $data,
                'title' => "Customers"
            ]);
        }
    }


    // RENDER LOGICS
    public function restaurantAccountViewFile($id, $filename){
        return view('admin.restaurant-account-view-pdf',[
            'id' => $id,
            'filename' => $filename
        ]);
    }
    public function restaurantApplicantViewFile($id, $filename){
        return view('admin.restaurant-applicant-view-pdf',[
            'id' => $id,
            'filename' => $filename
        ]);
    }
    public function logout(){
        Session::flush();
        return redirect('/admin/login');
    }
    public function approveRestaurantApplicant(Request $request){
        $applicant = RestaurantApplicant::where('id', $request->applicantId)->first();
        $account = RestaurantAccount::select('emailAddress')->where('emailAddress', $applicant->emailAddress)->first();

        if($account != null){
            $request->session()->flash('emailAlreadyExist');
            return redirect('/admin/restaurant-applicants/'.$request->applicantId);
        }

        $defaultUsername = time().$applicant->lname;
        $defaultPassword = $applicant->id.str_replace(' ', '', $applicant->rBranch);
        $hashPassword = Hash::make($defaultPassword);

        $result = RestaurantAccount::create([
            'resApp_id' => $applicant->id,
            'verified' => "No",
            'status' => 'Unpublished',
            'fname' => $applicant->fname,
            'mname' => $applicant->mname,
            'lname' => $applicant->lname,
            'address' => $applicant->address,
            'city' => $applicant->city,
            'postalCode' => $applicant->postalCode,
            'state' => $applicant->state,
            'country' => $applicant->country,
            'role' => $applicant->role,
            'birthDate' => $applicant->birthDate,
            'gender' => $applicant->gender,
            'contactNumber' => $applicant->contactNumber,
            'landlineNumber' => $applicant->landlineNumber,
            'emailAddress' => $applicant->emailAddress,

            'username' => $defaultUsername,
            'password' => $hashPassword,

            'rName' => $applicant->rName,
            'rBranch' => $applicant->rBranch,
            'rAddress' => $applicant->rAddress,
            'rCity' => $applicant->rCity,
            'rPostalCode' => $applicant->rPostalCode,
            'rState' => $applicant->rState,
            'rCountry' => $applicant->rCountry,
            
            'rRadius' => 500,

            'rNumberOfTables' => 10,
            'r2seater' => 0,
            'r3seater' => 0,
            'r4seater' => 10,
            'r5seater' => 0,
            'r6seater' => 0,
            'r7seater' => 0,
            'r8seater' => 0,
            'r9seater' => 0,
            'r10seater' => 0,

            'rTimeLimit' => 0,

            'bir' => $applicant->bir,
            'dti' => $applicant->dti,
            'mayorsPermit' => $applicant->mayorsPermit,
            'staffValidId' => $applicant->staffValidId,
        ]);

        $nextIdFolderName = $result->id;
        
        // MAKE DIRECTORY FOR RESTAURANT ACCOUNT
        if(!is_dir('storage/uploads/restaurantAccounts')){
            mkdir('storage/uploads/restaurantAccounts/');
        }
        mkdir('storage/uploads/restaurantAccounts/'.$nextIdFolderName);


        // MAKE DIRECTORIES FOR ACCOUNTS
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
        
        mkdir('uploads/restaurantAccounts/foodItem/'.$nextIdFolderName);
        mkdir('uploads/restaurantAccounts/foodSet/'.$nextIdFolderName);
        mkdir('uploads/restaurantAccounts/orderSet/'.$nextIdFolderName);
        mkdir('uploads/restaurantAccounts/post/'.$nextIdFolderName);
        mkdir('uploads/restaurantAccounts/gcashQr/'.$nextIdFolderName);
        mkdir('uploads/restaurantAccounts/logo/'.$nextIdFolderName);
        mkdir('uploads/restaurantAccounts/promo/'.$nextIdFolderName);

        StoreHour::create([
            'restAcc_id' => $nextIdFolderName,
            'openingTime' => "08:00",
            'closingTime' => "22:00",
            'days' => "MO,TU,WE,TH,FR",
            'type' => "Permanent",
        ]);

        Cancellation::create([
            'restAcc_id' => $nextIdFolderName,
            'noOfCancellation' => 10,
            'noOfDays' => 30,
            'blockDays' => "5",
        ]);

        NoShow::create([
            'restAcc_id' => $nextIdFolderName,
            'noOfNoShows' => 10,
            'noOfDays' => 30,
            'blockDays' => "5",
        ]);

        Runaway::create([
            'restAcc_id' => $nextIdFolderName,
            'noOfRunaways' => 1,
            'noOfDays' => 30,
            'blockDays' => "Permanent",
        ]);


        RestaurantTaskList::create([
            'restAcc_id' => $nextIdFolderName,
            'taskCode' => "SPND",
            'taskTitle' => "Spend Amount Of Money",
            'taskDescription' => "Spend nth pesos in 1 visit only",
            'taskInput' => 4000,
        ]);
        RestaurantTaskList::create([
            'restAcc_id' => $nextIdFolderName,
            'taskCode' => "BRNG",
            'taskTitle' => "Bring Friends",
            'taskDescription' => "Bring nth friends in our store",
            'taskInput' => 5,
        ]);
        RestaurantTaskList::create([
            'restAcc_id' => $nextIdFolderName,
            'taskCode' => "ORDR",
            'taskTitle' => "Order Add On/s",
            'taskDescription' => "Order nth add on/s per visit",
            'taskInput' => 2,
        ]);
        RestaurantTaskList::create([
            'restAcc_id' => $nextIdFolderName,
            'taskCode' => "VST",
            'taskTitle' => "Visit in Store",
            'taskDescription' => "Visit in our store",
            'taskInput' => 0,
        ]);
        RestaurantTaskList::create([
            'restAcc_id' => $nextIdFolderName,
            'taskCode' => "FDBK",
            'taskTitle' => "Give Feedback",
            'taskDescription' => "Give a feedback/review per visit",
            'taskInput' => 0,
        ]);
        RestaurantTaskList::create([
            'restAcc_id' => $nextIdFolderName,
            'taskCode' => "PUGC",
            'taskTitle' => "Pay using GCash",
            'taskDescription' => "Pay using GCash",
            'taskInput' => 0,
        ]);
        RestaurantTaskList::create([
            'restAcc_id' => $nextIdFolderName,
            'taskCode' => "LUDN",
            'taskTitle' => "Eat during lunch/dinner hours",
            'taskDescription' => "Eat during lunch/dinner hours",
            'taskInput' => 0,
        ]);


        RestaurantRewardList::create([
            'restAcc_id' => $nextIdFolderName,
            'rewardCode' => "DSCN",
            'rewardTitle' => "Discount",
            'rewardDescription' => "Discount nth in a Total Bill",
            'rewardInput' => 20,
        ]);
        RestaurantRewardList::create([
            'restAcc_id' => $nextIdFolderName,
            'rewardCode' => "FRPE",
            'rewardTitle' => "Free Person",
            'rewardDescription' => "Free nth person in a group",
            'rewardInput' => 1,
        ]);
        RestaurantRewardList::create([
            'restAcc_id' => $nextIdFolderName,
            'rewardCode' => "HLF",
            'rewardTitle' => "Free Half",
            'rewardDescription' => "Half in the group will be free",
            'rewardInput' => 0,
        ]);
        RestaurantRewardList::create([
            'restAcc_id' => $nextIdFolderName,
            'rewardCode' => "ALL",
            'rewardTitle' => "All are Free",
            'rewardDescription' => "All people in the group will be free",
            'rewardInput' => 0,
        ]);

        File::copy(
            public_path('storage/uploads/restaurantApplicants/'.$applicant->id.'/'.$applicant->bir), 
            public_path('storage/uploads/restaurantAccounts/'.$nextIdFolderName.'/'.$applicant->bir));
        File::copy(
            public_path('storage/uploads/restaurantApplicants/'.$applicant->id.'/'.$applicant->dti), 
            public_path('storage/uploads/restaurantAccounts/'.$nextIdFolderName.'/'.$applicant->dti));
        File::copy(
            public_path('storage/uploads/restaurantApplicants/'.$applicant->id.'/'.$applicant->mayorsPermit), 
            public_path('storage/uploads/restaurantAccounts/'.$nextIdFolderName.'/'.$applicant->mayorsPermit));
        File::copy(
            public_path('storage/uploads/restaurantApplicants/'.$applicant->id.'/'.$applicant->staffValidId), 
            public_path('storage/uploads/restaurantAccounts/'.$nextIdFolderName.'/'.$applicant->staffValidId));
            
        $link = env('APP_URL') . '/restaurant/login';
        $linkToVerify = env('APP_URL') . '/restaurant/email-verification/'.$nextIdFolderName.'/new';
        $details = [
            'applicantName' => $applicant->fname.' '.$applicant->lname,
            'username' => $defaultUsername,
            'password' => $defaultPassword,
            'link' => $link,
            'linkToVerify' => $linkToVerify,
        ];
        Mail::to($applicant->emailAddress)->send(new RestaurantFormApprove($details));

        RestaurantApplicant::where('id', $applicant->id)->update(['status' => "Approved"]);
        $request->session()->flash('approved');
        return redirect('/admin/restaurant-applicants');
    }
    public function deleteRestaurantApplicant(Request $request, $id){
        File::deleteDirectory(public_path('storage/uploads/restaurantApplicants/'.$id));
        DB::table('restaurant_applicants')->where('id', $id)->delete();
        $request->session()->flash('deleted');
        return redirect('/admin/restaurant-applicants');
    }
    public function declineRestaurantApplicant(Request $request){
        $selectedData = array();
        if(!empty($request->checkbox)){
            foreach ($request->checkbox as $key=>$name){
                array_push($selectedData, $request->checkbox[$key]);
            }
        }

        $details = [
            'link' => env('APP_URL') . '/restaurant/register',
            'applicantName' => $request->applicantName,
            'selectedData' => $selectedData
        ];

        Mail::to($request->applicantEmail)->send(new RestaurantFormDecline($details));
        RestaurantApplicant::where('id', $request->applicantId)->update(['status' => "Declined"]);
        $request->session()->flash('declined');
        return redirect('/admin/restaurant-applicants');
    }
    public function restaurantApplicantDownloadFile($id, $filename){
        return response()->download(public_path('storage/uploads/restaurantApplicants/'.$id.'/'.$filename));
    }
    public function login(Request $request){
        $request->validate([ 
            'username' => 'required',
            'password' => 'required'
        ]);

        if($request->username == env('ADMIN_USERNAME') && $request->password == env('ADMIN_PASSWORD')){
            $request->session()->put('userType', 'admin');
            $request->session()->put('userValidation', 'S@mquiks@lwav');
            return redirect('/admin/dashboard');
        } else {
            $request->session()->flash('invalid', "Username and Password is incorrect");
            return redirect('/admin/login');
        }
    }
}
