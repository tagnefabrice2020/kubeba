<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

//php artisan l5-swagger:generate
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**
* Confirm Account 
*/
Route::get('/{email}/confirmAccount/{token}', [App\Http\Controllers\User\UserController::class, 'confirmAccount'])->name('users.confirmAccount');
Route::get('/sendEmailVerificationToken/{email}', [App\Http\Controllers\User\UserController::class, 'sendEmailVerificationToken'])->name('users.sendEmailVerificationToken');
