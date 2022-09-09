<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
    public function edit(User $currentUser, User $user)
    {
        return $currentUser->is_teacher || $currentUser->id == $user->id;
    }
}
