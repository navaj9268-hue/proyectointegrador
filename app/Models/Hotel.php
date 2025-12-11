<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'description',
    ];

    // Relaciones
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
