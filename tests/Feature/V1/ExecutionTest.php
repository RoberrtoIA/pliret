<?php

namespace Tests\Feature\V1;

use App\Models\Assignment;
use App\Models\Execution;
use App\Models\Grading;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExecutionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }


    /** @test */
    public function it_can_list_executions()
    {
        $count = 3;
        Execution::factory()->count($count)->create();

        $this->sanctumActingAsManager();

        $this->get(route('api.v1.executions.index'))
            ->assertOk()
            ->assertJsonCount($count, 'data');
    }

    /** @test */
    public function trainer_can_see_only_their_own_executions()
    {
        $execution = Execution::factory()->create();

        $trainer = $this->sanctumActingAsTrainer();
        $execution->trainers()->attach($trainer);

        Execution::factory()->count(2)->create();

        $this->get(route('api.v1.executions.index'))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $execution->id]);
    }

    /** @test */
    public function trainee_can_see_only_their_own_executions()
    {
        $execution = Execution::factory()->create();

        $trainee = $this->sanctumActingAsTrainee();
        $execution->enrollments()->attach($trainee);

        Execution::factory()->count(2)->create();

        $this->get(route('api.v1.executions.index'))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $execution->id]);
    }

    /** @test */
    public function it_shows_an_execution()
    {
        $execution = Execution::factory()->create();

        $this->sanctumActingAsManager();

        $this->get(route(
            'api.v1.executions.show',
            ['execution' => $execution->id]
        ))
            ->assertOk()
            ->assertJsonFragment(['id' => $execution->id]);
    }

    /** @test */
    public function it_not_shows_a_trainer_not_owned_execution()
    {
        $execution = Execution::factory()->create();

        $this->sanctumActingAsTrainer();

        $this->get(route('api.v1.executions.show', ['execution' => $execution->id]))
            ->assertNotFound();
    }

    /** @test */
    public function it_not_shows_a_trainee_not_owned_execution()
    {
        $execution = Execution::factory()->create();

        $this->sanctumActingAsTrainee();

        $this->get(route('api.v1.executions.show', ['execution' => $execution->id]))
            ->assertNotFound();
    }

    /** @test */
    public function it_not_shows_a_trainee_finished_execution()
    {
        $execution = Execution::factory()->create([
            'finished' => now(),
        ]);

        $trainee = $this->sanctumActingAsTrainee();
        $execution->enrollments()->attach($trainee);

        $this->get(route('api.v1.executions.show', ['execution' => $execution->id]))
            ->assertNotFound();
    }

    /** @test */
    public function it_shows_trainee_score_for_execution()
    {
        $execution = Execution::factory()->create();

        $trainee = $this->sanctumActingAsTrainee();
        $execution->enrollments()->attach($trainee);

        $this->get(route('api.v1.executions.show', ['execution' => $execution->id]))
            ->assertOk()
            // score default value
            ->assertJsonFragment(['score' => 0]);
    }

    /** @test */
    public function trainer_can_see_execution_trainees_and_grades()
    {
        $execution = Execution::factory()->create();

        $trainer = $this->sanctumActingAsTrainer();
        $execution->trainers()->attach($trainer);
        $trainee = $this->newUser(roles: ['trainee']);
        $execution->enrollments()->attach($trainee);
        $execution->enrollments()->attach($this->newUser(roles: ['trainee']));
        $assignment = Assignment::factory()
            ->for($execution)
            ->for($trainee)
            ->create();
        Grading::factory()->count(3)
            ->for($assignment)
            ->create();

        $this->get(route('api.v1.executions.show', ['execution' => $execution->id]))
            ->assertOk()
            ->assertJsonCount(3, 'data.assignments.0.gradings')
            ->assertJsonCount(2, 'data.enrollments');
    }

    /** @test */
    public function it_creates_a_new_execution()
    {
        $data = collect(Execution::factory()->make()->toArray())
            ->only(['program_id', 'start_date', 'end_date'])
            ->all();

        $this->sanctumActingAsManager();

        $this->assertDatabaseMissing('executions', $data);

        $this->post(route('api.v1.executions.store'), $data)
            ->assertCreated()
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('executions', $data);
    }

    /** @test */
    public function it_updates_an_execution()
    {
        $now = CarbonImmutable::now();
        $execution = Execution::factory()->create([
            'start_date' => $now->format('Y-m-d'),
            'end_date' => $now->addDays(1)->format('Y-m-d'),
        ]);
        $data = [
            'start_date' => $now->addDays(2)->format('Y-m-d'),
            'end_date' => $now->addDays(3)->format('Y-m-d'),
        ];

        $this->sanctumActingAsManager();

        $this->assertDatabaseMissing('executions', $data);

        $this->patch(route(
            'api.v1.executions.update',
            ['execution' => $execution->id]
        ), $data)
            ->assertOk()
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('executions', $data);
    }

    /** @test */
    public function it_soft_deletes_an_execution()
    {
        $execution = Execution::factory()->create();

        $this->sanctumActingAsManager();

        $this->delete(route(
            'api.v1.executions.destroy',
            ['execution' => $execution->id]
        ))
            ->assertOk();

        $this->assertSoftDeleted('executions', ['id' => $execution->id]);
    }

    /**
     * @test
     * @dataProvider crudAuthProvider
     */
    public function execution_crud_has_right_authorization(
        $role,
        $route,
        $routeParams,
        $method,
        $data,
        $expectedStatus
    ) {
        if ($routeParams['execution'] ?? false) {
            Execution::factory()->create(['id' => 1]);
        }

        if ($role) {
            $this->sanctumActingAs([$role]);
        }

        $this->$method(route("api.v1.executions.$route", $routeParams), $data)
            ->assertStatus($expectedStatus);
    }

    protected function crudAuthProvider()
    {
        return [
            'guest_index' => [null, 'index', [], 'get', [], 401],
            'manager_index' => ['manager', 'index', [], 'get', [], 200],
            'developer_index' => ['developer', 'index', [], 'get', [], 403],
            'trainer_index' => ['trainer', 'index', [], 'get', [], 200],
            'trainee_index' => ['trainee', 'index', [], 'get', [], 200],
            'guest_show' => [null, 'show', ['execution' => 1], 'get', [], 401],
            'manager_show' => ['manager', 'show', ['execution' => 1], 'get', [], 200],
            'developer_show' => ['developer', 'show', ['execution' => 1], 'get', [], 403],
            'trainer_show' => ['trainer', 'show', ['execution' => 1], 'get', [], 404],
            'trainee_show' => ['trainee', 'show', ['execution' => 1], 'get', [], 404],
            'guest_store' => [null, 'store', [], 'post', [], 401],
            'manager_store' => ['manager', 'store', [], 'post', [], 422],
            'developer_store' => ['developer', 'store', [], 'post', [], 403],
            'trainer_store' => ['trainer', 'store', [], 'post', [], 403],
            'trainee_store' => ['trainee', 'store', [], 'post', [], 403],
            'guest_update' => [null, 'update', ['execution' => 1], 'patch', [], 401],
            'manager_update' => ['manager', 'update', ['execution' => 1], 'patch', [], 200],
            'developer_update' => ['developer', 'update', ['execution' => 1], 'patch', [], 403],
            'trainer_update' => ['trainer', 'update', ['execution' => 1], 'patch', [], 403],
            'trainee_update' => ['trainee', 'update', ['execution' => 1], 'patch', [], 403],
            'guest_destroy' => [null, 'destroy', ['execution' => 1], 'delete', [], 401],
            'manager_destroy' => ['manager', 'destroy', ['execution' => 1], 'delete', [], 200],
            'developer_destroy' => ['developer', 'destroy', ['execution' => 1], 'delete', [], 403],
            'trainer_destroy' => ['trainer', 'destroy', ['execution' => 1], 'delete', [], 403],
            'trainee_destroy' => ['trainee', 'destroy', ['execution' => 1], 'delete', [], 403],
        ];
    }
}
