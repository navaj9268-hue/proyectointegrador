<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ControladorHabitacion;
use App\Http\Controllers\ControladorInventario;
use App\Http\Controllers\ControladorUsuario;
use App\Http\Controllers\ControladorReporte;
use App\Http\Controllers\ControladorReservacion;
use App\Http\Controllers\ControladorPago;
use App\Http\Controllers\ControladorVehiculo;
use App\Http\Controllers\ControladorCatalogo;

Route::get('/', fn() => redirect()->route('inicio'));

/*
|--------------------------------------------------------------------------
| TÉRMINOS
|--------------------------------------------------------------------------
*/

Route::get(
    'terminos-y-condiciones',
    fn() => view('terminos-y-condiciones')
)->name('terminos-y-condiciones');

Route::get(
    'aceptar-terminos',
    fn() => redirect()
        ->route('register')
        ->with('terms_accepted', true)
)->name('aceptar-terminos');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get(
    'register',
    [AuthController::class,'showRegister']
)->name('register');

Route::post(
    'register',
    [AuthController::class,'register']
);

Route::get(
    'login',
    [AuthController::class,'showLogin']
)->name('login');

Route::post(
    'login',
    [AuthController::class,'login']
);

Route::post(
    'logout',
    [AuthController::class,'logout']
)->name('logout');

Route::get(
    'olvide-contraseña',
    [AuthController::class,'showForgotPassword']
)->name('password.request');

Route::post(
    'olvide-contraseña',
    [AuthController::class,'sendPasswordResetLink']
)->name('password.email');

Route::get(
    'reset-contraseña/{token}',
    [AuthController::class,'showResetForm']
)->name('password.reset');

