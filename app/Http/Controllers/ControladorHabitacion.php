<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habitacion;
use App\Models\Hotel;

class ControladorHabitacion extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $rooms = Habitacion::with('hotel')
            ->when($q, fn($query) => $query->where('numero','like', "%{$q}%")
                                           ->orWhere('tipo','like', "%{$q}%"))
            ->orderBy('id','desc')
            ->paginate(10);

        return view('habitaciones.indice', compact('rooms'));
    }

    public function create()
    {
        $hotels = Hotel::all();
        return view('habitaciones.crear', compact('hotels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hotel_id' => 'required|exists:hoteles,id',
            'numero' => 'required|string|max:50',
            'tipo' => 'nullable|string|max:100',
            'precio' => 'required|numeric|min:0',
            'status' => 'nullable|in:available,occupied,maintenance',
            'notas' => 'nullable|string',
        ]);

        $data['status'] = $data['status'] ?? 'available';

        Habitacion::create($data);

        return redirect()->route('habitaciones.index')->with('success', 'Habitación creada correctamente.');
    }

    public function show(Habitacion $room)
    {
        // Opcional — no lo usamos, pero Laravel resource lo incluye.
        return view('habitaciones.mostrar', compact('room'));
    }

    public function edit(Habitacion $room)
    {
        $hotels = Hotel::all();
        return view('habitaciones.editar', compact('room', 'hotels'));
    }

    public function update(Request $request, Habitacion $room)
    {
        $data = $request->validate([
            'hotel_id' => 'nullable|exists:hoteles,id',
            'numero' => 'required|string|max:50',
            'tipo' => 'nullable|string|max:100',
            'precio' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance',
            'notas' => 'nullable|string',
        ]);

        $room->update($data);

        return redirect()->route('habitaciones.index')->with('success', 'Habitación actualizada.');
    }

    public function destroy(Habitacion $room)
    {
        $room->delete();
        return redirect()->route('habitaciones.index')->with('success', 'Habitación eliminada.');
    }
}
