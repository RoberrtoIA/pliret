<?php

namespace Tests\Feature\V1;

use App\Models\Assignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;

class AssignmentTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Event::fake();
    }

    /** @test */
    public function it_start_a_interview()
    {
        $assignment = Assignment::factory()->create();

        $this->sanctumActingAsTrainer();

        $this->assertNull($assignment->interview_start_at);

        $this->post(route('api.v1.assignments.interview-start', ['assignment' => $assignment->id]))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $assignment->id,
            'interview_start_at' => Carbon::now()->toDateTimeString()
        ]);

        $assignment->refresh();

        $this->assertNotNull($assignment->interview_start_at);
    }

    /** @test */
    public function it_finish_a_interview()
    {
        $assignment = Assignment::factory()->create();

        $this->sanctumActingAsTrainer();

        $this->assertNull($assignment->interview_finish_at);

        $this->post(route('api.v1.assignments.interview-finish', ['assignment' => $assignment->id]))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $assignment->id,
            'interview_finish_at' => Carbon::now()->toDateTimeString()
        ]);

        $assignment->refresh();

        $this->assertNotNull($assignment->interview_finish_at);
    }

    /** @test */
    public function it_start_a_homework()
    {
        $assignment = Assignment::factory()->create();

        $this->sanctumActingAsTrainer();

        $this->assertNull($assignment->homework_start_at);

        $this->post(route('api.v1.assignments.homework-start', ['assignment' => $assignment->id]))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $assignment->id,
            'homework_start_at' => Carbon::now()->toDateTimeString()
        ]);

        $assignment->refresh();

        $this->assertNotNull($assignment->homework_start_at);
    }

    /** @test */
    public function it_finish_a_homework()
    {
        $assignment = Assignment::factory()->create();

        $this->sanctumActingAsTrainer();

        $this->assertNull($assignment->homework_finish_at);

        $this->post(route('api.v1.assignments.homework-finish', ['assignment' => $assignment->id]))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $assignment->id,
            'homework_finish_at' => Carbon::now()->toDateTimeString()
        ]);

        $assignment->refresh();

        $this->assertNotNull($assignment->homework_finish_at);
    }

    /** @test */
    public function it_upload_a_homework_solution()
    {
        $assignment = Assignment::factory()->create();

        $solution = $this->faker->sentence;

        $this->sanctumActingAsTrainee();

        $this->assertNull($assignment->homework_solution);

        $this->post(route('api.v1.assignments.homework-solution', ['assignment' => $assignment->id, 'homework_solution' => $solution]))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $assignment->id,
            'homework_solution' => $solution
        ]);

        $assignment->refresh();

        $this->assertEquals($assignment->homework_solution, $solution);
    }

}
