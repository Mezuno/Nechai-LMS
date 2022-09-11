<?php

namespace App\Http\Controllers;

use App\Http\Mail\EmailAssignNewUser;
use App\Http\Requests\AssignMultRequest;
use App\Http\Requests\OptionCreateRequest;
use App\Http\Requests\QuestionCreateRequest;
use App\Http\Requests\QuestionUpdateRequest;
use App\Http\Requests\QuizCreateRequest;
use App\Http\Requests\QuizUpdateRequest;
use App\Models\Assignment;
use App\Models\Option;
use App\Models\OptionType;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Statistic;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $searchParams = $request->input('search');
        $quizzes = Quiz::withTrashed()
            ->where('quiz_id', '>', 0)
            ->orderByDesc('quiz_id')
            ->search($searchParams)
            ->paginate(8);
        return view('pages.quizzes.index', compact('quizzes'));
    }

    public function assigned()
    {
        $quizzes = auth()->user()
            ->assignedQuizzes()
            ->with('statistics')
            ->orderByDesc('quiz_id')
            ->paginate(8);
        return view('pages.quizzes.assigned', compact('quizzes'));
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

        $questions = Question::where('quiz_id', $id)
            ->withCount('correct')
            ->get();
        $correctCount = 0;
        foreach ($questions as $question) {
            $correctCount += $question->correct_count;
        }
        $validated['max_points'] = $correctCount;

        Quiz::where('quiz_id', $id)->update($validated);
        return redirect()->route('quizzes.edit', ['id' => $id])
            ->with(['success' => __('success.'.__FUNCTION__.'Quiz')]);
    }

    public function play(int $id)
    {
        $quiz = Quiz::where('quiz_id', $id)->get()->first();
        $this->authorize('play', [$quiz]);
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
                        }
                    }
                    if ($option->is_correct) {
                        $maxPoints++;
                    }
                } elseif ($question->option_type_id == 3) {
                    if ($request->input($question->question_id)) {
                        foreach ($request->input($question->question_id) as $answer) {
                            if ($answer == $option->option_id && $option->is_correct) {
                                $checkboxAnswers += 1;
                            } elseif ($answer == $option->option_id && !$option->is_correct) {
                                $checkboxAnswers -= 1;
                            }
                        }
                    }
                    if ($option->is_correct) {
                        $maxPoints += 1;
                    }
                }
            }
        }

        $result = $radioAnswers + $checkboxAnswers;

        if ($result < 0) {
            $result = 0;
        }

        Statistic::create(['quiz_id' => $id, 'student_id' => auth()->id(), 'result' => $result]);

        Quiz::where('quiz_id', $id)->update(['max_points' => $maxPoints]);

        return redirect()->route('quizzes.result', ['id' => $id])
            ->with(['result' => 'Вы набрали '.$result.' из '.$maxPoints.' баллов']);
    }

    public function result(int $id)
    {
        $quiz = Quiz::where('quiz_id', $id)->get()->first();
        return view('pages.quizzes.result', compact('quiz'));
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

    public function statistics(Request $request)
    {
        $searchByNameParam = $request->input('searchByName');
        $searchByTestParam = $request->input('searchByTest');
        $statistics = Statistic::whereHas('student', function ($query) use ($searchByNameParam){
                                    $query->where('name', 'like', '%'.$searchByNameParam.'%');
                                })
                                ->whereHas('quiz', function ($query) use ($searchByTestParam){
                                    $query->where('title', 'like', '%'.$searchByTestParam.'%');
                                })
                                ->orderByDesc('statistic_id')
                                ->paginate(8);

        return view('pages.quizzes.statistics', compact('statistics'));
    }

    public function assign(int $quiz_id)
    {
        $assignments = Assignment::where('quiz_id', $quiz_id)
            ->with('students')
            ->orderByDesc('assignment_id')
            ->paginate(8);
        return view('pages.quizzes.assign', compact('assignments', 'quiz_id'));
    }

    public function assignAll(int $quiz_id)
    {
        $assignments = User::whereDoesntHave('assignments', function(Builder $query) use ($quiz_id) {
            $query->where('quiz_id', '=', $quiz_id);
        })
            ->orderByDesc('id')
            ->paginate(8);
        return view('pages.quizzes.assignAll', compact('assignments', 'quiz_id'));
    }

    public function createAssign(int $id, int $userId)
    {
        Assignment::firstOrCreate(['quiz_id' => $id, 'student_id' => $userId], ['quiz_id' => $id, 'student_id' => $userId]);
        return redirect()->route('quizzes.assign.all', ['id' => $id])
            ->with(['success' => __('success.'.__FUNCTION__.'Quiz')]);
    }

    public function destroyAssign(int $id, int $assignmentId)
    {
        Assignment::where('assignment_id', $assignmentId)->delete();
        return redirect()->route('quizzes.assign', ['id' => $id])
            ->with(['success' => __('success.'.__FUNCTION__.'Quiz')]);
    }

    public function multAssign(AssignMultRequest $request, int $id)
    {
//        if (empty($request->input('studentEmails'))) {
//            return redirect()->route('courses.edit.assignments', ['id' => $id, 'state' => 'all'])
//                ->with(['success' => __('success.'.__FUNCTION__.'Course')]);
//        }

        $emails = preg_split('/\n|\r\n?/', $request->validated('emails'));

        for ($i = 0; $i < count($emails); $i++) {
            $emails[$i] = trim($emails[$i]);
        }

        $userIds = [];
        $quiz = Quiz::where('quiz_id', $id)->get()->first();

        foreach($emails as $email) {
            if(!($user = User::where('email', $email)->get()->first())) {
                $password = Str::random();
                $user = User::create([
                    'name' => 'Temporary Name',
                    'email' => $email,
                    'password' => Hash::make($password),
                    'is_teacher' => 0,
                    'email_verified_at' => now()
                ]);

                Mail::to($email)->send(new EmailAssignNewUser($user, $password, $quiz));
            }

            $userIds[] = $user->id;
        }

        foreach ($userIds as $userId) {
            $assignData[] = ['student_id' => $userId, 'quiz_id' => $id];
        }

        $createCount = 0;
        for ($i = 0; $i < count($assignData); $i++) {
            if (Assignment::firstOrCreate($assignData[$i])) {
                $createCount++;
            }
        }

        return redirect()->route('quizzes.assign.all', ['id' => $id])
            ->with(['success' => __('success.'.__FUNCTION__.'Quiz')]);
    }
}
