<?php

namespace Database\Factories;

use App\Models\Habitacion;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reserva>
 */
class ReservaFactory extends Factory
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
            'status' => fake()->randomElement(['pendiente', 'confirmada', 'cancelada']),
            'total' => fake()->randomFloat(2, 200, 2000),
            'checkin' => $checkin,
            'checkout' => $checkout,
        ];
    }
}