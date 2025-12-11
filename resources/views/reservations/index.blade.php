@extends('layouts.app')
@section('title','Reservas')

@section('content')

<style>
    :root {
        --r-red: #b23a3a;
        --muted: #6c6c6c;
        --bg-soft: #fff6f6;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .page-title {
        font-weight: 800;
        color: var(--r-red);
        font-size: 1.35rem;
    }

    .status-pill {
        padding: 4px 10px;
        border-radius: 20px;
        color: white;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-booked { background: #b23a3a; }
    .status-checked_in { background: #ff9800; }
    .status-checked_out { background: #4caf50; }
    .status-cancelled { background: #777; }

    .card-wrapper {
        background: white;
        border-radius: 12px;
        border: 1px solid rgba(178,58,58,0.05);
        padding: 16px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.04);
    }
</style>

<div class="page-header">
    <div class="page-title">📚 Reservas</div>

    <a href="{{ route('reservations.calendar') }}" class="btn btn-sm btn-outline-danger">
        📅 Ver calendario
    </a>
</div>

<div class="card-wrapper">

    <!-- filtros -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar huésped..." class="form-control">
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">-- Estado --</option>
                <option value="booked" @selected(request('status')=='booked')>Reservada</option>
                <option value="checked_in" @selected(request('status')=='checked_in')>Check-in</option>
                <option value="checked_out" @selected(request('status')=='checked_out')>Check-out</option>
                <option value="cancelled" @selected(request('status')=='cancelled')>Cancelada</option>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Buscar</button>
        </div>
    </form>

    <!-- tabla -->
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Huésped</th>
                <th>Habitación</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($reservations as $r)
            <tr>
                <td>{{ $r->guest->name ?? '-' }}</td>
                <td>{{ $r->room->number ?? '-' }}</td>
                <td>{{ $r->checkin_at->format('d/m/Y') }}</td>
                <td>{{ $r->checkout_at->format('d/m/Y') }}</td>

                <td>
                    <span class="status-pill status-{{ $r->status }}">
                        {{ str_replace('_',' ', ucfirst($r->status)) }}
                    </span>
                </td>

                <td class="text-end">
                    <a href="{{ route('reservations.edit', $r->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>

                    <form action="{{ route('reservations.destroy', $r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar reserva?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-3">
                    No hay reservas registradas.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <!-- paginación -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">
            Mostrando {{ $reservations->firstItem() }} - {{ $reservations->lastItem() }} de {{ $reservations->total() }}
        </small>

        <div>
            {{ $reservations->withQueryString()->links() }}
        </div>
    </div>

</div>

@endsection
