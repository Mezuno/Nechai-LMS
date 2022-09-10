<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $searchParams = $request->input('search');
        $users = User::withTrashed()->where('id', '>', 0)
            ->orderByDesc('id')
            ->search($searchParams)
            ->paginate(8);
        return view('pages.users.index', compact('users'));
    }

    public function edit(int $id)
    {
        $user = User::where('id', $id)->get()->first();
        $this->authorize('edit', [$user]);
        return view('pages.users.edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, int $id)
    {
        $validated = $request->validated();
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        User::where('id', $id)->update($validated);
        return redirect()->route('users.edit', ['id' => $id])
            ->with(['success' => __('success.'.__FUNCTION__.'User')]);
    }

    public function create()
    {
        return view('pages.users.create');
    }

    public function store(UserCreateRequest $request)
    {
        $validated = $request->validated();
        dd($validated);
    }

    public function view()
    {
        return view('pages.users.view');
    }

    public function destroy(int $id)
    {
        if (auth()->id() !== $id) {
            Assignment::where('student_id', $id)->delete();
            User::where('id', $id)->delete();
            return redirect()->route('users')
                ->with(['success' => __('success.'.__FUNCTION__.'User')]);
        } else {
            return redirect()->route('users')
                ->with(['failed' => __('failed.'.__FUNCTION__.'User')]);
        }
    }

    public function restore(int $id)
    {
        User::where('id', $id)->restore();
        return redirect()->route('users')
            ->with(['success' => __('success.'.__FUNCTION__.'User')]);
    }


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
}