Route::post(
    'reset-contraseña',
    [AuthController::class,'resetPassword']
)->name('password.update');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | INICIO
    |--------------------------------------------------------------------------
    */

    Route::get(
        'inicio',
        [HomeController::class,'index']
    )->name('inicio');

    /*
    |--------------------------------------------------------------------------
    | CATÁLOGO
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:cliente|admin')->group(function () {

        Route::get(
            'catalogo/habitaciones',
            [ControladorCatalogo::class, 'index']
        )->name('catalogo.index');

        Route::get(
            'catalogo/habitaciones/{room}',
            [ControladorCatalogo::class, 'show']
        )->name('catalogo.mostrar');

        Route::post(
            'catalogo/reservaciones',
            [ControladorCatalogo::class, 'storeReservation']
        )->name('catalogo.reservaciones.store');

    });

    /*
    |--------------------------------------------------------------------------
    | RESERVACIONES
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:cliente|admin')->group(function () {

        Route::get(
            'reservaciones/calendario',
            [ControladorReservacion::class, 'calendar']
        )->name('reservaciones.calendar');

        Route::get(
            'reservaciones/gestion',
            [ControladorReservacion::class, 'management']
        )->name('reservaciones.management');

        Route::get(
            'reservaciones/eventos',
            [ControladorReservacion::class, 'events']
        )->name('reservaciones.events');

        /*
        |--------------------------------------------------------------------------
        | HABITACIONES DISPONIBLES (debe ir antes del wildcard {reservation})
        |--------------------------------------------------------------------------
        */

        Route::get(
            'reservaciones/disponibles',
            [ControladorReservacion::class, 'available']
        )->name('reservaciones.disponible');

        Route::post(
            'reservaciones',
            [ControladorReservacion::class, 'store']
        )->name('reservaciones.store');

        Route::get(
            'reservaciones/{reservation}',
            [ControladorReservacion::class, 'show']
        )->name('reservaciones.mostrar');

        /*
        |--------------------------------------------------------------------------
        | EDITAR RESERVA
        |--------------------------------------------------------------------------
        */

        Route::get(
            'reservaciones/{reservation}/editar',
            [ControladorReservacion::class, 'edit']
        )->name('reservaciones.edit');

        Route::put(
            'reservaciones/{reservation}',
            [ControladorReservacion::class, 'update']
        )->name('reservaciones.update');

        Route::delete(
            'reservaciones/{reservation}',
            [ControladorReservacion::class, 'destroy']
        )->name('reservaciones.destroy');

    });

    /*
    |--------------------------------------------------------------------------
    | PAGOS
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:cliente|admin')->group(function () {

        Route::resource(
            'pagos',
            ControladorPago::class
        )->parameters([
            'pagos' => 'payment'
        ])->names([

            'index'   => 'pagos.index',
            'create'  => 'pagos.create',
            'store'   => 'pagos.store',
            'show'    => 'pagos.show',
            'edit'    => 'pagos.edit',
            'update'  => 'pagos.update',
            'destroy' => 'pagos.destroy',

        ]);

    });

    /*
    |--------------------------------------------------------------------------
    | SOLO ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | HABITACIONES
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'habitaciones',
            ControladorHabitacion::class
        )->parameters([
            'habitaciones' => 'room'
        ])->names([

            'index'   => 'habitaciones.index',
            'create'  => 'habitaciones.create',
            'store'   => 'habitaciones.store',
            'show'    => 'habitaciones.show',
            'edit'    => 'habitaciones.edit',
            'update'  => 'habitaciones.update',
            'destroy' => 'habitaciones.destroy',

        ]);

        /*
        |--------------------------------------------------------------------------
        | INVENTARIOS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'inventarios',
            ControladorInventario::class
        )->parameters([
            'inventarios' => 'inventory'
        ])->except([
            'show'
        ])->names([

            'index'   => 'inventarios.index',
            'create'  => 'inventarios.create',
            'store'   => 'inventarios.store',
            'edit'    => 'inventarios.edit',
            'update'  => 'inventarios.update',
            'destroy' => 'inventarios.destroy',

        ]);

        /*
        |--------------------------------------------------------------------------
        | PERFIL
        |--------------------------------------------------------------------------
        */

        Route::get(
            'perfil',
            [ControladorUsuario::class, 'profile']
        )->name('usuarios.profile');

        Route::put(
            'perfil',
            [ControladorUsuario::class, 'updateProfile']
        )->name('usuarios.profile.update');

        /*
        |--------------------------------------------------------------------------
        | USUARIOS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'usuarios',
            ControladorUsuario::class
        )->parameters([
            'usuarios' => 'user'
        ])->except([
            'show'
        ])->names([

            'index'   => 'usuarios.index',
            'create'  => 'usuarios.create',
            'store'   => 'usuarios.store',
            'edit'    => 'usuarios.edit',
            'update'  => 'usuarios.update',
            'destroy' => 'usuarios.destroy',

        ]);

        /*
        |--------------------------------------------------------------------------
        | VEHÍCULOS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'vehiculos',
            ControladorVehiculo::class
        )->parameters([
            'vehiculos' => 'vehicle'
        ])->names([

            'index'   => 'vehiculos.index',
            'create'  => 'vehiculos.create',
            'store'   => 'vehiculos.store',
            'show'    => 'vehiculos.show',
            'edit'    => 'vehiculos.edit',
            'update'  => 'vehiculos.update',
            'destroy' => 'vehiculos.destroy',

        ]);

        /*
        |--------------------------------------------------------------------------
        | REGISTRAR SALIDA
        |--------------------------------------------------------------------------
        */

        Route::put(
            'vehiculos/{vehicle}/registrar-salida',
            [ControladorVehiculo::class, 'registerExit']
        )->name('vehiculos.registrar-salida');

        /*
        |--------------------------------------------------------------------------
        | ESTACIONAMIENTO
        |--------------------------------------------------------------------------
        */

        Route::get(
            'vehiculos-estacionamiento',
            [ControladorVehiculo::class, 'estacionamiento']
        )->name('vehiculos.estacionamiento');

        /*
        |--------------------------------------------------------------------------
        | REPORTES
        |--------------------------------------------------------------------------
        */

        Route::prefix('reportes')
            ->name('reportes.')
            ->group(function () {

            Route::get(
                'habitaciones',
                [ControladorReporte::class,'roomsPdf']
            )->name('habitaciones');

            Route::get(
                'inventarios',
                [ControladorReporte::class,'inventoriesPdf']
            )->name('inventarios');

            Route::get(
                'usuarios',
                [ControladorReporte::class,'usersPdf']
            )->name('usuarios');

            Route::get(
                'general',
                [ControladorReporte::class,'generalPdf']
            )->name('general');

        });

    });

});