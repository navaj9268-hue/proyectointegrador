<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Guest;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function calendar()
    {
        $rooms = Room::orderBy('number')->get();
        return view('reservations.calendar', compact('rooms'));
    }

    public function events(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $query = Reservation::with(['room', 'guest']);
        if ($start && $end) {
            $query = $query->where(function($q) use($start, $end) {
                $q->whereBetween('checkin_at', [$start, $end])
                  ->orWhereBetween('checkout_at', [$start, $end])
                  ->orWhere(function($q2) use($start, $end) {
                      $q2->where('checkin_at', '<', $start)->where('checkout_at', '>', $end);
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
        $data = $request->only(['room_id', 'guest_name', 'checkin_at', 'checkout_at', 'notes']);

        $validator = Validator::make($data, [
            'room_id' => ['nullable', 'exists:rooms,id'],
            'guest_name' => ['required', 'string', 'max:255'],
            'checkin_at' => ['required', 'date'],
            'checkout_at' => ['required', 'date', 'after_or_equal:checkin_at'],
            'notes' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $start = Carbon::parse($data['checkin_at'])->toDateString();
        $end = Carbon::parse($data['checkout_at'])->toDateString();

        if (!empty($data['room_id'])) {
            if ($this->reservationOverlaps($data['room_id'], $start, $end)) {
                return response()->json(['success' => false, 'message' => 'La habitación está ocupada en esas fechas.'], 409);
            }
        }

        $guest = Guest::firstOrCreate(['name' => $data['guest_name']]);

        $reservation = Reservation::create([
            'hotel_id' => null,
            'room_id' => $data['room_id'] ?? null,
            'guest_id' => $guest->id,
            'checkin_at' => $start,
            'checkout_at' => $end,
            'total' => 0,
            'status' => 'booked',
            'notes' => $data['notes'] ?? null,
        ]);

        return response()->json(['success' => true, 'reservation' => $reservation]);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->only(['room_id', 'checkin_at', 'checkout_at', 'status', 'notes']);

        $validator = Validator::make($data, [
            'room_id' => ['nullable', 'exists:rooms,id'],
            'checkin_at' => ['sometimes', 'required', 'date'],
            'checkout_at' => ['sometimes', 'required', 'date', 'after_or_equal:checkin_at'],
            'status' => ['sometimes', Rule::in(['booked', 'checked_in', 'checked_out', 'cancelled'])],
            'notes' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $start = isset($data['checkin_at']) ? Carbon::parse($data['checkin_at'])->toDateString() : $reservation->checkin_at;
        $end = isset($data['checkout_at']) ? Carbon::parse($data['checkout_at'])->toDateString() : $reservation->checkout_at;
        $roomId = $data['room_id'] ?? $reservation->room_id;

        if (!empty($roomId)) {
            if ($this->reservationOverlaps($roomId, $start, $end, $reservation->id)) {
                return response()->json(['success' => false, 'message' => 'Solapamiento con otra reserva.'], 409);
            }
        }

        $reservation->update(array_filter([
            'room_id' => $roomId,
            'checkin_at' => $start,
            'checkout_at' => $end,
            'status' => $data['status'] ?? $reservation->status,
            'notes' => $data['notes'] ?? $reservation->notes,
        ]));

        return response()->json(['success' => true, 'reservation' => $reservation]);
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Mostrar detalle de una reserva
     */
    public function show(Reservation $reservation)
    {
        // Cargamos relaciones que usarás en la vista
        $reservation->load(['room', 'guest', 'user']);

        return view('reservations.show', compact('reservation'));
    }

    protected function reservationOverlaps(int $roomId, string $start, string $end, int $excludeId = null): bool
    {
        $q = Reservation::where('room_id', $roomId)->where('status', '!=', 'cancelled');
        if ($excludeId) $q->where('id', '!=', $excludeId);

        $exists = $q->where(function($sub) use ($start, $end) {
            $sub->where('checkin_at', '<=', $end)
                ->where('checkout_at', '>=', $start);
        })->exists();

        return $exists;
    }
}
