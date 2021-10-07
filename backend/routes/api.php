<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// controllers
use App\Http\Controllers\LoginController;

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

Route::post('sign-up', [LoginController::class, 'userSignUp']);
Route::post('login', [LoginController::class, 'userLogin']);
Route::post('logout', [LoginController::class, 'userLogout']);
// Route::post('forgot-password', [PasswordController::class, 'forgotPassoword']);
// Route::post('reset-password', [PasswordController::class, 'resetPassword']);
