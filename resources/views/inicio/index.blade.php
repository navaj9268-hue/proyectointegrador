@extends('layouts.app')
@section('title','Inicio')

@section('content')

<style>
  :root{
    --r-red: #b23a3a;
    --soft-red: #fff4f4;
    --muted: #6c6c6c;
    --card-shadow: 0 10px 30px rgba(178,58,58,0.06);
  }

  /* Container */
  .home-container { max-width:1200px; margin:0 auto; padding: 20px 16px; }

  /* HERO */
  .hero {
    display:flex;
    gap:18px;
    align-items:center;
    background: linear-gradient(90deg, rgba(178,58,58,0.04), rgba(255,242,242,0.02));
    padding:20px;
    border-radius: 14px;
    box-shadow: var(--card-shadow);
    margin-bottom:20px;
    flex-wrap:wrap;
  }
  .hero .logo {
    width:96px;
    height:96px;
    min-width:96px;
    border-radius:12px;
    background: #fff;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    border: 1px solid rgba(178,58,58,0.06);
  }
  .hero .logo img { width:86px; height:86px; object-fit:contain; }

  .hero .hero-main { flex:1 1 320px; min-width:220px; }
  .hero h2 { margin:0 0 6px 0; color:var(--r-red); font-weight:800; font-size:1.45rem; }
  .hero p { margin:0; color:var(--muted); }

  .hero .hero-actions { display:flex; gap:8px; align-items:center; margin-left:auto; flex: 0 0 auto; }

  /* STATS (separate row, not inside hero) */
  .stats-row { display:flex; gap:12px; margin-top:14px; }
  .stat {
    flex:1;
    border-radius:10px;
    padding:14px;
    background:#fff;
    border:1px solid rgba(178,58,58,0.04);
    box-shadow: 0 6px 18px rgba(178,58,58,0.03);
    display:flex;
    flex-direction:column;
    gap:6px;
    align-items:flex-start;
  }
  .stat .num { font-size:1.4rem; font-weight:800; color:var(--r-red); }
  .stat .label { color:var(--muted); font-size:.9rem; }

  /* GRID: left info / right quick cards */
  .grid { display:grid; grid-template-columns: 320px 1fr; gap:16px; margin-top:18px; align-items:start; }
  .hotel-card {
    border-radius: 12px;
    padding: 18px;
    background: #fff;
    border: 1px solid rgba(178,58,58,0.04);
    box-shadow: 0 8px 20px rgba(37,6,6,0.04);
  }
  .quick-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap:12px; }

  .quick {
    background: linear-gradient(180deg,#fffaf9,#fff2f2);
    border-radius:12px;
    padding:16px;
    border:1px solid rgba(178,58,58,0.04);
    display:flex;
    gap:12px;
    align-items:center;
    transition: transform .14s ease, box-shadow .14s ease;
  }
  .quick:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(178,58,58,0.06); text-decoration:none; }
  .quick .icon {
    width:56px; height:56px; border-radius:10px; display:flex; align-items:center; justify-content:center;
    font-size:1.4rem; background: rgba(178,58,58,0.08); color:var(--r-red);
  }

  /* Recent activity */
  .recent { margin-top:16px; }

  /* Empty / spacing helpers */
  .muted { color:var(--muted); }

  /* RESPONSIVE: stack nicely */
  @media (max-width: 991px) {
    .grid { grid-template-columns: 1fr; }
    .hero { padding:16px; }
    .hero .hero-actions { width:100%; justify-content:flex-start; margin-top:10px; }
    .stats-row { flex-wrap:wrap; }
    .stat { flex: 1 1 45%; }
  }
  @media (max-width: 575px) {
    .stat { flex: 1 1 100%; }
    .hero .logo { width:78px; height:78px; min-width:78px; }
  }
</style>

