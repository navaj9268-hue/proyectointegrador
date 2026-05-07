@extends('layouts.app')
@section('title','Detalle de Habitación - ' . $room->number)
@php
  use Carbon\Carbon;
@endphp
@section('content')

<div class="container">
  <div class="row mb-4">
    <div class="col-12">
      <a href="{{ route('catalogo.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
        ← Volver al catálogo
      </a>
    </div>
  </div>

  <div class="row">
    <!-- Información de la habitación -->
    <div class="col-lg-8">
      <div class="card mb-4 shadow-sm">
        <div class="p-5 text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 5rem;">
          🛏️
        </div>
        
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Habitación {{ $room->number }}</h2>
            <span class="badge bg-{{ $room->status == 'disponible' ? 'success' : ($room->status == 'ocupada' ? 'danger' : 'warning') }}" style="font-size: 1rem; padding: 0.5rem 1rem;">
              {{ ucfirst($room->status) }}
            </span>
          </div>

          <hr>

          <div class="row g-4 mb-4">
            <div class="col-md-6">
              <h6 class="text-muted mb-2">Tipo de habitación</h6>
              <p class="h5">{{ $room->type ?? 'Habitación estándar' }}</p>
            </div>
            <div class="col-md-6">
              <h6 class="text-muted mb-2">Precio por noche</h6>
              <p class="h5 text-danger">${{ number_format($room->price, 2, ',', '.') }}</p>
            </div>
          </div>

          @if($room->notes)
            <div class="mb-4">
              <h6 class="text-muted mb-2">Descripción</h6>
              <p>{{ $room->notes }}</p>
            </div>
          @endif

          <hr>

          <h6 class="mb-3">Comodidades incluidas:</h6>
          <ul class="list-unstyled">
            <li class="mb-2"><i class="fas fa-wifi"></i> WiFi gratis</li>
            <li class="mb-2"><i class="fas fa-tv"></i> Televisión HD</li>
            <li class="mb-2"><i class="fas fa-wind"></i> Aire acondicionado</li>
            <li class="mb-2"><i class="fas fa-shower"></i> Baño privado</li>
            <li class="mb-2"><i class="fas fa-coffee"></i> Minibar</li>
            <li class="mb-2"><i class="fas fa-safe"></i> Caja de seguridad</li>
          </ul>
        </div>
      </div>

      <!-- Calendario de disponibilidad -->
      @if(count($reservations) > 0)
        <div class="card shadow-sm">
          <div class="card-header bg-light">
            <h6 class="mb-0">Fechas ocupadas</h6>
          </div>
          <div class="card-body">
            <div class="row g-2">
              @foreach($reservations as $res)
                <div class="col-md-6">
                  <div class="alert alert-warning mb-0 small">
                    <strong>{{ $res->checkin_at->format('d/m/Y') }}</strong>
                    hasta
                    <strong>{{ $res->checkout_at->format('d/m/Y') }}</strong>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endif
    </div>

    <!-- Formulario de reserva -->
    <div class="col-lg-4">
      <div class="card shadow-sm position-sticky" style="top: 20px;">
        <div class="card-header" style="background: linear-gradient(90deg, #b23a3a, #ff6b6b); color: white;">
          <h5 class="mb-0">Realizar reserva</h5>
        </div>
        
        <div class="card-body">
          @if($errors->any())
            <div class="alert alert-danger mb-3">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('catalogo.reservaciones.store') }}" method="POST">
            @csrf

            <input type="hidden" name="room_id" value="{{ $room->id }}">

            <div class="mb-3">
              <label class="form-label fw-bold">Nombre completo</label>
              <input type="text" name="guest_name" class="form-control @error('guest_name') is-invalid @enderror" 
                     value="{{ old('guest_name', auth()->user()->name) }}" required>
              @error('guest_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Email</label>
              <input type="email" name="guest_email" class="form-control @error('guest_email') is-invalid @enderror" 
                     value="{{ old('guest_email', auth()->user()->email) }}" required>
              @error('guest_email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Teléfono</label>
              <input type="tel" name="guest_phone" class="form-control @error('guest_phone') is-invalid @enderror" 
                     value="{{ old('guest_phone') }}" required>
              @error('guest_phone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Número de documento</label>
              <input type="text" name="numero_documento" class="form-control @error('numero_documento') is-invalid @enderror" 
                     value="{{ old('numero_documento') }}" required>
              @error('numero_documento')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Fecha de entrada</label>
              <input type="date" name="fecha_entrada" class="form-control @error('fecha_entrada') is-invalid @enderror" 
                     value="{{ old('fecha_entrada') }}" required min="{{ now()->format('Y-m-d') }}">
              @error('fecha_entrada')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Fecha de salida</label>
              <input type="date" name="fecha_salida" class="form-control @error('fecha_salida') is-invalid @enderror" 
                     value="{{ old('fecha_salida') }}" required>
              @error('fecha_salida')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Notas adicionales</label>
              <textarea name="notas" class="form-control" rows="3" placeholder="Peticiones especiales, alergias, etc."></textarea>
            </div>

            <!-- Cálculo de precio -->
            <div class="alert alert-light mb-3">
              <div class="d-flex justify-content-between mb-2">
                <span>Tarifa por noche:</span>
                <span class="fw-bold">${{ number_format($room->price, 2, ',', '.') }}</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span>Cantidad de noches:</span>
                <span class="fw-bold" id="nights">-</span>
              </div>
              <hr class="my-2">
              <div class="d-flex justify-content-between">
                <span class="fw-bold">Total estimado:</span>
                <span class="fw-bold h6 text-danger" id="total">-</span>
              </div>
            </div>

            <button type="submit" class="btn w-100" style="background: linear-gradient(90deg, #b23a3a, #ff6b6b); color: white; font-weight: bold;">
              Reservar ahora
            </button>

            <small class="text-muted d-block mt-3 text-center">
              Procederás al pago después de confirmar la reserva
            </small>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const checkinInput = document.querySelector('input[name="fecha_entrada"]');
    const checkoutInput = document.querySelector('input[name="fecha_salida"]');
    const nightsSpan = document.getElementById('nights');
    const totalSpan = document.getElementById('total');
    const pricePerNight = {{ $room->price }};

    function calculateTotal() {
      if (checkinInput.value && checkoutInput.value) {
        const checkin = new Date(checkinInput.value);
        const checkout = new Date(checkoutInput.value);
        const nights = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
        
        if (nights > 0) {
          nightsSpan.textContent = nights;
          const total = nights * pricePerNight;
          totalSpan.textContent = '$' + total.toLocaleString('es-ES', { minimumFractionDigits: 2 });
        }
      }
    }

    checkinInput.addEventListener('change', calculateTotal);
    checkoutInput.addEventListener('change', calculateTotal);
  });
</script>

@endsection
