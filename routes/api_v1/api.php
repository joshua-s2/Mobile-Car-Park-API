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
    Route::patch('/', 'UserProfileController@update');
});

Route::prefix('park')->group(function () {
	Route::put('/', 'CarParkController@store');
});
