<?php

namespace Tests\Feature\V1;

use App\Models\Grading;
use App\Models\Assignment;
use App\Events\GradeUpdated;
use App\Events\HomeworkFinished;
use App\Services\HomeworkService;
use App\Models\EvaluationCriteria;
use Illuminate\Support\Facades\Event;
use App\Listeners\UpdateHomeworkGrade;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateHomeworkGradeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_is_triggered_by_homework_finished_event()
    {
        Event::fake();
        $assignment = $this->mock(Assignment::class);

        HomeworkFinished::dispatch($assignment);

        Event::assertListening(
            HomeworkFinished::class,
            UpdateHomeworkGrade::class
        );
    }

    /** @test */
    public function it_updates_homework_grade()
    {
        Event::fake([
            GradeUpdated::class
        ]);
        $count = 4;
        $assignment = Assignment::factory()->create(['homework_grade' => 0]);
        /** @var Grading */
        $gradings = Grading::factory($count)
            ->for($assignment)
            ->create([
                'gradable_id' => EvaluationCriteria::factory(),
                'gradable_type' => EvaluationCriteria::class,
                'grade' => fake()->randomFloat(1, 1, 10)
            ]);
        $event = new HomeworkFinished($assignment);
        $service = $this->app->make(HomeworkService::class);
        $listener = new UpdateHomeworkGrade;

        $averange = $gradings->reduce(function ($carry, $item) {
            return $carry + $item->grade;
        }, 0) / $count;

        $this->assertEquals(0, $assignment->homework_grade);

        $listener->handle($event, $service);

        $assignment->refresh();

        $this->assertEquals($averange, $assignment->homework_grade);
        Event::assertDispatched(GradeUpdated::class);
    }
}
