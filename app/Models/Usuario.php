<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UsuarioFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'rfc_id',
        'phone',
        'address',
        'client_code',
        'status',
        'category',
        'credit_limit',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'credit_limit' => 'decimal:2',
        ];
    }

    public static function statuses(): array
    {
        return [
            'active' => 'Activo',
            'inactive' => 'Inactivo',
            'blacklist' => 'Lista negra',
        ];
    }

    public static function categories(): array
    {
        return [
            'minorista' => 'Minorista',
            'mayorista' => 'Mayorista',
            'vip' => 'VIP',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCliente()
    {
        return $this->role === 'cliente';
    }
}
