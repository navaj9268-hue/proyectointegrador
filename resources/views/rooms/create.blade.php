@extends('layouts.app')
@section('title','Crear habitación')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Crear habitación</h4>
  <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">← Volver</a>
</div>

<div class="card card-accent p-3">
  <div class="card-body">
    <form method="POST" action="{{ route('rooms.store') }}">
      @csrf

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Número</label>
          <input name="number" value="{{ old('number') }}" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Tipo</label>
          <input name="type" value="{{ old('type') }}" class="form-control" placeholder="Ej. Doble, Suite">
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Precio</label>
          <input name="price" value="{{ old('price', '0.00') }}" type="number" step="0.01" class="form-control" required>
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Estado</label>
          <select name="status" class="form-select" required>
            <option value="available" @selected(old('status')=='available')>Disponible</option>
            <option value="occupied" @selected(old('status')=='occupied')>Ocupada</option>
            <option value="maintenance" @selected(old('status')=='maintenance')>Mantenimiento</option>
          </select>
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Hotel (opcional)</label>
          <select name="hotel_id" class="form-select">
            <option value="">-- Ninguno --</option>
            @foreach($hotels as $h)
              <option value="{{ $h->id }}" @selected(old('hotel_id') == $h->id)>{{ $h->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Notas</label>
        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-primary" type="submit">Guardar habitación</button>
        <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>
@endsection
