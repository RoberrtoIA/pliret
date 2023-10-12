<?php

namespace App\Http\Controllers\V1\Execution;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExecutionResource;
use App\Models\Execution;
use App\Models\User;
use App\Services\ExecutionService;

class ExecutionAssignTrainerController extends Controller
{
    public function __invoke(
        Execution $execution,
        User $trainer,
        ExecutionService $service
    ) {
        $service->assignTrainer($execution, $trainer);

        return new ExecutionResource($execution->load('trainers'));
    }
}
