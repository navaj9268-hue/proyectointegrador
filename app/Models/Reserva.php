<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = [
        'user_id',
        'room_id',
        'fecha_entrada',
        'fecha_salida',
        'status',
        'total',
        'checkin',
        'checkout',
    ];

    protected $casts = [
        'fecha_entrada'  => 'date',
        'fecha_salida' => 'date',
        'total'    => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\Usuario::class);
    }

    public function room()
    {
        return $this->belongsTo(Habitacion::class);
    }

    public function getCheckinAttribute()
    {
        return $this->attributes['fecha_entrada'] ?? null;
    }

    public function setCheckinAttribute($value)
    {
        $this->attributes['fecha_entrada'] = $value;
    }

    public function getCheckoutAttribute()
    {
        return $this->attributes['fecha_salida'] ?? null;
    }

    public function setCheckoutAttribute($value)
    {
        $this->attributes['fecha_salida'] = $value;
    }
}
