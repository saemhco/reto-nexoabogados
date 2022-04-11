<?php

use App\Http\Controllers\v1\Auth\RolController;
use App\Http\Controllers\v1\Subscription\PlanController;
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

Route::apiResource('roles', RolController::class)->except('destroy')->names('roles');
Route::apiResource('plan', PlanController::class)->except('destroy')->names('plan');
