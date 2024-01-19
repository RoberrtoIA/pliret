<?php

namespace Tests\Feature\V1;

use App\Models\Module;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModuleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function it_can_list_modules()
    {
        $count = 5;
        Module::factory()->count($count)->create();

        $this->sanctumActingAsDeveloper();

        $this->get(route('api.v1.modules.index'))
            ->assertOk()
            ->assertJsonCount($count, 'data');
    }

    /** @test */
    public function it_shows_a_module()
    {
        $module = Module::factory()->create();

        $this->sanctumActingAsDeveloper();

        $this->get(route('api.v1.modules.show', ['module' => $module->id]))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $module->id,
                'created_at' => $module->created_at
            ]);
    }

    /** @test */
    public function it_creates_a_new_module()
    {
        $data = Module::factory()->make();

        $data->program->makeHidden('deleted_at');

        $data = $data->toArray();

        $expectation = $data;

        $this->sanctumActingAsDeveloper();

        $this->assertDatabaseCount('modules', 0);

        $this->post(route('api.v1.modules.store'), $data)
            ->assertCreated()
            ->assertJsonFragment($expectation);

        $this->assertDatabaseCount('modules', 1);
    }

    /** @test */
    public function it_updates_a_module()
    {
        $module = Module::factory()->create();
        $module->program->makeHidden('deleted_at');
        $data = ['title' => 'changed'];
        $expectation = collect($module->toArray())->merge($data)->all();

        unset($expectation['program_id']);

        $this->sanctumActingAsDeveloper();

        $this->patch(route(
            'api.v1.modules.update',
            ['module' => $module->id]
        ), $data)
            ->assertOk()
            ->assertJsonFragment($expectation);
    }

    /** @test */
    public function it_soft_deletes_a_module()
    {
        $module = Module::factory()->create();

        $this->sanctumActingAsDeveloper();

        $this->delete(route(
            'api.v1.modules.destroy',
            ['module' => $module->id]
        ))
            ->assertOk();

        $this->assertSoftDeleted('modules', $module->toArray());
    }
}
