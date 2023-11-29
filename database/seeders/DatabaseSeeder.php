<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Conference;
use App\Models\Talk;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $user = User::all()->first();
        if (empty($user)) {
            User::factory()->create([
                'name' => 'chang180',
                'email' => 'chang180@gmail.com',
                'password' => bcrypt('password'),
            ]);
        }

        Conference::factory(10)->create();
        Venue::factory(10)->create();
        Talk::factory(10)->create();
    }
}
