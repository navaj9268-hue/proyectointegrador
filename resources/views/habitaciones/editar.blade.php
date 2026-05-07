@extends('layouts.app')
@section('title','Editar habitación')
@section('content')
<div class="d-flex justify-content-between mb-3">
  <h4>Editar habitación #{{ $room->id }}</h4>
  <a href="{{ route('habitaciones.index') }}" class="btn btn-outline-secondary">Volver</a>
</div>

<form method="POST" action="{{ route('habitaciones.update', $room) }}">
  @csrf
  @method('PUT')

  <div class="mb-3">
    <label>Número</label>
    <input name="numero" value="{{ old('numero', $room->numero) }}" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Tipo</label>
    <input name="tipo" value="{{ old('tipo', $room->tipo) }}" class="form-control">
  </div>

  <div class="mb-3">
    <label>Precio</label>
    <input name="precio" value="{{ old('precio', $room->precio) }}" type="number" step="0.01" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Estado</label>
    <select name="status" class="form-select">
      <option value="available" @selected(old('status', $room->status)=='available')>Disponible</option>
      <option value="occupied" @selected(old('status', $room->status)=='occupied')>Ocupada</option>
      <option value="maintenance" @selected(old('status', $room->status)=='maintenance')>Mantenimiento</option>
    </select>
  </div>

  <div class="mb-3">
    <label>Hotel (opcional)</label>
    <select name="hotel_id" class="form-select">
      <option value="">-- Ninguno --</option>
      @foreach(\App\Models\Hotel::all() as $h)
        <option value="{{ $h->id }}" @selected(old('hotel_id', $room->hotel_id)==$h->id)>{{ $h->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="mb-3">
    <label>Notas</label>
    <textarea name="notas" class="form-control" rows="3">{{ old('notas', $room->notas) }}</textarea>
  </div>

  <button class="btn btn-primary">Guardar cambios</button>
  <a href="{{ route('habitaciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</form>
@endsection
