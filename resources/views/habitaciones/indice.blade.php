@extends('layouts.app')
@section('title','Habitaciones')

@section('content')
<style>
  /* Contenedor principal */
  .page-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    margin-bottom:18px;
  }

  .page-title {
    font-size:1.25rem;
    font-weight:700;
    color:#b23a3a;
    display:flex;
    align-items:center;
    gap:.6rem;
  }

  /* Search */
  .search-input .form-control {
    border-radius: 999px 0 0 999px;
    border-right:0;
  }
  .search-input .btn {
    border-radius: 0 999px 999px 0;
    border-left:0;
  }

  /* Table styles */
  .table-modern thead th {
    background: linear-gradient(90deg, rgba(178,58,58,0.06), rgba(255,102,102,0.03));
    color: #6a2a2a;
    font-weight:700;
    border-bottom: none;
  }
  .table-modern tbody tr:hover {
    background: rgba(178,58,58,0.03);
  }
  .price-chip {
    background: rgba(178,58,58,0.08);
    color: #b23a3a;
    padding: 4px 8px;
    border-radius: 999px;
    font-weight:700;
    font-size: .9rem;
  }

  /* Actions */
  .btn-outline-red {
    color: #b23a3a;
    border-color: rgba(178,58,58,0.12);
    background: transparent;
  }
  .btn-outline-red:hover {
    background: rgba(178,58,58,0.04);
  }

  /* Quick card create */
  .fab-create {
    position: fixed;
    right: 28px;
    bottom: 28px;
    z-index: 1050;
    border-radius: 50%;
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    box-shadow: 0 8px 26px rgba(178,58,58,0.16);
    background: linear-gradient(135deg,#b23a3a,#ff6b6b);
    color: #fff;
    border: none;
  }

  /* Responsive: cards on mobile */
  .room-card {
    border-radius: 12px;
    padding: 14px;
    margin-bottom: 12px;
    background: #fff;
    border: 1px solid rgba(178,58,58,0.04);
    box-shadow: 0 8px 20px rgba(37,6,6,0.03);
  }
  @media (max-width: 767px) {
    .table-modern { display:none; }
  }
  @media (min-width: 768px) {
    .room-card { display:none; }
    .fab-create { right: 40px; }
  }

  /* Empty state */
  .empty-state {
    text-align:center;
    padding:28px;
    border-radius:12px;
    background: linear-gradient(180deg, #fff6f6, #fffaf9);
    border:1px dashed rgba(178,58,58,0.08);
    color:#8a5656;
  }
</style>

<div class="page-header mb-3">
  <div class="page-title">
    <span>🛏️</span>
    Habitaciones
    <small class="ms-2 text-muted">({{ $rooms->total() ?? 0 }} en total)</small>
  </div>

  <div class="d-flex gap-2 align-items-center">
    <form method="GET" class="d-flex search-input" style="min-width:280px;">
      <input name="q" value="{{ request('q') }}" placeholder="🔎 Buscar por número o tipo..." class="form-control form-control-sm" />
      <button class="btn btn-sm btn-outline-secondary">Buscar</button>
    </form>

    <a href="{{ route('habitaciones.create') }}" class="btn btn-sm btn-primary d-none d-md-inline-flex" title="Crear habitación" style="background: linear-gradient(90deg,#b23a3a,#ff6b6b); border:none;">
      ➕ Nueva habitación
    </a>
  </div>
</div>

{{-- Mensaje de éxito --}}
@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Tabla para escritorio --}}
<table class="table table-borderless table-modern">
  <thead>
    <tr>
      <th style="width:60px">#</th>
      <th>Habitación</th>
      <th>Tipo</th>
      <th style="width:130px">Precio</th>
      <th style="width:120px">Estado</th>
      <th>Hotel</th>
      <th class="text-end" style="width:160px">Acciones</th>
    </tr>
  </thead>
  <tbody>
    @forelse($rooms as $room)
      <tr>
        <td>{{ $room->id }}</td>
        <td><strong>{{ $room->number }}</strong></td>
        <td>{{ $room->type ?? '-' }}</td>
        <td><span class="price-chip">${{ number_format($room->price, 2) }}</span></td>
        <td>
          @if($room->status == 'available')
            <span class="badge bg-success">Disponible</span>
          @elseif($room->status == 'occupied')
            <span class="badge bg-warning text-dark">Ocupada</span>
          @else
            <span class="badge bg-secondary">Mantenimiento</span>
          @endif
        </td>
        <td>{{ $room->hotel->name ?? '-' }}</td>
        <td class="text-end">
          <a href="{{ route('habitaciones.edit', $room) }}" class="btn btn-sm btn-outline-red me-1">Editar</a>

          <form action="{{ route('habitaciones.destroy', $room) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Eliminar habitación?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger">Eliminar</button>
          </form>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="7">
          <div class="empty-state">
            <h5>No hay habitaciones aún</h5>
            <p class="mb-2">Crea tu primera habitación para que los usuarios puedan reservar.</p>
            <a href="{{ route('habitaciones.create') }}" class="btn btn-sm" style="background: linear-gradient(90deg,#b23a3a,#ff6b6b); color:#fff;">➕ Crear habitación</a>
          </div>
        </td>
      </tr>
    @endforelse
  </tbody>
