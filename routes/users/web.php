<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes [Users]
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('/users')->middleware(['auth'])->group(function() {
    Route::get('/{id}', [UserController::class, 'view'])->name('users.view')->where('id', '[0-9]+');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit')->where('id', '[0-9]+');
    Route::patch('/{id}/avatar', [UserController::class, 'updateAvatar'])->name('users.update.avatar')->where('id', '[0-9]+');
    Route::patch('/{id}', [UserController::class, 'update'])->name('users.update')->where('id', '[0-9]+');
});

Route::prefix('/users')->middleware(['auth.admin'])->group(function() {
    Route::get('', [UserController::class, 'index'])->name('users');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('', [UserController::class, 'store'])->name('users.store');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.delete')->where('id', '[0-9]+');
    Route::patch('/{id}/restore', [UserController::class, 'restore'])->name('users.restore')->where('id', '[0-9]+');
});
