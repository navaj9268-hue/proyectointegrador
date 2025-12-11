@extends('layouts.app')
@section('title','Editar inventario')
@section('content')
<div class="d-flex justify-content-between mb-3">
  <h4>Editar artículo</h4>
  <a href="{{ route('inventories.index') }}" class="btn btn-outline-secondary">Volver</a>
</div>

<form method="POST" action="{{ route('inventories.update', $inventory) }}">
  @csrf
  @method('PUT')
  <div class="mb-3">
    <label>Item</label>
    <input name="item" value="{{ old('item', $inventory->item) }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Cantidad</label>
    <input name="quantity" value="{{ old('quantity', $inventory->quantity) }}" type="number" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Ubicación</label>
    <input name="location" value="{{ old('location', $inventory->location) }}" class="form-control">
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
  <a href="{{ route('inventories.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</form>
@endsection
