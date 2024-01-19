<?php

namespace Tests\Feature\V1;

use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ProgramTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function it_can_list_programs()
    {
        $count = 5;
        Program::factory()->count($count)->create();

        $this->sanctumActingAsManager();

        $this->get(route('api.v1.programs.index'))
            ->assertOk()
            ->assertJsonCount($count, 'data');
    }

    /** @test */
    public function developer_can_see_only_their_own_programs()
    {
        $program = Program::factory()->create();

        $developer = $this->sanctumActingAsDeveloper();
        $program->developers()->attach($developer);

        Program::factory()->count(2)->create();

        $this->get(route('api.v1.programs.index'))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $program->id]);
    }

    /** @test */
    public function it_shows_a_program()
    {
        $program = Program::factory()->create()->load(['tags', 'modules']);

        $this->sanctumActingAsManager();

        $this->get(route('api.v1.programs.show', ['program' => $program->id]))
            ->assertOk()
            ->assertJsonFragment([
                'data' => $program->toArray()
            ]);
    }

    /** @test */
    public function it_not_shows_a_developer_not_owned_program()
    {
        $program = Program::factory()->create();

        $this->sanctumActingAsDeveloper();

        $this->get(route('api.v1.programs.show', ['program' => $program->id]))
            ->assertNotFound();
    }

    /** @test */
    public function developer_can_see_all_program_developers()
    {
        $program = Program::factory()->create();
        $developer = $this->sanctumActingAsDeveloper();
        $anotherDev = $this->newUser(roles: ['developer']);
        $program->developers()->sync([$developer->id, $anotherDev->id]);

        $this->get(route('api.v1.programs.show', ['program' => $program->id]))
            ->assertOk()
            ->assertJsonCount(2, 'data.developers');
    }

    /** @test */
    public function it_creates_a_new_program()
    {
        $data = Program::factory()->make()->toArray();

        $data['tags'] = $this->faker->state();


        $this->sanctumActingAsManager();

        $this->assertDatabaseCount('programs', 0);

        $this->post(route('api.v1.programs.store'), $data)
            ->assertCreated();

        $this->assertDatabaseCount('programs', 1);
    }

    /** @test */
    public function it_updates_a_program()
    {
        $program = Program::factory()->create();
        $data = ['title' => 'Title changed'];
        $expectation = collect($program->toArray())->merge($data)->all();

        $this->sanctumActingAsManager();

        $this->patch(route(
            'api.v1.programs.update',
            ['program' => $program->id]
        ), $data)
            ->assertOk()
            ->assertJsonFragment($expectation);
    }

    /** @test */
    public function it_soft_deletes_a_program()
    {
        $program = Program::factory()->create();

        $this->sanctumActingAsManager();

        $this->delete(route(
            'api.v1.programs.destroy',
            ['program' => $program->id]
        ))
            ->assertOk();

        $this->assertSoftDeleted('programs', $program->toArray());
    }

    /**
     * @test
     * @dataProvider crudAuthProvider
     */
    public function program_crud_has_right_authorization(
        $role,
        $route,
        $routeParams,
        $method,
        $data,
        $expectedStatus
    ) {
        if ($routeParams['program'] ?? false) {
            Program::factory()->create(['id' => 1]);
        }

        if ($role) {
            $this->sanctumActingAs([$role]);
        }

        $this->$method(route("api.v1.programs.$route", $routeParams), $data)
            ->assertStatus($expectedStatus);
    }

    protected function crudAuthProvider()
    {
        return [
            'guest_index' => [null, 'index', [], 'get', [], 401],
            'manager_index' => ['manager', 'index', [], 'get', [], 200],
            'developer_index' => ['developer', 'index', [], 'get', [], 200],
            'trainer_index' => ['trainer', 'index', [], 'get', [], 403],
            'trainee_index' => ['trainee', 'index', [], 'get', [], 403],
            'guest_show' => [null, 'show', ['program' => 1], 'get', [], 401],
            'manager_show' => ['manager', 'show', ['program' => 1], 'get', [], 200],
            'developer_show' => ['developer', 'show', ['program' => 1], 'get', [], 404],
            'trainer_show' => ['trainer', 'show', ['program' => 1], 'get', [], 403],
            'trainee_show' => ['trainee', 'show', ['program' => 1], 'get', [], 403],
            'guest_store' => [null, 'store', [], 'post', [], 401],
            'manager_store' => ['manager', 'store', [], 'post', [], 422],
            'developer_store' => ['developer', 'store', [], 'post', [], 403],
            'trainer_store' => ['trainer', 'store', [], 'post', [], 403],
            'trainee_store' => ['trainee', 'store', [], 'post', [], 403],
            'guest_update' => [null, 'update', ['program' => 1], 'patch', [], 401],
            'manager_update' => ['manager', 'update', ['program' => 1], 'patch', [], 200],
            'developer_update' => ['developer', 'update', ['program' => 1], 'patch', [], 403],
            'trainer_update' => ['trainer', 'update', ['program' => 1], 'patch', [], 403],
            'trainee_update' => ['trainee', 'update', ['program' => 1], 'patch', [], 403],
            'guest_destroy' => [null, 'destroy', ['program' => 1], 'delete', [], 401],
            'manager_destroy' => ['manager', 'destroy', ['program' => 1], 'delete', [], 200],
            'developer_destroy' => ['developer', 'destroy', ['program' => 1], 'delete', [], 403],
            'trainer_destroy' => ['trainer', 'destroy', ['program' => 1], 'delete', [], 403],
            'trainee_destroy' => ['trainee', 'destroy', ['program' => 1], 'delete', [], 403],
        ];
    }
}
