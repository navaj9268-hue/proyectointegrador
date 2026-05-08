<?php

namespace App\Http\Controllers;

use App\Mail\ReservationConfirmationMail;
use App\Models\Habitacion;
use App\Models\Huesped;
use App\Models\Reservacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ControladorCatalogo extends Controller
{
    // Mostrar catálogo de habitaciones
    public function index(Request $request)
    {
        $query = Habitacion::query()->orderByDesc('id');

        // Para clientes, mostrar solo habitaciones disponibles.
        if (auth()->check() && auth()->user()->role === 'cliente') {
            $query->where('status', 'available');
        }

        // Filtrar por estado si es especificado
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filtrar por precio máximo
        if ($request->has('max_price') && $request->max_price) {
            $query->where('precio', '<=', $request->max_price);
        }

        $rooms = $query->paginate(9);
        
        return view('catalogo.habitaciones', compact('rooms'));
    }

    // Mostrar detalle de habitación
    public function show(Habitacion $room)
    {
        // Obtener reservas existentes para esta habitación
        $reservations = $room->reservations()
            ->where('fecha_salida', '>=', now())
            ->get();

        return view('catalogo.detalle-habitacion', compact('room', 'reservations'));
    }

    // Guardar reserva desde el catálogo
    public function storeReservation(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:habitaciones,id',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email',
            'guest_phone' => 'required|string|max:20',
            'fecha_entrada' => 'required|date|after_or_equal:today',
            'fecha_salida' => 'required|date|after:fecha_entrada',
            'notas' => 'nullable|string'
        ]);

        // Verificar disponibilidad
        $room = Habitacion::findOrFail($validated['room_id']);
        
        $exists = Reservacion::where('room_id', $room->id)
            ->where(function($q) use ($validated) {
                $q->whereBetween('fecha_entrada', [$validated['fecha_entrada'], $validated['fecha_salida']])
                  ->orWhereBetween('fecha_salida', [$validated['fecha_entrada'], $validated['fecha_salida']]);
            })
            ->exists();

        if ($exists) {
            return back()->with('error', 'La habitación no está disponible en esas fechas');
        }

        // Crear o buscar huésped
        $guest = Huesped::firstOrCreate(
            ['email' => $validated['guest_email']],
            [
                'name' => $validated['guest_name'],
                'phone' => $validated['guest_phone']
            ]
        );

        // Calcular total
        $checkin = \Carbon\Carbon::parse($validated['fecha_entrada']);
        $checkout = \Carbon\Carbon::parse($validated['fecha_salida']);
        $nights = $checkin->diffInDays($checkout);
        $total = $nights * $room->price;

        // Crear reserva
        $reservation = Reservacion::create([
            'hotel_id' => $room->hotel_id,
            'room_id' => $room->id,
            'guest_id' => $guest->id,
            'fecha_entrada' => $validated['fecha_entrada'],
            'fecha_salida' => $validated['fecha_salida'],
            'total' => $total,
            'notas' => $validated['notas'] ?? null,
            'status' => 'booked'
        ]);

        // Enviar email de confirmación
        Mail::to($guest->email)->send(new ReservationConfirmationMail($reservation, $guest));

        return redirect()->route('reservaciones.mostrar', $reservation->id)
            ->with('success', 'Reserva creada exitosamente. Se ha enviado un email de confirmación.');
    }
}
