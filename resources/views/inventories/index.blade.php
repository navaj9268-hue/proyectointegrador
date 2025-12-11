@extends('layouts.app')
@section('title','Inventario')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Inventario</h4>
  <div>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCreateInventory">+ Nuevo</button>
  </div>
</div>

<form method="GET" class="mb-3">
  <div class="row g-2">
    <div class="col-md-8">
      <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por artículo o ubicación...">
    </div>
    <div class="col-md-4">
      <button class="btn btn-outline-secondary w-100">Buscar</button>
    </div>
  </div>
</form>

<table class="table table-hover">
  <thead>
    <tr>
      <th>Item</th>
      <th>Cantidad</th>
      <th>Ubicación</th>
      <th>Hotel</th>
      <th class="text-end">Acciones</th>
    </tr>
  </thead>
  <tbody>
    @foreach($inventories as $it)
      <tr>
        <td>{{ $it->item }}</td>
        <td>{{ $it->quantity }}</td>
        <td>{{ $it->location }}</td>
        <td>{{ $it->hotel->name ?? '-' }}</td>
        <td class="text-end">
          <a href="{{ route('inventories.edit', $it) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
          <form action="{{ route('inventories.destroy', $it) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Eliminar?')">Eliminar</button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

{{ $inventories->withQueryString()->links() }}

<!-- Modal crear inventario -->
<div class="modal fade" id="modalCreateInventory" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('inventories.store') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Nuevo artículo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3"><label>Item</label><input name="item" class="form-control" required></div>
        <div class="mb-3"><label>Cantidad</label><input name="quantity" type="number" class="form-control" value="1" required></div>
        <div class="mb-3"><label>Ubicación</label><input name="location" class="form-control"></div>
        <div class="mb-3">
          <label>Hotel (opcional)</label>
          <select name="hotel_id" class="form-select">
            <option value="">-- Ninguno --</option>
            @foreach(\App\Models\Hotel::all() as $h)
              <option value="{{ $h->id }}">{{ $h->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-success">Guardar</button>
      </div>
    </form>
  </div>
</div>
@endsection
