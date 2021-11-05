<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// HOMEPAGE ROUTES
Route::get('/', function () {
    return view('homepage.samquicksal-home');
});
Route::get('/restaurant', function () {
    return view('homepage.restaurant-home');
});

// RESTAURANT ROUTES
Route::get('/restaurant/register', [RestaurantController::class, 'registerView']);
Route::post('/restaurant/register', [RestaurantController::class, 'addRestaurant']);

Route::get('/restaurant/register2', [RestaurantController::class, 'registerView2']);
Route::post('/restaurant/register2', [RestaurantController::class, 'register2']);
Route::get('/restaurant/email-verification/{id}/{status}', [RestaurantController::class, 'verifyEmail']);

Route::middleware(['restaurantLoggedIn'])->group(function(){
    Route::get('/restaurant/logout', [RestaurantController::class, 'logout']);
    Route::get('/restaurant/dashboard', [RestaurantController::class, 'dashboardView']);
    // -------FOOD ITEM------------ //
    Route::get('/restaurant/manage-restaurant/food-menu/food-item', [RestaurantController::class, 'manageRestaurantFoodItemView']);
    Route::get('/restaurant/manage-restaurant/food-menu/food-item/edit/{id}', [RestaurantController::class, 'editFoodItemView']);
    Route::get('/restaurant/manage-restaurant/food-menu/food-item/delete/{id}', [RestaurantController::class, 'deleteFoodItem']);
    Route::post('/restaurant/manage-restaurant/food-menu/food-item/add', [RestaurantController::class, 'addFoodItem']);
    Route::post('/restaurant/manage-restaurant/food-menu/food-item/edit/{id}', [RestaurantController::class, 'editFoodItem']);
    // -------FOOD SET------------ //
    Route::get('/restaurant/manage-restaurant/food-menu/food-set', [RestaurantController::class, 'manageRestaurantFoodSetView']);
    Route::get('/restaurant/manage-restaurant/food-menu/food-set/detail/{id}', [RestaurantController::class, 'foodSetDetailView']);
    Route::get('/restaurant/manage-restaurant/food-menu/food-set/detail/edit/{id}', [RestaurantController::class, 'foodSetEditView']);
    Route::get('/restaurant/manage-restaurant/food-menu/food-set/delete/{id}', [RestaurantController::class, 'deleteFoodSet']);
    Route::get('/restaurant/manage-restaurant/food-menu/food-set/item/delete/{foodSetid}/{foodSetItemId}', [RestaurantController::class, 'foodSetItemDelete']);
    Route::post('/restaurant/manage-restaurant/food-menu/food-set/add', [RestaurantController::class, 'addFoodSet']);
    Route::post('/restaurant/manage-restaurant/food-menu/food-set/detail', [RestaurantController::class, 'addItemToFoodSet']);
    Route::post('/restaurant/manage-restaurant/food-menu/food-set/edit/{id}', [RestaurantController::class, 'editFoodSet']);
    // -------ORDER SET------------ //
    Route::get('/restaurant/manage-restaurant/food-menu/order-set', [RestaurantController::class, 'manageRestaurantOrderSetView']);
    Route::get('/restaurant/manage-restaurant/food-menu/order-set/detail/{id}', [RestaurantController::class, 'orderSetDetailView']);
    Route::get('/restaurant/manage-restaurant/food-menu/order-set/delete/{id}', [RestaurantController::class, 'deleteOrderSet']);
    Route::get('/restaurant/manage-restaurant/food-menu/order-set/detail/edit/{id}', [RestaurantController::class, 'orderSetEditView']);
    Route::get('/restaurant/manage-restaurant/food-menu/order-set/food-set/delete/{orderSetid}/{orderSetFoodSetId}', [RestaurantController::class, 'orderSetFoodSetDelete']);
    Route::get('/restaurant/manage-restaurant/food-menu/order-set/food-item/delete/{orderSetid}/{orderSetFoodItemId}', [RestaurantController::class, 'orderSetFoodItemDelete']);
    Route::post('/restaurant/manage-restaurant/food-menu/order-set/add', [RestaurantController::class, 'addOrderSet']);
    Route::post('/restaurant/manage-restaurant/food-menu/order-set/edit/{id}', [RestaurantController::class, 'editOrderSet']);
    Route::post('/restaurant/manage-restaurant/food-menu/order-set/detail/add-set/{id}', [RestaurantController::class, 'orderSetAddFoodSet']);
    Route::post('/restaurant/manage-restaurant/food-menu/order-set/detail/add-item/{id}', [RestaurantController::class, 'orderSetAddFoodItem']);
    // -------RESTAURANT INFORMATION------------ //
    Route::get('/restaurant/manage-restaurant/about/restaurant-information/resend-email-verification/{emailAddress}/{fname}/{lname}', [RestaurantController::class, 'resendEmailVerification']);
    Route::get('/restaurant/manage-restaurant/about/restaurant-information', [RestaurantController::class, 'manageRestaurantInformationView']);
    Route::post('/restaurant/manage-restaurant/about/restaurant-information/updateContact', [RestaurantController::class, 'updateRestaurantContact']);
    Route::post('/restaurant/manage-restaurant/about/restaurant-information/updateEmailAddress', [RestaurantController::class, 'updateEmailAddress']);
    Route::post('/restaurant/manage-restaurant/about/restaurant-information/updateUsername', [RestaurantController::class, 'updateRestaurantUsername']);
    Route::post('/restaurant/manage-restaurant/about/restaurant-information/updateTables', [RestaurantController::class, 'updateRestaurantTables']);
    Route::post('/restaurant/manage-restaurant/about/restaurant-information/updatePassword', [RestaurantController::class, 'updateRestaurantPassword']);
    Route::post('/restaurant/manage-restaurant/about/restaurant-information/updateLogo', [RestaurantController::class, 'updateRestaurantLogo']);
    Route::post('/restaurant/manage-restaurant/about/restaurant-information/updateGcashQr', [RestaurantController::class, 'updateRestaurantGcashQr']);
    Route::get('/restaurant/manage-restaurant/about/restaurant-post', [RestaurantController::class, 'manageRestaurantPostView']);
    Route::post('/restaurant/manage-restaurant/about/restaurant-post/add', [RestaurantController::class, 'addPost']);
    Route::get('/restaurant/manage-restaurant/about/restaurant-post/delete/{id}', [RestaurantController::class, 'deletePost']);
    Route::get('/restaurant/manage-restaurant/about/restaurant-post/edit/{id}', [RestaurantController::class, 'editPostView']);
    Route::post('/restaurant/manage-restaurant/about/restaurant-post/edit/{id}', [RestaurantController::class, 'editPost']);
    // -------PROMOS------------ //
    Route::get('/restaurant/manage-restaurant/promo', [RestaurantController::class, 'manageRestaurantPromoView']);
    Route::get('/restaurant/manage-restaurant/promo/edit/{id}', [RestaurantController::class, 'editPromoView']);
    Route::get('/restaurant/manage-restaurant/promo/delete/{id}', [RestaurantController::class, 'deletePromo']);
    Route::post('/restaurant/manage-restaurant/promo/edit/{id}', [RestaurantController::class, 'editPromo']);
    Route::get('/restaurant/manage-restaurant/promo/add', [RestaurantController::class, 'addPromoView']);
    Route::post('/restaurant/manage-restaurant/promo/add', [RestaurantController::class, 'addPromo']);
    Route::get('/restaurant/manage-restaurant/promo/mechanics/delete/{id}/{currentPromoId}', [RestaurantController::class, 'deletePromoMechanic']);
    Route::get('/restaurant/manage-restaurant/promo/promoDraft/{id}', [RestaurantController::class, 'promoDraft']);
    Route::get('/restaurant/manage-restaurant/promo/promoPost/{id}', [RestaurantController::class, 'promoPost']);
    // -------STORE HOURS------------ //
    Route::get('/restaurant/manage-restaurant/time/store-hours', [RestaurantController::class, 'storeHoursView']);
    Route::post('/restaurant/manage-restaurant/time/store-hours/add', [RestaurantController::class, 'addStoreHours']);
    Route::get('/restaurant/manage-restaurant/time/store-hours/delete/{id}', [RestaurantController::class, 'deleteStoreHours']);
    Route::post('/restaurant/manage-restaurant/time/store-hours/edit', [RestaurantController::class, 'editStoreHours']);
    // -------UNAVAILABLE DATES------------ //
    Route::get('/restaurant/manage-restaurant/time/unavailable-dates', [RestaurantController::class, 'unavailableDatesView']);
    Route::post('/restaurant/manage-restaurant/time/unavailable-dates/add', [RestaurantController::class, 'addUnavailableDates']);
    Route::get('/restaurant/manage-restaurant/time/unavailable-dates/delete/{id}', [RestaurantController::class, 'deleteUnavailableDates']);
    Route::post('/restaurant/manage-restaurant/time/unavailable-dates/edit', [RestaurantController::class, 'editUnavailableDates']);
    // -------TIME LIMIT------------ //
    Route::get('/restaurant/manage-restaurant/time/time-limit', [RestaurantController::class, 'timeLimitView']);
    Route::get('/restaurant/manage-restaurant/time/time-limit/edit', [RestaurantController::class, 'editTimeLimitView']);
    Route::post('/restaurant/manage-restaurant/time/time-limit/edit', [RestaurantController::class, 'editTimeLimit']);
    // -------STAMP CARD------------ //
    Route::get('/restaurant/manage-restaurant/task-rewards/stamp-card', [RestaurantController::class, 'stampCardView']);
    Route::post('/restaurant/manage-restaurant/task-rewards/stamp-card/add', [RestaurantController::class, 'addStampCard']);
    // -------REWARDS------------ //
    Route::get('/restaurant/manage-restaurant/task-rewards/rewards', [RestaurantController::class, 'rewardsView']);
    Route::post('/restaurant/manage-restaurant/task-rewards/rewards/edit/reward1', [RestaurantController::class, 'editReward1']);
    Route::post('/restaurant/manage-restaurant/task-rewards/rewards/edit/reward2', [RestaurantController::class, 'editReward2']);
    // -------TASKS------------ //
    Route::get('/restaurant/manage-restaurant/task-rewards/tasks', [RestaurantController::class, 'tasksView']);
    Route::post('/restaurant/manage-restaurant/task-rewards/tasks/edit/task1', [RestaurantController::class, 'editTask1']);
    Route::post('/restaurant/manage-restaurant/task-rewards/tasks/edit/task2', [RestaurantController::class, 'editTask2']);
    Route::post('/restaurant/manage-restaurant/task-rewards/tasks/edit/task3', [RestaurantController::class, 'editTask3']);
    // -------CANCEL RESERVATION------------ //
    Route::get('/restaurant/manage-restaurant/offense/cancel-reservation', [RestaurantController::class, 'cancelReservationView']);
    Route::post('/restaurant/manage-restaurant/offense/cancel-reservation/edit/{id}', [RestaurantController::class, 'editCancelReservation']);
    // -------NO SHOW------------ //
    Route::get('/restaurant/manage-restaurant/offense/no-show', [RestaurantController::class, 'noShowView']);
    Route::post('/restaurant/manage-restaurant/offense/no-show/edit/{id}', [RestaurantController::class, 'editNoShow']);
    // -------RUNAWAY------------ //
    Route::get('/restaurant/manage-restaurant/offense/runaway', [RestaurantController::class, 'runawayView']);
    Route::post('/restaurant/manage-restaurant/offense/runaway/edit/{id}', [RestaurantController::class, 'editRunaway']);
    // -------POLICY------------ //
    Route::get('/restaurant/manage-restaurant/offense/policy', [RestaurantController::class, 'policyView']);
    Route::post('/restaurant/manage-restaurant/offense/policy/add', [RestaurantController::class, 'addPolicy']);
    Route::post('/restaurant/manage-restaurant/offense/policy/edit', [RestaurantController::class, 'editPolicy']);
    Route::get('/restaurant/manage-restaurant/offense/policy/delete/{id}', [RestaurantController::class, 'deletePolicy']);
    // -------CHECKLIST------------ //
    Route::get('/restaurant/manage-restaurant/checklist', [RestaurantController::class, 'manageRestaurantChecklistView']);
    // -------CUSTOMER BOOKING------------ //
    Route::get('/restaurant/live-transaction/customer-booking/queue', [RestaurantController::class, 'ltCustBookQListView']);
    Route::get('/restaurant/live-transaction/customer-booking/queue/{id}', [RestaurantController::class, 'ltCustBookQParticularView']);
    Route::get('/restaurant/live-transaction/customer-booking/queue/approve/{id}', [RestaurantController::class, 'ltCustBookQApprove']);
    Route::post('/restaurant/live-transaction/customer-booking/queue/decline/{id}', [RestaurantController::class, 'ltCustBookQDecline']);
    Route::get('/restaurant/live-transaction/customer-booking/reserve', [RestaurantController::class, 'ltCustBookRListView']);
    Route::get('/restaurant/live-transaction/customer-booking/reserve/{id}', [RestaurantController::class, 'ltCustBookRParticularView']);
    Route::get('/restaurant/live-transaction/customer-booking/reserve/approve/{id}', [RestaurantController::class, 'ltCustBookRApprove']);
    Route::post('/restaurant/live-transaction/customer-booking/reserve/decline/{id}', [RestaurantController::class, 'ltCustBookRDecline']);
    // -------APPROVED CUSTOMER------------ //
    Route::get('/restaurant/live-transaction/approved-customer/queue/add-walk-in', [RestaurantController::class, 'ltAppCustQAddWalkInView']);
    Route::post('/restaurant/live-transaction/approved-customer/queue/add-walk-in', [RestaurantController::class, 'ltAppCustQAddWalkIn']);
    Route::get('/restaurant/live-transaction/approved-customer/queue', [RestaurantController::class, 'ltAppCustQListView']);
    Route::get('/restaurant/live-transaction/approved-customer/queue/{id}', [RestaurantController::class, 'ltAppCustQParticularView']);
    Route::get('/restaurant/live-transaction/approved-customer/queue/no-show/{id}', [RestaurantController::class, 'ltAppCustQNoShow']);
    Route::post('/restaurant/live-transaction/approved-customer/queue/admit/{id}', [RestaurantController::class, 'ltAppCustQAdmit']);
    Route::get('/restaurant/live-transaction/approved-customer/queue/validate/{id}', [RestaurantController::class, 'ltAppCustQValidate']);
    Route::get('/restaurant/live-transaction/approved-customer/reserve', [RestaurantController::class, 'ltAppCustRListView']);
    Route::get('/restaurant/live-transaction/approved-customer/reserve/{id}', [RestaurantController::class, 'ltAppCustRParticularView']);
    Route::get('/restaurant/live-transaction/approved-customer/reserve/no-show/{id}', [RestaurantController::class, 'ltAppCustRNoShow']);
    Route::post('/restaurant/live-transaction/approved-customer/reserve/admit/{id}', [RestaurantController::class, 'ltAppCustRAdmit']);
    Route::get('/restaurant/live-transaction/approved-customer/reserve/validate/{id}', [RestaurantController::class, 'ltAppCustRValidate']);
    // -------CUSTOMER ORDERING------------ //
    Route::get('/restaurant/live-transaction/customer-ordering/list', [RestaurantController::class, 'ltCustOrderListView']);
    Route::get('/restaurant/live-transaction/customer-ordering/list/{id}/order-request', [RestaurantController::class, 'ltCustOrderORPartView']);
    Route::get('/restaurant/live-transaction/customer-ordering/list/{id}/order-request/request-done/{request_id}', [RestaurantController::class, 'ltCustOrderORRequestDone']);
    Route::post('/restaurant/live-transaction/customer-ordering/list/{id}/order-request', [RestaurantController::class, 'ltCustOrderORServe']);
    Route::get('/restaurant/live-transaction/customer-ordering/list/{id}/order-summary', [RestaurantController::class, 'ltCustOrderOSPartView']);
    Route::get('/restaurant/live-transaction/customer-ordering/list/{id}/order-summary/complete', [RestaurantController::class, 'ltCustOrderOSComplete']);
    Route::get('/restaurant/live-transaction/customer-ordering/list/{id}/order-summary/runaway', [RestaurantController::class, 'ltCustOrderOSRunaway']);
    Route::post('/restaurant/live-transaction/customer-ordering/list/{id}/order-summary', [RestaurantController::class, 'ltCustOrderApplyDiscounts']);
    Route::get('/restaurant/live-transaction/customer-ordering/list/{id}/order-request/grant-access', [RestaurantController::class, 'ltCustOrderGrantAccess']);
    

});
Route::middleware(['restaurantLoggedOut'])->group(function(){
    Route::get('/restaurant/login', [RestaurantController::class, 'loginView']);
    Route::post('/restaurant/login', [RestaurantController::class, 'login']);
    Route::get('/restaurant/login/forgot-password', [RestaurantController::class, 'forgotPasswordView']);
    Route::post('/restaurant/login/forgot-password', [RestaurantController::class, 'forgotPassword']);
    Route::get('/restaurant/login/reset-password/{token}/{emailAddress}', [RestaurantController::class, 'resetPasswordView']);
    Route::post('/restaurant/login/reset-password/{token}/{emailAddress}', [RestaurantController::class, 'resetPassword']);
});


