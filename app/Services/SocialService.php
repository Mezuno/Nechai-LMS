<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialService
{
    public function saveSocialData($user)
    {
        $email = $user->getEmail();
        $fullname = $user->getName();
        $partOfName = explode(" ", $fullname);
        $name = $partOfName[0].' '.$partOfName[1];
        $avatar = $user->getAvatar();

        if($this->checkEmptyColumn($email, $name)) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'email' => "$email",
                    'password' => Hash::make(Str::random(60)),
                    'name' => $name,
//                    'avatar_filename' => $avatar,
                    'email_verified_at' => NOW(),
                    'remember_token' => Str::random(20),
                ]
            );
            return $user;
        }
        return false;
    }

    public function checkEmptyColumn($email, $name)
    {
        if(empty($email) || empty($name)) {
            return false;
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }
}
