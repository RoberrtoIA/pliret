<?php

namespace App\Http\Controllers\V1\Assignment;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use App\Services\InterviewService;

class InterviewFinishController extends Controller
{
    public function __invoke(Assignment $assignment, InterviewService $service)
    {
        return new AssignmentResource($service->finishEvaluation($assignment));
    }
}
