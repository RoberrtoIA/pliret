<?php

namespace Tests\Feature\V1;

use App\Models\Execution;
use App\Models\Module;
use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssignUserModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }


    /** @test */
    public function it_assings_a_module_to_user()
    {
        $program = Program::factory()->create();
        $module = Module::factory()->for($program)->create();
        $execution = Execution::factory()->for($program)->create();
        $user = $this->newUser(roles: ['trainee']);

        $identifiers = [
            'user_id' => $user->id,
            'execution_id' => $execution->id,
            'module_id' => $module->id,
        ];

        $this->sanctumActingAsManager();

        $this->assertDatabaseMissing('assignments', $identifiers);

        $response = $this->post(
            route('api.v1.executions.assign-trainee-module'),
            $identifiers
        )
            ->assertOk()
            ->json()['data'];

        $this->assertArrayHasKey('assignments', $response);
        $this->assertArrayHasKey('module', $response['assignments'][0] ?? []);
        $this->assertArrayHasKey('user', $response['assignments'][0] ?? []);
        $this->assertDatabaseHas('assignments', $identifiers);
    }
}
