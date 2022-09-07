<?php

namespace App\Http\Controllers;

use App\Http\Requests\OptionCreateRequest;
use App\Http\Requests\QuestionCreateRequest;
use App\Http\Requests\QuestionUpdateRequest;
use App\Http\Requests\QuizCreateRequest;
use App\Http\Requests\QuizUpdateRequest;
use App\Models\Option;
use App\Models\OptionType;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::withTrashed()
            ->where('quiz_id', '>', 0)
            ->orderByDesc('quiz_id')
            ->paginate(8);
        return view('pages.quizzes.index', compact('quizzes'));
    }

    public function edit(int $id)
    {
        $quiz = Quiz::where('quiz_id', $id)->get()->first();
        $questions = Question::where('quiz_id', $id)
            ->with('type')
            ->with('options')
            ->orderByDesc('question_id')
            ->get();

        return view('pages.quizzes.edit', compact('quiz', 'questions'));
    }

    public function update(QuizUpdateRequest $request, int $id)
    {
        $validated = $request->validated();
        Quiz::where('quiz_id', $id)->update($validated);
        return redirect()->route('quizzes.edit', ['id' => $id])
            ->with(['success' => __('success.'.__FUNCTION__.'Quiz')]);
    }

    public function play(int $id)
    {
        $quiz = Quiz::where('quiz_id', $id)->get()->first();
        $questions = Question::where('quiz_id', $id)
            ->with('type')
            ->with('options')
            ->orderByDesc('question_id')
            ->get();
        return view('pages.quizzes.play', compact('quiz', 'questions'));
    }

    public function check(Request $request, int $id)
    {
        $questions = Question::where('quiz_id', $id)
            ->with('type')
            ->with('options')
            ->orderByDesc('question_id')
            ->get();

        $radioAnswers = 0;
        $checkboxAnswers = 0;
        $maxPoints = 0;

        foreach ($questions as $question) {
            foreach ($question->options as $option) {
                if ($question->option_type_id == 1) {
                    if ($request->input($question->question_id) && $request->input($question->question_id) == $option->option_id) {
                        if ($request->input($question->question_id) == $option->option_id && $option->is_correct) {
                            $radioAnswers++;
//                            dump('Correct answer '.$question->question_id.' '.$option->option_id);
                        }
                    }
                    if ($option->is_correct) {
                        $maxPoints++;
                    }
                } elseif ($question->option_type_id == 3) {
                    if ($request->input($question->question_id)) {
                        foreach ($request->input($question->question_id) as $answer) {
                            if ($answer == $option->option_id && $option->is_correct) {
                                $checkboxAnswers += 0.5;
//                                dump('Correct answer '.$question->question_id.' '.$option->option_id);
                            }
                        }
                    }
                    if ($option->is_correct) {
                        $maxPoints += 0.5;
                    }
                }
            }
        }
//        dd('Вы набрали '.$radioAnswers+$checkboxAnswers.' из '.$maxPoints);
        return redirect()->route('quizzes.play', ['id' => $id])
            ->with(['result' => 'Вы набрали '.$radioAnswers+$checkboxAnswers.' из '.$maxPoints.' баллов']);
    }

    public function store(QuizCreateRequest $request)
    {
        $validated = $request->validated();
        Quiz::create($validated);
        return redirect()->route('quizzes')
            ->with(['success' => __('success.'.__FUNCTION__.'Quiz')]);
    }

    public function create()
    {
        return view('pages.quizzes.create');
    }

    public function restore(int $id): RedirectResponse
    {
        Quiz::where([['quiz_id', '=', $id]])->restore();
        return redirect()->route('quizzes')
            ->with(['success' => __('success.'.__FUNCTION__.'Quiz')]);
    }

    public function destroy(int $id): RedirectResponse
    {
        Quiz::where('quiz_id', $id)->delete();
        return redirect()->route('quizzes')
            ->with(['success' => __('success.'.__FUNCTION__.'Quiz')]);
    }

    public function editQuestion(int $id, int $questionId)
    {
        $question = Question::where('question_id', $questionId)->with('options')->get()->first();
        $types = OptionType::all();
        return view('pages.quizzes.questions.edit', compact('question', 'types'));
    }

    public function updateQuestion(QuestionUpdateRequest $request, int $id, int $questionId)
    {
        $validated = $request->validated();
        Question::where('question_id', $questionId)->update(['body' => $validated['body'], 'option_type_id' => $validated['type']]);
        $options = Option::where('question_id', $questionId)->get();
        foreach ($options as $option) {
            if (in_array($option->option_id, $validated['isCorrect'])) {
                $isCorrect = true;
            } else {
                $isCorrect = false;
            }
            $request->validate([
                'option'.$option->option_id => 'min:1|max:255',
            ]);
            Option::where('option_id', $option->option_id)->update(['body' => $request->input('option'.$option->option_id), 'is_correct' => $isCorrect]);
        }
        return redirect()->route('quizzes.edit.questions', ['id' => $id, 'question_id' => $questionId])
            ->with(['success' => __('success.'.__FUNCTION__.'Quiz')]);
    }

    public function destroyQuestion(int $id, int $questionId)
    {
        Question::where('question_id', $questionId)->delete();
        return redirect()->route('quizzes.edit', ['id' => $id, 'question_id' => $questionId])
            ->with(['success' => __('success.'.__FUNCTION__.'Quiz')]);
    }

    public function storeQuestion(QuestionCreateRequest $request, int $id)
    {
        $validated = $request->validated();
        $question = Question::create($validated);
        Option::create(['question_id' => $question->question_id, 'body' => 'Первая опция', 'is_correct' => 1]);
        return redirect()->route('quizzes.edit', ['id' => $id])
            ->with(['success' => __('success.'.__FUNCTION__.'Quiz')]);
    }

    public function createQuestion(int $quiz_id)
    {
        return view('pages.quizzes.questions.create', compact('quiz_id'));
    }

    public function createOption(int $quiz_id, int $question_id)
    {
        return view('pages.quizzes.questions.options.create', compact('quiz_id', 'question_id'));
    }

    public function storeOption(OptionCreateRequest $request, $id, $questionId)
    {
        $validated = $request->validated();
        Option::create($validated);
        return redirect()->route('quizzes.edit.questions', ['id' => $id, 'question_id' => $questionId])
            ->with(['success' => __('success.'.__FUNCTION__.'Quiz')]);
    }

    public function destroyOption($id, $questionId, $optionId)
    {
        Option::where('option_id', $optionId)->delete();
        return redirect()->route('quizzes.edit.questions', ['id' => $id, 'question_id' => $questionId])
            ->with(['success' => __('success.'.__FUNCTION__.'Quiz')]);
    }
}
