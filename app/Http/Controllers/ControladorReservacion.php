<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservacion;
use App\Models\Habitacion;
use App\Models\Huesped;
use Carbon\Carbon;

class ControladorReservacion extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | CALENDARIO
    |--------------------------------------------------------------------------
    */

    public function calendar()
    {
        $rooms = Habitacion::orderBy('numero')->get();

        return view(
            'reservaciones.calendario',
            compact('rooms')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EVENTOS CALENDARIO
    |--------------------------------------------------------------------------
    */

    public function events(Request $request)
    {

        $start = $request->get('start');
        $end   = $request->get('end');

        $query = Reservacion::with([
            'room',
            'guest',
            'payments.user'
        ]);

        /*
        |--------------------------------------------------------------------------
        | CLIENTE SOLO VE SUS RESERVAS
        |--------------------------------------------------------------------------
        */

        if(auth()->user()->role === 'cliente'){

            $query->where(function($query){

                $query->whereHas('payments', function($q){

                    $q->where(
                        'user_id',
                        auth()->id()
                    );

                })

                ->orWhereHas('guest', function($q){

                    $q->where(
                        'email',
                        auth()->user()->email
                    );

                });

            });

        }

        /*
        |--------------------------------------------------------------------------
        | FILTRO FECHAS
        |--------------------------------------------------------------------------
        */

        if ($start && $end) {

            $query->where(function($q)
            use($start, $end){

                $q->whereBetween(
                    'fecha_entrada',
                    [$start, $end]
                )

                ->orWhereBetween(
                    'fecha_salida',
                    [$start, $end]
                )

                ->orWhere(function($q2)
                use($start, $end){

                    $q2->where(
                        'fecha_entrada',
                        '<',
                        $start
                    )

                    ->where(
                        'fecha_salida',
                        '>',
                        $end
                    );

                });

            });

        }

        $reservations = $query->get();

        $events = $reservations->map(function($r){

            return [

                'id' => $r->id,

                'title' =>
                    ($r->guest->name ?? 'Huésped')
                    . ' — Hab '
                    . ($r->room->numero ?? '-'),

                'start' => Carbon::parse(
                    $r->fecha_entrada
                )->toDateString(),

                'end' => Carbon::parse(
                    $r->fecha_salida
                )->addDay()->toDateString(),

                'url' => route(
                    'reservaciones.mostrar',
                    $r->id
                ),

                'color' => $r->status == 'cancelled'
                    ? '#c4c4c4'
                    : (
                        $r->status == 'checked_in'
                        ? '#e06d6d'
                        : '#b23a3a'
                    ),

                'extendedProps' => [

                    'status' => $r->status,

                    'room_id' => $r->room_id,

                    'guest' => $r->guest?->name,

                    'notes' => $r->notas,

                ],

            ];

        });

        return response()->json($events);
    }

    /*
    |--------------------------------------------------------------------------
    | HABITACIONES DISPONIBLES
    |--------------------------------------------------------------------------
    */

    public function available(Request $request)
    {

        $checkin  = $request->get('checkin');
        $checkout = $request->get('checkout');

        $ocupadas = Reservacion::where('status', '!=', 'cancelled')
            ->when($checkin && $checkout, function($q) use ($checkin, $checkout) {

                $q->where('fecha_entrada', '<=', $checkout)
                  ->where('fecha_salida',  '>=', $checkin);

            })
            ->pluck('room_id');

        $rooms = Habitacion::whereNotIn('id', $ocupadas)
            ->orderBy('numero')
            ->get(['id', 'numero', 'tipo', 'precio']);

        return response()->json($rooms);

    }

    /*
    |--------------------------------------------------------------------------
    | CREAR RESERVA
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {

        $data = $request->validate([

            'room_id' => [
                'required',
                'exists:habitaciones,id'
            ],

            'guest_name' => [
                'required',
                'string',
                'max:255'
            ],

            'fecha_entrada' => [
                'required',
                'date'
            ],

            'fecha_salida' => [
                'required',
                'date',
                'after_or_equal:fecha_entrada'
            ],

            'notas' => [
                'nullable',
                'string'
            ],

        ]);

        $start = Carbon::parse(
            $data['fecha_entrada']
        )->toDateString();

        $end = Carbon::parse(
            $data['fecha_salida']
        )->toDateString();

        /*
        |--------------------------------------------------------------------------
        | VALIDAR DISPONIBILIDAD
        |--------------------------------------------------------------------------
        */

        if (
            $this->reservationOverlaps(
                $data['room_id'],
                $start,
                $end
            )
        ) {

            return response()->json([
                'success' => false,
                'message' => 'La habitación ya está ocupada.'
            ], 409);

        }

        /*
        |--------------------------------------------------------------------------
        | CREAR HUÉSPED
        |--------------------------------------------------------------------------
        */

        $guest = Huesped::firstOrCreate(

            [
                'email' => auth()->user()->email
            ],

            [
                'name' => $data['guest_name']
            ]

        );

        /*
        |--------------------------------------------------------------------------
        | CREAR RESERVACIÓN
        |--------------------------------------------------------------------------
        */

        $reservation = Reservacion::create([

            'hotel_id' => 1,

            'room_id' => $data['room_id'],

            'guest_id' => $guest->id,

            'fecha_entrada' => $start,

            'fecha_salida' => $end,

            'total' => 0,

            'status' => 'booked',

            'notas' => $data['notas'] ?? null,

        ]);

        /*
        |--------------------------------------------------------------------------
        | CREAR PAGO AUTOMÁTICO
        |--------------------------------------------------------------------------
        */

        $reservation->payments()->create([

            'user_id' => auth()->id(),

            'monto' => 0,

            'metodo' => 'pendiente',

            'nombre_pagador' => auth()->user()->name,

        ]);

        /*
        |--------------------------------------------------------------------------
        | CAMBIAR ESTADO HABITACIÓN
        |--------------------------------------------------------------------------
        */

        if($reservation->room){

            $reservation->room->update([

                'status' => 'ocupada'

            ]);

        }

        return response()->json([

            'success' => true,

            'message' => 'Reservación creada correctamente',

            'reservation' => $reservation

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR RESERVA
    |--------------------------------------------------------------------------
    */

    public function show(Reservacion $reservation)
    {

        $reservation->load([
            'room',
            'guest',
            'payments.user'
        ]);

        /*
        |--------------------------------------------------------------------------
        | CLIENTE SOLO VE SUS RESERVAS
        |--------------------------------------------------------------------------
        */

        if(auth()->user()->role === 'cliente'){

            $allowed = false;

            if(
                $reservation->payments()
                    ->where(
                        'user_id',
                        auth()->id()
                    )
                    ->exists()
            ){
                $allowed = true;
            }

            if(
                $reservation->guest &&
                $reservation->guest->email === auth()->user()->email
            ){
                $allowed = true;
            }

            if(!$allowed){

                abort(403);

            }

        }

        return view(
            'reservaciones.mostrar',
            compact('reservation')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDITAR
    |--------------------------------------------------------------------------
    */

    public function edit(Reservacion $reservation)
    {

        if(auth()->user()->role !== 'admin'){

            abort(403);

        }

        $reservation->load([
            'room',
            'guest'
        ]);

        return view(
            'reservaciones.editar',
            compact('reservation')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ELIMINAR
    |--------------------------------------------------------------------------
    */

    public function destroy(Reservacion $reservation)
    {

        if(auth()->user()->role !== 'admin'){

            abort(403);

        }

        if($reservation->room){

            $reservation->room->update([

                'status' => 'disponible'

            ]);

        }

        $reservation->delete();

        return response()->json([

            'success' => true

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDAR SOLAPAMIENTO
    |--------------------------------------------------------------------------
    */

    protected function reservationOverlaps(
        int $roomId,
        string $start,
        string $end,
        ?int $excludeId = null
    ): bool
    {

        $q = Reservacion::where(
                'room_id',
                $roomId
            )
            ->where(
                'status',
                '!=',
                'cancelled'
            );

        if ($excludeId){

            $q->where(
                'id',
                '!=',
                $excludeId
            );

        }

        return $q->where(function($sub)
        use ($start, $end){

            $sub->where(
                    'fecha_entrada',
                    '<=',
                    $end
                )

                ->where(
                    'fecha_salida',
                    '>=',
                    $start
                );

        })->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | PANEL GESTIÓN
    |--------------------------------------------------------------------------
    */

    public function management(Request $request)
    {

        $query = Reservacion::with([
            'room',
            'guest',
            'payments.user'
        ]);

        /*
        |--------------------------------------------------------------------------
        | CLIENTE SOLO VE SUS RESERVAS
        |--------------------------------------------------------------------------
        */

        if(auth()->user()->role === 'cliente'){

            $query->where(function($query){

                $query->whereHas('payments', function($q){

                    $q->where(
                        'user_id',
                        auth()->id()
                    );

                })

                ->orWhereHas('guest', function($q){

                    $q->where(
                        'email',
                        auth()->user()->email
                    );

                });

            });

        }

        $reservations = $query
            ->orderBy(
                'fecha_entrada',
                'desc'
            )
            ->paginate(15);

        /*
        |--------------------------------------------------------------------------
        | HABITACIONES DISPONIBLES
        |--------------------------------------------------------------------------
        */

        $rooms = Habitacion::whereRaw(
                'LOWER(status) = ?',
                ['disponible']
            )
            ->orderBy('numero')
            ->get();

        return view(
            'reservaciones.gestion',
            compact(
                'reservations',
                'rooms'
            )
        );
    }
}