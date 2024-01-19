<?php

namespace Tests\Feature\V1;

use Tests\TestCase;
use App\Models\Grading;
use App\Models\Assignment;
use App\Events\GradeUpdated;
use App\Listeners\UpdateScore;
use App\Events\HomeworkFinished;
use App\Models\EvaluationCriteria;
use Illuminate\Support\Facades\Event;
use App\Listeners\UpdateHomeworkGrade;
use App\Models\Execution;
use App\Services\UserService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateScoreTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_is_triggered_by_interview_finished_event()
    {
        Event::fake();
        $assignment = $this->mock(Assignment::class);

        GradeUpdated::dispatch($assignment);

        Event::assertListening(
            GradeUpdated::class,
            UpdateScore::class
        );
    }

    /** @test */
    public function it_updates_score()
    {
        $trainee = $this->newUser(roles: ['trainee']);
        $execution = Execution::factory()->create();
        $execution->enrollments()->attach($trainee);
        $assignment = Assignment::factory()
            ->for($execution)
            ->for($trainee)
            ->create([
                'interview_grade' => 2,
                'homework_grade' => 4
            ]);
        $event = new GradeUpdated($assignment);
        $service = $this->app->make(UserService::class);
        $listener = new UpdateScore;

        $averange = 3;
        $this->assertEquals(0, $execution->enrollments[0]->enrollment->score);

        $listener->handle($event, $service);
        $execution->refresh();

        $assignment->refresh();

        $this->assertEquals($averange, $execution->enrollments[0]->enrollment->score);
    }
}
