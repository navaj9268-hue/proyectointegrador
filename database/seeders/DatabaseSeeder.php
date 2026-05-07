<?php

namespace Database\Seeders;

use App\Models\Habitacion;
use App\Models\Hotel;
use App\Models\Huesped;
use App\Models\Inventario;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\Reservacion;
use App\Models\Usuario;
use App\Models\Vehiculo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear un usuario administrador
        Usuario::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $usuarios = Usuario::factory(149)->create();
        $hoteles = Hotel::factory(150)->create();
        $habitaciones = Habitacion::factory()->count(150)->make()->each(function ($habitacion) use ($hoteles) {
            $habitacion->hotel_id = $hoteles->random()->id;
            $habitacion->save();
        });
        $huespedes = Huesped::factory(150)->create();
        Inventario::factory()->count(150)->make()->each(function ($inventario) use ($hoteles) {
            $inventario->hotel_id = $hoteles->random()->id;
            $inventario->save();
        });
        $reservas = Reserva::factory()->count(150)->make()->each(function ($reserva) use ($usuarios, $habitaciones) {
            $reserva->user_id = $usuarios->random()->id;
            $reserva->room_id = $habitaciones->random()->id;
            $reserva->save();
        });
        $reservaciones = Reservacion::factory()->count(150)->make()->each(function ($reservacion) use ($hoteles, $habitaciones, $huespedes) {
            $reservacion->hotel_id = $hoteles->random()->id;
            $reservacion->room_id = $habitaciones->random()->id;
            $reservacion->guest_id = $huespedes->random()->id;
            $reservacion->save();
        });
        Pago::factory()->count(150)->make()->each(function ($pago) use ($reservaciones, $usuarios) {
            $pago->reservation_id = $reservaciones->random()->id;
            $pago->user_id = $usuarios->random()->id;
            $pago->save();
        });
        Vehiculo::factory()->count(150)->make()->each(function ($vehiculo) use ($reservaciones) {
            $vehiculo->reservation_id = $reservaciones->random()->id;
            $vehiculo->save();
        });
    }
}
