<!doctype html>
<html lang="es">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width,initial-scale=1">

    {{-- CSRF TOKEN --}}
    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        @yield('title','Hotel')
    </title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        :root{
            --r-red:#b23a3a;
            --soft-red:#ffecec;
            --muted:#6c6c6c;

            --accent:linear-gradient(
                135deg,
                rgba(178,58,58,0.95),
                rgba(255,102,102,0.95)
            );
        }

        html,body{
            height:100%;
            background:#fff9f9;
            font-family:Inter,system-ui,-apple-system,"Segoe UI",Roboto;
        }

        /* NAVBAR */

        .topbar{
            background:var(--accent);
            color:#fff;
            box-shadow:0 6px 18px rgba(178,58,58,.12);
        }

        .topbar .navbar-brand{
            font-weight:700;
            color:#fff;
        }

        .topbar .nav-actions .btn-light{
            background:rgba(255,255,255,.12);
            color:#fff;
            border:none;
        }

        /* LAYOUT */

        .app-wrap{
            display:flex;
            min-height:calc(100vh - 64px);
        }

        aside.sidebar{
            width:250px;

            background:linear-gradient(
                180deg,
                #fff1f2 0%,
                #fff7f7 100%
            );

            border-right:1px solid rgba(0,0,0,.04);

            padding:22px;

            position:sticky;

            top:64px;

            height:calc(100vh - 64px);

            overflow-y:auto;
        }

        .nav-link{

            color:#4b3131;

            padding:.7rem .8rem;

            border-radius:12px;

            transition:.2s;

            display:flex;

            align-items:center;

            gap:10px;

            font-weight:500;
        }

        .nav-link:hover{

            background:rgba(178,58,58,.08);

            color:var(--r-red);

            transform:translateX(4px);
        }

        .nav-link.active{

            background:linear-gradient(
                90deg,
                rgba(178,58,58,.12),
                rgba(255,102,102,.06)
            );

            color:var(--r-red);

            font-weight:700;
        }

        main.content{
            flex:1;
            padding:28px;
        }

        .badge-accent{
            background:rgba(255,255,255,.18);
            color:#fff;
            border-radius:999px;
            padding:.45rem .8rem;
            font-weight:700;
        }

        .app-footer{
            font-size:.85rem;
            color:#999;
        }

        @media(max-width:991px){

            aside.sidebar{
                display:none;
                position:fixed;
                z-index:1045;
                left:0;
                top:64px;
            }

            aside.sidebar.show{
                display:block;
            }
        }

    </style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar topbar navbar-expand-lg">

    <div class="container-fluid">

        <div class="d-flex align-items-center gap-3">

            @auth

            <button id="btnToggleSidebar"
                    class="btn btn-light d-lg-none">
                ☰
            </button>

            @endauth

            <a class="navbar-brand d-flex align-items-center"
               href="{{ auth()->check() ? route('inicio') : route('login') }}">

                <span style="font-size:1.2rem; margin-right:.45rem;">
                    🏨
                </span>

                <span>
                    Hotel Muñozo
                </span>

            </a>

        </div>

        @auth

        <div class="collapse navbar-collapse">

            <div class="ms-auto d-flex align-items-center gap-2 nav-actions">

                {{-- BUSCADOR --}}
                <form class="d-flex me-2"
                      role="search"
                      action="{{ url()->current() }}"
                      method="GET">

                    <input name="q"
                           class="form-control form-control-sm"
                           type="search"
                           placeholder="🔎 Buscar..."
                           value="{{ request('q') }}"
                           style="min-width:220px;">
                </form>

                {{-- SOLO ADMIN --}}
                @if(auth()->user()->role === 'admin')

                <div class="top-quick d-none d-md-block">

                    <a href="{{ route('reservaciones.calendar') }}"
                       class="btn btn-sm btn-light">
                        📆 Calendario
                    </a>

                    <a href="{{ route('pagos.index') }}"
                       class="btn btn-sm btn-light">
                        💳 Pagos
                    </a>

                </div>

                @endif

                {{-- USUARIO --}}
                <div class="d-flex align-items-center gap-2">

                    <span class="badge badge-accent">

                        👋 Hola,

                        {{ auth()->user()->role === 'admin'
                            ? 'Administrador'
                            : 'Cliente'
                        }}

                    </span>

                    <div class="dropdown">

                        <a class="btn btn-light btn-sm dropdown-toggle"
                           href="#"
                           data-bs-toggle="dropdown">

                            👤 Cuenta
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>

                                <a class="dropdown-item"
                                   href="{{ route('usuarios.profile') }}">

                                    Perfil
                                </a>

                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>

                                <form method="POST"
                                      action="{{ route('logout') }}">

                                    @csrf

                                    <button class="dropdown-item">

                                        Cerrar sesión 🚪

                                    </button>

                                </form>

                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

        @endauth

        {{-- INVITADO --}}
        @guest

        <div class="ms-auto">

            <a class="btn btn-sm btn-light"
               href="{{ route('login') }}">

                Entrar 🔐

            </a>

        </div>

        @endguest

    </div>

