<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'checkin',
        'checkout',
        'status',
        'total',
    ];

    protected $casts = [
        'checkin'  => 'date',
        'checkout' => 'date',
        'total'    => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
