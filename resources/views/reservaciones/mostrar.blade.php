@extends('layouts.app')

@section('title', 'Reserva #' . ($reservation->id ?? ''))

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                🛎️ Detalle de reserva
            </h3>

            <small class="text-muted">
                Reserva #{{ $reservation->id }}
            </small>

        </div>

        <div class="d-flex gap-2">

            {{-- TODOS PUEDEN VER CALENDARIO --}}
            <a href="{{ route('reservaciones.calendar') }}"
               class="btn btn-outline-secondary rounded-3">
                ← Calendario
            </a>

            {{-- SOLO ADMIN VE LISTA --}}
            @if(auth()->user()->role === 'admin')

                <a href="{{ route('reservaciones.management') }}"
                   class="btn btn-outline-secondary rounded-3">
                    📋 Lista
                </a>

            @endif

        </div>

    </div>

    <div class="row g-4">

        {{-- INFORMACIÓN --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">
                        Información de la reserva
                    </h5>

                    <div class="row g-3">

                        {{-- HUESPED --}}
                        <div class="col-md-6">

                            <div class="border rounded-4 p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Huésped
                                </small>

                                <div class="fw-semibold">

                                    @if($reservation->guest)

                                        {{ $reservation->guest->name }}

                                    @else

                                        {{ $reservation->guest_name ?? '—' }}

                                    @endif

                                </div>

                            </div>

                        </div>

                        {{-- HABITACIÓN --}}
                        <div class="col-md-6">

                            <div class="border rounded-4 p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Habitación
                                </small>

                                <div class="fw-semibold">

                                    {{ $reservation->room->number ?? '-' }}

                                    @if($reservation->room)

                                        ({{ $reservation->room->type ?? '-' }})

                                    @endif

                                </div>

                            </div>

                        </div>

                        {{-- ENTRADA --}}
                        <div class="col-md-6">

                            <div class="border rounded-4 p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Entrada
                                </small>

                                <div class="fw-semibold">

                                    {{ \Carbon\Carbon::parse($reservation->fecha_entrada)->format('d/m/Y') }}

                                </div>

                            </div>

                        </div>

                        {{-- SALIDA --}}
                        <div class="col-md-6">

                            <div class="border rounded-4 p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Salida
                                </small>

                                <div class="fw-semibold">

                                    {{ \Carbon\Carbon::parse($reservation->fecha_salida)->format('d/m/Y') }}

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- NOTAS --}}
                    <div class="mt-4">

                        <h6 class="fw-bold">
                            📝 Notas
                        </h6>

                        <div class="border rounded-4 p-3 bg-light">

                            {{ $reservation->notas ?? 'Sin notas' }}

                        </div>

                    </div>

                    {{-- BOTONES SOLO ADMIN --}}
                    @if(auth()->user()->role === 'admin')

                        <div class="mt-4 d-flex gap-2">

                            <a href="{{ route('reservaciones.edit', $reservation) }}"
                               class="btn btn-outline-primary rounded-3">

                                ✏️ Editar

                            </a>

                            <form action="{{ route('reservaciones.destroy', $reservation) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Eliminar reserva?')">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger rounded-3">

                                    🗑 Eliminar

                                </button>

                            </form>

                        </div>

                    @endif

                </div>

            </div>

            {{-- PAGOS --}}
            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">
                        💳 Pagos relacionados
                    </h5>

                    @if($reservation->payments && $reservation->payments->count())

                        <div class="list-group list-group-flush">

                            @foreach($reservation->payments as $p)

                                <div class="list-group-item px-0 py-3 border-bottom">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <div>

                                            <div class="fw-semibold">

                                                {{ $p->nombre_pagador ?? $p->user->name ?? '—' }}

                                            </div>

                                            <small class="text-muted">

                                                Método:
                                                {{ $p->metodo }}

                                                ·

                                                TX:
                                                {{ $p->id_transaccion ?? '—' }}

                                            </small>

                                        </div>

                                        <div>

                                            <span class="badge bg-success fs-6 rounded-pill px-3 py-2">

                                                $ {{ number_format($p->monto,2) }}

                                            </span>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-muted">

                            No hay pagos registrados.

                        </div>

                    @endif

                </div>

            </div>

        </div>

        {{-- RESUMEN --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">
                        📌 Resumen
                    </h5>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Estado
                        </small>

                        <span class="badge bg-primary px-3 py-2 rounded-pill">

                            {{ ucfirst($reservation->status ?? '—') }}

                        </span>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Creado
                        </small>

                        <div class="fw-semibold">

                            {{ $reservation->created_at->format('d/m/Y H:i') }}

                        </div>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Total estimado
                        </small>

                        <div class="fw-bold fs-4 text-success">

                            $ {{ number_format($reservation->total ?? 0,2) }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection