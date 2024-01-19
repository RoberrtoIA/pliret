<?php

namespace Tests\Feature\V1;

use App\Models\Grading;
use App\Models\Question;
use App\Models\Assignment;
use App\Events\GradeUpdated;
use App\Events\InterviewFinished;
use App\Services\InterviewService;
use Illuminate\Support\Facades\Event;
use App\Listeners\UpdateInterviewGrade;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateInterviewGradeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_is_triggered_by_interview_finished_event()
    {
        Event::fake();
        $assignment = $this->mock(Assignment::class);

        InterviewFinished::dispatch($assignment);

        Event::assertListening(
            InterviewFinished::class,
            UpdateInterviewGrade::class
        );
    }

    /** @test */
    public function it_updates_interview_grade()
    {
        Event::fake([
            GradeUpdated::class
        ]);
        $count = 4;
        $assignment = Assignment::factory()->create(['interview_grade' => 0]);
        /** @var Grading */
        $gradings = Grading::factory($count)
            ->for($assignment)
            ->create([
                'gradable_id' => Question::factory(),
                'gradable_type' => Question::class,
                'grade' => fake()->randomFloat(1, 1, 10)
            ]);
        $event = new InterviewFinished($assignment);
        $service = $this->app->make(InterviewService::class);
        $listener = new UpdateInterviewGrade;

        $averange = $gradings->reduce(function ($carry, $item) {
            return $carry + $item->grade;
        }, 0) / $count;

        $this->assertEquals(0, $assignment->interview_grade);

        $listener->handle($event, $service);

        $assignment->refresh();

        $this->assertEquals($averange, $assignment->interview_grade);
        Event::assertDispatched(GradeUpdated::class);
    }
}
