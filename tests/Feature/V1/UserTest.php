<?php

namespace Tests\Feature\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function it_can_list_users()
    {
        $this->sanctumActingAsManager();
        $count = User::count();

        $this->get(route('api.v1.users.index'))
            ->assertOk()
            ->assertJsonCount($count, 'data');

        $this->newUser(roles:['trainee']);

        $this->get(route('api.v1.users.index'))
            ->assertJsonCount(++$count, 'data');
    }

    /** @test */
    public function it_shows_an_user()
    {
        $me = $this->sanctumActingAsManager();

        $this->get(route('api.v1.users.show', $me->id))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $me->id,
                'email' => $me->email,
            ]);
    }

    /** @test */
    public function it_updates_an_user()
    {
        $user = $this->newUser(roles:['trainee']);
        $data = [
            'name' => 'edit user name',
            'roles' => ['developer']
        ];

        $this->sanctumActingAsManager();

        $this->patch(route('api.v1.users.update', $user->id), $data)
            ->assertOk()
            ->assertJsonFragment([
                'id' => $user->id,
                'email' => $user->email,
                'name' => $data['name'],
                'roles' => [
                    ['name' => 'developer']
                ],
            ]);
    }

    /** @test */
    public function it_soft_deletes_an_user()
    {
        $user = $this->newUser(roles:['trainee', 'deadUser']);

        $this->sanctumActingAsManager();

        $this->delete(route(
            'api.v1.users.destroy',
            ['user' => $user->id]
        ))
            ->assertOk();

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    /**
     * @test
     * @dataProvider crudAuthProvider
     */
    public function user_crud_has_right_authorization(
        $role,
        $route,
        $routeParams,
        $method,
        $data,
        $expectedStatus
    ) {
        if ($routeParams) {
            $this->newUser(['id' => 1], ['trainee']);
        }

        if ($role) {
            $this->sanctumActingAs([$role]);
        }

        $this->$method(route("api.v1.users.$route", $routeParams), $data)
            ->assertStatus($expectedStatus);
    }

    protected function crudAuthProvider()
    {
        return [
            'guest_index' => [null, 'index', [], 'get', [], 401],
            'manager_index' => ['manager', 'index', [], 'get', [], 200],
            'developer_index' => ['developer', 'index', [], 'get', [], 403],
            'trainer_index' => ['trainer', 'index', [], 'get', [], 403],
            'trainee_index' => ['trainee', 'index', [], 'get', [], 403],
            'guest_show' => [null, 'show', ['user' => 1], 'get', [], 401],
            'manager_show' => ['manager', 'show', ['user' => 1], 'get', [], 200],
            'developer_show' => ['developer', 'show', ['user' => 1], 'get', [], 403],
            'trainer_show' => ['trainer', 'show', ['user' => 1], 'get', [], 403],
            'trainee_show' => ['trainee', 'show', ['user' => 1], 'get', [], 403],
            'guest_update' => [null, 'update', ['user' => 1], 'patch', [], 401],
            'manager_update' => ['manager', 'update', ['user' => 1], 'patch', [], 200],
            'developer_update' => ['developer', 'update', ['user' => 1], 'patch', [], 403],
            'trainer_update' => ['trainer', 'update', ['user' => 1], 'patch', [], 403],
            'trainee_update' => ['trainee', 'update', ['user' => 1], 'patch', [], 403],
            'guest_destroy' => [null, 'destroy', ['user' => 1], 'delete', [], 401],
            'manager_destroy' => ['manager', 'destroy', ['user' => 1], 'delete', [], 200],
            'developer_destroy' => ['developer', 'destroy', ['user' => 1], 'delete', [], 403],
            'trainer_destroy' => ['trainer', 'destroy', ['user' => 1], 'delete', [], 403],
            'trainee_destroy' => ['trainee', 'destroy', ['user' => 1], 'delete', [], 403],
        ];
    }

}
