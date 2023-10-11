<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::query()->create(['name' => 'manager'])->abilities()
            ->sync([
                Ability::create(['name' => 'manage_executions'])->id,
                Ability::create(['name' => 'manage_user_accounts'])->id,
                Ability::create(['name' => 'manage_programs'])->id,
            ]);

        Role::query()->create(['name' => 'developer'])->abilities()
            ->sync([
                Ability::create(['name' => 'add_program_content'])->id,
            ]);

        Role::query()->create(['name' => 'trainer'])->abilities()
            ->sync([
                Ability::create(['name' => 'see_program_content_details'])->id,
                Ability::create(['name' => 'take_homework'])->id,
                Ability::create(['name' => 'take_quiz'])->id,
            ]);

        Role::query()->create(['name' => 'trainee'])->abilities()
            ->sync([
                Ability::create(['name' => 'see_program_content'])->id,
                Ability::create(['name' => 'see_grading_content'])->id,
            ]);
    }
}
