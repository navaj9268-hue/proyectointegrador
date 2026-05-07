@extends('reportes.plantilla_pdf')

@section('content')
  <h4>Reporte de Inventario @if($hotel) - {{ $hotel->name }} @endif</h4>

  <table>
    <thead>
      <tr>
        <th style="width:40px">#</th>
        <th>Artículo</th>
        <th>Cantidad</th>
        <th>Ubicación</th>
        <th>Hotel</th>
        <th>Notas</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $it)
        <tr>
          <td class="center">{{ $it->id }}</td>
          <td>{{ $it->item }}</td>
          <td class="center">{{ $it->quantity }}</td>
          <td>{{ $it->location ?? '-' }}</td>
          <td>{{ $it->hotel->name ?? '-' }}</td>
          <td>{{ Str::limit($it->notes, 80) }}</td>
        </tr>
      @endforeach
      @if($items->isEmpty())
        <tr><td colspan="6" class="center small">No hay artículos para mostrar.</td></tr>
      @endif
    </tbody>
  </table>
@endsection
