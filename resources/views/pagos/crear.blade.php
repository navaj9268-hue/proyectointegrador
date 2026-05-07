@extends('layouts.app')
@section('title', isset($payment) ? 'Editar pago' : 'Crear pago')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>{{ isset($payment) ? 'Editar pago' : 'Registrar nuevo pago' }}</h4>
  <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">← Volver</a>
</div>

<div class="card p-3">
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ isset($payment) ? route('pagos.update', $payment) : route('pagos.store') }}">
    @csrf
    @if(isset($payment)) @method('PUT') @endif

    <div class="row g-2">
      <div class="col-md-6 mb-2">
        <label class="form-label">Reserva (opcional)</label>
        <select name="reservation_id" class="form-select">
          <option value="">-- Ninguna --</option>
          @foreach($reservations as $res)
            <option value="{{ $res->id }}" @selected(old('reservation_id', $payment->reservation_id ?? '') == $res->id)>{{ $res->guest->name ?? 'Huésped' }} — {{ $res->room->number ?? '—' }} ({{ $res->checkin_at->format('d/m/Y') }})</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-6 mb-2">
        <label class="form-label">Monto</label>
        <input name="monto" type="number" step="0.01" value="{{ old('monto', $payment->monto ?? '') }}" class="form-control" required>
      </div>

      <div class="col-md-4 mb-2">
        <label class="form-label">Método</label>
        <select name="metodo" class="form-select" required>
          <option value="efectivo" @selected(old('metodo', $payment->metodo ?? '') == 'efectivo')>Efectivo</option>
          <option value="tarjeta" @selected(old('metodo', $payment->metodo ?? '') == 'tarjeta')>Tarjeta</option>
          <option value="transferencia" @selected(old('metodo', $payment->metodo ?? '') == 'transferencia')>Transferencia</option>
          <option value="otro" @selected(old('metodo', $payment->metodo ?? '') == 'otro')>Otro</option>
        </select>
      </div>

      <div class="col-md-4 mb-2">
        <label class="form-label">ID Transacción</label>
        <input name="id_transaccion" value="{{ old('id_transaccion', $payment->id_transaccion ?? '') }}" class="form-control">
      </div>

      <div class="col-md-4 mb-2">
        <label class="form-label">Pagó (nombre)</label>
        <input name="nombre_pagador" value="{{ old('nombre_pagador', $payment->nombre_pagador ?? '') }}" class="form-control">
      </div>

      <div class="col-12 mb-2">
        <label class="form-label">Notas</label>
        <textarea name="notas" class="form-control" rows="3">{{ old('notas', $payment->notas ?? '') }}</textarea>
      </div>
    </div>

    <div class="mt-2">
      <button class="btn btn-primary">{{ isset($payment) ? 'Actualizar pago' : 'Registrar pago' }}</button>
      <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
