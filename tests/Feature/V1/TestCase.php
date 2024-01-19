<?php

namespace Tests\Feature\V1;

use App\Models\User;
use Tests\TestCase as Base;
use Laravel\Sanctum\Sanctum;

class TestCase extends Base
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeader('Accept', 'application/json');
    }

    /**
     * Sanctum acting as shortcut with new user as default
     */
    protected function sanctumActingAs(array $roles = [], User $user = null)
    {
        $user = $user ?? $this->newUser(roles: $roles);
        $abilities = $this->app->make(\App\Services\UserRoleService::class)
            ->getFlattenAbilities($user);
        return Sanctum::actingAs($user, $abilities);
    }

    protected function sanctumActingAsManager()
    {
        return $this->sanctumActingAs(['manager']);
    }

    protected function sanctumActingAsDeveloper()
    {
        return $this->sanctumActingAs(['developer']);
    }

    protected function sanctumActingAsTrainer()
    {
        return $this->sanctumActingAs(['trainer']);
    }

    protected function sanctumActingAsTrainee()
    {
        return $this->sanctumActingAs(['trainee']);
    }
}
