<?php

namespace App\Http\Controllers\V1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\UserRoleService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;

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

    public function socialLogin(Request $request, string $driver)
    {
        $this->validateSocialDriver($driver);

        $socialToken = $request->bearerToken();
        /** @var mixed */
        $socialite = Socialite::driver($driver);
        $email = $socialite
            ->userFromToken($socialToken)
            ?->email;

        $user = $this->findUserByEmail($email);

        if (!$user) {
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

    protected function findUserByEmail(string $email): User|null
    {
        return User::where('email', $email)->with('roles')->first();
    }

    protected function validateSocialDriver(string $driver)
    {
        Validator::make(compact('driver'), [
            'driver' => Rule::in(config('app.login.drivers')),
        ])->validate();
    }
}
