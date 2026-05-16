<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'tipo',
        'status',
        'fecha_entrada',
        'fecha_salida',
        'lugar_estacionamiento',
        'tarifa_por_hora',
        'total_cobrado',
        'notas',
    ];

    protected $casts = [
        'fecha_entrada'   => 'datetime',
        'fecha_salida'    => 'datetime',
        'tarifa_por_hora' => 'decimal:2',
        'total_cobrado'   => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    const STATUS_ESTACIONADO = 'estacionado';
    const STATUS_SALIDA      = 'salida';

    public static function statuses(): array
    {
        return [
            self::STATUS_ESTACIONADO => 'Estacionado',
            self::STATUS_SALIDA      => 'Salida',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | TIPOS DE VEHÍCULO
    |--------------------------------------------------------------------------
    */

    public static function tipos(): array
    {
        return [
            'auto'      => 'Automóvil',
            'moto'      => 'Motocicleta',
            'camioneta' => 'Camioneta',
            'bus'       => 'Autobús',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function reservation()
    {
        return $this->belongsTo(Reservacion::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // Vehículos estacionados actualmente
    public function scopeEstacionados($query)
    {
        return $query->where('status', self::STATUS_ESTACIONADO);
    }

    // Vehículos con salida registrada
    public function scopeSalidas($query)
    {
        return $query->where('status', self::STATUS_SALIDA);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS DE TIEMPO
    |--------------------------------------------------------------------------
    */

    // Horas de estancia
    public function horasEstancia(): int
    {
        $entrada = $this->fecha_entrada
            ? Carbon::parse($this->fecha_entrada)
            : now();

        $salida = $this->fecha_salida
            ? Carbon::parse($this->fecha_salida)
            : now();

        return (int) ceil(
            $entrada->diffInMinutes($salida) / 60
        );
    }

    // Tiempo legible
    public function tiempoEstancia(): string
    {
        $entrada = $this->fecha_entrada
            ? Carbon::parse($this->fecha_entrada)
            : now();

        $salida = $this->fecha_salida
            ? Carbon::parse($this->fecha_salida)
            : now();

        $minutos = (int) $entrada->diffInMinutes($salida);

        $horas = intdiv($minutos, 60);

        $mins = $minutos % 60;

        if ($horas === 0) {
            return "{$mins} min";
        }

        if ($mins === 0) {
            return "{$horas} h";
        }

        return "{$horas} h {$mins} min";
    }

    /*
    |--------------------------------------------------------------------------
    | COBRO
    |--------------------------------------------------------------------------
    */

    public function calcularTotal(): float
    {
        $horas = max(1, $this->horasEstancia());

        $tarifa = (float) ($this->tarifa_por_hora ?? 0);

        return round($horas * $tarifa, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS DE ESTADO
    |--------------------------------------------------------------------------
    */

    public function estaEstacionado(): bool
    {
        return $this->status === self::STATUS_ESTACIONADO;
    }

    public function yaSalio(): bool
    {
        return $this->status === self::STATUS_SALIDA;
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getLicensePlateAttribute()
    {
        return $this->placa;
    }

    public function getBrandAttribute()
    {
        return $this->marca;
    }

    public function getModelAttribute()
    {
        return $this->modelo;
    }

    public function getParkingSpotAttribute()
    {
        return $this->lugar_estacionamiento;
    }

    public function getNotesAttribute()
    {
        return $this->notas;
    }

    public function getEntryDateAttribute()
    {
        return $this->fecha_entrada;
    }

    public function getExitDateAttribute()
    {
        return $this->fecha_salida;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setLicensePlateAttribute($value)
    {
        $this->attributes['placa'] = strtoupper($value);
    }

    public function setBrandAttribute($value)
    {
        $this->attributes['marca'] = $value;
    }

    public function setModelAttribute($value)
    {
        $this->attributes['modelo'] = $value;
    }

    public function setParkingSpotAttribute($value)
    {
        $this->attributes['lugar_estacionamiento'] = $value;
    }

    public function setNotesAttribute($value)
    {
        $this->attributes['notas'] = $value;
    }

    public function setEntryDateAttribute($value)
    {
        $this->attributes['fecha_entrada'] = $value;
    }

    public function setExitDateAttribute($value)
    {
        $this->attributes['fecha_salida'] = $value;
    }
}