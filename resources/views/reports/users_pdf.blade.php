@extends('reports.layout_pdf')

@section('content')
  <h4>Reporte de Usuarios</h4>

  <table>
    <thead>
      <tr>
        <th style="width:40px">#</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Creado</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $u)
        <tr>
          <td class="center">{{ $u->id }}</td>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td class="small">{{ $u->created_at ? $u->created_at->format('Y-m-d') : '-' }}</td>
        </tr>
      @endforeach
      @if($users->isEmpty())
        <tr><td colspan="4" class="center small">No hay usuarios para mostrar.</td></tr>
      @endif
    </tbody>
  </table>
@endsection
