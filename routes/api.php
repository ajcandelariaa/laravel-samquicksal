<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/customer/test-customers', [CustomerController::class, 'customers']);
Route::get('/customer/test-restaurants', [CustomerController::class, 'restaurants']);


Route::get('/customer/restaurants', [CustomerController::class, 'getListOfRestaurants']);
Route::get('/customer/promos', [CustomerController::class, 'getListOfPromos']);
Route::get('/customer/get-restaurants/about/{id}', [CustomerController::class, 'getRestaurantAboutInfo']);
Route::get('/customer/get-restaurants/rewards/{id}', [CustomerController::class, 'getRestaurantsRewardsInfo']);
Route::get('/customer/get-restaurants/promo/detail/{promoId}/{restaurantId}', [CustomerController::class, 'getRestaurantsPromoDetailInfo']);
Route::get('/customer/get-restaurants/choose-order-set/{id}/{custId}', [CustomerController::class, 'getRestaurantChooseOrderSet']);
Route::get('/customer/get-restaurants/get-date-time/{id}', [CustomerController::class, 'getReservationDateAndTimeForm']);
Route::get('/customer/get-restaurants/menu/{id}', [CustomerController::class, 'getRestaurantsMenuInfo']);
Route::get('/customer/get-homepage-info/{id}', [CustomerController::class, 'getCustomerHomepageInfo']);
Route::get('/customer/get-account-info/{id}', [CustomerController::class, 'getCustomerAccountInfo']);
Route::get('/customer/get-notifications/{id}', [CustomerController::class, 'getCustomerNotification']);
Route::get('/customer/get-live-status/{id}', [CustomerController::class, 'getCustomerLiveStatus']);
Route::get('/customer/cancel-booking/{id}', [CustomerController::class, 'cancelCustomerBooking']);

Route::get('/customer/get-notifications/pending/{cust_id}/{notif_id}', [CustomerController::class, 'getNotificationPending']);
Route::get('/customer/get-notifications/cancelled/{cust_id}/{notif_id}', [CustomerController::class, 'getNotificationCancelled']);
Route::get('/customer/get-notifications/declined/{cust_id}/{notif_id}', [CustomerController::class, 'getNotificationDeclined']);
Route::get('/customer/get-notifications/approved/{cust_id}/{notif_id}', [CustomerController::class, 'getNotificationApproved']);
Route::get('/customer/get-notifications/no-show/{cust_id}/{notif_id}', [CustomerController::class, 'getNotificationNoShow']);
Route::get('/customer/get-notifications/runaway/{cust_id}/{notif_id}', [CustomerController::class, 'getNotificationRunaway']);


Route::post('/customer/register-customer', [CustomerController::class, 'registerCustomer']);
Route::post('/customer/login-customer', [CustomerController::class, 'loginCustomer']);
Route::post('/customer/submit-queue-form', [CustomerController::class, 'submitQueueForm']);
Route::post('/customer/forgot-password', [CustomerController::class, 'forgotPassword']);




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
