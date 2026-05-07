<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Huesped>
 */
class HuespedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake('es_ES')->name(),
            'email' => fake('es_ES')->unique()->safeEmail(),
            'telefono' => fake('es_ES')->phoneNumber(),
            'numero_documento' => fake()->unique()->numberBetween(10000000, 99999999),
            'name' => fake('es_ES')->name(),
            'phone' => fake('es_ES')->phoneNumber(),
            'document_number' => fake()->unique()->numberBetween(10000000, 99999999),
        ];
    }
}