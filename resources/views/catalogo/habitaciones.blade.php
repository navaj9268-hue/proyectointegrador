@extends('layouts.app')
@section('title','Catálogo de Habitaciones')
@section('content')

<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-12">
      <h2 class="mb-4">🛏️ Catálogo de Habitaciones</h2>

      <!-- Filtros -->
      <div class="card mb-4">
        <div class="card-body">
          <form method="GET" class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Buscar por estado:</label>
              <select name="status" class="form-select">
                <option value="">-- Todos --</option>
                <option value="disponible" {{ request('status') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                <option value="ocupada" {{ request('status') == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                <option value="mantenimiento" {{ request('status') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Precio máximo:</label>
              <input type="number" name="max_price" class="form-control" value="{{ request('max_price') }}" placeholder="Máximo precio">
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-primary">Buscar</button>
              <a href="{{ route('catalogo.index') }}" class="btn btn-secondary">Borrar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Grid de habitaciones -->
  <div class="row g-4">
    @forelse($rooms as $room)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm hover-shadow" style="transition: transform 0.2s; cursor: pointer;" 
             onclick="window.location='{{ route('catalogo.mostrar', $room->id) }}'">
          
          <!-- Imagen placeholder -->
          <div class="bg-light p-5 text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 3rem;">
            🛏️
          </div>

          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h5 class="card-title mb-0">Habitación {{ $room->number }}</h5>
              <span class="badge bg-{{ $room->status == 'disponible' ? 'success' : ($room->status == 'ocupada' ? 'danger' : 'warning') }}">
                {{ ucfirst($room->status) }}
              </span>
            </div>

            <p class="text-muted small mb-3">
              {{ $room->type ?? 'Habitación estándar' }}
            </p>

            <div class="mb-3">
              <h6 class="text-danger mb-2">
                ${{ number_format($room->price, 2, ',', '.') }}
                <small class="text-muted">/noche</small>
              </h6>
            </div>

            @if($room->notes)
              <p class="small text-muted mb-3">
                {{ Str::limit($room->notes, 80) }}
              </p>
            @endif

            <button class="btn btn-sm w-100" style="background: linear-gradient(90deg, #b23a3a, #ff6b6b); color: white;" 
                    onclick="event.stopPropagation(); window.location='{{ route('catalogo.mostrar', $room->id) }}'">
              Ver detalles y reservar →
            </button>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info text-center py-5">
          <h5>No hay habitaciones disponibles</h5>
          <p>Intenta ajustar tus filtros</p>
        </div>
      </div>
    @endforelse
  </div>

  <!-- Paginación -->
  <div class="row mt-5">
    <div class="col-12">
      {{ $rooms->links() }}
    </div>
  </div>
</div>

<style>
.hover-shadow:hover {
  box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
  transform: translateY(-4px);
}
</style>

@endsection
