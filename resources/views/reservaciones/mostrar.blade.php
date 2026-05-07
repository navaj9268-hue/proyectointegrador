@extends('layouts.app')
@section('title', 'Reserva #' . ($reservation->id ?? ''))

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Detalle de reserva <small class="text-muted">#{{ $reservation->id }}</small></h4>
    <div>
      <a href="{{ route('reservaciones.calendar') }}" class="btn btn-sm btn-outline-secondary">← Calendario</a>
      <a href="{{ route('reservaciones.index') }}" class="btn btn-sm btn-outline-secondary">Lista</a>
    </div>
  </div>

  <div class="row">
    <div class="col-md-8">
      <div class="card mb-3 card-accent p-3">
        <h5 class="mb-2">Información de la reserva</h5>

        <p><strong>Huésped:</strong>
          @if($reservation->guest)
            {{ $reservation->guest->name }}
          @else
            {{ $reservation->guest_name ?? '—' }}
          @endif
        </p>

        <p><strong>Habitación:</strong> {{ $reservation->room->number ?? '-' }} {!! $reservation->room ? '(' . ($reservation->room->type ?? '-') . ')' : '' !!}</p>

        <p>
          <strong>Fechas:</strong>
          {{ \Carbon\Carbon::parse($reservation->checkin_at)->format('d/m/Y') }}
          →
          {{ \Carbon\Carbon::parse($reservation->checkout_at)->format('d/m/Y') }}
        </p>

        <p><strong>Notas:</strong><br>
          {{ $reservation->notes ?? 'Sin notas' }}
        </p>

        <div class="mt-3">
          <a href="{{ route('reservaciones.edit', $reservation) ?? '#' }}" class="btn btn-sm btn-outline-secondary">✏️ Editar</a>

          <form action="{{ route('reservaciones.destroy', $reservation) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Eliminar reserva?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger">Eliminar</button>
          </form>
        </div>
      </div>

      <!-- Información adicional: pago(s) relacionados -->
      <div class="card p-3">
        <h6>Pagos relacionados</h6>
        @if($reservation->payments && $reservation->payments->count())
          <ul class="list-group list-group-flush">
            @foreach($reservation->payments as $p)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ $p->payer_name ?? $p->user->name ?? '—' }}</strong>
                  <div class="small text-muted">Método: {{ $p->method }} · Transacción: {{ $p->transaction_id }}</div>
                </div>
                <div><strong>$ {{ number_format($p->amount,2) }}</strong></div>
              </li>
            @endforeach
          </ul>
        @else
          <p class="text-muted mb-0">No hay pagos registrados.</p>
        @endif
      </div>

    </div>

    <div class="col-md-4">
      <div class="card p-3 card-accent">
        <h6>Resumen</h6>
        <p class="mb-1"><strong>Estado:</strong> {{ $reservation->status ?? '—' }}</p>
        <p class="mb-1"><strong>Creado por:</strong> -</p>
        <p class="mb-1"><strong>Creado:</strong> {{ $reservation->created_at->format('d/m/Y H:i') }}</p>
      </div>
    </div>
  </div>
</div>
@endsection
