<?php

namespace App\Http\Controllers\V1;

use App\Models\Execution;
use App\Services\ExecutionService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExecutionResource;
use App\Http\Requests\StoreExecutionRequest;
use App\Http\Requests\UpdateExecutionRequest;

class ExecutionController extends Controller
{

    public function index()
    {
        /** @var \App\Models\User */
        $user = request()->user();

        $executions = Execution::query();

        if ($user->tokenCan('see_program_content_details')) {
            $executions = $user->myExecutionsAsTrainer();
        }

        if ($user->tokenCan('see_program_content')) {
            $executions = $user->myExecutionsAsTrainee()->whereNull('finished');
        }

        return ExecutionResource::collection(
            $executions->with('program')->paginate(100)
        );
    }

    public function store(
        StoreExecutionRequest $request,
        ExecutionService $service
    ) {
        return new ExecutionResource($service->createExecution($request));
    }

    public function show($execution)
    {
        $id = $execution;
        /** @var \App\Models\User */
        $user = request()->user();

        if ($user->tokenCan('see_program_content_details')) {
            $execution = $user->myExecutionsAsTrainer()->findOrFail($id);
        }

        if ($user->tokenCan('see_program_content')) {
            $execution = $user->myExecutionsAsTrainee()->whereNull('finished')
                ->findOrFail($id);
        }

        if ($execution === $id) {
            $execution = Execution::query()->findOrFail($id);
        }

        if ($user->tokenCan('see_program_content_details')) {
            $execution->load([
                'trainers',
                'enrollments',
                'program.modules.evaluation_criteria.grades',
                'program.modules.topics',
                'assignments.gradings'
            ]);
        }

        if ($user->tokenCan('see_program_content')) {
            $execution->load([
                'program.modules.topics',
                'assignments' => function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->with('gradings');
                }
            ]);
        }

        return new ExecutionResource($execution);
    }

    public function update(
        UpdateExecutionRequest $request,
        Execution $execution,
        ExecutionService $service
    ) {
        return new ExecutionResource(
            $service->updateExecution($request, $execution)
        );
    }

    public function destroy(Execution $execution)
    {
        $execution->delete();

        return response(['message' => 'Execution was deleted.']);
    }
}
