@extends('layouts.app')
@section('title','Pagos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Pagos</h4>
  <a href="{{ route('pagos.create') }}" class="btn btn-sm" style="background:linear-gradient(90deg,#b23a3a,#ff6b6b); color:#fff;">+ Nuevo pago</a>
</div>

<div class="card p-3">
  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
      <input name="q" value="{{ request('q') }}" placeholder="Buscar por transacción, método o pagador..." class="form-control">
    </div>

    <div class="col-md-3">
      <select name="reservation_id" class="form-select">
        <option value="">-- Filtrar por reserva --</option>
        @foreach($reservations as $res)
          <option value="{{ $res->id }}" @selected(request('reservation_id') == $res->id)>{{ $res->guest->name ?? 'Huésped' }} — {{ $res->room->number ?? '—' }} ({{ $res->checkin_at->format('d/m/Y') }})</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-2">
      <button class="btn btn-primary w-100">Filtrar</button>
    </div>
  </form>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Monto</th>
          <th>Método</th>
          <th>Reserva / Huésped</th>
          <th>Registró</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($payments as $p)
          <tr>
            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
            <td>$ {{ number_format($p->amount,2) }}</td>
            <td>{{ ucfirst($p->method) }} @if($p->transaction_id) <br><small class="text-muted">TX: {{ $p->transaction_id }}</small>@endif</td>
            <td>
              @if($p->reservation)
                <a href="{{ route('reservaciones.mostrar', $p->reservation) }}">{{ $p->reservation->guest->name ?? '-' }}</a><br>
                <small class="text-muted">Hab: {{ $p->reservation->room->number ?? '-' }} | {{ $p->reservation->checkin_at->format('d/m/Y') }} → {{ $p->reservation->checkout_at->format('d/m/Y') }}</small>
              @else
                <small class="text-muted">Pago suelto / offline</small>
              @endif
            </td>
            <td>{{ $p->user->name ?? '-' }}</td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-secondary" href="{{ route('pagos.show', $p) }}">Ver</a>
              <a class="btn btn-sm btn-outline-primary" href="{{ route('pagos.edit', $p) }}">Editar</a>
              <form action="{{ route('pagos.destroy', $p) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Eliminar pago?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted">No hay pagos registrados.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-between align-items-center mt-3">
    <div class="text-muted small">Mostrando {{ $payments->firstItem() ?? 0 }} - {{ $payments->lastItem() ?? 0 }} de {{ $payments->total() }}</div>
    <div>{{ $payments->withQueryString()->links() }}</div>
  </div>
</div>
@endsection
