<?php

namespace Tests\Feature\V1;

use App\Models\Module;
use App\Models\Assignment;
use App\Events\HomeworkStarted;
use Illuminate\Support\Facades\Event;
use App\Listeners\TakeHomeworkSnapshot;
use App\Models\EvaluationCriteria;
use App\Services\HomeworkService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TakeHomeworkSnapshotTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_triggered_by_homework_started_event()
    {
        Event::fake();
        $assignment = $this->mock(Assignment::class);

        HomeworkStarted::dispatch($assignment);

        Event::assertListening(
            HomeworkStarted::class,
            TakeHomeworkSnapshot::class
        );
    }

    /** @test */
    public function it_takes_homework_snapshot()
    {
        $count = 4;
        $module = Module::factory()->create();
        $assignment = Assignment::factory()->for($module)->create();
        EvaluationCriteria::factory($count)
            ->for($module)
            ->create();
        $event = new HomeworkStarted($assignment);
        $service = $this->app->make(HomeworkService::class);
        $listener = new TakeHomeworkSnapshot;

        $this->assertNull($assignment->homework_snapshot);

        $listener->handle($event, $service);

        $assignment->refresh();

        $this->assertNotNull($assignment->homework_snapshot);
        $this->assertArrayHasKey(
            'id',
            $assignment->homework_snapshot
        );
        $this->assertEquals($module->id, $assignment->homework_snapshot['id']);
        $this->assertArrayHasKey(
            'evaluation_criteria',
            $assignment->homework_snapshot
        );
        $this->assertCount($count, $assignment->homework_snapshot['evaluation_criteria']);
    }
}
