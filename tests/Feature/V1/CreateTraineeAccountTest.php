<?php

namespace Tests\Feature\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTraineeAccountTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }


    /** @test */
    public function it_creates_a_new_trainee_user()
    {
        $data = User::factory()->make()->toArray();

        $this->sanctumActingAsManager();

        $this->assertDatabaseMissing('users', $data);

        $this->post(
            route('api.v1.users.createTraineeAccount'),
            $data
        )
            ->assertCreated()
            ->assertJsonFragment($data)
            ->assertJsonFragment(['roles' => [
                ['name' => 'trainee']
            ]]);

        $this->assertDatabaseHas('users', $data);
    }

    /**
     * @test
     * @dataProvider endPointProvider
     */
    public function create_trainee_account_has_right_authorization(
        $role,
        $expectedStatus
    ) {
        if ($role) {
            $this->sanctumActingAs([$role]);
        }

        $this->post(route('api.v1.users.createTraineeAccount'))
            ->assertStatus($expectedStatus);
    }

    protected function endPointProvider()
    {
        return [
            'guest' => [null, 401],
            'manager' => ['manager', 422],
            'developer' => ['developer', 403],
            'trainer' => ['trainer', 403],
            'trainee' => ['trainee', 403],
        ];
    }
}
