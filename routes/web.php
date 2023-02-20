<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('', 'view');
        Route::post('', 'authenticate')->middleware('throttle:3,1');
    });

    Route::prefix('forgot-password')->group(function () {
        Route::get('', [ForgotPassword::class, 'view']);
        Route::post('', [ForgotPassword::class, 'sendEmail']);
    });
});

Route::post('logout', [LogoutController::class, 'logout']);

Route::get('dashboard', [DashboardController::class, 'index']);
