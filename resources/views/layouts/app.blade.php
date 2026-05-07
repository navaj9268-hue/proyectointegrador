<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','Hotel')</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root{
      --r-red: #b23a3a;
      --soft-red: #ffecec;
      --muted: #6c6c6c;
      --accent: linear-gradient(135deg, rgba(178,58,58,0.95), rgba(255,102,102,0.95));
    }

    html,body { height:100%; background: #fff9f9; font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }

    /* NAVBAR */
    .topbar {
      background: var(--accent);
      color: #fff;
      box-shadow: 0 6px 18px rgba(178,58,58,0.12);
    }
    .topbar .navbar-brand { font-weight:700; letter-spacing: .2px; color: #fff; }
    .topbar .nav-actions .btn-light {
      background: rgba(255,255,255,0.12);
      color: #fff;
      border: none;
      box-shadow: none;
    }

    /* LAYOUT */
    .app-wrap { display:flex; min-height: calc(100vh - 64px); } /* restamos navbar */
    aside.sidebar {
      width: 250px;
      background: linear-gradient(180deg, #fff1f2 0%, #fff7f7 100%);
      border-right: 1px solid rgba(0,0,0,0.04);
      padding: 22px;
      position: sticky;
      top: 64px;
      height: calc(100vh - 64px);
    }
    .brand-sm { display:flex; align-items:center; gap:.5rem; }

    .sidebar .panel-title { font-weight:700; color: var(--r-red); margin-bottom:6px; }
    .sidebar small.text-muted { color: var(--muted) }

    .nav-link {
      color: #4b3131;
      padding: .6rem .75rem;
      border-radius: 10px;
      transition: all .15s ease;
      display:flex;
      align-items:center;
      gap:8px;
    }
    .nav-link .emoji { margin-right: 8px; font-size: 1.05rem; }
    .nav-link:hover { transform: translateX(6px); background: rgba(178,58,58,0.05); color: var(--r-red); text-decoration:none; }
    .nav-link.active {
      background: linear-gradient(90deg, rgba(178,58,58,0.12), rgba(255,102,102,0.06));
      color: var(--r-red);
      font-weight:700;
      box-shadow: inset 0 1px 0 rgba(255,255,255,0.35);
    }

    /* CONTENT */
    main.content { flex:1; padding: 28px; }
    .card-accent {
      border-radius: 14px;
      border: none;
      box-shadow: 0 8px 22px rgba(37, 6, 6, 0.06);
      overflow: hidden;
    }

    /* Quick cards */
    .quick-card {
      border-radius: 12px;
      padding: 18px;
      background: #fff;
      border: 1px solid rgba(178,58,58,0.04);
      transition: transform .18s ease, box-shadow .18s ease;
    }
    .quick-card:hover { transform: translateY(-6px); box-shadow: 0 10px 30px rgba(178,58,58,0.08); }

    /* Footer small */
    .app-footer { font-size: .85rem; color: var(--muted); margin-top: 18px; }

    /* Responsive */
    @media (max-width: 991px) {
      aside.sidebar { display:none; position:fixed; z-index:1045; left:0; top:64px; height: calc(100vh - 64px); }
      aside.sidebar.show { display:block; }
      .app-wrap { padding-left:0; }
    }

    /* Badge */
    .badge-accent {
      background: rgba(178,58,58,0.12);
      color: var(--r-red);
      font-weight:700;
      border-radius: 999px;
      padding: .25rem .5rem;
    }

    /* small helpers for top quick links */
    .top-quick a { margin-left:6px; }
  </style>
</head>
<body>

<!-- TOP NAVBAR -->
<nav class="navbar topbar navbar-expand-lg">
  <div class="container-fluid">
    <div class="d-flex align-items-center gap-3">
      <button id="btnToggleSidebar" class="btn btn-light d-lg-none" title="Abrir menú">☰</button>

      <a class="navbar-brand d-flex align-items-center" href="{{ route('inicio') }}">
        <span style="font-size:1.2rem; margin-right:.45rem;">🏨</span>
        <span>Hotel <small style="opacity:.85; font-weight:600;">Muñozo</small></span>
      </a>
    </div>

    <div class="collapse navbar-collapse">
      <div class="ms-auto d-flex align-items-center gap-2 nav-actions">
        <form class="d-flex me-2" role="search" action="{{ url()->current() }}" method="GET">
          <input name="q" class="form-control form-control-sm" type="search" placeholder="🔎 Buscar..." value="{{ request('q') }}" style="min-width:200px;">
        </form>

        {{-- quick top links: calendario y pagos --}}
        <div class="top-quick d-none d-md-block">
          <a href="{{ route('reservaciones.calendar') }}" class="btn btn-sm btn-light" title="Calendario">📆 Calendario</a>
          <a href="{{ route('pagos.index') }}" class="btn btn-sm btn-light" title="Pagos">💳 Pagos</a>
        </div>

        @auth
        <div class="d-flex align-items-center gap-2">
          <span class="badge badge-accent">👋 Hola, {{ auth()->user()->name }}</span>

          <div class="dropdown">
            <a class="btn btn-light btn-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span style="margin-right:6px;">👤</span><small>Cuenta</small>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="{{ route('usuarios.edit', auth()->user()) }}">Perfil</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="dropdown-item">Cerrar sesión 🚪</button>
                </form>
              </li>
            </ul>
          </div>
        </div>
        @endauth

        @guest
          <a class="btn btn-sm btn-light" href="{{ route('login') }}">Entrar 🔐</a>
        @endguest
      </div>
    </div>
  </div>
</nav>

<!-- APP WRAP -->
<div class="app-wrap">
  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebarMenu" aria-label="Sidebar">
    <div class="mb-4 d-flex align-items-center justify-content-between">
      <div>
        <div class="brand-sm">
          <span style="font-size:1.25rem;">🧭</span>
          <div>
            <div style="font-weight:800; color:var(--r-red)">Panel</div>
            <small class="text-muted">Control del hotel</small>
          </div>
        </div>
      </div>
      <div>
        <span class="badge bg-danger text-white">Beta</span>
      </div>
    </div>

    <nav class="nav flex-column">
      <a class="nav-link @if(request()->routeIs('inicio')) active @endif" href="{{ route('inicio') }}">
        <span class="emoji">🏠</span> Inicio
      </a>

      <a class="nav-link mt-2 @if(request()->is('habitaciones*')) active @endif" href="{{ route('habitaciones.index') }}">
        <span class="emoji">🛏️</span> Habitaciones
      </a>

      <a class="nav-link mt-2 @if(request()->is('inventarios*')) active @endif" href="{{ route('inventarios.index') }}">
        <span class="emoji">📦</span> Inventario
      </a>

      <a class="nav-link mt-2 @if(request()->is('catalogo*')) active @endif" href="{{ route('catalogo.index') }}">
        <span class="emoji">🛍️</span> Catálogo
      </a>

      <a class="nav-link mt-2 @if(request()->is('usuarios*')) active @endif" href="{{ route('usuarios.index') }}">
        <span class="emoji">👥</span> Usuarios
      </a>

      {{-- Nuevos enlaces añadidos: Calendario y Pagos --}}
      <a class="nav-link mt-3 @if(request()->is('reservaciones*') || request()->routeIs('reservaciones.calendar')) active @endif" href="{{ route('reservaciones.calendar') }}">
        <span class="emoji">📆</span> Calendario
      </a>

      <a class="nav-link mt-2 @if(request()->routeIs('reservaciones.management')) active @endif" href="{{ route('reservaciones.management') }}">
        <span class="emoji">📋</span> Gestión
      </a>

      <a class="nav-link mt-2 @if(request()->is('pagos*')) active @endif" href="{{ route('pagos.index') }}">
        <span class="emoji">💳</span> Pagos
      </a>

      <a class="nav-link mt-2 @if(request()->is('vehiculos*')) active @endif" href="{{ route('vehiculos.index') }}">
        <span class="emoji">🚗</span> Vehículos
      </a>

      <div class="mt-4">
        <hr>
        <small class="text-muted">Atajos</small>
        <ul class="list-unstyled mt-2">
          <li><a class="nav-link" href="{{ route('habitaciones.create') }}">➕ Nueva habitación</a></li>
          <li><a class="nav-link" href="{{ route('inventarios.create') }}">➕ Nuevo inventario</a></li>
        </ul>
      </div>

      <div class="app-footer mt-4">
        <small>❤️ Hecho por Karen Solis Flores </small>
      </div>
    </nav>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="content">
    <div class="container-fluid">
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @yield('content')
    </div>
  </main>
</div>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Toggle sidebar on small screens
  document.getElementById('btnToggleSidebar')?.addEventListener('click', function(){
    const sb = document.getElementById('sidebarMenu');
    sb.classList.toggle('show');
  });

  // Close sidebar when clicking outside (mobile)
  document.addEventListener('click', function(e){
    const sb = document.getElementById('sidebarMenu');
    const btn = document.getElementById('btnToggleSidebar');
    if (!sb || !btn) return;
    if (window.innerWidth < 992 && !sb.contains(e.target) && !btn.contains(e.target)) {
      sb.classList.remove('show');
    }
  });
</script>

@stack('scripts')
</body>
</html>
