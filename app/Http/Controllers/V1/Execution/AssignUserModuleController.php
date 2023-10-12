<?php

namespace App\Http\Controllers\V1\Execution;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignUserModuleRequest;
use App\Http\Resources\ExecutionResource;
use App\Models\Assignment;
use App\Models\Execution;

class AssignUserModuleController extends Controller
{
    public function __invoke(AssignUserModuleRequest $request)
    {
        $attributes = $request->validated();

        Assignment::query()->create([
            'execution_id' => $attributes['execution_id'],
            'module_id' => $attributes['module_id'],
            'user_id' => $attributes['user_id'],
        ]);

        return new ExecutionResource(
            Execution::query()
                ->with(['assignments.module', 'assignments.user', 'enrollments'])
                ->find($attributes['execution_id'])
        );
    }
}
