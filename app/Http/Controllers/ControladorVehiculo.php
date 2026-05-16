<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Reservacion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ControladorVehiculo extends Controller
{
    // Capacidad total
    const CAPACIDAD_TOTAL = 50;

    // Tarifa default
    const TARIFA_DEFAULT = 30.00;

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Vehiculo::with('reservation')
            ->latest('fecha_entrada');

        // BUSQUEDA
        if ($request->filled('q')) {

            $q = $request->q;

            $query->where(function ($s) use ($q) {

                $s->where('placa', 'like', "%{$q}%")
                  ->orWhere('marca', 'like', "%{$q}%")
                  ->orWhere('modelo', 'like', "%{$q}%");

            });
        }

        // FILTRO STATUS
        if ($request->filled('status')) {

            $query->where('status', $request->status);

        } else {

            $query->where(
                'status',
                Vehiculo::STATUS_ESTACIONADO
            );

        }

        $vehicles = $query->paginate(15)
            ->withQueryString();

        $parkedCount = Vehiculo::estacionados()
            ->count();

        $availableSpots = max(
            0,
            self::CAPACIDAD_TOTAL - $parkedCount
        );

        $totalSpots = self::CAPACIDAD_TOTAL;

        $ocupacion = $totalSpots > 0
            ? round(($parkedCount / $totalSpots) * 100)
            : 0;

        $lugaresOcupados = Vehiculo::estacionados()
            ->whereNotNull('lugar_estacionamiento')
            ->pluck('lugar_estacionamiento')
            ->toArray();

        // 🔥 CORREGIDO
        return view('vehiculos.indice', compact(
            'vehicles',
            'parkedCount',
            'availableSpots',
            'totalSpots',
            'ocupacion',
            'lugaresOcupados'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $reservaciones = Reservacion::whereIn(
                'status',
                ['confirmed', 'checked_in']
            )
            ->with('guest')
            ->get();

        $lugaresOcupados = Vehiculo::estacionados()
            ->whereNotNull('lugar_estacionamiento')
            ->pluck('lugar_estacionamiento')
            ->toArray();

        $tipos = Vehiculo::tipos();

        $capacidad = self::CAPACIDAD_TOTAL;

        // 🔥 CORREGIDO
        return view(
            'vehiculos.crear',
            compact(
                'reservaciones',
                'lugaresOcupados',
                'tipos',
                'capacidad'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'placa' => 'required|string|max:20',

            'marca' => 'required|string|max:50',

            'modelo' => 'required|string|max:50',

            'color' => 'nullable|string|max:30',

            'tipo' => 'nullable|string|in:auto,moto,camioneta,bus',

            'lugar_estacionamiento' => 'nullable|string|max:10',

            'tarifa_por_hora' => 'nullable|numeric|min:0',

            'reservation_id' => 'nullable|exists:reservaciones,id',

            'notas' => 'nullable|string|max:500',

        ]);

        // VALIDAR LUGAR
        if ($request->filled('lugar_estacionamiento')) {

            $ocupado = Vehiculo::estacionados()

                ->where(
                    'lugar_estacionamiento',
                    $request->lugar_estacionamiento
                )

                ->exists();

            if ($ocupado) {

                return back()

                    ->withErrors([
                        'lugar_estacionamiento' =>
                            'Ese lugar ya está ocupado.'
                    ])

                    ->withInput();
            }
        }

        Vehiculo::create([

            'placa' => strtoupper($request->placa),

            'marca' => $request->marca,

            'modelo' => $request->modelo,

            'color' => $request->color,

            'tipo' => $request->tipo ?? 'auto',

            'status' => Vehiculo::STATUS_ESTACIONADO,

            'fecha_entrada' => now(),

            'lugar_estacionamiento' =>
                $request->lugar_estacionamiento,

            'tarifa_por_hora' =>
                $request->tarifa_por_hora
                    ?? self::TARIFA_DEFAULT,

            'reservation_id' => $request->reservation_id,

            'notas' => $request->notas,

        ]);

        return redirect()

            ->route('vehiculos.index')

            ->with(
                'success',
                'Vehículo registrado correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Vehiculo $vehicle)
    {
        $vehicle->load('reservation.guest');

        // 🔥 CORREGIDO
        return view(
            'vehiculos.mostrar',
            compact('vehicle')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Vehiculo $vehicle)
    {
        $reservaciones = Reservacion::whereIn(
                'status',
                ['confirmed', 'checked_in']
            )

            ->with('guest')

            ->get();

        $lugaresOcupados = Vehiculo::estacionados()

            ->whereNotNull('lugar_estacionamiento')

            ->where('id', '!=', $vehicle->id)

            ->pluck('lugar_estacionamiento')

            ->toArray();

        $tipos = Vehiculo::tipos();

        $capacidad = self::CAPACIDAD_TOTAL;

        // 🔥 CORREGIDO
        return view(
            'vehiculos.editar',
            compact(
                'vehicle',
                'reservaciones',
                'lugaresOcupados',
                'tipos',
                'capacidad'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Vehiculo $vehicle
    )
    {
        $request->validate([

            'placa' => 'required|string|max:20',

            'marca' => 'required|string|max:50',

            'modelo' => 'required|string|max:50',

            'color' => 'nullable|string|max:30',

            'tipo' => 'nullable|string|in:auto,moto,camioneta,bus',

            'lugar_estacionamiento' => 'nullable|string|max:10',

            'tarifa_por_hora' => 'nullable|numeric|min:0',

            'reservation_id' => 'nullable|exists:reservaciones,id',

            'notas' => 'nullable|string|max:500',

        ]);

        $vehicle->update([

            'placa' => strtoupper($request->placa),

            'marca' => $request->marca,

            'modelo' => $request->modelo,

            'color' => $request->color,

            'tipo' => $request->tipo,

            'lugar_estacionamiento' =>
                $request->lugar_estacionamiento,

            'tarifa_por_hora' =>
                $request->tarifa_por_hora,

            'reservation_id' =>
                $request->reservation_id,

            'notas' => $request->notas,

        ]);

        return redirect()

            ->route('vehiculos.index')

            ->with(
                'success',
                'Vehículo actualizado correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Vehiculo $vehicle)
    {
        $vehicle->delete();

        return redirect()

            ->route('vehiculos.index')

            ->with(
                'success',
                'Vehículo eliminado correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER EXIT
    |--------------------------------------------------------------------------
    */

    public function registerExit(Vehiculo $vehicle)
    {
        if ($vehicle->yaSalio()) {

            return back()

                ->with(
                    'error',
                    'Este vehículo ya salió.'
                );
        }

        $fechaSalida = now();

        $totalCobrado = $vehicle->calcularTotal();

        $vehicle->update([

            'status' => Vehiculo::STATUS_SALIDA,

            'fecha_salida' => $fechaSalida,

            'total_cobrado' => $totalCobrado,

        ]);

        return redirect()

            ->route('vehiculos.index')

            ->with(
                'success',
                'Salida registrada correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | ESTACIONAMIENTO
    |--------------------------------------------------------------------------
    */

    public function estacionamiento()
    {
        $parkedCount = Vehiculo::estacionados()
            ->count();

        $availableSpots = max(
            0,
            self::CAPACIDAD_TOTAL - $parkedCount
        );

        $totalSpots = self::CAPACIDAD_TOTAL;

        $ocupacion = $totalSpots > 0
            ? round(($parkedCount / $totalSpots) * 100)
            : 0;

        $vehicles = Vehiculo::estacionados()

            ->with('reservation.guest')

            ->latest('fecha_entrada')

            ->paginate(10);

        $lugaresOcupados = Vehiculo::estacionados()

            ->whereNotNull('lugar_estacionamiento')

            ->get([
                'lugar_estacionamiento',
                'placa',
                'marca',
                'modelo',
                'id'
            ])

            ->keyBy('lugar_estacionamiento');

        return view(
            'vehiculos.estacionamiento',
            compact(
                'vehicles',
                'parkedCount',
                'availableSpots',
                'totalSpots',
                'ocupacion',
                'lugaresOcupados'
            )
        );
    }
}