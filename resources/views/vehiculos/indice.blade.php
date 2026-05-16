@extends('layouts.app')
@section('title','Vehículos')

@section('content')

<style>

:root{
    --primary:#b23a3a;
    --primary-light:#ff6b6b;
    --bg:#f6f7fb;
    --card:#ffffff;
    --text:#2c2c2c;
    --border:#ececec;
}

body{
    background:var(--bg);
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    flex-wrap:wrap;
    gap:15px;
}

.page-title{
    font-size:30px;
    font-weight:700;
    color:var(--text);
}

.main-card{
    background:var(--card);
    border-radius:24px;
    padding:25px;
    box-shadow:0 10px 35px rgba(0,0,0,0.06);
}

.search-box{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-bottom:25px;
}

.search-input,
.select-modern{
    height:48px;
    border-radius:12px;
    border:1px solid var(--border);
    padding:0 15px;
}

.search-input{
    min-width:260px;
}

.btn-modern{
    border:none;
    border-radius:12px;
    padding:12px 22px;
    font-weight:600;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:6px;
}

.btn-primary-modern{
    background:linear-gradient(90deg,var(--primary),var(--primary-light));
    color:#fff;
}

.table-modern{
    width:100%;
    border-collapse:separate;
    border-spacing:0 10px;
}

.table-modern thead th{
    font-size:13px;
    text-transform:uppercase;
    color:#888;
    padding-bottom:10px;
}

.table-modern tbody tr{
    background:#fff;
    box-shadow:0 4px 15px rgba(0,0,0,0.03);
}

.table-modern tbody td{
    padding:16px 14px;
    border-top:1px solid #f1f1f1;
    border-bottom:1px solid #f1f1f1;
}

.table-modern tbody td:first-child{
    border-left:1px solid #f1f1f1;
    border-radius:14px 0 0 14px;
}

.table-modern tbody td:last-child{
    border-right:1px solid #f1f1f1;
    border-radius:0 14px 14px 0;
}

.badge-estacionado{
    background:#dcfce7;
    color:#15803d;
    padding:7px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

.badge-salida{
    background:#fee2e2;
    color:#dc2626;
    padding:7px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

.btn-action{
    border:none;
    border-radius:10px;
    padding:8px 12px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
}

.btn-view{
    background:#eef2ff;
    color:#4f46e5;
}

.btn-edit{
    background:#f1f3f9;
    color:#444;
}

.btn-delete{
    background:#fee2e2;
    color:#dc2626;
}

.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:18px;
    margin-bottom:25px;
}

.stat-card{
    background:#fff;
    border-radius:18px;
    padding:20px;
    box-shadow:0 4px 15px rgba(0,0,0,0.04);
}

.stat-label{
    font-size:13px;
    text-transform:uppercase;
    color:#888;
    font-weight:700;
    margin-bottom:10px;
}

.stat-value{
    font-size:34px;
    font-weight:700;
}

</style>

<div class="page-header">

    <div class="page-title">
        🚗 Vehículos
    </div>

    <div class="d-flex gap-2">

        <a href="{{ route('vehiculos.estacionamiento') }}"
           class="btn-modern btn-light">

            🅿 Estacionamiento

        </a>

        <a href="{{ route('vehiculos.create') }}"
           class="btn-modern btn-primary-modern">

            + Registrar

        </a>

    </div>

</div>

<!-- STATS -->

<div class="stats-grid">

    <div class="stat-card">

        <div class="stat-label">
            Estacionados
        </div>

        <div class="stat-value">
            {{ $parkedCount }}
        </div>

    </div>

    <div class="stat-card">

        <div class="stat-label">
            Disponibles
        </div>

        <div class="stat-value">
            {{ $availableSpots }}
        </div>

    </div>

    <div class="stat-card">

        <div class="stat-label">
            Capacidad
        </div>

        <div class="stat-value">
            {{ $totalSpots }}
        </div>

    </div>

    <div class="stat-card">

        <div class="stat-label">
            Ocupación
        </div>

        <div class="stat-value">
            {{ $ocupacion }}%
        </div>

    </div>

</div>

<div class="main-card">

    @if(session('success'))

        <div class="alert alert-success mb-4">

            {{ session('success') }}

        </div>

    @endif

    <!-- BUSQUEDA -->

    <form method="GET" class="search-box">

        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            class="search-input"
            placeholder="🔎 Buscar placa, marca..."
        >

        <select name="status" class="select-modern">

            <option value="">
                Todos
            </option>

            <option value="estacionado"
                @selected(request('status') == 'estacionado')>

                Estacionados

            </option>

            <option value="salida"
                @selected(request('status') == 'salida')>

                Salida

            </option>

        </select>

        <button class="btn-modern btn-primary-modern">

            Buscar

        </button>

    </form>

    <!-- TABLA -->

    <div class="table-responsive">

        <table class="table-modern">

            <thead>

                <tr>

                    <th>Placa</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Tipo</th>
                    <th>Lugar</th>
                    <th>Estado</th>
                    <th>Entrada</th>
                    <th class="text-end">Acciones</th>

                </tr>

            </thead>

            <tbody>

                @forelse($vehicles as $v)

                    <tr>

                        <td>
                            <strong>{{ $v->placa }}</strong>
                        </td>

                        <td>
                            {{ $v->marca }}
                        </td>

                        <td>
                            {{ $v->modelo }}
                        </td>

                        <td>

                            {{ \App\Models\Vehiculo::tipos()[$v->tipo] ?? $v->tipo }}

                        </td>

                        <td>

                            {{ $v->lugar_estacionamiento ?? 'N/A' }}

                        </td>

                        <td>

                            @if($v->estaEstacionado())

                                <span class="badge-estacionado">

                                    🟢 Estacionado

                                </span>

                            @else

                                <span class="badge-salida">

                                    🔴 Salida

                                </span>

                            @endif

                        </td>

                        <td>

                            {{ $v->fecha_entrada?->format('d/m/Y H:i') }}

                        </td>

                        <td class="text-end">

                            <a href="{{ route('vehiculos.show',$v) }}"
                               class="btn-action btn-view">

                                👁

                            </a>

                            <a href="{{ route('vehiculos.edit',$v) }}"
                               class="btn-action btn-edit">

                                ✏️

                            </a>

                            <form
                                action="{{ route('vehiculos.destroy',$v) }}"
                                method="POST"
                                style="display:inline-block;"
                                onsubmit="return confirm('¿Eliminar vehículo?')"
                            >

                                @csrf
                                @method('DELETE')

                                <button class="btn-action btn-delete">

                                    🗑

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8">

                            <div class="text-center py-5">

                                <h5>
                                    No hay vehículos registrados
                                </h5>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <!-- PAGINACION -->

    <div class="mt-4">

        {{ $vehicles->withQueryString()->links() }}

    </div>

</div>

@endsection