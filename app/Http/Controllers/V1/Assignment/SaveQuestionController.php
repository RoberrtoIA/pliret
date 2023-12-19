<?php

namespace App\Http\Controllers\V1\Assignment;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveGradableRequest;
use App\Http\Resources\GradingResource;
use App\Models\Question;
use App\Services\GradingService;

class SaveQuestionController extends Controller
{
    public function __invoke(SaveGradableRequest $request, GradingService $service)
    {
        return GradingResource::collection(
            $service->upsert($request, Question::class)
        );
    }
}
