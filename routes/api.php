<?php

use App\Http\Controllers\V1\Authentication\PasswordController;
use App\Http\Controllers\V1\Authentication\PasswordResetController;
use App\Http\Controllers\V1\Authentication\PasswordResetLinkController;
use App\Http\Controllers\V1\Authentication\ProfileController;
use App\Http\Controllers\V1\Authentication\TokenController;
use App\Http\Controllers\V1\Authentication\UserActivationController;
use App\Http\Controllers\V1\Authentication\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1', 'as' => 'v1.'], function () {
    Route::post('password-resets', [PasswordResetController::class, 'store']);
    Route::post('password-reset-links', [PasswordResetLinkController::class, 'store']);

    Route::post('tokens', [TokenController::class, 'store']);

    Route::post('users/{user}/activations', [UserActivationController::class, 'store']);

    Route::group(['middleware' => 'auth'], function () {
        Route::put('password', [PasswordController::class, 'update']);

        Route::controller(ProfileController::class)->group(function () {
            Route::get('profile', 'show');
            Route::put('profile', 'update');
        });

        Route::delete('tokens', [TokenController::class, 'destroy']);

        Route::apiResource('users', UserController::class);
    });
});
