<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuizPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quiz  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function play(User $user, Quiz $quiz)
    {
        return $user->assignedQuizzes()
            ->where('quizzes.quiz_id', $quiz->quiz_id)
            ->exists()
            && !$user->statistic()
            ->where('statistics.quiz_id', $quiz->quiz_id)
            ->exists();
    }
}
