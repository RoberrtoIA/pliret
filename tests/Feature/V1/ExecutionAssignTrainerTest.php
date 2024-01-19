<?php

namespace Tests\Feature\V1;

use App\Models\Execution;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExecutionAssignTrainerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }


    /** @test */
    public function it_assigns_a_trainer_to_execution()
    {
        $execution = Execution::factory()->create();
        $trainer = $this->newUser(roles: ['trainer']);
        $trainerIdentifiers = [
            'execution_id' => $execution->id,
            'user_id' => $trainer->id,
        ];

        $this->sanctumActingAsManager();

        $this->assertDatabaseMissing('trainers', $trainerIdentifiers);

        $this->get(route('api.v1.executions.assign-trainer', [
            'execution' => $execution->id,
            'trainer' => $trainer->id,
        ]))
            ->assertOk()
            ->assertJsonFragment($trainerIdentifiers)
            ->assertJsonFragment(['id' => $execution->id])
            ->assertJsonCount(1, 'data.trainers')
            ->assertJsonFragment(['id' => $trainer->id]);

        $this->assertDatabaseHas('trainers', $trainerIdentifiers);
    }
}