</nav>

<!-- APP -->
<div class="app-wrap">

    {{-- SIDEBAR --}}
    @auth

    <aside class="sidebar"
           id="sidebarMenu">

        {{-- =========================
             ADMIN
        ========================== --}}
        @if(auth()->user()->role === 'admin')

        <nav class="nav flex-column">

            <a class="nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}"
               href="{{ route('inicio') }}">

                🏠 Inicio

            </a>

            <a class="nav-link mt-2"
               href="{{ route('habitaciones.index') }}">

                🛏️ Habitaciones

            </a>

            <a class="nav-link mt-2"
               href="{{ route('inventarios.index') }}">

                📦 Inventario

            </a>

            <a class="nav-link mt-2"
               href="{{ route('catalogo.index') }}">

                🛍️ Catálogo

            </a>

            <a class="nav-link mt-2"
               href="{{ route('usuarios.index') }}">

                👥 Usuarios

            </a>

            <a class="nav-link mt-2"
               href="{{ route('reservaciones.calendar') }}">

                📆 Calendario

            </a>

            <a class="nav-link mt-2"
               href="{{ route('reservaciones.management') }}">

                📋 Gestión

            </a>

            <a class="nav-link mt-2"
               href="{{ route('pagos.index') }}">

                💳 Pagos

            </a>

            <a class="nav-link mt-2"
               href="{{ route('vehiculos.index') }}">

                🚗 Vehículos

            </a>

        </nav>

        @endif

        {{-- =========================
             CLIENTE
        ========================== --}}
        @if(auth()->user()->role === 'cliente')

        <nav class="nav flex-column">

            <a class="nav-link {{ request()->is('catalogo*') ? 'active' : '' }}"
               href="{{ route('catalogo.index') }}">

                🛏️ Habitaciones

            </a>

            <a class="nav-link mt-2 {{ request()->routeIs('reservaciones.calendar') ? 'active' : '' }}"
               href="{{ route('reservaciones.calendar') }}">

                📆 Mis reservaciones

            </a>

            <a class="nav-link mt-2 {{ request()->is('pagos*') ? 'active' : '' }}"
               href="{{ route('pagos.index') }}">

                💳 Mis pagos

            </a>

        </nav>

        @endif

    </aside>

    @endauth

    {{-- CONTENIDO --}}
    <main class="content">

        <div class="container-fluid">

            @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

            @endif

            @yield('content')

        </div>

    </main>

</div>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

document.getElementById('btnToggleSidebar')
?.addEventListener('click', function(){

    const sb =
        document.getElementById('sidebarMenu');

    if(sb){

        sb.classList.toggle('show');
    }

});

</script>

@stack('scripts')

</body>
</html>