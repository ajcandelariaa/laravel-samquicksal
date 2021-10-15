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
Route::get('/customer/get-restaurants/about/{id}', [CustomerController::class, 'getRestaurantAboutInfo']);
Route::get('/customer/get-restaurants/rewards/{id}', [CustomerController::class, 'getRestaurantsRewardsInfo']);
Route::get('/customer/get-homepage-info/{id}', [CustomerController::class, 'getCustomerHomepageInfo']);
Route::get('/customer/get-account-info/{id}', [CustomerController::class, 'getCustomerAccountInfo']);



Route::post('/customer/register-customer', [CustomerController::class, 'registerCustomer']);
Route::post('/customer/login-customer', [CustomerController::class, 'loginCustomer']);




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
