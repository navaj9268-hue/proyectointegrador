<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Habitacion>
 */
class HabitacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero' => fake()->numberBetween(100, 999),
            'tipo' => fake()->randomElement(['individual', 'doble', 'suite']),
            'precio' => fake()->randomFloat(2, 50, 500),
            'status' => fake()->randomElement(['disponible', 'ocupada', 'mantenimiento']),
            'notas' => fake('es_ES')->sentence(),
            'number' => fake()->numberBetween(100, 999),
            'type' => fake()->randomElement(['individual', 'doble', 'suite']),
            'price' => fake()->randomFloat(2, 50, 500),
            'notes' => fake('es_ES')->sentence(),
        ];
    }
}