<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Seat;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date_at',
        'sale_start_at',
        'sale_end_at',
        'is_dynamic_price',
        'max_number_allowed',
        'cover_image_path',
    ];

    protected $casts = [
        'event_date_at' => 'datetime',
        'sale_start_at' => 'datetime',
        'sale_end_at' => 'datetime',
        'is_dynamic_price' => 'boolean',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function calculatePrice(Seat $seat, array $priceData): float
    {
        if (!$this->is_dynamic_price) {
            return $seat->base_price;
        }
        
        $basePrice = $seat->base_price;
        $daysUntil = max(0, $priceData['daysUntil']); //ne legyen negatív
        $occupancy = $priceData['occupancy'];

        $multiplier = 1 + (0.5 * (1 - $daysUntil / 100)) + (0.5 * $occupancy);

        $finalPrice = $basePrice * $multiplier;

        return round($finalPrice, 2);
    }
}
