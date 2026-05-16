@extends('layouts.app')
@section('title','Pagos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">💳 Pagos</h3>

        <small class="text-muted">
            Historial y administración de pagos registrados
        </small>
    </div>

    {{-- SOLO ADMIN --}}
    @if(auth()->user()->role === 'admin')

        <a href="{{ route('pagos.create') }}"
           class="btn shadow-sm"
           style="
                background:linear-gradient(90deg,#b23a3a,#ff6b6b);
                color:#fff;
                border:none;
                border-radius:12px;
                padding:10px 18px;
                font-weight:600;
           ">

            + Nuevo pago

        </a>

    @endif

</div>

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-4">

        {{-- FILTROS --}}
        <form method="GET" class="row g-3 mb-4">

            <div class="col-md-4">

                <label class="form-label fw-semibold">
                    Buscar
                </label>

                <input
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Buscar por transacción, método o pagador..."
                    class="form-control rounded-3"
                >

            </div>

            <div class="col-md-4">

                <label class="form-label fw-semibold">
                    Reservación
                </label>

                <select name="reservation_id"
                        class="form-select rounded-3">

                    <option value="">
                        -- Filtrar por reserva --
                    </option>

                    @foreach($reservations as $res)

                        <option value="{{ $res->id }}"
                            @selected(request('reservation_id') == $res->id)>

                            {{ $res->guest->name ?? 'Huésped' }}
                            —
                            Hab {{ $res->room->number ?? '—' }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-2 d-flex align-items-end">

                <button class="btn btn-primary w-100 rounded-3">

                    🔎 Filtrar

                </button>

            </div>

        </form>

        {{-- ALERTA --}}
        @if(session('success'))

            <div class="alert alert-success rounded-3 shadow-sm">

                {{ session('success') }}

            </div>

        @endif

        {{-- TABLA --}}
        <div class="table-responsive">

            <table class="table align-middle">

                <thead style="background:#f8f9fc;">

                    <tr>

                        <th class="fw-bold">Fecha</th>
                        <th class="fw-bold">Monto</th>
                        <th class="fw-bold">Método</th>
                        <th class="fw-bold">Reserva / Huésped</th>
                        <th class="fw-bold">Registró</th>
                        <th class="fw-bold text-end">Acciones</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($payments as $p)

                        <tr>

                            {{-- FECHA --}}
                            <td>

                                <div class="fw-semibold">

                                    {{ $p->created_at->format('d/m/Y') }}

                                </div>

                                <small class="text-muted">

                                    {{ $p->created_at->format('H:i') }}

                                </small>

                            </td>

                            {{-- MONTO --}}
                            <td>

                                <span class="badge bg-success fs-6 px-3 py-2 rounded-pill">

                                    $ {{ number_format($p->amount,2) }}

                                </span>

                            </td>

                            {{-- MÉTODO --}}
                            <td>

                                <div class="fw-semibold">

                                    {{ ucfirst($p->method) }}

                                </div>

                                @if($p->transaction_id)

                                    <small class="text-muted">

                                        TX:
                                        {{ $p->transaction_id }}

                                    </small>

                                @endif

                            </td>

                            {{-- RESERVACIÓN --}}
                            <td>

                                @if($p->reservation)

                                    <div class="fw-semibold">

                                        <a href="{{ route('reservaciones.mostrar', $p->reservation) }}"
                                           class="text-decoration-none">

                                            {{ $p->reservation->guest->name ?? '-' }}

                                        </a>

                                    </div>

                                    <small class="text-muted">

                                        Hab:
                                        {{ $p->reservation->room->number ?? '-' }}

                                        |

                                        {{ $p->reservation->checkin_at->format('d/m/Y') }}

                                    </small>

                                @else

                                    <small class="text-muted">

                                        Pago offline

                                    </small>

                                @endif

                            </td>

                            {{-- USUARIO --}}
                            <td>

                                {{ $p->user->name ?? '-' }}

                            </td>

                            {{-- ACCIONES --}}
                            <td class="text-end">

                                <div class="d-flex justify-content-end gap-2">

                                    {{-- VER --}}
                                    <a class="btn btn-sm btn-light border rounded-3"
                                       href="{{ route('pagos.show', $p) }}">

                                        👁 Ver

                                    </a>

                                    {{-- SOLO ADMIN --}}
                                    @if(auth()->user()->role === 'admin')

                                        {{-- EDITAR --}}
                                        <a class="btn btn-sm btn-primary rounded-3"
                                           href="{{ route('pagos.edit', $p) }}">

                                            ✏ Editar

                                        </a>

                                        {{-- ELIMINAR --}}
                                        <form action="{{ route('pagos.destroy', $p) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Eliminar pago?')">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger rounded-3">

                                                🗑 Eliminar

                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center text-muted py-5">

                                No hay pagos registrados.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINACIÓN --}}
        <div class="d-flex justify-content-between align-items-center mt-4">

            <div class="text-muted small">

                Mostrando

                {{ $payments->firstItem() ?? 0 }}

                -

                {{ $payments->lastItem() ?? 0 }}

                de

                {{ $payments->total() }}

            </div>

            <div>

                {{ $payments->withQueryString()->links() }}

            </div>

        </div>

    </div>

</div>

@endsection