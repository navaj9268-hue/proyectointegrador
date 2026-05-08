@extends('layouts.app')
@section('title','Crear inventario')
@section('content')
<div class="d-flex justify-content-between mb-3">
  <h4>Agregar nuevo artículo</h4>
  <a href="{{ route('inventarios.index') }}" class="btn btn-outline-secondary">Volver</a>
</div>

@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('inventarios.store') }}">
  @csrf
  
  <div class="mb-3">
    <label>Hotel <span class="text-danger">*</span></label>
    <select name="hotel_id" class="form-select @error('hotel_id') is-invalid @enderror" required>
      <option value="">-- Selecciona un hotel --</option>
      @foreach($hotels as $h)
        <option value="{{ $h->id }}" @selected(old('hotel_id')==$h->id)>{{ $h->name }}</option>
      @endforeach
    </select>
    @error('hotel_id')
      <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label>Artículo <span class="text-danger">*</span></label>
    <input name="articulo" value="{{ old('articulo') }}" class="form-control @error('articulo') is-invalid @enderror" placeholder="Nombre del artículo" required>
    @error('articulo')
      <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label>Cantidad <span class="text-danger">*</span></label>
    <input name="cantidad" value="{{ old('cantidad') }}" type="number" class="form-control @error('cantidad') is-invalid @enderror" placeholder="0" min="0" required>
    @error('cantidad')
      <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label>Ubicación</label>
    <input name="ubicacion" value="{{ old('ubicacion') }}" class="form-control" placeholder="Ej: Bodega A, Piso 2">
    @error('ubicacion')
      <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label>Notas</label>
    <textarea name="notas" class="form-control" rows="3" placeholder="Observaciones adicionales">{{ old('notas') }}</textarea>
    @error('notas')
      <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
  </div>

  <button type="submit" class="btn btn-primary">Guardar artículo</button>
  <a href="{{ route('inventarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</form>
@endsection
