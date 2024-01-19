<?php

namespace Tests\Feature\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateEmployeeAccountTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }


    /**
     * @test
     * @dataProvider employeeRoleProvider
     */
    public function it_creates_any_employee_role_user($role)
    {
        $userData = User::factory()->make()->toArray();
        $postData = array_merge($userData, ['roles' => [$role]]);

        $this->sanctumActingAsManager();

        $this->assertDatabaseMissing('users', $userData);

        $this->post(
            route('api.v1.users.createEmployeeAccount'),
            $postData
        )
            ->assertCreated()
            ->assertJsonFragment($userData)
            ->assertJsonFragment(['roles' => [
                ['name' => $role]
            ]]);

        $this->assertDatabaseHas('users', $userData);
    }

    /**
     * @test
     * @dataProvider roleAuthProvider
     */
    public function create_employee_account_has_right_authorization(
        $role,
        $expectedStatus
    ) {
        if ($role)
        $this->sanctumActingAs([$role]);

        $this->post(route('api.v1.users.createEmployeeAccount'))
            ->assertStatus($expectedStatus);
    }

    protected function employeeRoleProvider()
    {
        return [
            'manager' => ['manager'],
            'developer' => ['developer'],
            'trainer' => ['trainer'],
        ];
    }

    protected function roleAuthProvider()
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
