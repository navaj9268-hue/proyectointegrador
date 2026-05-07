<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';

    protected $fillable = [
        'reservation_id',
        'placa',
        'marca',
        'modelo',
        'color',
        'status',
        'fecha_entrada',
        'fecha_salida',
        'lugar_estacionamiento',
        'notas',
    ];

    protected $casts = [
        'fecha_entrada' => 'datetime',
        'fecha_salida' => 'datetime',
    ];

    /**
     * Relación con Reservation
     */
    public function reservation()
    {
        return $this->belongsTo(Reservacion::class);
    }

    public function getLicensePlateAttribute()
    {
        return $this->attributes['placa'] ?? null;
    }

    public function setLicensePlateAttribute($value)
    {
        $this->attributes['placa'] = $value;
    }

    public function getBrandAttribute()
    {
        return $this->attributes['marca'] ?? null;
    }

    public function setBrandAttribute($value)
    {
        $this->attributes['marca'] = $value;
    }

    public function getModelAttribute()
    {
        return $this->attributes['modelo'] ?? null;
    }

    public function setModelAttribute($value)
    {
        $this->attributes['modelo'] = $value;
    }

    public function getEntryDateAttribute()
    {
        return $this->attributes['fecha_entrada'] ?? null;
    }

    public function setEntryDateAttribute($value)
    {
        $this->attributes['fecha_entrada'] = $value;
    }

    public function getExitDateAttribute()
    {
        return $this->attributes['fecha_salida'] ?? null;
    }

    public function setExitDateAttribute($value)
    {
        $this->attributes['fecha_salida'] = $value;
    }

    public function getParkingSpotAttribute()
    {
        return $this->attributes['lugar_estacionamiento'] ?? null;
    }

    public function setParkingSpotAttribute($value)
    {
        $this->attributes['lugar_estacionamiento'] = $value;
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
