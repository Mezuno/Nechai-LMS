<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes [News]
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/news', [NewsController::class, 'index'])->middleware(['auth'])->name('news');

Route::prefix('/news')->middleware(['auth.admin'])->group(function() {
    Route::post('/category/', [NewsController::class, 'storeCategory'])->name('news.category.store');
    Route::post('', [NewsController::class, 'store'])->name('news.store');
    Route::patch('/{id}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/{id}', [NewsController::class, 'destroy'])->name('news.delete');
    Route::patch('/{id}', [NewsController::class, 'restore'])->name('news.restore');
});
