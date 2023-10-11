<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserRoleService
{

    /**
     * Get flat array of all the abilities from user roles
     */
    public function getFlattenAbilities(User $user): array
    {
        $roles = $user->roles()->with('abilities')->get();

        $abilities = collect();
        foreach ($roles as $role) {
            $abilities = $abilities->merge($role->abilities->pluck('name'));
        }

        return $abilities->all();
    }

    public function getRoleIdListFromNames(array $roleNames): array
    {
        return Role::query()->whereIn('name', $roleNames)->pluck('id')->all();
    }

    public function syncUserRoles(User $user, ?array $roleNames): static
    {
        if ($roleNames) {
            $rolesId = $this->getRoleIdListFromNames($roleNames);
            $user->roles()->sync($rolesId);
        }

        return $this;
    }

    /**
     * @throws Illuminate\Validation\ValidationException
     */
    public function validateTrainerRole(User $user): void
    {
        $this->validateRole($user, 'trainer');
    }

    /**
     * @throws Illuminate\Validation\ValidationException
     */
    public function validateTraineeRole(User $user): void
    {
        $this->validateRole($user, 'trainee');
    }

    /**
     * @throws Illuminate\Validation\ValidationException
     */
    public function validateDeveloperRole(User $user): void
    {
        $this->validateRole($user, 'developer');
    }

    /**
     * @throws Illuminate\Validation\ValidationException
     */
    protected function validateRole(User $user, string $role): void
    {
        Validator::make([], [])->after(function ($validator) use ($user, $role) {
            if (!$user->roles()->pluck('name')->contains($role)) {
                $validator->errors()
                    ->add($role, "The user is not a $role.");
            }
        })
            ->validate();
    }
}
