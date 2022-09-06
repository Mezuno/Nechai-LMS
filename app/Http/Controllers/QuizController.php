<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\OptionType;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::all();
        return view('pages.quizzes.index', compact('quizzes'));
    }

    public function edit(int $id)
    {
        $quiz = Quiz::where('quiz_id', $id)->get();
        $questions = Question::where('quiz_id', $id)
                            ->with('type')
                            ->with('options')
                            ->get();

        return view('pages.quizzes.edit', compact('quiz', 'questions'));
    }
}
