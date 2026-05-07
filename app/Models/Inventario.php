<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventarios';

    protected $fillable = [
        'hotel_id',
        'articulo',
        'cantidad',
        'ubicacion',
        'notas',
        'item',
        'quantity',
        'location',
        'notes',
    ];

    protected $casts = [
        'cantidad' => 'integer',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function getItemAttribute()
    {
        return $this->attributes['articulo'] ?? null;
    }

    public function setItemAttribute($value)
    {
        $this->attributes['articulo'] = $value;
    }

    public function getQuantityAttribute()
    {
        return $this->attributes['cantidad'] ?? null;
    }

    public function setQuantityAttribute($value)
    {
        $this->attributes['cantidad'] = $value;
    }

    public function getLocationAttribute()
    {
        return $this->attributes['ubicacion'] ?? null;
    }

    public function setLocationAttribute($value)
    {
        $this->attributes['ubicacion'] = $value;
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
