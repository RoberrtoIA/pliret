<?php

namespace Database\Factories;

use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Execution>
 */
class ExecutionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start_date = fake()->dateTimeThisMonth(
            (new Carbon())->endOfMonth()
        );
        $end_date = (new Carbon($start_date))
            ->addDays(fake()->numberBetween(80, 90));
        return [
            'program_id' => Program::factory(),
            'start_date' => $start_date->format('Y-m-d'),
            'end_date' => $end_date->format('Y-m-d'),
            'created_at' => $timestamp = fake()->dateTimeThisMonth(),
            'updated_at' => $timestamp,
        ];
    }
}
