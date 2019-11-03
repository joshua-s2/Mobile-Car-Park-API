<?php

use Illuminate\Support\Facades\Route;

/*
 * API V1 Routes
 */

Route::prefix('auth')->namespace('Auth')->group(function () {
    Route::post('send-otp', 'OTPController@sendOTP');
    Route::post('verify-otp', 'OTPController@verifyOTP');
    Route::post('register', 'RegisterController');

    Route::post('login', 'LoginController');
});

Route::prefix('user')->middleware('auth')->group( function () {
    Route::get('/', 'UserProfileController@show');
    Route::put('/', 'UserProfileController@update');
    Route::patch('/settings', 'UserProfileController@manageProfile');
});

Route::prefix('vehicles')->middleware('auth')->group( function () {
    Route::get('/', 'VehiclesController@index');
    Route::post('/', 'VehiclesController@store');
    Route::put('{id}', 'VehiclesController@update');
    Route::delete('{id}', 'VehiclesController@delete');
});

Route::group(['prefix' => 'park', 'middleware' => 'auth'], function () {
	Route::get('/', 'CarParkController@apiIndex');
    Route::get('all', 'CarParkController@index');
    Route::get('{id}', 'CarParkController@show');

    Route::group(['middleware' => 'admin'], function () {
        Route::get('active', 'CarParkController@showActive');
        Route::get('inactive', 'CarParkController@showInActive');
    	Route::post('/', 'CarParkController@store');
    	Route::put('{id}', 'CarParkController@update');
    });

    Route::get('/history/{id?}', 'CarParkHistoryController');
    Route::post('/book', 'CarParkBookingController@store');
});
