<?php

namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
class TopicFactory extends Factory
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
            'index' => array_shift($index),
            'title' => fake()->sentence(),
            'description' => fake()->text(),
            'content' => fake()->paragraphs(asText:true),
            'module_id' => Module::factory(),
        ];
    }
}
