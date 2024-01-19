<?php

namespace Tests\Feature\V1;

use App\Models\Execution;
use App\Events\ExecutionFinished;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinishExecutionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Event::fake();
    }


    /** @test */
    public function it_finishes_an_execution()
    {
        $execution = Execution::factory()->create();

        $this->sanctumActingAsManager();

        $this->assertNull($execution->finished);

        $response = $this->get(route(
            'api.v1.executions.finish',
            ['execution' => $execution->id]
        ))
            ->assertOk()
            ->assertJsonFragment(['id' => $execution->id]);

        $data = $response->json('data');

        $this->assertNotNull($data['finished'] ?? null);
    }

    /** @test */
    public function it_dispatches_execution_finished_event()
    {
        $execution = Execution::factory()->create();

        $this->sanctumActingAsManager();

        $this->get(route(
            'api.v1.executions.finish',
            ['execution' => $execution->id]
        ))
            ->assertOk();

        Event::assertDispatched(ExecutionFinished::class);
    }

    /**
     * @test
     * @dataProvider authProvider
     */
    public function only_manager_can_finishes_an_execution($role, $expectation)
    {
        Execution::factory()->create(['id' => 1]);

        if ($role) {
            $this->sanctumActingAs([$role]);
        }

        $this->get(route('api.v1.executions.finish', ['execution' => 1]))
            ->assertStatus($expectation);
    }

    protected function authProvider()
    {
        return [
            'guest' => [null, 401],
            'manager' => ['manager', 200],
            'developer' => ['developer', 403],
            'trainer' => ['trainer', 403],
            'trainee' => ['trainee', 403],
        ];
    }
}
