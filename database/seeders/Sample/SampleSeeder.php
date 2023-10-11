<?php

namespace Database\Seeders\Sample;

use App\Models\EvaluationCriteria;
use App\Models\Module;
use App\Models\Program;
use App\Models\Question;
use App\Models\Role;
use App\Models\Topic;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Seeder;

class SampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DatabaseSeeder::class
        ]);

        User::factory()->create([
            'name' => 'developer',
            'email' => 'developer@example.com',
            'password' => 'password'
        ])
            ->roles()
            ->attach(Role::where('name', 'developer')->first());

        User::factory()->create([
            'name' => 'trainer',
            'email' => 'trainer@example.com',
            'password' => 'password'
        ])
            ->roles()
            ->attach(Role::where('name', 'trainer')->first());

        User::factory()->create([
            'name' => 'trainee',
            'email' => 'trainee@example.com',
            'password' => 'password'
        ])
            ->roles()
            ->attach(Role::where('name', 'trainee')->first());

        for ($i = 0; $i < 3; $i++) {
            $program = Program::factory()->create();

            $modules = Module::factory([
                'program_id' => $program->id
            ])->count(3)->create();

            foreach ($modules as $module) {
                $topics = Topic::factory([
                    'module_id' => $module->id
                ])->count(3)->create();

                $evaluations = EvaluationCriteria::factory([
                    'module_id' => $module->id
                ])->count(10)->create();

                foreach ($topics as $topic) {
                    $questions = Question::factory([
                        'topic_id' => $topic->id
                    ])->count(8)->create();
                }
            }
        }
    }
}
