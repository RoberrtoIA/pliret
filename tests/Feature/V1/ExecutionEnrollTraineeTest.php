<?php

namespace Tests\Feature\V1;

use App\Models\Execution;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExecutionEnrollTraineeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }


    /** @test */
    public function it_enrolls_a_trainee_to_execution()
    {
        $execution = Execution::factory()->create();
        $trainee = $this->newUser(roles: ['trainee']);
        $enrollmentIdentifiers = [
            'execution_id' => $execution->id,
            'user_id' => $trainee->id,
        ];

        $this->sanctumActingAsManager();

        $this->assertDatabaseMissing('enrollments', $enrollmentIdentifiers);

        $this->get(route('api.v1.executions.enroll-trainee', [
            'execution' => $execution->id,
            'trainee' => $trainee->id,
        ]))
            ->assertOk()
            ->assertJsonFragment($enrollmentIdentifiers)
            ->assertJsonFragment(['id' => $execution->id])
            ->assertJsonCount(1, 'data.enrollments')
            ->assertJsonFragment(['id' => $trainee->id]);

        $this->assertDatabaseHas('enrollments', $enrollmentIdentifiers);
    }
}
