<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function newUser(array $attributes = [], array $roles = []): User
    {
        /** @var User */
        $user = User::factory()->create($attributes);

        if ($roles) {
            $roles = Role::whereIn('name', $roles)->pluck('id');
            $user->roles()->sync($roles);
        }

        return $user;
    }
}
