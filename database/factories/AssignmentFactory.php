<?php

namespace Database\Factories;

use App\Models\Execution;
use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'execution_id' => Execution::factory(),
            'module_id' => Module::factory(),
            'user_id' => User::factory(),
            'interview_grade' => fake()->randomFloat(1, 1, 10),
            'interview_start_at' => null,
            'interview_finish_at' => null,
            'interview_snapshot' => null,
            'homework_grade' => fake()->randomFloat(1, 1, 10),
            'homework_start_at' => null,
            'homework_finish_at' => null,
            'homework_solution' => null,
            'homework_snapshot' => null,
        ];
    }
}
