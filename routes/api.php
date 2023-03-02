<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('/', function () {
        return 'Welcome to Tribog API';
    });

    // Route::post('/dev-send-otp', [DevSendOtpsController::class, 'DevSendOTP']);
    // Route::post('/dev-validate-otp', [DevValidateOtpsController::class, 'DevValidateOTP']);
    // Route::post('/register', [RegistersController::class, 'Register']);

    // Route::post('/test', [TestController::class, 'Show']);
});
