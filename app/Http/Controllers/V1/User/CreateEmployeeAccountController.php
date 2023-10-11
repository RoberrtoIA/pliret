<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;

class CreateEmployeeAccountController extends Controller
{
    public function __invoke(
        StoreEmployeeRequest $request,
        UserService $service
    ) {
        return new UserResource($service->createUser($request)->load('roles'));
    }
}
