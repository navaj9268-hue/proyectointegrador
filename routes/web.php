<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaymentController;



Route::get('/', fn() => redirect()->route('home'));

/*
 * Auth
 */
Route::get('register', [AuthController::class,'showRegister'])->name('register');
Route::post('register', [AuthController::class,'register']);
Route::get('login', [AuthController::class,'showLogin'])->name('login');
Route::post('login', [AuthController::class,'login']);
Route::post('logout', [AuthController::class,'logout'])->name('logout');

/*
 * Protected routes (auth)
 */
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class,'index'])->name('home');

    // Recursos principales
    Route::resource('rooms', RoomController::class);
    Route::resource('inventories', InventoryController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);

    // Reportes PDF
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('rooms', [ReportController::class,'roomsPdf'])->name('rooms');
        Route::get('inventories', [ReportController::class,'inventoriesPdf'])->name('inventories');
        Route::get('users', [ReportController::class,'usersPdf'])->name('users');
        Route::get('general', [ReportController::class,'generalPdf'])->name('general');
    });

    // Calendario / Reservas (endpoints AJAX + vista)
    Route::get('reservations/calendar', [ReservationController::class, 'calendar'])->name('reservations.calendar');
    Route::get('reservations/events', [ReservationController::class, 'events'])->name('reservations.events');
    Route::post('reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::put('reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    // Pagos
    Route::resource('payments', PaymentController::class);
});

// Rutas para crear y verificar disponibilidad de reservas
Route::get('reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
Route::get('reservations/available', [ReservationController::class, 'available'])->name('reservations.available');
Route::get('{reservation}', [ReservationController::class,'show'])->name('show');


// dentro del group auth
Route::get('reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');