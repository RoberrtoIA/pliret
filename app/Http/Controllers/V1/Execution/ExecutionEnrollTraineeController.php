<?php

namespace App\Http\Controllers\V1\Execution;

use App\Models\User;
use App\Models\Execution;
use App\Services\ExecutionService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExecutionResource;

class ExecutionEnrollTraineeController extends Controller
{
    public function __invoke(
        Execution $execution,
        User $trainee,
        ExecutionService $service
    ) {
        $service->enrollTrainee($execution, $trainee);

        return new ExecutionResource($execution->load('enrollments'));
    }
}