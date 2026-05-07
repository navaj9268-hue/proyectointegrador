@extends('layouts.app')

@section('title', 'Gestión de Vehículos')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-car"></i> Gestión de Vehículos
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('vehiculos.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Registrar Vehículo
            </a>

        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="text-success font-weight-bold text-uppercase mb-1">Vehículos Estacionados</div>
                    <div class="h3 mb-0">{{ $parkedCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="text-warning font-weight-bold text-uppercase mb-1">Lugares Disponibles</div>
                    <div class="h3 mb-0">{{ $totalSpots - $parkedCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="text-info font-weight-bold text-uppercase mb-1">Total de Lugares</div>
                    <div class="h3 mb-0">{{ $totalSpots }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="text-primary font-weight-bold text-uppercase mb-1">Ocupación</div>
                    <div class="h3 mb-0">{{ round(($parkedCount / $totalSpots) * 100) }}%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Vehículos -->
    <div class="card shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Historial de Vehículos</h5>
        </div>
        <div class="card-body">
            @if($vehicles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Placa</th>
                                <th>Marca / Modelo</th>
                                <th>Color</th>
                                <th>Huésped</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicles as $vehicle)
                                <tr>
                                    <td>
                                        <span class="badge badge-primary">{{ $vehicle->license_plate }}</span>
                                    </td>
                                    <td>{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                                    <td>
                                        @if($vehicle->color)
                                            <span class="badge" style="background-color: {{ $vehicle->color }}">{{ ucfirst($vehicle->color) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($vehicle->reservation)
                                            {{ $vehicle->reservation->guest->name ?? 'N/A' }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $vehicle->entry_date?->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td>{{ $vehicle->exit_date?->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td>
                                        @if($vehicle->status === 'parking')
                                            <span class="badge bg-success">En estacionamiento</span>
                                        @else
                                            <span class="badge bg-secondary">Salió</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('vehiculos.show', $vehicle) }}" class="btn btn-info" title="Ver">
                                                👁️
                                            </a>
                                            <a href="{{ route('vehiculos.edit', $vehicle) }}" class="btn btn-warning" title="Editar">
                                                ✏️
                                            </a>
                                            @if($vehicle->status === 'parking')
                                                <form action="{{ route('vehiculos.registrar-salida', $vehicle) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger" title="Registrar Salida" onclick="return confirm('¿Registrar salida del vehículo?')">
                                                        🚪
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('vehiculos.destroy', $vehicle) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Eliminar" onclick="return confirm('¿Eliminar vehículo?')">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Mostrando {{ $vehicles->firstItem() }} a {{ $vehicles->lastItem() }} de {{ $vehicles->total() }} vehículos
                    </div>
                    {{ $vehicles->links() }}
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> No hay vehículos registrados.
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }
    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }
    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }
    .border-left-primary {
        border-left: 4px solid #007bff !important;
    }
</style>
@endsection
