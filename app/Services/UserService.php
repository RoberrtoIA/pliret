<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\Assignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class UserService
{

    public function __construct(protected UserRoleService $userRoleService)
    {
    }

    public function createUser(FormRequest $request): User
    {
        $data = $request->validated();
        $roles = $data['roles'] ?? $request->roles;
        /** @var User */
        $user = null;
        DB::transaction(function () use ($data, &$user, $roles) {
            /** @var User */
            $user = User::query()->create($data);
            $this->userRoleService->syncUserRoles($user, $roles);
        });

        return $user;
    }

    public function updateUser(FormRequest $request, User $user): User
    {
        $data = $request->validated();
        /** @var User */
        DB::transaction(function () use ($data, $user) {
            $roles = $data['roles'] ?? null;
            $user->fill($data)->save();
            $this->userRoleService->syncUserRoles($user, $roles);
        });

        return $user;
    }

    public function updateScore(Assignment $assignment)
    {
        $execution = $assignment->execution;
        $assignments = $execution->assignments()->where('user_id', $assignment->user_id)->get();
        $averange = 0;
        $cont = 0;
        foreach ($assignments as $item) {
            $averange = $averange + $item->interview_grade;
            $averange = $averange + $item->homework_grade;
            $cont += 2;
        }
        $averange = $averange / $cont;

        $execution->enrollments()->updateExistingPivot($assignment->user_id, [
            'score' => $averange
        ]);

        return $execution;
    }
}
