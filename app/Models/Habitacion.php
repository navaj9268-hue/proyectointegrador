<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Habitacion extends Model
{
    use HasFactory;

    protected $table = 'habitaciones';

    protected $fillable = [
        'hotel_id',
        'numero',
        'tipo',
        'precio',
        'status',
        'notas',
        'number',
        'type',
        'price',
        'notes',
    ];

    // Casts
    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings()
    {
        return $this->hasMany(Reserva::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservacion::class, 'room_id');
    }

    public function getNumberAttribute()
    {
        return $this->attributes['numero'] ?? null;
    }

    public function setNumberAttribute($value)
    {
        $this->attributes['numero'] = $value;
    }

    public function getTypeAttribute()
    {
        return $this->attributes['tipo'] ?? null;
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['tipo'] = $value;
    }

    public function getPriceAttribute()
    {
        return $this->attributes['precio'] ?? null;
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['precio'] = $value;
    }

    public function getNotesAttribute()
    {
        return $this->attributes['notas'] ?? null;
    }

    public function setNotesAttribute($value)
    {
        $this->attributes['notas'] = $value;
    }
}
