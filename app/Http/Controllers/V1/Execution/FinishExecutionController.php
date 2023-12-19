<?php

namespace App\Http\Controllers\V1\Execution;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExecutionResource;
use App\Models\Execution;
use App\Services\ExecutionService;

class FinishExecutionController extends Controller
{
    public function __invoke(Execution $execution, ExecutionService $service)
    {
        return new ExecutionResource($service->finishExecution($execution));
    }
}
