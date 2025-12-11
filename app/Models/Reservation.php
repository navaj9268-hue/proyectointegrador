<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model {
  protected $fillable = ['hotel_id','room_id','guest_id','checkin_at','checkout_at','total','status','notes'];
  protected $casts = ['checkin_at' => 'date','checkout_at' => 'date'];
  public function room(){ return $this->belongsTo(Room::class); }
  public function guest(){ return $this->belongsTo(Guest::class); }
  public function hotel(){ return $this->belongsTo(Hotel::class); }
}
