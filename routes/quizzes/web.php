<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes [Quizzes]
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('/quizzes')->middleware(['auth'])->group(function() {
    Route::get('/assigned', [QuizController::class, 'assigned'])->name('quizzes.assigned');
    Route::get('/{id}/play', [QuizController::class, 'play'])->name('quizzes.play')->where('id', '[0-9]+');
    Route::post('/{id}/check', [QuizController::class, 'check'])->name('quizzes.check')->where('id', '[0-9]+');
    Route::get('/{id}/result', [QuizController::class, 'result'])->name('quizzes.result')->where('id', '[0-9]+');
});

Route::prefix('/quizzes')->middleware(['auth.admin'])->group(function() {
    Route::get('', [QuizController::class, 'index'])->name('quizzes');
    Route::get('/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/{id}/edit', [QuizController::class, 'edit'])->name('quizzes.edit')->where('id', '[0-9]+');
    Route::patch('/{id}/', [QuizController::class, 'update'])->name('quizzes.update')->where('id', '[0-9]+');
    Route::delete('/{id}/delete', [QuizController::class, 'destroy'])->name('quizzes.delete')->where('id', '[0-9]+');
    Route::patch('/{id}/restore', [QuizController::class, 'restore'])->name('quizzes.restore')->where('id', '[0-9]+');

    Route::get('/{id}/assign', [QuizController::class, 'assign'])->name('quizzes.assign')->where('id', '[0-9]+');
    Route::get('/{id}/assign/all', [QuizController::class, 'assignAll'])->name('quizzes.assign.all')->where('id', '[0-9]+');
    Route::post('/{id}/assign/{user_id}', [QuizController::class, 'createAssign'])->name('quizzes.assign.create')->where('id', '[0-9]+')->where('user_id', '[0-9]+');
    Route::delete('/{id}/assign/{assignment_id}', [QuizController::class, 'destroyAssign'])->name('quizzes.assign.delete')->where('id', '[0-9]+')->where('assignment_id', '[0-9]+');
    Route::post('/{id}/assign/mult', [QuizController::class, 'multAssign'])->name('quizzes.assign.mult')->where('id', '[0-9]+');

    Route::get('/{id}/edit/{question_id}', [QuizController::class, 'editQuestion'])->name('quizzes.edit.questions')->where('id', '[0-9]+')->where('question_id', '[0-9]+');
    Route::patch('/{id}/update/{question_id}', [QuizController::class, 'updateQuestion'])->name('quizzes.update.questions')->where('id', '[0-9]+')->where('question_id', '[0-9]+');
    Route::delete('/{id}/delete/{question_id}', [QuizController::class, 'destroyQuestion'])->name('quizzes.delete.questions')->where('id', '[0-9]+')->where('question_id', '[0-9]+');
    Route::get('/{id}/create/', [QuizController::class, 'createQuestion'])->name('quizzes.create.questions')->where('id', '[0-9]+');
    Route::post('/{id}/', [QuizController::class, 'storeQuestion'])->name('quizzes.store.questions')->where('id', '[0-9]+');

    Route::delete('/{id}/edit/{question_id}/delete/{option_id}', [QuizController::class, 'destroyOption'])->name('quizzes.delete.options')->where('id', '[0-9]+')->where('question_id', '[0-9]+')->where('option_id', '[0-9]+');
    Route::get('/{id}/edit/{question_id}/create/', [QuizController::class, 'createOption'])->name('quizzes.create.options')->where('id', '[0-9]+')->where('question_id', '[0-9]+');
    Route::post('/{id}/edit/{question_id}/', [QuizController::class, 'storeOption'])->name('quizzes.store.options')->where('id', '[0-9]+')->where('question_id', '[0-9]+');

    Route::get('/statistics', [QuizController::class, 'statistics'])->name('quizzes.statistics');
});
