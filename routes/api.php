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


Route::get('/customer/restaurants', [CustomerController::class, 'getListOfRestaurants']);
Route::get('/customer/get-restaurants/{id}', [CustomerController::class, 'getRestaurantById']);

Route::post('/customer/register-customer', [CustomerController::class, 'registerCustomer']);




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
