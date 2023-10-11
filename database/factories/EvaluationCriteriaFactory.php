<?php

namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EvaluationCriteria>
 */
class EvaluationCriteriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'objetive' => fake()->sentence(),
            'grade_definitions' => fake()->randomLetter(),
            'module_id' => Module::factory(),
        ];
    }
}
