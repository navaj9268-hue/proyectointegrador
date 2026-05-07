@extends('layouts.app')
@section('title','Crear reservación')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Crear reservación</h4>
    <div>
      <a href="{{ route('reservaciones.calendar') }}" class="btn btn-outline-secondary">← Volver al calendario</a>
      <a href="{{ route('reservaciones.crear') }}" class="btn btn-sm btn-light ms-2">Nueva</a>
    </div>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card card-accent p-3 mb-3">
    <div class="card-body">
      <form id="reserveForm" method="POST" action="{{ route('reservaciones.store') }}">
        @csrf

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Check-in</label>
            <input id="checkin" name="fecha_entrada" value="{{ old('fecha_entrada') }}" type="date" class="form-control" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Check-out</label>
            <input id="checkout" name="fecha_salida" value="{{ old('fecha_salida') }}" type="date" class="form-control" required>
          </div>

          <div class="col-md-4 d-flex align-items-end">
            <button type="button" id="btnSearch" class="btn" style="background:linear-gradient(90deg,#b23a3a,#ff6b6b); color:#fff;">🔎 Buscar habitaciones</button>
            <button type="button" id="btnAutoAssign" class="btn btn-outline-secondary ms-2" title="Asignar automáticamente la primera habitación disponible">⚡ Auto-asignar</button>
          </div>

          <div class="col-md-6">
            <label class="form-label">Habitación disponible</label>
            <select id="roomSelect" name="room_id" class="form-select">
              <option value="">-- Buscar fechas y seleccionar --</option>
            </select>
            <div class="form-text">Si no seleccionas habitación se guardará la reserva sin habitación asignada (o se auto-asignará si activas 'Auto-asignar').</div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Nombre del huésped</label>
            <input name="guest_name" value="{{ old('guest_name') }}" class="form-control" required>
          </div>

          <div class="col-12">
            <label class="form-label">Notas</label>
            <textarea name="notas" class="form-control" rows="3">{{ old('notas') }}</textarea>
          </div>

          <div class="col-12 d-flex gap-2">
            <button class="btn btn-success">Crear reserva</button>
            <button type="button" id="btnReset" class="btn btn-outline-secondary">Limpiar</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div id="availableMsg"></div>

  <hr>

  <div class="card p-3">
    <div class="card-body">
      <h6>Instrucciones rápidas</h6>
      <ul class="mb-0">
        <li>1) Selecciona Check-in y Check-out.</li>
        <li>2) Haz click en <strong>Buscar habitaciones</strong> para ver las disponibles.</li>
        <li>3) Selecciona la habitación que desees o pulsa <strong>Auto-asignar</strong> para elegir la primera disponible.</li>
        <li>4) Completa nombre del huésped y guarda.</li>
      </ul>
    </div>
  </div>
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

  // Usamos token inline si no hay meta csrf en layout
  const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

  async function buscar() {
    availableMsg.innerHTML = '';
    roomSelect.innerHTML = '<option>Buscando...</option>';

    const cIn = checkin.value;
    const cOut = checkout.value;
    if (!cIn || !cOut) {
      availableMsg.innerHTML = '<div class="alert alert-warning">Selecciona fecha de entrada y salida primero.</div>';
      roomSelect.innerHTML = '<option value="">-- Selecciona fechas --</option>';
      return [];
    }

    try {
      const url = `{{ route('reservaciones.disponible') }}?checkin=${encodeURIComponent(cIn)}&checkout=${encodeURIComponent(cOut)}`;
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) throw new Error('Error en la búsqueda');
      const data = await res.json();
      if (!data.success) throw new Error('No se pudieron obtener habitaciones');

      const rooms = data.data || [];
      if (rooms.length === 0) {
        roomSelect.innerHTML = '<option value="">-- No hay habitaciones disponibles --</option>';
        availableMsg.innerHTML = '<div class="alert alert-info">No se encontraron habitaciones disponibles para esas fechas.</div>';
      } else {
        roomSelect.innerHTML = '<option value="">-- Selecciona habitación (opcional) --</option>';
        rooms.forEach(r => {
          // r puede venir con campos: id, number, type, price
          const label = `${r.number}${r.type ? ' — '+r.type : ''} ${r.price ? ' — $'+parseFloat(r.price).toFixed(2) : ''}`;
          const opt = document.createElement('option');
          opt.value = r.id;
          opt.textContent = label;
          roomSelect.appendChild(opt);
        });
        availableMsg.innerHTML = `<div class="alert alert-success">Se encontraron ${rooms.length} habitación(es) disponibles.</div>`;
      }
      return rooms;
    } catch (e) {
      console.error(e);
      roomSelect.innerHTML = '<option value="">-- Error al buscar --</option>';
      availableMsg.innerHTML = '<div class="alert alert-danger">Error al buscar habitaciones disponibles.</div>';
      return [];
    }
  }

  btnSearch.addEventListener('click', buscar);

  // Auto-assign: busca y elige la primera
  btnAuto.addEventListener('click', async function(){
    const rooms = await buscar();
    if (rooms.length > 0) {
      roomSelect.value = rooms[0].id;
      availableMsg.innerHTML += '<div class="mt-2"><small class="text-muted">Primera habitación disponible seleccionada automáticamente.</small></div>';
    }
  });

  btnReset.addEventListener('click', function(){
    document.getElementById('reserveForm').reset();
    roomSelect.innerHTML = '<option value="">-- Selecciona fechas --</option>';
    availableMsg.innerHTML = '';
  });

  // Optional: si el usuario cambia fechas y ya había resultados, limpiar el select
  [checkin, checkout].forEach(el => {
    el.addEventListener('change', function(){
      roomSelect.innerHTML = '<option value="">-- Buscar fechas y seleccionar --</option>';
      availableMsg.innerHTML = '';
    });
  });
});
</script>
@endsection
