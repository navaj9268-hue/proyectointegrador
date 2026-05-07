@extends('layouts.app')

@section('title', 'Ver Vehículo')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-car"></i> Detalles del Vehículo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase">Placa</h6>
                            <p class="h5"><span class="badge bg-primary">{{ $vehicle->license_plate }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase">Estado</h6>
                            <p class="h5">
                                @if($vehicle->status === 'parking')
                                    <span class="badge bg-success">En estacionamiento</span>
                                @else
                                    <span class="badge bg-secondary">Salió</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6 class="text-muted text-uppercase">Marca</h6>
                            <p>{{ $vehicle->brand ?? 'No especificada' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted text-uppercase">Modelo</h6>
                            <p>{{ $vehicle->model ?? 'No especificado' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted text-uppercase">Color</h6>
                            <p>
                                @if($vehicle->color)
                                    <span class="badge" style="background-color: {{ $vehicle->color }}; color: white;">
                                        {{ ucfirst($vehicle->color) }}
                                    </span>
                                @else
                                    No especificado
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase">Entrada</h6>
                            <p>
                                @if($vehicle->entry_date)
                                    {{ $vehicle->entry_date->format('d/m/Y H:i:s') }}
                                @else
                                    No registrada
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase">Salida</h6>
                            <p>
                                @if($vehicle->exit_date)
                                    {{ $vehicle->exit_date->format('d/m/Y H:i:s') }}
                                @else
                                    Aún estacionado
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase">Lugar de Estacionamiento</h6>
                            <p>{{ $vehicle->parking_spot ?? 'No asignado' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase">Huésped</h6>
                            <p>
                                @if($vehicle->reservation && $vehicle->reservation->guest)
                                    {{ $vehicle->reservation->guest->name }}
                                @else
                                    No asociado
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($vehicle->notes)
                        <hr>
                        <div class="mb-3">
                            <h6 class="text-muted text-uppercase">Notas</h6>
                            <p>{{ $vehicle->notes }}</p>
                        </div>
                    @endif

                    <hr>

                    <!-- Botones de Acción -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('vehiculos.edit', $vehicle) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        @if($vehicle->status === 'parking')
                            <form action="{{ route('vehiculos.registrar-salida', $vehicle) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Registrar salida del vehículo?')">
                                    <i class="fas fa-sign-out-alt"></i> Registrar Salida
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">Información del Registro</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-1">Creado</p>
                    <p class="small">{{ $vehicle->created_at->format('d/m/Y H:i') }}</p>

                    <p class="text-muted mb-1">Última actualización</p>
                    <p class="small">{{ $vehicle->updated_at->format('d/m/Y H:i') }}</p>

                    @if($vehicle->exit_date)
                        <hr>
                        <p class="text-muted mb-1">Tiempo de estancia</p>
                        <p class="small">
                            {{ $vehicle->entry_date->diffInHours($vehicle->exit_date) }} horas aproximadamente
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
