<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Models\Program;
use App\Services\ProgramService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;

class ProgramController extends Controller
{
    public function __construct(protected ProgramService $programService)
    {
    }

    public function index()
    {
        $user = request()->user();

        $programs = Program::query();

        if ($user->tokenCan('add_program_content')) {
            $programs = $user->myProgramsAsDeveloper()
                ->with('modules.evaluation_criteria')
                ->with('modules.topics.questions');
        }

        return ProgramResource::collection($programs->get());
    }

    public function store(StoreProgramRequest $request)
    {
        $attributes = $request->validated();

        return new ProgramResource($this->programService->createProgram($attributes)->load('tags'));
    }

    public function show(Program $program)
    {
        $user = request()->user();

        if ($user->tokenCan('add_program_content')) {
            $program->developers()->findOrFail($user->id);
        }

        if (
            $user->tokenCan('add_program_content')
            or $user->tokenCan('add_program_content')
        ) {
            $program->load('developers');
        }

        return new ProgramResource($program->load(['tags', 'modules.topics']));
    }

    public function update(Program $program, UpdateProgramRequest $request)
    {
        $attributes = $request->validated();

        $program = $this->programService->updateProgram($program, $attributes);

        return new ProgramResource($program->load('tags'));
    }

    public function destroy(Program $program)
    {
        $program->delete();

        $response = [
            'message' => 'Program Deleted'
        ];

        return response($response, 200);
    }
}
