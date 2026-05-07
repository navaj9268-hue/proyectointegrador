<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventario>
 */
class InventarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productos = [
            'jabón', 'champú', 'gel de baño', 'toallas', 'sábanas', 'almohadas', 'colchón', 'edredón',
            'productos de limpieza', 'detergente', 'desinfectante', 'limpiador multiusos', 'papel higiénico',
            'servilletas', 'platos', 'vasos', 'cubiertos', 'cafetera', 'microondas', 'nevera',
            'televisor', 'aire acondicionado', 'ventilador', 'bombillas', 'llaves', 'tarjetas magnéticas',
            'amenities', 'minibar', 'botella de agua', 'snacks', 'kit de primeros auxilios'
        ];

        $ubicaciones = [
            'almacén principal', 'cocina', 'recepción', 'piscina', 'gimnasio', 'spa', 'bar',
            'habitación 101', 'habitación 102', 'habitación 201', 'suite presidencial', 'terraza'
        ];

        return [
            'articulo' => fake()->randomElement($productos),
            'cantidad' => fake()->numberBetween(1, 100),
            'ubicacion' => fake()->randomElement($ubicaciones),
            'notas' => fake('es_ES')->sentence(),
            'item' => fake()->randomElement($productos),
            'quantity' => fake()->numberBetween(1, 100),
            'location' => fake()->randomElement($ubicaciones),
            'notes' => fake('es_ES')->sentence(),
        ];
    }
}