<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;

class HomeController extends Controller
{
    public function index()
    {
        // Si no existe un hotel, crea uno de ejemplo para que la vista muestre datos
        $hotel = Hotel::first();
        if (! $hotel) {
            $hotel = Hotel::create([
                'name' => 'Hotel El Buen Descanso',
                'address' => 'Calle Falsa 123, Ciudad',
                'phone' => '+52 55 1234 5678',
                'email' => 'info@buen-descanso.com',
                'description' => 'Hotel demo generado automáticamente.',
            ]);
        }

        return view('inicio.index', compact('hotel'));
    }
}
