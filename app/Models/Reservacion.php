<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model {
    use HasFactory;

  protected $table = 'reservaciones';
  protected $fillable = ['hotel_id','room_id','guest_id','fecha_entrada','fecha_salida','total','status','notas','checkin_at','checkout_at','notes'];
  protected $casts = ['fecha_entrada' => 'date','fecha_salida' => 'date'];

  public function room(){ return $this->belongsTo(Habitacion::class); }
  public function guest(){ return $this->belongsTo(Huesped::class); }
  public function hotel(){ return $this->belongsTo(Hotel::class); }
  public function payments(){ return $this->hasMany(Pago::class, 'reservation_id'); }

  public function getCheckinAtAttribute()
  {
      return isset($this->attributes['fecha_entrada']) ? $this->asDateTime($this->attributes['fecha_entrada']) : null;
  }

  public function setCheckinAtAttribute($value)
  {
      $this->attributes['fecha_entrada'] = $value;
  }

  public function getCheckoutAtAttribute()
  {
      return isset($this->attributes['fecha_salida']) ? $this->asDateTime($this->attributes['fecha_salida']) : null;
  }

  public function setCheckoutAtAttribute($value)
  {
      $this->attributes['fecha_salida'] = $value;
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
