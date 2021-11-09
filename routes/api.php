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
Route::get('/customer/rated-restaurants', [CustomerController::class, 'getListOfRatedRestaurants']);
Route::post('/customer/nearby-restaurants', [CustomerController::class, 'getListOfNearbyRestaurants']);
Route::get('/customer/promos', [CustomerController::class, 'getListOfPromos']);
Route::get('/customer/get-restaurants/about/{id}', [CustomerController::class, 'getRestaurantAboutInfo']);
Route::get('/customer/get-restaurants/rewards/{id}', [CustomerController::class, 'getRestaurantsRewardsInfo']);
Route::get('/customer/get-restaurants/reviews/{rest_id}', [CustomerController::class, 'getRestaurantsReviews']);
Route::get('/customer/get-restaurants/promo/detail/{promoId}/{restaurantId}', [CustomerController::class, 'getRestaurantsPromoDetailInfo']);
Route::get('/customer/get-restaurants/choose-order-set/{id}/{custId}', [CustomerController::class, 'getRestaurantChooseOrderSet']);
Route::get('/customer/get-restaurants/get-date-time/{rest_id}', [CustomerController::class, 'getReservationDateAndTimeForm']);
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
Route::get('/customer/get-notifications/completed/{cust_id}/{notif_id}', [CustomerController::class, 'getNotificationCompleted']);
Route::get('/customer/get-notifications/qr-validate/{cust_id}/{notif_id}', [CustomerController::class, 'getNotificationQrValidate']);
Route::get('/customer/get-notifications/qr-approved/{cust_id}/{notif_id}', [CustomerController::class, 'getNotificationQrApproved']);
Route::get('/customer/get-notifications/geofencing/{cust_id}/{notif_id}', [CustomerController::class, 'getNotificationGeofencing']);

Route::get('/customer/get-booking-history/{cust_id}', [CustomerController::class, 'getBookingHistory']);
Route::post('/customer/get-booking-history/cancelled', [CustomerController::class, 'getBookingHistoryCancelled']);
Route::post('/customer/get-booking-history/complete', [CustomerController::class, 'getBookingHistoryComplete']);


Route::get('/customer/scan-qr/{cust_id}/{request_cust_id}', [CustomerController::class, 'customerScanQr']);
Route::get('/customer/ordering/request-access/{cust_id}/{request_cust_id}', [CustomerController::class, 'orderingRequestAccess']);
Route::post('/customer/ordering/request-access/approved-declined', [CustomerController::class, 'orderingRequestAppDec']);

Route::get('/customer/ordering/check-customer-access/food-set/{cust_id}', [CustomerController::class, 'orderingCheckCustAccess']);

Route::get('/customer/ordering/food-set/{cust_id}', [CustomerController::class, 'getOrderingFoodSets']);
Route::get('/customer/ordering/food-item/{restAcc_id}/{orderSet_id}/{foodSet_id}', [CustomerController::class, 'getOrderingFoodItems']);
Route::get('/customer/ordering/orders/{cust_id}', [CustomerController::class, 'getOrderingOrders']);
Route::get('/customer/ordering/orders/submit/{cust_id}', [CustomerController::class, 'orderingSubmitOrders']);
Route::post('/customer/ordering/food-item/add', [CustomerController::class, 'orderingAddFoodItem']);
Route::post('/customer/ordering/food-item/update', [CustomerController::class, 'orderingUpdateFoodItem']);
Route::post('/customer/ordering/assistance', [CustomerController::class, 'orderingAddAssistance']);
Route::post('/customer/ordering/checkout', [CustomerController::class, 'orderingCheckout']);
Route::get('/customer/ordering/assistance/history/{cust_id}', [CustomerController::class, 'getOrderingAssistHist']);
Route::get('/customer/ordering/bill/{cust_id}', [CustomerController::class, 'getOrderingBill']);
Route::get('/customer/ordering/checkout/status/{cust_id}', [CustomerController::class, 'getOrderingCheckoutStatus']);
Route::get('/customer/ordering/gcash/status/{cust_id}', [CustomerController::class, 'getOrderingGCashStatus']);
Route::get('/customer/ordering/checkout/rating-feedback/{cust_id}', [CustomerController::class, 'getOrderingRatingFeedback']);
Route::post('/customer/ordering/checkout/rating-feedback/submit', [CustomerController::class, 'ratingFeedbackSubmit']);

Route::post('/customer/ordering/checkout/gcash-upload-image', [CustomerController::class, 'submitGcashReceipt']);


Route::get('/customer/get-stamp-cards/{cust_id}', [CustomerController::class, 'getStampCards']);
Route::get('/customer/get-stamp-cards/details/{stamp_id}', [CustomerController::class, 'getStampCardDetails']);

Route::post('/customer/geofencing/notification', [CustomerController::class, 'geofenceNotifyCustomer']);



Route::post('/customer/register-customer', [CustomerController::class, 'registerCustomer']);
Route::post('/customer/login-customer', [CustomerController::class, 'loginCustomer']);
Route::post('/customer/submit-queue-form', [CustomerController::class, 'submitQueueForm']);
Route::post('/customer/submit-reserve-form', [CustomerController::class, 'submitReserveForm']);
Route::post('/customer/forgot-password', [CustomerController::class, 'forgotPassword']);
Route::post('/customer/update-device-token', [CustomerController::class, 'updateDeviceToken']);




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
