@extends('layouts.app')
@section('title','Estacionamiento')
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

.page-header{
    display:flex;justify-content:space-between;align-items:center;
    margin-bottom:25px;flex-wrap:wrap;gap:15px;
}
.page-title{
    font-size:28px;font-weight:700;color:var(--text);
    display:flex;align-items:center;gap:12px;
}
.page-title span{ color:var(--primary); }

/* STAT CARDS */
.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:16px;
    margin-bottom:24px;
}
.stat-card{
    background:var(--card);
    border-radius:18px;
    padding:20px 22px;
    box-shadow:0 4px 20px rgba(0,0,0,0.05);
    border:1px solid rgba(0,0,0,0.03);
    border-left:5px solid var(--accent);
}
.stat-card .label{
    font-size:12px;font-weight:700;text-transform:uppercase;
    letter-spacing:0.8px;color:var(--accent);margin-bottom:8px;
}
.stat-card .value{
    font-size:36px;font-weight:700;color:var(--text);line-height:1;
}
.stat-card .sub{
    font-size:12px;color:#999;margin-top:4px;
}

/* PROGRESS */
.progress-card{
    background:var(--card);border-radius:18px;padding:22px 24px;
    box-shadow:0 4px 20px rgba(0,0,0,0.05);border:1px solid rgba(0,0,0,0.03);
    margin-bottom:24px;
}
.progress-card h6{
    font-weight:700;margin-bottom:14px;color:var(--text);
}
.progress-bar-wrap{
    background:#f0f0f0;border-radius:999px;height:18px;overflow:hidden;
}
.progress-bar-fill{
    height:100%;border-radius:999px;
    background:linear-gradient(90deg,var(--primary),var(--primary-light));
    transition:width 0.8s ease;display:flex;align-items:center;
    justify-content:flex-end;padding-right:10px;
    font-size:11px;font-weight:700;color:#fff;
}

/* MAIN CARD */
.main-card{
    background:var(--card);border-radius:22px;padding:25px;
    box-shadow:0 10px 35px rgba(0,0,0,0.06);
    border:1px solid rgba(0,0,0,0.03);
}

