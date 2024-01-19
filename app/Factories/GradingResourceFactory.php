<?php

namespace App\Factories;

use App\Http\Resources\EvaluationCriteriaResource;
use App\Http\Resources\QuestionResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

class GradingResourceFactory
{
    public function make(Model $gradable, string $gradableType): JsonResource
    {
        return $gradableType === Question::class
            ? new QuestionResource($gradable)
            : new EvaluationCriteriaResource($gradable);
    }
}