<div class="home-container">

  <!-- HERO -->
  <div class="hero">
    <div class="logo">
      {{-- Cambia la ruta si tu logo está en public/logo.png o public/img/logo.png --}}
      <img src="{{ asset('img/logo.png') }}" alt="Logo" onerror="this.src='{{ asset('logo.png') }}'">
    </div>

    <div class="hero-main">
      <h2>¡Bienvenido, {{ auth()->user()->name ?? 'Admin' }}! 👋</h2>
      <p>Panel de control del <strong>{{ 'Hotel Muñoz' }}</strong>. Gestiona habitaciones, inventario y usuarios desde aquí.</p>

      <!-- Stats separadas (no dentro del hero visualmente) -->
      <div class="stats-row">
        <div class="stat">
          <div class="num">
            {{ class_exists(\App\Models\Room::class) && \Illuminate\Support\Facades\Schema::hasTable('rooms') ? \App\Models\Room::count() : 0 }}
          </div>
          <div class="label">Habitaciones</div>
        </div>

        <div class="stat">
          <div class="num">
            {{ class_exists(\App\Models\Room::class) && \Illuminate\Support\Facades\Schema::hasTable('rooms') ? \App\Models\Room::where('status','available')->count() : 0 }}
          </div>
          <div class="label">Disponibles</div>
        </div>

        <div class="stat">
          <div class="num">
            {{ class_exists(\App\Models\Inventory::class) && \Illuminate\Support\Facades\Schema::hasTable('inventories') ? \App\Models\Inventory::count() : 0 }}
          </div>
          <div class="label">Items en inventario</div>
        </div>
      </div>
    </div>

    <div class="hero-actions">
      <a href="{{ route('habitaciones.create') }}" class="btn btn-sm" style="background:linear-gradient(90deg,#b23a3a,#ff6b6b); color:#fff; border:none;">➕ Crear habitación</a>
      <a href="{{ route('inventarios.index') }}" class="btn btn-sm btn-outline-secondary">📦 Inventario</a>

      {{-- Botones de reportes PDF integrados --}}
      <div class="btn-group ms-2" role="group" aria-label="Reportes">
        <a href="{{ route('reportes.habitaciones') }}" class="btn btn-sm btn-outline-secondary" title="PDF Habitaciones">📄 Habitaciones</a>
        <a href="{{ route('reportes.inventarios') }}" class="btn btn-sm btn-outline-secondary" title="PDF Inventario">📄 Inventario</a>
        <a href="{{ route('reportes.usuarios') }}" class="btn btn-sm btn-outline-secondary" title="PDF Usuarios">📄 Usuarios</a>
        <a href="{{ route('reportes.general') }}" class="btn btn-sm" style="background:linear-gradient(90deg,#b23a3a,#ff6b6b); color:#fff;" title="Reporte general">📊 General</a>
      </div>
    </div>
  </div>

  <!-- GRID: left info / right quick actions -->
  <div class="grid">
    <!-- LEFT -->
    <div>
      <div class="hotel-card">
        <h5 style="color:var(--r-red); margin-bottom:8px;">{{ $hotel->name ?? 'Hotel Muñoz' }}</h5>

        <div class="mb-2 muted">
          <div><strong>Dirección:</strong> {{  '....' }}</div>
          <div><strong>Teléfono:</strong> {{  '+52 55 1234 5678' }}</div>
          <div><strong>Email:</strong> {{ 'hotelMuñoz@gmail.com' }}</div>
        </div>

        <p class="muted small mb-3">
          {{ \Illuminate\Support\Str::limit( 'Contamos con informacion   sobre servicios, ubicación y comodidades.', 200) }}
        </p>

        <div style="display:flex; gap:8px;">
        
          <a href="{{ route('usuarios.index') }}" class="btn btn-sm" style="background:linear-gradient(90deg,#b23a3a,#ff6b6b); color:#fff; border:none;">👥 Usuarios</a>
        </div>

        <div class="recent">
          <h6 style="margin-top:16px; margin-bottom:8px; color:var(--r-red);">Actividad reciente</h6>
          <div class="list-group">
            <div class="list-group-item d-flex justify-content-between align-items-start">
              <div>
                <strong>Reserva nueva</strong>
                <div class="small muted">Usuario: juan.perez — Habitación 101</div>
              </div>
              <small class="muted">Hace 2h</small>
            </div>

            <div class="list-group-item d-flex justify-content-between align-items-start">
              <div>
                <strong>Inventario actualizado</strong>
                <div class="small muted">Se añadieron 10 toallas al almacén</div>
              </div>
              <small class="muted">Hace 1d</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT -->
    <div>
      <div class="quick-grid">
        <a href="{{ route('habitaciones.index') }}" class="quick text-decoration-none">
          <div class="icon">🛏️</div>
          <div>
            <h5 style="margin:0; color:var(--r-red);">Habitaciones</h5>
            <p class="muted small mb-0">Crear, editar y ver estado de habitaciones.</p>
          </div>
        </a>

        <a href="{{ route('inventarios.index') }}" class="quick text-decoration-none">
          <div class="icon">📦</div>
          <div>
            <h5 style="margin:0; color:var(--r-red);">Inventario</h5>
            <p class="muted small mb-0">Controla artículos y stock.</p>
          </div>
        </a>

        <a href="{{ route('usuarios.index') }}" class="quick text-decoration-none">
          <div class="icon">👥</div>
          <div>
            <h5 style="margin:0; color:var(--r-red);">Usuarios</h5>
            <p class="muted small mb-0">Gestión de usuarios del sistema.</p>
          </div>
        </a>

        <a href="{{ route('habitaciones.create') }}" class="quick text-decoration-none">
          <div class="icon">➕</div>
          <div>
            <h5 style="margin:0; color:var(--r-red);">Nueva habitación</h5>
            <p class="muted small mb-0">Crear habitación desde aquí.</p>
          </div>
        </a>
      </div>
    </div>
  </div>

</div>

@endsection
