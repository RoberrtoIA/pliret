<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvaluationCriteriaRequest;
use App\Http\Requests\UpdateEvaluationCriteriaRequest;
use App\Http\Resources\EvaluationCriteriaResource;
use App\Models\EvaluationCriteria;

class EvaluationCriteriaController extends Controller
{
    public function index()
    {
        return EvaluationCriteriaResource::collection(EvaluationCriteria::all());
    }

    public function store(StoreEvaluationCriteriaRequest $request)
    {
        $attributes = $request->validated();

        $evaluation = EvaluationCriteria::create($attributes);

        return (new EvaluationCriteriaResource($evaluation->load('module')))->response()->setStatusCode(201);
    }

    public function show(EvaluationCriteria $evaluation)
    {
        return new EvaluationCriteriaResource($evaluation->load(['module', 'grades']));
    }

    public function update(EvaluationCriteria $evaluation, UpdateEvaluationCriteriaRequest $request)
    {
        $attributes = $request->validated();

        $evaluation->update($attributes);

        return new EvaluationCriteriaResource($evaluation->load('module'));
    }

    public function destroy(EvaluationCriteria $evaluation)
    {
        $evaluation->delete();

        $response = [
            'message' => 'Evaluation Criteria Deleted'
        ];

        return response($response, 200);
    }
}
