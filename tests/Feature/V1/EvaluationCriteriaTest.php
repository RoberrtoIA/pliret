<?php

namespace Tests\Feature\V1;

use App\Models\EvaluationCriteria;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EvaluationCriteriaTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function it_can_list_evaluation_criterias()
    {
        $evaluation_criterias = EvaluationCriteria::factory(3)->create()
            ->toArray();

        $this->sanctumActingAsDeveloper();

        $this->get(route('api.v1.evaluations.index'))
            ->assertOk()
            ->assertJsonFragment(['data' => $evaluation_criterias]);
    }

    /** @test */
    public function it_shows_a_evaluation_criteria()
    {
        $evaluation = EvaluationCriteria::factory()->create()->load('module');
        $evaluation->module->makeHidden('deleted_at');

        $this->sanctumActingAsDeveloper();

        $this->get(route('api.v1.evaluations.show', ['evaluation' => $evaluation->id]))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $evaluation->id,
                'created_at' => $evaluation->created_at,
                'objetive' => $evaluation->objetive
            ]);
    }

    /** @test */
    public function it_creates_a_new_evaluation_criteria()
    {
        $data = EvaluationCriteria::factory()->make()->toArray();

        $this->sanctumActingAsDeveloper();

        $this->assertDatabaseCount('evaluation_criterias', 0);

        $this->post(route('api.v1.evaluations.store'), $data)
            ->assertCreated();

        $this->assertDatabaseCount('evaluation_criterias', 1);
    }

    /** @test */
    public function it_updates_a_evaluation_criteria()
    {
        $evaluation = EvaluationCriteria::factory()->create()->makeHidden('module_id');
        $data = ['objetive' => 'changed'];
        $expectation = collect($evaluation->toArray())->merge($data)->all();

        $this->sanctumActingAsDeveloper();

        $this->patch(route(
            'api.v1.evaluations.update',
            ['evaluation' => $evaluation->id]
        ), $data)
            ->assertOk()
            ->assertJsonFragment($expectation);
    }

    /** @test */
    public function it_soft_deletes_a_evaluation_criteria()
    {
        $evaluation = EvaluationCriteria::factory()->create();

        $this->sanctumActingAsDeveloper();

        $this->delete(route(
            'api.v1.evaluations.destroy',
            ['evaluation' => $evaluation->id]
        ))
            ->assertOk();

        $this->assertSoftDeleted('evaluation_criterias', $evaluation->toArray());
    }
}
