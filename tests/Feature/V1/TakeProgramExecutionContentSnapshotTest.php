<?php

namespace Tests\Feature\V1;

use App\Models\Execution;
use App\Events\ExecutionFinished;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Listeners\TakeProgramExecutionContentSnapshot;
use App\Models\Module;
use App\Models\Program;
use App\Models\Topic;
use App\Services\ExecutionService;
use App\Services\UserRoleService;

class TakeProgramExecutionContentSnapshotTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_triggered_by_execution_finished_event()
    {
        Event::fake();
        $execution = $this->mock(Execution::class);

        ExecutionFinished::dispatch($execution);

        Event::assertListening(
            ExecutionFinished::class,
            TakeProgramExecutionContentSnapshot::class
        );
    }

    /** @test */
    public function it_takes_the_program_snapshot()
    {
        $program = Program::factory()->create();
        $module = Module::factory()->for($program)->create();
        Topic::factory()->for($module)->create();
        $execution = Execution::factory()->for($program)->create();
        $event = new ExecutionFinished($execution);
        $service = new ExecutionService(new UserRoleService);
        $listener = new TakeProgramExecutionContentSnapshot;

        $this->assertNull($execution->program_execution_content);

        $listener->handle($event, $service);

        $execution->refresh();

        $this->assertNotNull($execution->program_execution_content);
        $this->assertArrayHasKey(
            'modules',
            $execution->program_execution_content
        );
        $this->assertArrayHasKey(
            'topics',
            $execution->program_execution_content['modules'][0] ?? []
        );
    }
}
