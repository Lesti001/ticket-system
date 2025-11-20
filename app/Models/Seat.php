<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'seat_number',
        'base_price',
    ];

    public function tickets(): HasMany
    {
        return $this->HasMany(Ticket::class);
    }
}
