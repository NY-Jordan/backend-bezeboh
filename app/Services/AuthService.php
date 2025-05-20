<?php

namespace App\Services;

use App\Models\User;

class AuthService {

    /**
     * register a new user
     */
    public function register($name, $email, $password)  {

         $user = User::whereEmail($email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password]);
        }
        return $user;
    }
}
