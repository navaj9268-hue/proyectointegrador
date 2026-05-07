<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake('es_ES')->company(),
            'direccion' => fake('es_ES')->address(),
            'telefono' => fake('es_ES')->phoneNumber(),
            'email' => fake('es_ES')->unique()->safeEmail(),
            'descripcion' => fake('es_ES')->paragraph(),
            'name' => fake('es_ES')->company(),
            'address' => fake('es_ES')->address(),
            'phone' => fake('es_ES')->phoneNumber(),
            'description' => fake('es_ES')->paragraph(),
        ];
    }
}