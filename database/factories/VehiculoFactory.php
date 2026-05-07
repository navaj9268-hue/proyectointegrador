<?php

namespace Database\Factories;

use App\Models\Reservacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehiculo>
 */
class VehiculoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'placa' => fake('es_ES')->regexify('[A-Z]{3}[0-9]{3}'),
            'marca' => fake()->randomElement(['Toyota', 'Honda', 'Ford', 'Chevrolet']),
            'modelo' => fake('es_ES')->word(),
            'color' => fake('es_ES')->colorName(),
            'status' => fake()->randomElement(['estacionado', 'salido']),
            'fecha_entrada' => fake()->dateTime(),
            'fecha_salida' => fake()->dateTime(),
            'lugar_estacionamiento' => fake('es_ES')->word(),
            'notas' => fake('es_ES')->sentence(),
        ];
    }
}