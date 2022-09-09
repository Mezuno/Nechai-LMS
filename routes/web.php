<?php

use App\Http\Controllers\QuizController;
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

Route::get('/about', [App\Http\Controllers\MainController::class, 'about'])->name('about');
Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news');

Route::prefix('/news')->middleware(['auth.admin'])->group(function() {
    Route::post('', [App\Http\Controllers\NewsController::class, 'store'])->name('news.store');
    Route::patch('/{id}', [App\Http\Controllers\NewsController::class, 'update'])->name('news.update');
    Route::delete('/{id}', [App\Http\Controllers\NewsController::class, 'destroy'])->name('news.delete');
    Route::patch('/{id}', [App\Http\Controllers\NewsController::class, 'restore'])->name('news.restore');
});

Route::prefix('/users/{id}')->middleware(['auth'])->group(function() {
    Route::get('', [UserController::class, 'view'])->name('users.view');
    Route::get('/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('', [UserController::class, 'update'])->name('users.update');
});

Route::prefix('/users')->middleware(['auth.admin'])->group(function() {
    Route::get('', [UserController::class, 'index'])->name('users');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('', [UserController::class, 'store'])->name('users.store');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.delete');
    Route::patch('/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
});

Route::prefix('/quizzes')->middleware(['auth'])->group(function() {
    Route::get('/assigned', [QuizController::class, 'assigned'])->name('quizzes.assigned');
    Route::get('/{id}/play', [QuizController::class, 'play'])->name('quizzes.play');
    Route::post('/{id}/check', [QuizController::class, 'check'])->name('quizzes.check');
    Route::get('/{id}/result', [QuizController::class, 'result'])->name('quizzes.result');
});


Route::prefix('/quizzes')->middleware(['auth.admin'])->group(function() {
    Route::get('', [QuizController::class, 'index'])->name('quizzes');
    Route::get('/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/{id}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::patch('/{id}/', [QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/{id}/delete', [QuizController::class, 'destroy'])->name('quizzes.delete');
    Route::patch('/{id}/restore', [QuizController::class, 'restore'])->name('quizzes.restore');
    Route::get('/{id}/assign', [QuizController::class, 'assign'])->name('quizzes.assign');
    Route::get('/{id}/assign/all', [QuizController::class, 'assignAll'])->name('quizzes.assign.all');

    Route::post('/{id}/assign/{user_id}', [QuizController::class, 'createAssign'])->name('quizzes.assign.create');
    Route::delete('/{id}/assign/{assignment_id}', [QuizController::class, 'destroyAssign'])->name('quizzes.assign.delete');

    Route::get('/{id}/edit/{question_id}', [QuizController::class, 'editQuestion'])->name('quizzes.edit.questions');
    Route::patch('/{id}/update/{question_id}', [QuizController::class, 'updateQuestion'])->name('quizzes.update.questions');
    Route::delete('/{id}/delete/{question_id}', [QuizController::class, 'destroyQuestion'])->name('quizzes.delete.questions');
    Route::get('/{id}/create/', [QuizController::class, 'createQuestion'])->name('quizzes.create.questions');
    Route::post('/{id}/', [QuizController::class, 'storeQuestion'])->name('quizzes.store.questions');

    Route::delete('/{id}/edit/{question_id}/delete/{option_id}', [QuizController::class, 'destroyOption'])->name('quizzes.delete.options');
    Route::get('/{id}/edit/{question_id}/create/', [QuizController::class, 'createOption'])->name('quizzes.create.options');
    Route::post('/{id}/edit/{question_id}/', [QuizController::class, 'storeOption'])->name('quizzes.store.options');

    Route::get('/statistics', [QuizController::class, 'statistics'])->name('quizzes.statistics');
});

Auth::routes();
