@extends('layouts.app')
@section('title','Editar reservación')

@section('content')

<div class="container">

  <div class="d-flex justify-content-between align-items-center mb-3">

    <h4>Editar reservación</h4>

    <div>

      <a href="{{ route('reservaciones.calendar') }}"
         class="btn btn-outline-secondary">

        ← Volver al calendario

      </a>

      {{-- SOLO ADMIN --}}
      @if(auth()->user()->role === 'admin')

        <a href="{{ route('reservaciones.management') }}"
           class="btn btn-sm btn-light ms-2">

          Lista

        </a>

      @endif

    </div>

  </div>

  @if ($errors->any())

    <div class="alert alert-danger">

      <ul class="mb-0">

        @foreach ($errors->all() as $e)

          <li>{{ $e }}</li>

        @endforeach

      </ul>

    </div>

  @endif

  @if(session('success'))

    <div class="alert alert-success">

      {{ session('success') }}

    </div>

  @endif

  <div class="card card-accent p-3 mb-3">

    <div class="card-body">

      <form id="reserveForm"
            method="POST"
            action="{{ route('reservaciones.update', $reservation) }}">

        @csrf
        @method('PUT')

        <div class="row g-3">

          <div class="col-md-4">

            <label class="form-label">
              Check-in
            </label>

            <input
                id="checkin"
                name="fecha_entrada"
                value="{{ old('fecha_entrada', $reservation->checkin_at?->format('Y-m-d')) }}"
                type="date"
                class="form-control"
                required>

          </div>

          <div class="col-md-4">

            <label class="form-label">
              Check-out
            </label>

            <input
                id="checkout"
                name="fecha_salida"
                value="{{ old('fecha_salida', $reservation->checkout_at?->format('Y-m-d')) }}"
                type="date"
                class="form-control"
                required>

          </div>

          <div class="col-md-4 d-flex align-items-end">

            <button
                type="button"
                id="btnSearch"
                class="btn"
                style="background:linear-gradient(90deg,#b23a3a,#ff6b6b); color:#fff;">

              🔎 Buscar habitaciones

            </button>

            <button
                type="button"
                id="btnAutoAssign"
                class="btn btn-outline-secondary ms-2"
                title="Asignar automáticamente">

              ⚡ Auto-asignar

            </button>

          </div>

          <div class="col-md-6">

            <label class="form-label">
              Habitación disponible
            </label>

            <select id="roomSelect"
                    name="room_id"
                    class="form-select">

              <option value="">
                -- Buscar fechas y seleccionar --
              </option>

              @if($reservation->room)

                <option value="{{ $reservation->room->id }}" selected>

                  {{ $reservation->room->number }}

                  {{ $reservation->room->type
                      ? ' — '.$reservation->room->type
                      : '' }}

                  {{ $reservation->room->price
                      ? ' — $'.number_format($reservation->room->price,2)
                      : '' }}

                </option>

              @endif

            </select>

            <div class="form-text">
              Si no seleccionas habitación se guardará sin habitación asignada.
            </div>

          </div>

          <div class="col-md-6">

            <label class="form-label">
              Nombre del huésped
            </label>

            <input
                name="guest_name"
                value="{{ old('guest_name', $reservation->guest?->name ?? '') }}"
                class="form-control"
                required>

          </div>

          <div class="col-12">

            <label class="form-label">
              Notas
            </label>

            <textarea
                name="notas"
                class="form-control"
                rows="3">{{ old('notas', $reservation->notes) }}</textarea>

          </div>

          <div class="col-12 d-flex gap-2">

            <button class="btn btn-primary">

              Actualizar reserva

            </button>

            <button
                type="button"
                id="btnReset"
                class="btn btn-outline-secondary">

              Limpiar

            </button>

          </div>

        </div>

      </form>

    </div>

  </div>

  <div id="availableMsg"></div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function(){

  const btnSearch = document.getElementById('btnSearch');
  const btnAuto = document.getElementById('btnAutoAssign');

  const checkin = document.getElementById('checkin');
  const checkout = document.getElementById('checkout');

  const roomSelect = document.getElementById('roomSelect');

  const availableMsg = document.getElementById('availableMsg');

  const btnReset = document.getElementById('btnReset');

  async function buscar() {

    availableMsg.innerHTML = '';

    roomSelect.innerHTML =
      '<option>Buscando...</option>';

    const cIn = checkin.value;
    const cOut = checkout.value;

    if (!cIn || !cOut) {

      availableMsg.innerHTML =
        '<div class="alert alert-warning">Selecciona fechas primero.</div>';

      roomSelect.innerHTML =
        '<option value="">-- Selecciona fechas --</option>';

      return [];

    }

    try {

      const url =
        `{{ route('reservaciones.disponible') }}?checkin=${encodeURIComponent(cIn)}&checkout=${encodeURIComponent(cOut)}`;

      const res = await fetch(url, {
        headers: {
          'Accept': 'application/json'
        }
      });

      if (!res.ok)
        throw new Error('Error en búsqueda');

      const data = await res.json();

      const rooms = data.data || [];

      if (rooms.length === 0) {

        roomSelect.innerHTML =
          '<option value="">-- No disponibles --</option>';

        availableMsg.innerHTML =
          '<div class="alert alert-info">No hay habitaciones disponibles.</div>';

      } else {

        roomSelect.innerHTML =
          '<option value="">-- Selecciona habitación --</option>';

        rooms.forEach(r => {

          const opt = document.createElement('option');

          opt.value = r.id;

          opt.textContent =
            `${r.number} — ${r.type ?? ''} — $${parseFloat(r.price).toFixed(2)}`;

          roomSelect.appendChild(opt);

        });

        availableMsg.innerHTML =
          `<div class="alert alert-success">
            ${rooms.length} habitación(es) disponibles.
          </div>`;

      }

      return rooms;

    } catch (e) {

      console.error(e);

      availableMsg.innerHTML =
        '<div class="alert alert-danger">Error al buscar habitaciones.</div>';

      return [];

    }

  }

  btnSearch.addEventListener('click', buscar);

  btnAuto.addEventListener('click', async function(){

    const rooms = await buscar();

    if (rooms.length > 0) {

      roomSelect.value = rooms[0].id;

    }

  });

  btnReset.addEventListener('click', function(){

    document.getElementById('reserveForm').reset();

    roomSelect.innerHTML =
      '<option value="">-- Buscar fechas y seleccionar --</option>';

    availableMsg.innerHTML = '';

  });

});

</script>

@endsection