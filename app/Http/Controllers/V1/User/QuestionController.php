<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        return QuestionResource::collection(Question::all());
    }

    public function store(StoreQuestionRequest $request)
    {
        $attributes = $request->validated();

        $question = Question::create($attributes);

        return (new QuestionResource($question->load('topic')))->response()->setStatusCode(201);
    }

    public function show(Question $question)
    {
        return new QuestionResource($question->load('topic'));
    }

    public function update(Question $question, UpdateQuestionRequest $request)
    {
        $attributes = $request->validated();

        $question->update($attributes);

        return new QuestionResource($question->load('topic'));
    }

    public function destroy(Question $question)
    {
        $question->delete();

        $response = [
            'message' => 'Question Deleted'
        ];

        return response($response, 200);
    }
}
