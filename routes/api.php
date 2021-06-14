<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
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

/*
* Unauthenticated routes
*/
Route::group(['prefix' => 'v1'], function () {
    
    Route::post('/signup', [UserController::class, 'signup'])->name('users.signup');
});

/*
* Authenticated routes
*/
Route::group(['prefix' => 'v1' ,'middleware' => ['auth:api']], function () {
    
    Route::post('/users', [UserController::class, 'index'])->name('users.index');
});

Route::prefix('v1')->group(function () {
	Route::name('users.')->group(function() {
		/**
		* Forgot password
		*/
		Route::get('/forgotpassword', [App\Http\Controllers\User\UserController::class, 'forgotPassword'])->name('forgotpassword');
		/**
		* Reset password
		*/
		Route::get('/resetpassword', [App\Http\Controllers\User\UserController::class, 'resetPassword'])->name('resetpassword');
		/**
		* Middleware ['verified', 'authenticated']
		*/
		Route::middleware(['auth:api', 'verified'])->group(function () {
			/**
			* Change password
			*/
			Route::patch('/changePassword', [App\Http\Controllers\User\UserController::class, 'changePassword'])->name('changePassword');
		});

		

	});
});