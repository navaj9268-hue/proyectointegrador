@extends('layouts.app')

@section('title', 'Registrar Vehículo')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-car-plus"></i> Registrar Nuevo Vehículo
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('vehiculos.store') }}" method="POST">
                        @csrf

                        <!-- Placa -->
                        <div class="mb-3">
                            <label for="placa" class="form-label">Placa del Vehículo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('placa') is-invalid @enderror" 
                                   id="placa" name="placa" placeholder="ABC-123" 
                                   value="{{ old('placa') }}" required>
                            @error('placa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Marca -->
                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control @error('marca') is-invalid @enderror" 
                                   id="marca" name="marca" placeholder="Toyota, Honda, etc." 
                                   value="{{ old('marca') }}">
                            @error('marca')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Modelo -->
                        <div class="mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" class="form-control @error('modelo') is-invalid @enderror" 
                                   id="modelo" name="modelo" placeholder="Camry, Civic, etc." 
                                   value="{{ old('modelo') }}">
                            @error('modelo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Color -->
                        <div class="mb-3">
                            <label for="color" class="form-label">Color</label>
                            <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                   id="color" name="color" value="{{ old('color', '#000000') }}">
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
                                            {{ old('reservation_id') == $reservation->id ? 'selected' : '' }}>
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
                                   id="lugar_estacionamiento" name="lugar_estacionamiento" placeholder="A-01, B-05, etc." 
                                   value="{{ old('lugar_estacionamiento') }}">
                            @error('lugar_estacionamiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notas -->
                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas</label>
                            <textarea class="form-control @error('notas') is-invalid @enderror" 
                                      id="notas" name="notas" rows="3" placeholder="Observaciones adicionales...">{{ old('notas') }}</textarea>
                            @error('notas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Registrar Vehículo
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
