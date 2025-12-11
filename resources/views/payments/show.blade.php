@extends('layouts.app')
@section('title','Pago #' . $payment->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Pago #{{ $payment->id }}</h4>
  <div>
    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-outline-primary">Editar</a>
    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-secondary">Volver</a>
  </div>
</div>

<div class="card p-3">
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  <div class="row">
    <div class="col-md-6">
      <h5>Monto</h5>
      <p>$ {{ number_format($payment->amount,2) }}</p>

      <h6>Metodo</h6>
      <p>{{ ucfirst($payment->method) }} @if($payment->transaction_id) <br><small class="text-muted">Transacción: {{ $payment->transaction_id }}</small>@endif</p>

      <h6>Pagó</h6>
      <p>{{ $payment->payer_name ?? '-' }}</p>
    </div>

    <div class="col-md-6">
      <h6>Reserva</h6>
      @if($payment->reservation)
        <p>
          <a href="{{ route('reservations.show', $payment->reservation) }}">
            {{ $payment->reservation->guest->name ?? 'Huésped' }}
          </a><br>
          <small class="text-muted">Hab: {{ $payment->reservation->room->number ?? '-' }} · {{ $payment->reservation->checkin_at->format('d/m/Y') }} → {{ $payment->reservation->checkout_at->format('d/m/Y') }}</small>
        </p>
      @else
        <p class="text-muted">Sin reserva asociada</p>
      @endif

      <h6>Registró</h6>
      <p>{{ $payment->user->name ?? '-' }} <br><small class="text-muted">{{ $payment->created_at->format('d/m/Y H:i') }}</small></p>
    </div>
  </div>

  @if($payment->notes)
    <div class="mt-3">
      <h6>Notas</h6>
      <p>{{ $payment->notes }}</p>
    </div>
  @endif
</div>
@endsection
