@extends('layouts.app')

@section('title', 'Editar Vehículo')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit"></i> Editar Vehículo
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('vehiculos.update', $vehicle) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Placa -->
                        <div class="mb-3">
                            <label for="placa" class="form-label">Placa del Vehículo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('placa') is-invalid @enderror" 
                                   id="placa" name="placa" 
                                   value="{{ old('placa', $vehicle->placa) }}" required>
                            @error('placa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Marca -->
                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control @error('marca') is-invalid @enderror" 
                                   id="marca" name="marca" 
                                   value="{{ old('marca', $vehicle->marca) }}">
                            @error('marca')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Modelo -->
                        <div class="mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control @error('modelo') is-invalid @enderror" 
                                   id="modelo" name="modelo" 
                                   value="{{ old('modelo', $vehicle->modelo) }}">
                            @error('modelo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Color -->
                        <div class="mb-3">
                            <label for="color" class="form-label">Color</label>
                            <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                   id="color" name="color" 
                                   value="{{ old('color', $vehicle->color ?? '#000000') }}">
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Reservación -->
                        <div class="mb-3">
                            <label for="reservation_id" class="form-label">Reservación (Opcional)</label>
                            <select class="form-control @error('reservation_id') is-invalid @enderror" 
                                    id="reservation_id" name="reservation_id">
                                <option value="">-- Seleccionar Reservación --</option>
                                @foreach($reservations as $reservation)
                                    <option value="{{ $reservation->id }}" 
                                            {{ old('reservation_id', $vehicle->reservation_id) == $reservation->id ? 'selected' : '' }}>
                                        {{ $reservation->guest->name ?? 'N/A' }} ({{ $reservation->room->number ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('reservation_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Lugar de Estacionamiento -->
                        <div class="mb-3">
                            <label for="lugar_estacionamiento" class="form-label">Lugar de Estacionamiento</label>
                            <input type="text" class="form-control @error('lugar_estacionamiento') is-invalid @enderror" 
                                   id="lugar_estacionamiento" name="lugar_estacionamiento" 
                                   value="{{ old('lugar_estacionamiento', $vehicle->lugar_estacionamiento) }}">
                            @error('lugar_estacionamiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="parking" {{ old('status', $vehicle->status) === 'parking' ? 'selected' : '' }}>
                                    En estacionamiento
                                </option>
                                <option value="left" {{ old('status', $vehicle->status) === 'left' ? 'selected' : '' }}>
                                    Salió
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notas -->
                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas</label>
                            <textarea class="form-control @error('notas') is-invalid @enderror" 
                                      id="notas" name="notas" rows="3">{{ old('notas', $vehicle->notas) }}</textarea>
                            @error('notas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Info de Fechas -->
                        <div class="alert alert-light" role="alert">
                            <strong>Información de Entrada/Salida:</strong><br>
                            Entrada: {{ $vehicle->entry_date?->format('d/m/Y H:i') ?? 'No registrada' }}<br>
                            Salida: {{ $vehicle->exit_date?->format('d/m/Y H:i') ?? 'No registrada' }}
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Actualizar
                            </button>
                            <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
