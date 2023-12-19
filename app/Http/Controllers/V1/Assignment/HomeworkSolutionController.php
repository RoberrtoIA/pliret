<?php

namespace App\Http\Controllers\V1\Assignment;

use App\Http\Controllers\Controller;
use App\Http\Requests\HomeworkSolutionRequest;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use App\Services\HomeworkService;

class HomeworkSolutionController extends Controller
{
    public function __invoke(Assignment $assignment, HomeworkService $service, HomeworkSolutionRequest $request)
    {
        return new AssignmentResource($service->uploadHomeworkSolution($assignment, $request));
    }
}
