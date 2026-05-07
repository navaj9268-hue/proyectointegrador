@extends('layouts.app')
@section('title','Editar inventario')
@section('content')
<div class="d-flex justify-content-between mb-3">
  <h4>Editar artículo</h4>
  <a href="{{ route('inventarios.index') }}" class="btn btn-outline-secondary">Volver</a>
</div>

<form method="POST" action="{{ route('inventarios.update', $inventory) }}">
  @csrf
  @method('PUT')
  <div class="mb-3">
    <label>Artículo</label>
    <input name="articulo" value="{{ old('articulo', $inventory->articulo) }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Cantidad</label>
    <input name="cantidad" value="{{ old('cantidad', $inventory->cantidad) }}" type="number" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Ubicación</label>
    <input name="ubicacion" value="{{ old('ubicacion', $inventory->ubicacion) }}" class="form-control">
  </div>
  <div class="mb-3">
    <label>Notas</label>
    <textarea name="notas" class="form-control" rows="2">{{ old('notas', $inventory->notas) }}</textarea>
  </div>
  <div class="mb-3">
    <label>Hotel (opcional)</label>
    <select name="hotel_id" class="form-select">
      <option value="">-- Ninguno --</option>
      @foreach(\App\Models\Hotel::all() as $h)
        <option value="{{ $h->id }}" @selected(old('hotel_id', $inventory->hotel_id)==$h->id)>{{ $h->name }}</option>
      @endforeach
    </select>
  </div>

  <button class="btn btn-primary">Guardar</button>
  <a href="{{ route('inventarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</form>
@endsection
