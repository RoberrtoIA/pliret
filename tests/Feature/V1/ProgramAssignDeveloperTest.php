<?php

namespace Tests\Feature\V1;

use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProgramAssignDeveloperTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function it_assigns_a_developer_to_program()
    {
        $program = Program::factory()->create();
        $developer = $this->newUser(roles: ['developer']);
        $developerIdentifiers = [
            'program_id' => $program->id,
            'user_id' => $developer->id,
        ];

        $this->sanctumActingAsManager();

        $this->assertDatabaseMissing('developers', $developerIdentifiers);

        $this->get(route('api.v1.programs.assign-developer', [
            'program' => $program->id,
            'developer' => $developer->id,
        ]))
            ->assertOk()
            ->assertJsonFragment($developerIdentifiers)
            ->assertJsonFragment(['id' => $program->id])
            ->assertJsonCount(1, 'data.developers')
            ->assertJsonFragment(['id' => $developer->id]);

        $this->assertDatabaseHas('developers', $developerIdentifiers);
    }
}
