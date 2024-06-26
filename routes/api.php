<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\MainFeatureController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MtnMobileMoneyController;
use App\Http\Controllers\SecondaryFeatureController;
use App\Http\Controllers\VisitController;

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

require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::resource('properties', PropertyController::class);

    Route::resource('contracts', ContractController::class);

    Route::resource('notifications', NotificationController::class);

    Route::resource('transactions', TransactionController::class);

    Route::resource('schedules', ScheduleController::class);

    Route::resource('visits', VisitController::class);

    Route::get('/get_properties_by_owner', [PropertyController::class, 'getPropertiesByOwner']);

    Route::get('/initiate-transaction/{amount}/{reason}/{type}', [MtnMobileMoneyController::class, 'initiateTransaction']);
    Route::get('/action-transaction', [TransactionController::class, 'actionTransaction'])->name('action-transaction');

    Route::get('/payment-transaction/{amount}/{reason}/{type}/{phone}', [MtnMobileMoneyController::class, 'paymentTransaction']);
    Route::get('/transfert-transaction', [TransactionController::class, 'transfertTransaction'])->name('transfert-transaction');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});

// CONFIGURATION
Route::resource('cities', CityController::class);
Route::resource('activities', ActivityController::class);
Route::resource('main_features', MainFeatureController::class);
Route::resource('secondary_feature', SecondaryFeatureController::class);


