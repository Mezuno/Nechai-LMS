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

Route::get('/users', [UserController::class, 'index']);

Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes');
Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
Route::get('/quizzes/{id}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
Route::patch('/quizzes/{id}/', [QuizController::class, 'update'])->name('quizzes.update');
Route::delete('/quizzes/{id}/delete', [QuizController::class, 'destroy'])->name('quizzes.delete');
Route::patch('/quizzes/{id}/restore', [QuizController::class, 'restore'])->name('quizzes.restore');
Route::get('/quizzes/{id}/play', [QuizController::class, 'play'])->name('quizzes.play');
Route::post('/quizzes/{id}/check', [QuizController::class, 'check'])->name('quizzes.check');

Route::get('/quizzes/{id}/edit/{question_id}', [QuizController::class, 'editQuestion'])->name('quizzes.edit.questions');
Route::patch('/quizzes/{id}/update/{question_id}', [QuizController::class, 'updateQuestion'])->name('quizzes.update.questions');
Route::delete('/quizzes/{id}/delete/{question_id}', [QuizController::class, 'destroyQuestion'])->name('quizzes.delete.questions');
Route::get('/quizzes/{id}/create/', [QuizController::class, 'createQuestion'])->name('quizzes.create.questions');
Route::post('/quizzes/{id}/', [QuizController::class, 'storeQuestion'])->name('quizzes.store.questions');

Route::delete('/quizzes/{id}/edit/{question_id}/delete/{option_id}', [QuizController::class, 'destroyOption'])->name('quizzes.delete.options');
Route::get('/quizzes/{id}/edit/{question_id}/create/', [QuizController::class, 'createOption'])->name('quizzes.create.options');
Route::post('/quizzes/{id}/edit/{question_id}/', [QuizController::class, 'storeOption'])->name('quizzes.store.options');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
