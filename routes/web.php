<?php

use App\Http\Controllers\QuizController;
use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('/')->middleware(['auth'])->group(function() {
    Route::get('/about', [App\Http\Controllers\MainController::class, 'about'])->name('about');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/vk/auth', [SocialController::class, 'index'])->name('vk.auth');
    Route::get('/vk/auth/callback', [SocialController::class, 'callBack']);
});

Auth::routes();

Route::get('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');
