<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Huesped extends Model
{
    use HasFactory;

    protected $table = 'huespedes';

    protected $fillable = ['nombre','email','telefono','numero_documento','name','phone','document_number'];

    public function reservations()
    {
        return $this->hasMany(Reservacion::class);
    }

    public function getNameAttribute()
    {
        return $this->attributes['nombre'] ?? null;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['nombre'] = $value;
    }

    public function getPhoneAttribute()
    {
        return $this->attributes['telefono'] ?? null;
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['telefono'] = $value;
    }

    public function getDocumentNumberAttribute()
    {
        return $this->attributes['numero_documento'] ?? null;
    }

    public function setDocumentNumberAttribute($value)
    {
        $this->attributes['numero_documento'] = $value;
    }
}
