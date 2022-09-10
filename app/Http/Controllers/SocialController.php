<?php

namespace App\Http\Controllers;

use App\Services\SocialService;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function index()
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    public function callBack()
    {
        $user = Socialite::driver('vkontakte')->stateless()->user();
        $objSocial = new SocialService();
        $currentUser = $objSocial->saveSocialData($user);

        if(isset($currentUser->id)) {
            Auth::loginUsingId($currentUser->id, TRUE);

            return redirect()->intended('about');
        }

        return redirect()->route('login')->withErrors(['Go to the application and provide access to your data (Required: last name, first name, email. Optional: avatar)']);
    }
}