// ADMIN ROUTES
Route::middleware(['adminLoggedIn'])->group(function(){
    Route::get('/admin/logout', [AdminController::class, 'logout']);
    Route::get('/admin/dashboard', [AdminController::class, 'dashboardView']);
    Route::get('/admin/restaurant-applicants', [AdminController::class, 'restaurantApplicantsView']);
    Route::get('/admin/restaurant-applicant/downloadFile/{id}/{filename}', [AdminController::class, 'restaurantApplicantDownloadFile']);
    Route::get('/admin/restaurant-applicant/viewFile/{id}/{filename}', [AdminController::class, 'restaurantApplicantViewFile']);
    Route::get('/admin/restaurant-account/viewFile/{id}/{filename}', [AdminController::class, 'restaurantAccountViewFile']);
    Route::get('/admin/restaurant-applicants/{id}', [AdminController::class, 'restaurantApplicantView']);
    Route::get('/admin/restaurant-applicants/delete/{id}', [AdminController::class, 'deleteRestaurantApplicant']);
    Route::get('/admin/restaurant-accounts', [AdminController::class, 'restaurantAccountsView']);
    Route::get('/admin/restaurant-accounts/{id}', [AdminController::class, 'restaurantAccountView']);
    Route::get('/admin/customer-accounts', [AdminController::class, 'customerAccountsView']);
    Route::get('/admin/customer-accounts/{id}', [AdminController::class, 'customerAccountView']);
    
    Route::post('/admin/restaurant-applicants/decline', [AdminController::class, 'declineRestaurantApplicant']);
    Route::post('/admin/restaurant-applicants/approve', [AdminController::class, 'approveRestaurantApplicant']);
});
Route::middleware(['adminLoggedOut'])->group(function(){
    Route::get('/admin/login', [AdminController::class, 'loginView']);
    Route::post('/admin/login', [AdminController::class, 'login']);
});



// CUSTOMER MOBILE APP ROUTES
Route::get('/customer/forgot-password/{token}/{emailAddress}', [CustomerController::class, 'resetPasswordView']);
Route::post('/customer/forgot-password/{token}/{emailAddress}', [CustomerController::class, 'resetPassword']);

