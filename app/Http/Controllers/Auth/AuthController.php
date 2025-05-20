<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserTokenResource;
use App\Models\User;
use App\Services\AuthService;

class AuthController extends Controller
{
    function __construct( private AuthService $authService,){}

    /**
    * Create user
    *
    * @return  UserTokenResource
    */
    public function register(RegisterRequest $request) : UserTokenResource
    {
        $user  = $this->authService->register(
            $request->name,
            $request->email,
            $request->password,
        );
        return UserTokenResource::make($user);

    }


    /**
    * login user
    * @return UserTokenResource
    */
    public function login(LoginRequest $request)  : UserTokenResource {
        $findUserCredentials = User::findByPasswordAndEmail($request->email, $request->password);
        abort_if(!$findUserCredentials,422, 'Bad Credentials' );
        return UserTokenResource::make($findUserCredentials);
    }



}
