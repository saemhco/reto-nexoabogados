<?php

use App\Http\Controllers\v1\Auth\LoginController;
use App\Http\Controllers\v1\Auth\UserController;
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

Route::get('login', [LoginController::class, 'index'])->name('login.index');
Route::post('login', [LoginController::class, 'login'])->name('login.store');
Route::post('logout', [LoginController::class, 'logout'])->name('user.logout');

Route::apiResource('users', UserController::class)->except('destroy')->names('user');
Route::get('user/data', [UserController::class, 'user_data'])->name('user.data');
Route::post('user/password-update', [UserController::class, 'password_update'])->name('user.password-update');
Route::post('user/update-only-email', [UserController::class, 'update_only_email'])->name('update-only-email');
