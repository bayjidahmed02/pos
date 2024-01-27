<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;


// Authentication View Routes
Route::view('/registration', 'pages.auth.registration-page')->name('registration');
Route::view('/login', 'pages.auth.login-page')->name('login');
Route::view('/send-otp', 'pages.auth.send-otp-page')->name('sendOTP');
Route::view('/verify-otp', 'pages.auth.verify-otp-page');
Route::view('/profile', 'pages.dashboard.profile-page')->name('profile');


// Authentication API Routes
Route::post('/registration', [UserController::class, 'registration']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/send-otp', [UserController::class, 'sendOTP']);
Route::post('/verify-otp', [UserController::class, 'verifyOTP']);

// Authentication Middleware Routes
Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::view('/dashboard', 'pages.dashboard.dashboard-page');
    Route::view('/reset-password', 'pages.auth.reset-pass-page');
    Route::get('/profile-details', [UserController::class, 'profileDetails']);
    Route::post('/update', [UserController::class, 'updateProfile']);
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
});
