<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = ['name','email','phone','document_number'];
    public function reservations(){ return $this->hasMany(Reservation::class); }
}
