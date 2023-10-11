<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserRoleService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected UserRoleService $userRoleService)
    {
    }

    public function login(LoginUserRequest $request)
    {
        $attributes = $request->validated();

        $user = $this->findUserByEmail($attributes['email']);

        if (!$user || !Hash::check($attributes['password'], $user->password)) {
            return $this->invalidCredentialsResponse();
        }

        $this->setUserToken($user);

        return new UserResource($user);
    }

    protected function invalidCredentialsResponse()
    {
        $response = ['message' => 'Invalid Credentials'];

        return response($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    protected function setUserToken(User $user)
    {
        $token = $user->createToken(
            'access_token',
            $this->userRoleService->getFlattenAbilities($user)
        )->plainTextToken;

        $user->token = $token;
    }
}
