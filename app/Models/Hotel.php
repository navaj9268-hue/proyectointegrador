<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotel extends Model
{
    use HasFactory;

    protected $table = 'hoteles';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'descripcion',
        'name',
        'address',
        'phone',
        'description',
    ];

    // Relaciones
    public function rooms()
    {
        return $this->hasMany(Habitacion::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventario::class);
    }

    public function getNameAttribute()
    {
        return $this->attributes['nombre'] ?? null;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['nombre'] = $value;
    }

    public function getAddressAttribute()
    {
        return $this->attributes['direccion'] ?? null;
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['direccion'] = $value;
    }

    public function getPhoneAttribute()
    {
        return $this->attributes['telefono'] ?? null;
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['telefono'] = $value;
    }

    public function getDescriptionAttribute()
    {
        return $this->attributes['descripcion'] ?? null;
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['descripcion'] = $value;
    }
}