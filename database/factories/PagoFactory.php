<?php

namespace Database\Factories;

use App\Models\Reservacion;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pago>
 */
class PagoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'monto' => fake()->randomFloat(2, 100, 1000),
            'metodo' => fake()->randomElement(['efectivo', 'tarjeta', 'transferencia']),
            'id_transaccion' => fake()->uuid(),
            'nombre_pagador' => fake('es_ES')->name(),
            'notas' => fake('es_ES')->sentence(),
        ];
    }
}