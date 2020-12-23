<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::prefix('v1')->group(function () {
    Route::post('register', 'API\RegisterController@register');
    Route::post('login', 'API\RegisterController@login');
     
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'API\RegisterController@logout');
        Route::get('show', 'API\UserController@show');
        Route::resource('room', 'API\RoomController');
        Route::resource('booking', 'API\BookingController');
    });
});