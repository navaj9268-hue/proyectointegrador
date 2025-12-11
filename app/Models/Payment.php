<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'reservation_id',
        'amount',
        'method',
        'transaction_id',
        'payer_name',
        'notes',
        'user_id',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
