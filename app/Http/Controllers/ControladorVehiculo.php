<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Reservacion;
use Illuminate\Http\Request;

class ControladorVehiculo extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehiculo::with('reservation')->orderBy('created_at', 'desc')->paginate(15);
        $parkedCount = Vehiculo::where('status', 'parking')->count();
        $totalSpots = 20; // Configurar según necesidad

        return view('vehiculos.indice', compact('vehicles', 'parkedCount', 'totalSpots'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $reservations = Reservacion::where('status', 'checked_in')->get();
        return view('vehiculos.crear', compact('reservations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'placa' => 'required|string|unique:vehiculos,placa',
            'marca' => 'nullable|string',
            'modelo' => 'nullable|string',
            'color' => 'nullable|string',
            'reservation_id' => 'nullable|exists:reservaciones,id',
            'lugar_estacionamiento' => 'nullable|string',
            'notas' => 'nullable|string',
        ]);

        $validated['status'] = 'parking';
        $validated['fecha_entrada'] = now();

        Vehiculo::create($validated);

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo registrado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehiculo $vehicle)
    {
        $vehicle->load('reservation');
        return view('vehiculos.mostrar', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehiculo $vehicle)
    {
        $reservations = Reservacion::where('status', 'checked_in')->orWhere('id', $vehicle->reservation_id)->get();
        return view('vehiculos.editar', compact('vehicle', 'reservations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehiculo $vehicle)
    {
        $validated = $request->validate([
            'placa' => 'required|string|unique:vehiculos,placa,' . $vehicle->id,
            'marca' => 'nullable|string',
            'modelo' => 'nullable|string',
            'color' => 'nullable|string',
            'reservation_id' => 'nullable|exists:reservaciones,id',
            'lugar_estacionamiento' => 'nullable|string',
            'status' => 'required|in:parking,left',
            'notas' => 'nullable|string',
        ]);

        if ($request->status === 'left' && !$vehicle->exit_date) {
            $validated['fecha_salida'] = now();
        }

        $vehicle->update($validated);

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehiculo $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehiculos.index')->with('success', 'Vehículo eliminado exitosamente');
    }

    /**
     * Registrar salida de vehículo
     */
    public function registerExit(Vehiculo $vehicle)
    {
        $vehicle->update([
            'status' => 'left',
            'exit_date' => now(),
        ]);

        return redirect()->route('vehiculos.index')->with('success', 'Salida del vehículo registrada exitosamente');
    }

    /**
     * Dashboard de estacionamiento
     */
    public function parking()
    {
        $vehicles = Vehiculo::where('status', 'parking')->with('reservation')->paginate(10);
        $parkedCount = Vehiculo::where('status', 'parking')->count();
        $totalSpots = 20;
        $availableSpots = $totalSpots - $parkedCount;

        return view('vehiculos.estacionamiento', compact('vehicles', 'parkedCount', 'availableSpots', 'totalSpots'));
    }
}
