<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Module>
 */
class ModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $index = range(1,200);
        shuffle($index);

        return [
            'title' => fake()->sentence(),
            'description' => fake()->text(),
            'content' => fake()->paragraphs(asText:true),
            'homework_content' => fake()->url(),
            'program_id' => Program::factory(),
        ];
    }
}
