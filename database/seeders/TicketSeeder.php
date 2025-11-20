<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\Seat;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::all();
        $users = User::all();
        $seats = Seat::all();

        if ($events->isEmpty() || $users->isEmpty() || $seats->isEmpty()) {
            return;
        }

        foreach ($events as $event) {            
            $numberOfTickets = fake()->numberBetween(1, $seats->count() / 3);
            $shuffledSeats = $seats->shuffle()->take($numberOfTickets);

            foreach ($shuffledSeats as $seat) {            
                $user = $users->random();

                $purchaseTime = fake()->dateTimeBetween(
                    $event->sale_start_at,
                    $event->sale_end_at
                );

                Ticket::create([
                    'barcode' => fake()->unique()->numerify('#########'),
                    'admission_time' => null,
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'seat_id' => $seat->id,
                    'price' => $seat->base_price,
                    
                    'created_at' => $purchaseTime,
                    'updated_at' => $purchaseTime,
                ]);
            }
        }
    }
}
