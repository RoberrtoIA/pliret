<?php

namespace Tests\Feature\V1;

use App\Models\Assignment;
use App\Models\Grading;
use App\Models\Module;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SaveQuestionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function it_creates_new_question_gradings()
    {
        $count = 3;
        $module = Module::factory()->create();
        $topic = Topic::factory()->for($module)->create();
        $assignment = Assignment::factory()->for($module)->create();
        $questions = Question::factory()->for($topic)
            ->count($count)
            ->create();
        $data = [
            'assignment_id' => $assignment->id,
            'gradables' => $questions->map(function ($item) use ($assignment) {
                return Grading::factory()
                    ->for($item, 'gradable')
                    ->for($assignment)
                    ->make();
            })->toArray()
        ];

        $this->sanctumActingAsTrainer();

        $this->assertDatabaseCount('gradings', 0);

        $this->put(route('api.v1.assignments.save-question'), $data)
            ->assertOk()
            ->assertJsonCount($count, 'data');

        $this->assertDatabaseCount('gradings', $count);
    }
}
