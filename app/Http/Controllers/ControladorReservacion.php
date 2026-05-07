<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservacion;
use App\Models\Habitacion;
use App\Models\Huesped;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ControladorReservacion extends Controller
{
    public function calendar()
    {
        $rooms = Habitacion::orderBy('numero')->get();
        return view('reservaciones.calendario', compact('rooms'));
    }

    public function events(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $query = Reservacion::with(['room', 'guest']);
        if ($start && $end) {
            $query = $query->where(function($q) use($start, $end) {
                $q->whereBetween('fecha_entrada', [$start, $end])
                  ->orWhereBetween('fecha_salida', [$start, $end])
                  ->orWhere(function($q2) use($start, $end) {
                      $q2->where('fecha_entrada', '<', $start)->where('fecha_salida', '>', $end);
                  });
            });
        }

        $reservations = $query->get();

        $events = $reservations->map(function($r) {
            return [
                'id' => $r->id,
                'title' => ($r->guest->name ?? 'Huésped') . ' — ' . ($r->room->number ?? '—'),
                'start' => $r->checkin_at->toDateString(),
                'end' => Carbon::parse($r->checkout_at)->addDay()->toDateString(),
                'color' => $r->status == 'cancelled' ? '#c4c4c4' : ($r->status == 'checked_in' ? '#e06d6d' : '#b23a3a'),
                'extendedProps' => [
                    'status' => $r->status,
                    'room_id' => $r->room_id,
                    'guest' => $r->guest?->name,
                    'notes' => $r->notes,
                ],
            ];
        });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $data = $request->only(['room_id', 'guest_name', 'fecha_entrada', 'fecha_salida', 'notas']);

        $validator = Validator::make($data, [
            'room_id' => ['nullable', 'exists:habitaciones,id'],
            'guest_name' => ['required', 'string', 'max:255'],
            'fecha_entrada' => ['required', 'date'],
            'fecha_salida' => ['required', 'date', 'after_or_equal:fecha_entrada'],
            'notas' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $start = Carbon::parse($data['fecha_entrada'])->toDateString();
        $end = Carbon::parse($data['fecha_salida'])->toDateString();

        if (!empty($data['room_id'])) {
            if ($this->reservationOverlaps($data['room_id'], $start, $end)) {
                return response()->json(['success' => false, 'message' => 'La habitación está ocupada en esas fechas.'], 409);
            }
        }

        $guest = Huesped::firstOrCreate(['name' => $data['guest_name']]);

        $reservation = Reservacion::create([
            'hotel_id' => null,
            'room_id' => $data['room_id'] ?? null,
            'guest_id' => $guest->id,
            'fecha_entrada' => $start,
            'fecha_salida' => $end,
            'total' => 0,
            'status' => 'booked',
            'notas' => $data['notas'] ?? null,
        ]);

        return response()->json(['success' => true, 'reservation' => $reservation]);
    }

    public function update(Request $request, Reservacion $reservation)
    {
        $data = $request->only(['room_id', 'fecha_entrada', 'fecha_salida', 'status', 'notas']);

        $validator = Validator::make($data, [
            'room_id' => ['nullable', 'exists:habitaciones,id'],
            'fecha_entrada' => ['sometimes', 'required', 'date'],
            'fecha_salida' => ['sometimes', 'required', 'date', 'after_or_equal:fecha_entrada'],
            'status' => ['sometimes', Rule::in(['booked', 'checked_in', 'checked_out', 'cancelled'])],
            'notas' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $start = isset($data['fecha_entrada']) ? Carbon::parse($data['fecha_entrada'])->toDateString() : $reservation->checkin_at;
        $end = isset($data['fecha_salida']) ? Carbon::parse($data['fecha_salida'])->toDateString() : $reservation->checkout_at;
        $roomId = $data['room_id'] ?? $reservation->room_id;

        if (!empty($roomId)) {
            if ($this->reservationOverlaps($roomId, $start, $end, $reservation->id)) {
                return response()->json(['success' => false, 'message' => 'Solapamiento con otra reserva.'], 409);
            }
        }

        $reservation->update(array_filter([
            'room_id' => $roomId,
            'fecha_entrada' => $start,
            'fecha_salida' => $end,
            'status' => $data['status'] ?? $reservation->status,
            'notas' => $data['notas'] ?? $reservation->notas,
        ]));

        return response()->json(['success' => true, 'reservation' => $reservation]);
    }

    public function destroy(Reservacion $reservation)
    {
        $reservation->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Mostrar detalle de una reserva
     */
    public function show(Reservacion $reservation)
    {
        // Cargamos relaciones que usarás en la vista
        $reservation->load(['room', 'guest', 'user']);

        return view('reservaciones.mostrar', compact('reservation'));
    }

    protected function reservationOverlaps(int $roomId, string $start, string $end, int $excludeId = null): bool
    {
        $q = Reservacion::where('room_id', $roomId)->where('status', '!=', 'cancelled');
        if ($excludeId) $q->where('id', '!=', $excludeId);

        $exists = $q->where(function($sub) use ($start, $end) {
            $sub->where('fecha_entrada', '<=', $end)
                ->where('fecha_salida', '>=', $start);
        })->exists();

        return $exists;
    }

    // Panel administrativo de reservas
    public function management(Request $request)
    {
        $query = Reservacion::with(['room', 'guest']);

        // Filtrar por estado
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filtrar por fecha
        if ($request->has('date_from') && $request->date_from) {
            $query->where('fecha_entrada', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('fecha_salida', '<=', $request->date_to);
        }

        // Filtrar por huésped
        if ($request->has('guest') && $request->guest) {
            $query->whereHas('guest', function($q) {
                $q->where('name', 'like', '%' . request('guest') . '%');
            });
        }

        // Filtrar por habitación
        if ($request->has('room_id') && $request->room_id) {
            $query->where('room_id', $request->room_id);
        }

        $reservations = $query->orderBy('fecha_entrada', 'desc')->paginate(15);
        $rooms = Habitacion::orderBy('numero')->get();

        return view('reservaciones.gestion', compact('reservations', 'rooms'));
    }
}
