<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\UnitOfMeasurementController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\IncomeTransactionController;
use App\Http\Controllers\IncomeTransactionItemController;
use App\Http\Controllers\ExpenditureTransactionController;
use App\Http\Controllers\ExpenditureTransactionItemController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;

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
        Route::get('', 'view')->name('login');
        Route::post('', 'authenticate')->middleware('throttle:5,1')
                                        ->name('authenticate');
    });

    Route::prefix('forgot-password')->group(function () {
        Route::controller(ForgotPasswordController::class)->group(function () {
            Route::get('', 'view')->name('forgot-password.index');
            Route::post('', 'sendEmail')->middleware('throttle:5,1')
                                        ->name('forgot-password.send');
        });
    });

    Route::prefix('reset-password')->group(function () {
        Route::controller(ResetPasswordController::class)->group(function () {
            Route::get('{token}', 'view')->name('reset-password.index');
            Route::post('', 'reset')->middleware('throttle:5,1')
                                    ->name('reset-password.reset');
        });
    });
});

Route::middleware(['auth'])->group(function () {
    Route::middleware(['can:isAdmin'])->group(function () {
        Route::resource('categories', CategoryController::class)->except([
            'show'
        ]);
    
        Route::resource('brands', BrandController::class)->except([
            'show'
        ]);
    
        Route::resource('unit-of-measurements', UnitOfMeasurementController::class)->except([
            'show'
        ]);
    
        Route::get('items/{id}/image', [ItemController::class, 'openImage']);
    
        Route::resource('items', ItemController::class);

        Route::resource('users', UserController::class);
    });

    Route::post('logout', [LogoutController::class, 'logout'])
            ->name('logout');

    Route::get('dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile', 'edit')->name('profile.edit');
        Route::put('profile', 'update')->name('profile.update');
    });

    Route::controller(ChangePasswordController::class)->group(function () {
        Route::get('change-password', 'edit')->name('change-password.edit');
        Route::put('change-password', 'update')->name('change-password.update');
    });

    Route::delete('income-transaction-items/{item_id}/create', [
        IncomeTransactionItemController::class, 'deleteCreateSession'
    ])->name('income-transaction-items.delete-create-session');

    Route::delete('income-transaction-items/{income_transaction_id}/{item_id}', [
        IncomeTransactionItemController::class, 'deleteEditSession'
    ])->name('income-transaction-items.delete-edit-session');

    Route::resource('income-transaction-items', IncomeTransactionItemController::class)->except([
        'show', 'index', 'delete'
    ]);

    Route::resource('income-transactions', IncomeTransactionController::class);

    Route::delete('expenditure-transaction-items/{item_id}/create', [
        ExpenditureTransactionItemController::class, 'deleteCreateSession'
    ])->name('expenditure-transaction-items.delete-create-session');

    Route::delete('expenditure-transaction-items/{expenditure_transaction_id}/{item_id}', [
        ExpenditureTransactionItemController::class, 'deleteEditSession'
    ])->name('expenditure-transaction-items.delete-edit-session');

    Route::resource('expenditure-transaction-items', ExpenditureTransactionItemController::class)->except([
        'show', 'index', 'delete'
    ]);

    Route::resource('expenditure-transactions', ExpenditureTransactionController::class);

    Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
});

