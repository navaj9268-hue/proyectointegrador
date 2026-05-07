<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'reservation_id',
        'monto',
        'metodo',
        'id_transaccion',
        'nombre_pagador',
        'notas',
        'user_id',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservacion::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\Usuario::class);
    }

    public function getAmountAttribute()
    {
        return $this->attributes['monto'] ?? null;
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['monto'] = $value;
    }

    public function getMethodAttribute()
    {
        return $this->attributes['metodo'] ?? null;
    }

    public function setMethodAttribute($value)
    {
        $this->attributes['metodo'] = $value;
    }

    public function getTransactionIdAttribute()
    {
        return $this->attributes['id_transaccion'] ?? null;
    }

    public function setTransactionIdAttribute($value)
    {
        $this->attributes['id_transaccion'] = $value;
    }

    public function getPayerNameAttribute()
    {
        return $this->attributes['nombre_pagador'] ?? null;
    }

    public function setPayerNameAttribute($value)
    {
        $this->attributes['nombre_pagador'] = $value;
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
