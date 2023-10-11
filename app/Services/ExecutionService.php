<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Execution;
use Illuminate\Foundation\Http\FormRequest;

class ExecutionService
{
    public function __construct(protected UserRoleService $roleService)
    {
    }

    public function createExecution(FormRequest $request): Execution
    {
        $data = $request->validated();

        return Execution::create($data);
    }

    public function updateExecution(
        FormRequest $request,
        Execution $execution
    ): Execution {
        $data = $request->validated();
        $execution->update($data);

        return $execution;
    }
}
