<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Messages\MailMessage;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [

        'name',

        'email',

        'password',

        'role',

        'permisos',

        'rfc_id',

        'phone',

        'address',

        'client_code',

        'status',

        'category',

        'credit_limit',

    ];

    protected $hidden = [

        'password',

        'remember_token',

    ];

    protected function casts(): array
    {
        return [

            'email_verified_at' => 'datetime',

            'password' => 'hashed',

            'credit_limit' => 'decimal:2',

            'permisos' => 'array',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public static function statuses(): array
    {
        return [

            'active' => 'Activo',

            'inactive' => 'Inactivo',

            'blacklist' => 'Lista negra',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CATEGORÍAS
    |--------------------------------------------------------------------------
    */

    public static function categories(): array
    {
        return [

            'minorista' => 'Minorista',

            'mayorista' => 'Mayorista',

            'vip' => 'VIP',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ROLES
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCliente(): bool
    {
        return $this->role === 'cliente';
    }

    /*
    |--------------------------------------------------------------------------
    | PERMISOS
    |--------------------------------------------------------------------------
    */

    public function tienePermiso(string $permiso): bool
    {
        if ($this->isAdmin()) {

            return true;
        }

        return in_array(
            $permiso,
            $this->permisos ?? []
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RECUPERAR CONTRASEÑA PERSONALIZADO
    |--------------------------------------------------------------------------
    */

    public function sendPasswordResetNotification($token)
    {
        $url = url(route(

            'password.reset',

            [

                'token' => $token,

                'email' => $this->email,

            ],

            false
        ));

        $this->notify(

            new class($url) extends \Illuminate\Notifications\Notification {

                protected $url;

                public function __construct($url)
                {
                    $this->url = $url;
                }

                public function via($notifiable)
                {
                    return ['mail'];
                }

                public function toMail($notifiable)
                {
                    return (new MailMessage)

                        ->subject(
                            '🔐 Recuperar contraseña - Hotel Muñoz'
                        )

                        ->greeting(
                            'Hola 👋'
                        )

                        ->line(
                            'Recibimos una solicitud para restablecer tu contraseña.'
                        )

                        ->action(
                            'Restablecer contraseña',
                            $this->url
                        )

                        ->line(
                            'Este enlace expirará en 60 minutos.'
                        )

                        ->line(
                            'Si no solicitaste este cambio, puedes ignorar este correo.'
                        )

                        ->salutation(
                            '🏨 Hotel Muñoz'
                        );
                }
            }
        );
    }
}