<?php

use App\Http\Controllers\v1\Subscription\SubscriptionController;
use App\Http\Controllers\v1\Subscription\SubscriptionPanelController;
use Illuminate\Support\Facades\Route;

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

//Abogado
Route::post('subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
Route::get('current-subscription', [SubscriptionController::class, 'current_subscription'])->name('subscription.current_subscription');
Route::post('update-subscription', [SubscriptionController::class, 'update'])->name('subscription.update');
Route::post('renewal-cancel', [SubscriptionController::class, 'renewal_cancel'])->name('subscription.renewal_cancel');

//Panel
Route::get('subscription', [SubscriptionPanelController::class, 'index'])->name('subscription-panel.index');
Route::get('subscription/{subscription}', [SubscriptionPanelController::class, 'show'])->name('subscription-panel.show');
Route::post('renewal-cancel/{subscription}', [SubscriptionPanelController::class, 'renewal_cancel'])->name('subscription-panel.renewal_cancel');
Route::post('cancel/{subscription}', [SubscriptionPanelController::class, 'cancel'])->name('subscription-panel.cancel');
Route::get('processing-payment/{subscription}', [SubscriptionPanelController::class, 'processing_payment'])->name('subscription.processing_payment');
