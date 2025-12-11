<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'item',
        'quantity',
        'location',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
