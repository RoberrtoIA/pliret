<?php

namespace App\Http\Controllers\V1\Assignment;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use App\Services\HomeworkService;

class HomeworkFinishController extends Controller
{
    public function __invoke(Assignment $assignment, HomeworkService $service)
    {
        return new AssignmentResource($service->finishHomework($assignment));
    }
}
