<?php

namespace Tests\Feature\V1;

use App\Models\Assignment;
use App\Models\EvaluationCriteria;
use App\Models\Grading;
use App\Models\Module;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SaveEvaluationCriteriaTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function it_creates_new_evaluation_criteria_gradings()
    {
        $count = 3;
        $module = Module::factory()->create();
        $assignment = Assignment::factory()->for($module)->create();
        $evaluations = EvaluationCriteria::factory()->for($module)
            ->count($count)
            ->create();
        $data = [
            'assignment_id' => $assignment->id,
            'gradables' => $evaluations->map(function ($item) use ($assignment) {
                return Grading::factory()
                    ->for($item, 'gradable')
                    ->for($assignment)
                    ->make();
            })->toArray()
        ];

        $this->sanctumActingAsTrainer();

        $this->assertDatabaseCount('gradings', 0);

        $this->put(route('api.v1.assignments.save-evaluation-criteria'), $data)
            ->assertOk()
            ->assertJsonCount($count, 'data');

        $this->assertDatabaseCount('gradings', $count);
    }
}
