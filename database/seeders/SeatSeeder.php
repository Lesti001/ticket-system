<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seat;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['A', 'B'] as $row) {
            for ($i = 1; $i <= 10; $i++) {
                $seatNumber = $row . str_pad($i, 3, '0', STR_PAD_LEFT);

                Seat::create([
                    'seat_number' => $seatNumber,
                    'base_price' => fake()->numberBetween(5000, 10000)
                ]);
            }
        }
    }
}
