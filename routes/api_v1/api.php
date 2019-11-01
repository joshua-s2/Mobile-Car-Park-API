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

Route::prefix('park')->group(function () {
	Route::post('/', 'CarParkController@store');
});
