<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::with('roles')->get());
    }

    public function show(User $user)
    {
        return new UserResource($user->load('roles'));
    }

    public function update(
        UpdateUserRequest $request,
        User $user,
        UserService $service
    ) {
        return new UserResource(
            $service->updateUser($request, $user)->load('roles')
        );
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response(['message' => 'User was deleted.']);
    }
}
