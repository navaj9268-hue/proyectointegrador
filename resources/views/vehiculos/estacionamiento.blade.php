@extends('layouts.app')

@section('title', 'Panel de Estacionamiento')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-parking"></i> Panel de Estacionamiento
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('vehiculos.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Registrar Vehículo
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-left-success h-100">
                <div class="card-body">
                    <div class="text-success font-weight-bold text-uppercase mb-2">Vehículos Estacionados</div>
                    <div class="h2 mb-0 text-dark">{{ $parkedCount }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-left-warning h-100">
                <div class="card-body">
                    <div class="text-warning font-weight-bold text-uppercase mb-2">Lugares Disponibles</div>
                    <div class="h2 mb-0 text-dark">{{ $availableSpots }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-left-info h-100">
                <div class="card-body">
                    <div class="text-info font-weight-bold text-uppercase mb-2\">Capacidad Total</div>
                    <div class=\"h2 mb-0 text-dark\">{{ $totalSpots }}</div>
                </div>
            </div>
        </div>

        <div class=\"col-lg-3 col-md-6 mb-3\">
            <div class=\"card border-left-primary h-100\">
                <div class=\"card-body\">
                    <div class=\"text-primary font-weight-bold text-uppercase mb-2\">% Ocupación</div>
                    <div class=\"h2 mb-0 text-dark\">{{ round(($parkedCount / $totalSpots) * 100) }}%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicador Visual de Ocupación -->
    <div class=\"card shadow mb-4\">
        <div class=\"card-header bg-light\">
            <h5 class=\"mb-0\">Indicador de Ocupación</h5>
        </div>
        <div class=\"card-body\">
            <div class=\"progress\" style=\"height: 30px;\">
                <div class=\"progress-bar bg-success\" role=\"progressbar\" 
                     style=\"width: {{ ($parkedCount / $totalSpots) * 100 }}%\"
                     aria-valuenow=\"{{ $parkedCount }}\" aria-valuemin=\"0\" aria-valuemax=\"{{ $totalSpots }}\">
                    {{ round(($parkedCount / $totalSpots) * 100) }}% ocupado
                </div>
            </div>
            <small class=\"text-muted mt-2\">
                {{ $parkedCount }} de {{ $totalSpots }} lugares ocupados
            </small>
        </div>
    </div>

    <!-- Tabla de Vehículos Estacionados -->
    <div class=\"card shadow\">
        <div class=\"card-header bg-light\">
            <h5 class=\"mb-0\">Vehículos Actualmente Estacionados</h5>
        </div>
        <div class=\"card-body\">
            @if($vehicles->count() > 0)
                <div class=\"table-responsive\">
                    <table class=\"table table-hover mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th>Placa</th>
                                <th>Vehículo</th>
                                <th>Huésped</th>
                                <th>Lugar</th>
                                <th>Hora Entrada</th>
                                <th>Tiempo Estancia</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicles as $vehicle)
                                <tr>
                                    <td>
                                        <span class=\"badge bg-primary fs-6\">{{ $vehicle->license_plate }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $vehicle->brand }} {{ $vehicle->model }}</strong>
                                            @if($vehicle->color)
                                                <span class=\"badge ms-2\" style=\"background-color: {{ $vehicle->color }}; color: white;\">
                                                    {{ ucfirst($vehicle->color) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($vehicle->reservation && $vehicle->reservation->guest)
                                            <div>
                                                <strong>{{ $vehicle->reservation->guest->name }}</strong><br>
                                                <small class=\"text-muted\">Hab: {{ $vehicle->reservation->room->number ?? 'N/A' }}</small>
                                            </div>
                                        @else
                                            <span class=\"text-muted\">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($vehicle->parking_spot)
                                            <span class=\"badge bg-secondary\">{{ $vehicle->parking_spot }}</span>
                                        @else
                                            <span class=\"text-muted\">Sin asignar</span>
                                        @endif
                                    </td>
                                    <td>{{ $vehicle->entry_date?->format('H:i') ?? '-' }}</td>
                                    <td>
                                        <span class=\"badge bg-info\">
                                            {{ now()->diffInHours($vehicle->entry_date) }}h
                                        </span>
                                    </td>
                                    <td>
                                        <a href=\"{{ route('vehiculos.show', $vehicle) }}\" class=\"btn btn-sm btn-info\" title=\"Ver\">
                                            👁️
                                        </a>
                                        <a href=\"{{ route('vehiculos.edit', $vehicle) }}\" class=\"btn btn-sm btn-warning\" title=\"Editar\">
                                            ✏️
                                        </a>
                                        <form action=\"{{ route('vehiculos.registrar-salida', $vehicle) }}\" method=\"POST\" class=\"d-inline\">
                                            @csrf
                                            @method('PUT')
                                            <button type=\"submit\" class=\"btn btn-sm btn-danger\" title=\"Registrar Salida\"
                                                    onclick=\"return confirm('¿Registrar salida del vehículo?')\">
                                                🚪
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class=\"d-flex justify-content-between align-items-center mt-3\">
                    <div class=\"text-muted small\">
                        Mostrando {{ $vehicles->firstItem() }} a {{ $vehicles->lastItem() }} de {{ $vehicles->total() }} vehículos
                    </div>
                    {{ $vehicles->links() }}
                </div>
            @else
                <div class=\"alert alert-success mb-0\">
                    <i class=\"fas fa-check-circle\"></i> ¡Estacionamiento vacío! No hay vehículos registrados.
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .border-left-success {
        border-left: 5px solid #28a745 !important;
    }
    .border-left-warning {
        border-left: 5px solid #ffc107 !important;
    }
    .border-left-info {
        border-left: 5px solid #17a2b8 !important;
    }
    .border-left-primary {
        border-left: 5px solid #007bff !important;
    }
</style>
@endsection
