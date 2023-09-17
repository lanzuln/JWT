<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\dashboardController;
use App\Http\Middleware\tokenVarificationMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Auth backend route
Route::controller(UserController::class)->group(function () {
    Route::post('/user_registration', 'UserRegistration');
    Route::post('/user_login', 'login');
    Route::post('/user_send_otp', 'sendOTP');
    Route::post('/user_verify_otp', 'verifyOTP');
    Route::post('/user_reset_password', 'resetPassword')->middleware([tokenVarificationMiddleware::class]);
    Route::get('/get_profile', 'getProfile')->middleware([tokenVarificationMiddleware::class]);
    Route::post('/update_profile', 'updateProfile')->middleware([tokenVarificationMiddleware::class]);


    // Auth frontend page route
    Route::get('/login', 'LoginPage')->name('login');
    Route::get('/registration', 'RegistrationPage')->name('registration');
    Route::get('/send-otp', 'SendOtpPage')->name('send-otp');
    Route::get('/verify-otp', 'VerifyOTPPage')->name('verify-otp')->middleware([tokenVarificationMiddleware::class]);
    Route::get('/reset-password', 'ResetPasswordPage')->name('reset-password')->middleware([tokenVarificationMiddleware::class]);
    Route::get('/logout', 'logout')->name('logout');
});

// Dashboard Controller
Route::controller(dashboardController::class)->group(function () {
    Route::get('/dashboard', 'dashboardPage')->name('dashboard')->middleware([tokenVarificationMiddleware::class]);
    Route::get('/profile', 'profilePage')->name('profile')->middleware([tokenVarificationMiddleware::class]);
});
