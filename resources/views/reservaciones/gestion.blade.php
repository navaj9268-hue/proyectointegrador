@extends('layouts.app')
@section('title','Gestión de Reservas')
@section('content')

<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <h2>📋 Gestión de Reservas</h2>
        <a href="{{ route('reservaciones.calendar') }}" class="btn btn-sm btn-outline-primary">
          📆 Ver calendario
        </a>
      </div>
    </div>
  </div>

  <!-- Filtros -->
  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Estado:</label>
          <select name="status" class="form-select">
            <option value="">-- Todos --</option>
            <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Reservada</option>
            <option value="checked_in" {{ request('status') == 'checked_in' ? 'selected' : '' }}>Check-in realizado</option>
            <option value="checked_out" {{ request('status') == 'checked_out' ? 'selected' : '' }}>Check-out realizado</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">Desde:</label>
          <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>

        <div class="col-md-2">
          <label class="form-label">Hasta:</label>
          <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>

        <div class="col-md-3">
          <label class="form-label">Huésped:</label>
          <input type="text" name="guest" class="form-control" placeholder="Buscar por nombre" value="{{ request('guest') }}">
        </div>

        <div class="col-md-2">
          <label class="form-label">Habitación:</label>
          <select name="room_id" class="form-select">
            <option value="">-- Todas --</option>
            @foreach($rooms as $room)
              <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                Hab. {{ $room->number }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-primary">🔍 Filtrar</button>
          <a href="{{ route('reservaciones.management') }}" class="btn btn-secondary">Limpiar filtros</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabla de reservas -->
  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead style="background: #f8f9fa; border-top: 2px solid #dee2e6;">
          <tr>
            <th class="fw-bold">#ID</th>
            <th class="fw-bold">Huésped</th>
            <th class="fw-bold">Habitación</th>
            <th class="fw-bold">Entrada</th>
            <th class="fw-bold">Salida</th>
            <th class="fw-bold">Noches</th>
            <th class="fw-bold">Estado</th>
            <th class="fw-bold">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($reservations as $res)
            <tr class="align-middle">
              <td>
                <span class="badge bg-light text-dark">#{{ $res->id }}</span>
              </td>
              <td>
                <strong>{{ $res->guest?->name ?? 'Sin huésped' }}</strong>
                <br>
                <small class="text-muted">{{ $res->guest?->email ?? '—' }}</small>
              </td>
              <td>
                <strong>{{ $res->room?->number ?? '—' }}</strong>
                <br>
                <small class="text-muted">{{ $res->room?->type ?? '—' }}</small>
              </td>
              <td>
                {{ $res->checkin_at->format('d/m/Y') }}
                <br>
                <small class="text-muted">{{ $res->checkin_at->format('H:i') }}</small>
              </td>
              <td>
                {{ $res->checkout_at->format('d/m/Y') }}
                <br>
                <small class="text-muted">{{ $res->checkout_at->format('H:i') }}</small>
              </td>
              <td class="text-center">
                <strong>{{ $res->checkin_at->diffInDays($res->checkout_at) }}</strong>
              </td>
              <td>
                @if($res->status == 'booked')
                  <span class="badge bg-warning">Reservada</span>
                @elseif($res->status == 'checked_in')
                  <span class="badge bg-success">Check-in</span>
                @elseif($res->status == 'checked_out')
                  <span class="badge bg-secondary">Check-out</span>
                @elseif($res->status == 'cancelled')
                  <span class="badge bg-danger">Cancelada</span>
                @endif
              </td>
              <td>
                <div class="btn-group btn-group-sm">
                  <a href="{{ route('reservaciones.mostrar', $res->id) }}" class="btn btn-outline-primary" title="Ver detalles">👁️</a>
                  <a href="{{ route('reservaciones.edit', $res->id) }}" class="btn btn-outline-warning" title="Editar">✏️</a>
                  <button type="button" class="btn btn-outline-danger" title="Eliminar" 
                          onclick="if(confirm('¿Estás seguro de que deseas eliminar esta reserva?')) { document.getElementById('delete-form-{{ $res->id }}').submit(); }">🗑️</button>
                  
                  <form id="delete-form-{{ $res->id }}" action="{{ route('reservaciones.destroy', $res->id) }}" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-5">
                <div class="alert alert-info mb-0">
                  <p class="text-muted mb-0">❌ No hay reservas que coincidan con los filtros aplicados</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    @if($reservations->hasPages())
      <div class="card-footer">
        {{ $reservations->links() }}
      </div>
    @endif
  </div>

  <!-- Estadísticas -->
  <div class="row g-3 mt-4">
    <div class="col-md-3">
      <div class="card text-center">
        <div class="card-body">
          <h6 class="text-muted mb-2">Total de Reservas</h6>
          <h3 class="text-danger">{{ Illuminate\Support\Facades\DB::table('reservations')->count() }}</h3>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center">
        <div class="card-body">
          <h6 class="text-muted mb-2">Reservadas</h6>
          <h3 class="text-warning">{{ Illuminate\Support\Facades\DB::table('reservations')->where('status', 'booked')->count() }}</h3>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center">
        <div class="card-body">
          <h6 class="text-muted mb-2">Check-in Realizado</h6>
          <h3 class="text-success">{{ Illuminate\Support\Facades\DB::table('reservations')->where('status', 'checked_in')->count() }}</h3>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center">
        <div class="card-body">
          <h6 class="text-muted mb-2">Canceladas</h6>
          <h3 class="text-danger">{{ Illuminate\Support\Facades\DB::table('reservations')->where('status', 'cancelled')->count() }}</h3>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
