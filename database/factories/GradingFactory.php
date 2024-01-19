<?php

namespace Database\Factories;

use App\Models\EvaluationCriteria;
use App\Models\Grading;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grading>
 */
class GradingFactory extends Factory
{

    protected $model = Grading::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $gradableClass = collect([
            Question::class,
            EvaluationCriteria::class
        ])->random();

        return [
            'gradable_id' => $gradableClass::factory(),
            'gradable_type' => $gradableClass,
            'comments' => fake()->text($maxNbChars = 20),
            'grade' => fake()->randomFloat(1, 1, 3),
        ];
    }

}
