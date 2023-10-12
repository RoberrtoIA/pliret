<?php

namespace App\Http\Controllers\V1\Program;

use App\Models\User;
use App\Models\Program;
use App\Services\ProgramService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;

class ProgramAssignDeveloperController extends Controller
{
    public function __invoke(
        Program $program,
        User $developer,
        ProgramService $service
    ) {
        $service->assignDeveloper($program, $developer);

        return new ProgramResource($program->load('developers'));
    }
}
