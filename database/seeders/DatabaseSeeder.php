<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'gyuluska@example.com',
            'password' => bcrypt('password'),
            'admin' => false,
        ]);

        User::factory(5)->create();

        $this->call([
            SeatSeeder::class,
            EventSeeder::class,
            TicketSeeder::class,
        ]);  
    }
}
