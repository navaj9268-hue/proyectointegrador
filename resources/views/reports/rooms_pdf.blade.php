@extends('reports.layout_pdf')

@section('content')
  <h4>Reporte de Habitaciones @if($hotel) - {{ $hotel->name }} @endif</h4>
  @if($qStatus)
    <div class="small">Filtrado por estado: {{ $qStatus }}</div>
  @endif

  <table>
    <thead>
      <tr>
        <th style="width:40px">#</th>
        <th>Habitación</th>
        <th>Tipo</th>
        <th>Precio</th>
        <th>Estado</th>
        <th>Hotel</th>
        <th>Notas</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rooms as $r)
        <tr>
          <td class="center">{{ $r->id }}</td>
          <td>{{ $r->number }}</td>
          <td>{{ $r->type ?? '-' }}</td>
          <td class="right">${{ number_format($r->price,2) }}</td>
          <td>{{ ucfirst($r->status) }}</td>
          <td>{{ $r->hotel->name ?? '-' }}</td>
          <td>{{ Str::limit($r->notes, 80) }}</td>
        </tr>
      @endforeach
      @if($rooms->isEmpty())
        <tr><td colspan="7" class="center small">No hay habitaciones para mostrar.</td></tr>
      @endif
    </tbody>
  </table>
@endsection
