<?php

namespace Tests\Feature\V1;

use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopicTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function it_can_list_topics()
    {
        $topics = Topic::factory(3)->create()->toArray();

        $this->sanctumActingAsDeveloper();

        $this->get(route('api.v1.topics.index'))
            ->assertOk()
            ->assertJsonFragment(['data' => $topics]);
    }

    /** @test */
    public function it_shows_a_topic()
    {
        $topic = Topic::factory()->create()->load(['module', 'questions']);
        $topic->module->makeHidden('deleted_at');

        $this->sanctumActingAsDeveloper();

        $this->get(route('api.v1.topics.show', ['topic' => $topic->id]))
            ->assertOk()
            ->assertJsonFragment([
                'data' => $topic->toArray()
            ]);
    }

    /** @test */
    public function it_creates_a_new_topic()
    {
        $data = Topic::factory()->make()->toArray();

        $this->sanctumActingAsDeveloper();

        $this->assertDatabaseCount('topics', 0);

        $this->post(route('api.v1.topics.store'), $data)
            ->assertCreated();

        $this->assertDatabaseCount('topics', 1);
    }

    /** @test */
    public function it_updates_a_topic()
    {
        $topic = Topic::factory()->create()->makeHidden('module_id');
        $data = ['title' => 'changed'];
        $expectation = collect($topic->toArray())->merge($data)->all();

        $this->sanctumActingAsDeveloper();

        $this->patch(route(
            'api.v1.topics.update',
            ['topic' => $topic->id]
        ), $data)
            ->assertOk()
            ->assertJsonFragment($expectation);
    }

    /** @test */
    public function it_soft_deletes_a_module()
    {
        $topic = Topic::factory()->create();

        $this->sanctumActingAsDeveloper();

        $this->delete(route(
            'api.v1.topics.destroy',
            ['topic' => $topic->id]
        ))
            ->assertOk();

        $this->assertSoftDeleted('topics', $topic->toArray());
    }
}
