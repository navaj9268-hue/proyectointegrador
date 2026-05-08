@extends('layouts.app')
@section('title','Inventario')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Inventario</h4>
  <div>
    <a href="{{ route('inventarios.create') }}" class="btn btn-success">+ Nuevo artículo</a>
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
          <a href="{{ route('inventarios.edit', $it) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
          <form action="{{ route('inventarios.destroy', $it) }}" method="POST" style="display:inline-block;">
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
@endsection
