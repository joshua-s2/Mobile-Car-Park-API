<?php

use Illuminate\Support\Facades\Route;

/*
 * API V1 Routes
 */

Route::prefix('auth')->namespace('Auth')->group(function () {
    Route::post('send-otp', 'OTPController@getOTP');
    Route::post('verify-otp', 'OTPController@verifyOTP');
    Route::post('register', 'RegisterController');


    Route::post('login', 'LoginController');

    Route::get('user', function () {
        return auth()->user();
    })->middleware('auth');
});