</table>

{{-- Tarjetas para móvil --}}
<div class="d-md-none">
  @foreach($rooms as $room)
    <div class="room-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="d-flex align-items-baseline gap-2">
            <strong style="font-size:1.05rem">{{ $room->number }}</strong>
            <small class="text-muted">· {{ $room->type ?? '—' }}</small>
          </div>
          <div class="mt-2">
            <span class="price-chip">${{ number_format($room->price,2) }}</span>
            <span class="ms-2">
              @if($room->status == 'available')
                <span class="badge bg-success">Disponible</span>
              @elseif($room->status == 'occupied')
                <span class="badge bg-warning text-dark">Ocupada</span>
              @else
                <span class="badge bg-secondary">Mantenimiento</span>
              @endif
            </span>
          </div>
          <div class="mt-2 text-muted small">Hotel: {{ $room->hotel->name ?? '-' }}</div>
        </div>

        <div class="d-flex flex-column align-items-end gap-2">
          <a href="{{ route('habitaciones.edit', $room) }}" class="btn btn-sm btn-outline-red">Editar</a>
          <form action="{{ route('habitaciones.destroy', $room) }}" method="POST" onsubmit="return confirm('Eliminar habitación?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger">Eliminar</button>
          </form>
        </div>
      </div>
    </div>
  @endforeach
</div>

{{-- Paginación --}}
<div class="d-flex justify-content-between align-items-center mt-3">
  <div class="text-muted small">Mostrando {{ $rooms->firstItem() ?? 0 }} - {{ $rooms->lastItem() ?? 0 }} de {{ $rooms->total() }}</div>
  <div>{{ $rooms->withQueryString()->links() }}</div>
</div>

{{-- Modal Crear (mejorado) --}}
<div class="modal fade" id="modalCreateRoom" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="{{ route('habitaciones.store') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Nueva habitación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        @if ($errors->any() && old('number'))
          <div class="alert alert-danger">
            <strong>Hay errores:</strong>
            <ul class="mb-0">
              @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="mb-3">
          <label class="form-label">Número</label>
          <input name="numero" value="{{ old('numero') }}" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Tipo</label>
          <input name="tipo" value="{{ old('tipo') }}" class="form-control" placeholder="Ej. Doble, Suite">
        </div>

        <div class="row g-2">
          <div class="col-md-6 mb-3">
            <label class="form-label">Precio</label>
            <input name="precio" value="{{ old('precio', '0.00') }}" type="number" step="0.01" class="form-control" required>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
              <option value="available" @selected(old('status')=='available')>Disponible</option>
              <option value="occupied" @selected(old('status')=='occupied')>Ocupada</option>
              <option value="maintenance" @selected(old('status')=='maintenance')>Mantenimiento</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Hotel (opcional)</label>
          <select name="hotel_id" class="form-select">
            <option value="">-- Ninguno --</option>
            @foreach(\App\Models\Hotel::all() as $h)
              <option value="{{ $h->id }}" @selected(old('hotel_id')==$h->id)>{{ $h->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Notas</label>
          <textarea name="notas" class="form-control" rows="3">{{ old('notas') }}</textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" type="submit" style="background: linear-gradient(90deg,#b23a3a,#ff6b6b); border:none;">Crear habitación</button>
      </div>
    </form>
  </div>
</div>

{{-- Botón flotante para crear (mobile/desktop) --}}
<button class="fab-create" data-bs-toggle="modal" data-bs-target="#modalCreateRoom" title="Crear habitación">＋</button>

{{-- Reabrir modal si validación falló y el usuario venía del modal --}}
<script>
  document.addEventListener('DOMContentLoaded', function(){
    @if ($errors->any() && old('number'))
      var modal = new bootstrap.Modal(document.getElementById('modalCreateRoom'));
      modal.show();
    @endif
  });
</script>

@endsection
