<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index()
    {
        return TopicResource::collection(Topic::all());
    }

    public function store(StoreTopicRequest $request)
    {
        $attributes = $request->validated();

        $topic = Topic::create($attributes);

        return (new TopicResource($topic->load(['module', 'questions'])))->response()->setStatusCode(201);
    }

    public function show(Topic $topic)
    {
        return new TopicResource($topic->load(['module', 'questions']));
    }

    public function update(Topic $topic, UpdateTopicRequest $request)
    {
        $attributes = $request->validated();

        $topic->update($attributes);

        return new TopicResource($topic->load(['module', 'questions']));
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        $response = [
            'message' => 'Topic Deleted'
        ];

        return response($response, 200);
    }
}
