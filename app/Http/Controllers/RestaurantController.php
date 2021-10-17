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
use App\Models\StampCardTasks;
use App\Models\OrderSetFoodSet;
use App\Models\UnavailableDate;
use App\Mail\RestaurantVerified;
use App\Models\OrderSetFoodItem;
use App\Models\RestaurantAccount;
use App\Models\RestaurantTaskList;
use Illuminate\Support\Facades\DB;
use App\Models\RestaurantApplicant;
use App\Models\RestaurantRewardList;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\RestaurantForgotPassword;
use App\Models\RestaurantResetPassword;
use Illuminate\Support\Facades\Session;
use App\Mail\RestaurantFormAppreciation;
use App\Mail\RestaurantPasswordChanged;
use App\Mail\RestaurantUpdateEmail;
use Illuminate\Auth\Notifications\ResetPassword;

class RestaurantController extends Controller
{
    // FOR CHECKING THE EXPIRATION DATES OF STAMPS
    public function __construct(){}





    // RENDER VIEWS
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
        $promo = Promo::where('restAcc_id', '=', $resAccid)
                            ->where('id', '=', $id)
                            ->first();
        return view('restaurant.manageRestaurant.promo.promoEdit',[
            'promo' => $promo,
            'title' => 'Manage Promo',
            'resAccid' => $resAccid
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
    public function registerView(){
        return view('restaurant.register');
    }
    public function loginView(){
        return view('restaurant.login');
    }







    

    

    // RENDER LOGICS
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
        $promo = Promo::where('id', $id)
                                ->where('restAcc_id', $restAccId)
                                ->first();
        $request->validate([
            'promoTitle' => 'required',
            'promoDescription' => 'required',
            'promoMechanics' => 'required',
        ]);
        if($request->promoImage == null){
            Promo::where('id', $id)
            ->where('restAcc_id', $restAccId)
            ->update([
                'promoTitle' => $request->promoTitle,
                'promoDescription' => $request->promoDescription,
                'promoMechanics' => $request->promoMechanics,
            ]);
        } else {
            File::delete(public_path('uploads/restaurantAccounts/promo/'.$restAccId.'/'.$promo->promoImage));
            $promoImageName = time().'.'.$request->promoImage->extension();
            $request->promoImage->move(public_path('uploads/restaurantAccounts/promo/'.$restAccId), $promoImageName);
            Promo::where('id', $id)
            ->where('restAcc_id', $restAccId)
            ->update([
                'promoTitle' => $request->promoTitle,
                'promoDescription' => $request->promoDescription,
                'promoMechanics' => $request->promoMechanics,
                'promoImage' => $promoImageName,
            ]);
        }
        $request->session()->flash('edited');
        return redirect('/restaurant/manage-restaurant/promo/edit/'.$id);
    }
    public function addPromo(Request $request){
        $id = Session::get('loginId');
        $request->validate([
            'promoTitle' => 'required',
            'promoDescription' => 'required',
            'promoMechanics' => 'required',
            'promoImage' => 'required',
        ]);

        $promoImageName = time().'.'.$request->promoImage->extension();
        $request->promoImage->move(public_path('uploads/restaurantAccounts/promo/'.$id), $promoImageName);

        Promo::create([
            'restAcc_id' => $id,
            'promoPosted' => "Draft",
            'promoTitle' => $request->promoTitle,
            'promoDescription' => $request->promoDescription,
            'promoMechanics' => $request->promoMechanics,
            'promoImage' => $promoImageName,
        ]);
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
        $foodItemData = FoodItem::where('id', $id)
                                ->where('restAcc_id', $restAccId)
                                ->first();
        $request->validate([
            'foodName' => 'required',
            'foodDesc' => 'required',
            'foodPrice' => 'required',
        ]);
        if($request->foodImage == null){
            FoodItem::where('id', $id)
            ->where('restAcc_id', $restAccId)
            ->update([
                'foodItemName' => $request->foodName,
                'foodItemDescription' => $request->foodDesc,
                'foodItemPrice' => $request->foodPrice,
            ]);
        } else {
            File::delete(public_path('uploads/restaurantAccounts/foodItem/'.$restAccId.'/'.$foodItemData->foodItemImage));
            $foodImageName = time().'.'.$request->foodImage->extension();
            $request->foodImage->move(public_path('uploads/restaurantAccounts/foodItem/'.$restAccId), $foodImageName);
            FoodItem::where('id', $id)
            ->where('restAcc_id', $restAccId)
            ->update([
                'foodItemName' => $request->foodName,
                'foodItemDescription' => $request->foodDesc,
                'foodItemPrice' => $request->foodPrice,
                'foodItemImage' => $foodImageName,
            ]);
        }
        $request->session()->flash('edited');
        return redirect('/restaurant/manage-restaurant/food-menu/food-item/edit/'.$id);
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
        $checkIfExisting = 0;
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
