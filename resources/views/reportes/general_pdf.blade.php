@extends('reportes.plantilla_pdf')

@section('content')
  <h3>Reporte General</h3>

  <table>
    <tbody>
      <tr><th style="width:40%;">Hotel</th><td>{{ $hotel->name ?? '-' }}</td></tr>
      <tr><th>Dirección</th><td>{{ $hotel->address ?? '-' }}</td></tr>
      <tr><th>Teléfono</th><td>{{ $hotel->phone ?? '-' }}</td></tr>
      <tr><th>Habitaciones (total)</th><td>{{ $roomsCount }}</td></tr>
      <tr><th>Habitaciones disponibles</th><td>{{ $available }}</td></tr>
      <tr><th>Items en inventario</th><td>{{ $inventories }}</td></tr>
      <tr><th>Usuarios</th><td>{{ $users }}</td></tr>
    </tbody>
  </table>

  <div style="margin-top:12px;">
    <strong>Notas:</strong>
    <p class="small">Este reporte resume los principales indicadores del hotel.</p>
  </div>
@endsection
