<?php

namespace Tests\Feature\V1;

use App\Models\Module;
use App\Models\Assignment;
use App\Events\InterviewStarted;
use Illuminate\Support\Facades\Event;
use App\Listeners\TakeInterviewSnapshot;
use App\Models\Question;
use App\Models\Topic;
use App\Services\InterviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TakeInterviewSnapshotTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_triggered_by_interview_started_event()
    {
        Event::fake();
        $assignment = $this->mock(Assignment::class);

        InterviewStarted::dispatch($assignment);

        Event::assertListening(
            InterviewStarted::class,
            TakeInterviewSnapshot::class
        );
    }

    /** @test */
    public function it_takes_interview_snapshot()
    {
        $count = 4;
        $module = Module::factory()->create();
        $assignment = Assignment::factory()->for($module)->create();
        $topic1 = Topic::factory()->for($module)->create();
        Question::factory($count)
            ->for($topic1)
            ->create();
        $topic2 = Topic::factory()->for($module)->create();
        Question::factory($count)
            ->for($topic2)
            ->create();
        $event = new InterviewStarted($assignment);
        $service = $this->app->make(InterviewService::class);
        $listener = new TakeInterviewSnapshot;

        $this->assertNull($assignment->interview_snapshot);

        $listener->handle($event, $service);

        $assignment->refresh();

        $this->assertNotNull($assignment->interview_snapshot);
        $this->assertArrayHasKey(
            'id',
            $assignment->interview_snapshot
        );
        $this->assertEquals($module->id, $assignment->interview_snapshot['id']);
        $this->assertCount(
            2,
            $assignment->interview_snapshot['topics']
        );
        for ($i = 0; $i < 2; $i++) {
            $this->assertCount(
                $count,
                $assignment->interview_snapshot['topics'][0]['questions']
            );
        }
    }
}
