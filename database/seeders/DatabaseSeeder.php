<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'admin',
            'email' => config('app.admin_email'),
            'password' => config('app.admin_password')
        ])
            ->roles()
            ->attach(Role::where('name', 'manager')->first());
    }
}
