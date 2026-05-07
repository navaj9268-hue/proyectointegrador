<?php

namespace Database\Factories;

use App\Models\Habitacion;
use App\Models\Hotel;
use App\Models\Huesped;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservacion>
 */
class ReservacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkin = fake()->dateTimeBetween('now', '+1 month');
        $checkoutEnd = (clone $checkin)->modify('+1 week');
        $checkout = fake()->dateTimeBetween($checkin, $checkoutEnd);
        return [
            'fecha_entrada' => $checkin,
            'fecha_salida' => $checkout,
            'total' => fake()->randomFloat(2, 200, 2000),
            'status' => fake()->randomElement(['pendiente', 'confirmada', 'cancelada']),
            'notas' => fake('es_ES')->sentence(),
            'checkin_at' => $checkin,
            'checkout_at' => $checkout,
            'notes' => fake('es_ES')->sentence(),
        ];
    }
}