/* SEARCH */
.top-actions{
    display:flex;justify-content:space-between;align-items:center;
    margin-bottom:20px;gap:15px;flex-wrap:wrap;
}
.search-group{ display:flex;gap:10px;flex-wrap:wrap; }
.search-input{
    min-width:240px;height:44px;border-radius:12px;
    border:1px solid var(--border);padding:0 14px;font-size:14px;transition:0.3s;
}
.search-input:focus{
    border-color:var(--primary);
    box-shadow:0 0 0 0.2rem rgba(178,58,58,0.10);outline:none;
}
.select-modern{
    height:44px;border-radius:12px;border:1px solid var(--border);padding:0 12px;font-size:14px;
}
.btn-modern{
    height:44px;padding:0 20px;border:none;border-radius:12px;
    font-weight:600;font-size:14px;cursor:pointer;transition:0.3s;
    display:inline-flex;align-items:center;gap:6px;text-decoration:none;
}
.btn-primary-modern{
    background:linear-gradient(90deg,var(--primary),var(--primary-light));color:#fff;
}
.btn-primary-modern:hover{
    transform:translateY(-2px);box-shadow:0 8px 20px rgba(178,58,58,0.25);color:#fff;
}
.btn-secondary-modern{
    background:#f1f3f9;color:#444;border:1px solid var(--border);
}
.btn-secondary-modern:hover{ background:#e2e6f0; }

/* TABLE */
.table-modern{width:100%;border-collapse:separate;border-spacing:0 10px;}
.table-modern thead th{
    color:#888;font-size:13px;font-weight:600;padding-bottom:8px;
    text-transform:uppercase;letter-spacing:0.4px;
}
.table-modern tbody tr{
    background:#fff;transition:0.2s;
    box-shadow:0 2px 10px rgba(0,0,0,0.03);
}
.table-modern tbody tr:hover{ transform:scale(1.005);box-shadow:0 4px 18px rgba(0,0,0,0.07); }
.table-modern tbody td{
    padding:16px 14px;vertical-align:middle;
    border-top:1px solid #f1f1f1;border-bottom:1px solid #f1f1f1;
}
.table-modern tbody td:first-child{
    border-left:1px solid #f1f1f1;border-radius:14px 0 0 14px;
}
.table-modern tbody td:last-child{
    border-right:1px solid #f1f1f1;border-radius:0 14px 14px 0;
}

/* BADGES */
.badge-placa{
    background:#eef2ff;color:#4f46e5;padding:6px 12px;
    border-radius:8px;font-weight:700;font-size:14px;letter-spacing:1px;
}
.badge-lugar{
    background:#f0fdf4;color:#16a34a;padding:5px 10px;
    border-radius:8px;font-weight:700;font-size:13px;
}
.badge-tiempo{
    background:#fff7ed;color:#ea580c;padding:5px 10px;
    border-radius:8px;font-weight:600;font-size:12px;
}
.badge-estacionado{
    background:#dcfce7;color:#16a34a;padding:5px 12px;
    border-radius:999px;font-size:12px;font-weight:600;
}
.badge-salida{
    background:#fee2e2;color:#dc2626;padding:5px 12px;
    border-radius:999px;font-size:12px;font-weight:600;
}

/* COLOR DOT */
.color-dot{
    width:14px;height:14px;border-radius:50%;display:inline-block;
    border:2px solid rgba(0,0,0,0.1);vertical-align:middle;margin-right:6px;
}

/* ACTIONS */
.btn-accion{
    border-radius:10px;padding:7px 13px;border:none;
    font-size:13px;font-weight:600;cursor:pointer;transition:0.2s;
    text-decoration:none;display:inline-flex;align-items:center;gap:4px;
}
.btn-ver    { background:#eef2ff;color:#4f46e5; }
.btn-editar { background:#f1f3f9;color:#444; }
.btn-salida { background:#fff7ed;color:#ea580c; }
.btn-accion:hover{ opacity:0.8;transform:translateY(-1px); }

/* PAGINATION */
.pagination-simple{
    display:flex;justify-content:space-between;align-items:center;
    margin-top:20px;flex-wrap:wrap;gap:10px;
}
.btn-page{
    height:38px;padding:0 16px;border:1px solid var(--border);border-radius:10px;
    background:#fff;color:var(--text);font-weight:600;cursor:pointer;
    transition:0.2s;text-decoration:none;display:inline-flex;align-items:center;gap:6px;
}
.btn-page:hover{ background:var(--primary);color:#fff;border-color:var(--primary); }
.btn-page.disabled{ opacity:0.4;pointer-events:none; }

/* EMPTY */
.empty-box{ text-align:center;padding:50px 20px; }

@media(max-width:768px){
    .page-title{ font-size:22px; }
    .stats-grid{ grid-template-columns:repeat(2,1fr); }
    .search-input{ min-width:100%;width:100%; }
    .top-actions,.search-group{ flex-direction:column; }
}
</style>

<div class="page-header">
    <div class="page-title">
        <span>🅿</span> Estacionamiento
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a href="{{ route('vehiculos.estacionamiento') }}" class="btn-modern btn-secondary-modern">
            🗺 Mapa de lugares
        </a>
        <a href="{{ route('vehiculos.create') }}" class="btn-modern btn-primary-modern">
            + Registrar vehículo
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
@endif

<!-- STATS -->
<div class="stats-grid">
    <div class="stat-card" style="--accent:#16a34a">
        <div class="label">Estacionados</div>
        <div class="value">{{ $parkedCount }}</div>
        <div class="sub">vehículos activos</div>
    </div>
    <div class="stat-card" style="--accent:#2563eb">
        <div class="label">Disponibles</div>
        <div class="value">{{ $availableSpots }}</div>
        <div class="sub">lugares libres</div>
    </div>
    <div class="stat-card" style="--accent:#7c3aed">
        <div class="label">Capacidad total</div>
        <div class="value">{{ $totalSpots }}</div>
        <div class="sub">lugares totales</div>
    </div>
    <div class="stat-card" style="--accent:{{ $ocupacion >= 80 ? '#dc2626' : ($ocupacion >= 50 ? '#ea580c' : '#16a34a') }}">
        <div class="label">Ocupación</div>
        <div class="value">{{ $ocupacion }}%</div>
        <div class="sub">del estacionamiento</div>
    </div>
</div>

<!-- BARRA DE OCUPACIÓN -->
<div class="progress-card">
    <h6>Indicador de ocupación</h6>
    <div class="progress-bar-wrap">
        <div class="progress-bar-fill" style="width:{{ $ocupacion }}%">
            @if($ocupacion > 10) {{ $ocupacion }}% @endif
        </div>
    </div>
    <small style="color:#999;margin-top:8px;display:block;">
        {{ $parkedCount }} de {{ $totalSpots }} lugares ocupados
    </small>
</div>

<!-- TABLA -->
<div class="main-card">

    <form method="GET" class="top-actions">
        <div class="search-group">
            <input type="text" name="q" value="{{ request('q') }}"
                   class="search-input" placeholder="🔎 Buscar placa, marca...">
            <select name="status" class="select-modern">
                <option value="estacionado" @selected(!request('status') || request('status')==='estacionado')>Estacionados</option>
                <option value="salida"      @selected(request('status')==='salida')>Con salida</option>
                <option value=""            @selected(request('status')==='')>Todos</option>
            </select>
            <button type="submit" class="btn-modern btn-primary-modern">Buscar</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Vehículo</th>
                    <th>Huésped</th>
                    <th>Lugar</th>
                    <th>Entrada</th>
                    <th>Tiempo</th>
                    <th>Estatus</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $v)
                    <tr>
                        <td>
                            <span class="badge-placa">{{ $v->placa }}</span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;">
                                @if($v->color)
                                    <span class="color-dot" style="background:{{ $v->color }}"></span>
                                @endif
                                <div>
                                    <strong>{{ $v->marca }} {{ $v->modelo }}</strong><br>
                                    <small style="color:#999">{{ \App\Models\Vehiculo::tipos()[$v->tipo] ?? $v->tipo }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($v->reservation && $v->reservation->guest)
                                <strong>{{ $v->reservation->guest->name }}</strong><br>
                                <small style="color:#999">Hab: {{ $v->reservation->room->number ?? 'N/A' }}</small>
                            @else
                                <span style="color:#ccc">—</span>
                            @endif
                        </td>
                        <td>
                            @if($v->lugar_estacionamiento)
                                <span class="badge-lugar">{{ $v->lugar_estacionamiento }}</span>
                            @else
                                <span style="color:#ccc">—</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $v->fecha_entrada?->format('d/m/Y') }}</div>
                            <small style="color:#999">{{ $v->fecha_entrada?->format('H:i') }}</small>
                        </td>
                        <td>
                            @if($v->estaEstacionado())
                                <span class="badge-tiempo">{{ $v->tiempoEstancia() }}</span>
                            @else
                                <span style="color:#999;font-size:13px;">{{ $v->tiempoEstancia() }}</span>
                            @endif
                        </td>
                        <td>
                            @if($v->estaEstacionado())
                                <span class="badge-estacionado">🟢 Estacionado</span>
                            @else
                                <span class="badge-salida">🔴 Salida</span>
                            @endif
                        </td>
                        <td class="text-end" style="white-space:nowrap;">
                            <a href="{{ route('vehiculos.show', $v) }}" class="btn-accion btn-ver" title="Ver">👁</a>
                            <a href="{{ route('vehiculos.edit', $v) }}" class="btn-accion btn-editar" title="Editar">✏️</a>
                            @if($v->estaEstacionado())
                                <form action="{{ route('vehiculos.registrar-salida', $v) }}" method="POST" style="display:inline-block;"
                                      onsubmit="return confirm('¿Registrar salida de {{ $v->placa }}?')">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn-accion btn-salida" title="Registrar salida">🚪 Salida</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-box">
                                <div style="font-size:48px;margin-bottom:12px;">🅿️</div>
                                <h5>No hay vehículos</h5>
                                <p style="color:#999">
                                    @if(request('status') === 'salida') No hay registros de salida.
                                    @else El estacionamiento está vacío.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINACIÓN -->
    <div class="pagination-simple">
        <span style="color:#999;font-size:13px;">
            Mostrando {{ $vehicles->firstItem() ?? 0 }} - {{ $vehicles->lastItem() ?? 0 }}
            de {{ $vehicles->total() }}
        </span>
        <div style="display:flex;gap:8px;">
            @if($vehicles->onFirstPage())
                <span class="btn-page disabled">← Anterior</span>
            @else
                <a href="{{ $vehicles->previousPageUrl() }}" class="btn-page">← Anterior</a>
            @endif
            @if($vehicles->hasMorePages())
                <a href="{{ $vehicles->nextPageUrl() }}" class="btn-page">Siguiente →</a>
            @else
                <span class="btn-page disabled">Siguiente →</span>
            @endif
        </div>
    </div>

</div>

@endsection