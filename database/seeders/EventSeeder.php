<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        for ($i = 0; $i < 10; $i++) {       
            $saleStart = Carbon::now()->addDays(fake()->numberBetween(1, 10));
            $saleEnd = (clone $saleStart)->addDays(fake()->numberBetween(5, 15));
            $eventDate = (clone $saleEnd)->addDays(fake()->numberBetween(1, 7));

            Event::create([
                'title' => fake()->sentence(3),
                'description' => fake()->paragraph(2),
                
                'event_date_at' => $eventDate,
                'sale_start_at' => $saleStart,
                'sale_end_at' => $saleEnd,
                
                'is_dynamic_price' => fake()->boolean(25),
                'max_number_allowed' => fake()->numberBetween(3, 10),
                'cover_image_path' => null,
            ]);
        }
    }
}
