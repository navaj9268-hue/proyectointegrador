@extends('layouts.app')
@section('title','Inicio')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
:root{
    --red:#b23a3a;
    --red-dark:#8c2c2c;
    --red-lite:#fff1f1;
    --muted:#7a6868;
    --radius:18px;
}

body{
    font-family:'DM Sans',sans-serif;
    background:#faf7f7;
}

.dashboard-container{
    max-width:1200px;
    margin:auto;
    padding:25px;
}

/* ================= CLIENTE ================= */

.client-welcome{
    background:linear-gradient(135deg,#8c2c2c,#d45e5e);
    border-radius:25px;
    padding:70px 40px;
    text-align:center;
    color:white;
    box-shadow:0 15px 40px rgba(178,58,58,.18);
}

.client-welcome img{
    width:110px;
    height:110px;
    object-fit:contain;
    border-radius:20px;
    background:rgba(255,255,255,.15);
    padding:15px;
    margin-bottom:25px;
}

.client-welcome h1{
    font-size:3rem;
    font-weight:800;
    margin-bottom:15px;
}

.client-welcome p{
    font-size:1.1rem;
    opacity:.92;
    margin-bottom:35px;
}

.client-actions{
    display:flex;
    justify-content:center;
    gap:15px;
    flex-wrap:wrap;
}

.client-btn{
    padding:14px 28px;
    border-radius:14px;
    text-decoration:none;
    font-weight:700;
    transition:.2s;
}

.client-btn-primary{
    background:white;
    color:var(--red);
}

.client-btn-outline{
    border:2px solid rgba(255,255,255,.35);
    color:white;
}

.client-btn:hover{
    transform:translateY(-3px);
    color:inherit;
}

/* ================= ADMIN ================= */

.hero-banner{
    background:linear-gradient(135deg,#8c2c2c,#d45e5e);
    border-radius:22px;
    padding:35px;
    color:white;
    margin-bottom:25px;
}

.hero-content{
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:20px;
}

.hero-left{
    display:flex;
    align-items:center;
    gap:20px;
}

.hero-logo{
    width:90px;
    height:90px;
    border-radius:18px;
    background:rgba(255,255,255,.15);
    display:flex;
    justify-content:center;
    align-items:center;
}

.hero-logo img{
    width:70px;
}

.hero-title{
    font-size:2rem;
    font-weight:800;
    margin-bottom:8px;
}

.hero-buttons{
    display:flex;
    gap:10px;
}

.hero-btn{
    padding:12px 22px;
    border-radius:12px;
    text-decoration:none;
    font-weight:700;
}

.hero-btn-primary{
    background:white;
    color:var(--red);
}

.hero-btn-outline{
    border:1px solid rgba(255,255,255,.35);
    color:white;
}

.stats-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:18px;
    margin-bottom:25px;
}

.stat-card{
    background:white;
    border-radius:18px;
    padding:25px;
    box-shadow:0 5px 20px rgba(0,0,0,.04);
}

.stat-number{
    font-size:2rem;
    font-weight:800;
    color:var(--red);
}

.quick-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:15px;
}

.quick-card{
    background:white;
    border-radius:18px;
    padding:22px;
    text-decoration:none;
    color:#333;
    box-shadow:0 5px 20px rgba(0,0,0,.04);
    transition:.2s;
}

.quick-card:hover{
    transform:translateY(-4px);
    color:#333;
}

.quick-card h5{
    color:var(--red);
    font-weight:700;
}

@media(max-width:768px){

    .stats-grid{
        grid-template-columns:1fr;
    }

    .quick-grid{
        grid-template-columns:1fr;
    }

    .client-welcome h1{
        font-size:2rem;
    }
}
</style>

<div class="dashboard-container">

{{-- =========================================================
    CLIENTE
========================================================= --}}
@if(auth()->user()->role === 'cliente')

<div class="client-welcome">

    <img src="{{ asset('img/logo.png') }}" alt="Hotel">

    <h1>
        👋 Bienvenido,
        {{ auth()->user()->name }}
    </h1>

    <p>
        Gracias por hospedarte en
        <strong>Hotel Muñoz</strong>.
        Desde aquí puedes consultar habitaciones,
        reservaciones y pagos.
    </p>

    <div class="client-actions">

        <a href="{{ route('catalogo.index') }}"
           class="client-btn client-btn-primary">

            🛏️ Ver habitaciones

        </a>

        <a href="{{ route('reservaciones.calendar') }}"
           class="client-btn client-btn-outline">

            📆 Mis reservaciones

        </a>

        <a href="{{ route('pagos.index') }}"
           class="client-btn client-btn-outline">

            💳 Mis pagos

        </a>

    </div>

</div>

{{-- =========================================================
    ADMIN
========================================================= --}}
@else

<div class="hero-banner">

    <div class="hero-content">

        <div class="hero-left">

            <div class="hero-logo">
                <img src="{{ asset('img/logo.png') }}">
            </div>

            <div>

                <small style="opacity:.8">
                    PANEL DE ADMINISTRACIÓN
                </small>

                <div class="hero-title">
                    ¡Bienvenido,
                    {{ auth()->user()->name }}!
                </div>

                <div>
                    Gestiona habitaciones,
                    inventario y usuarios.
                </div>

            </div>

        </div>

        <div class="hero-buttons">

            <a href="{{ route('habitaciones.create') }}"
               class="hero-btn hero-btn-primary">

                ➕ Crear habitación

            </a>

            <a href="{{ route('inventarios.index') }}"
               class="hero-btn hero-btn-outline">

                📦 Inventario

            </a>

        </div>

    </div>

</div>

<div class="stats-grid">

    <div class="stat-card">

        <div class="stat-number">
            {{ \App\Models\Habitacion::count() }}
        </div>

        <div>Total habitaciones</div>

    </div>

    <div class="stat-card">

        <div class="stat-number">
            {{ \App\Models\Habitacion::where('status','disponible')->count() }}
        </div>

        <div>Disponibles</div>

    </div>

    <div class="stat-card">

        <div class="stat-number">
            {{ \App\Models\Inventario::count() }}
        </div>

        <div>Inventario</div>

    </div>

</div>

<div class="quick-grid">

    <a href="{{ route('habitaciones.index') }}"
       class="quick-card">

        <h5>🛏️ Habitaciones</h5>

        <p>
            Crear, editar y administrar habitaciones.
        </p>

    </a>

    <a href="{{ route('inventarios.index') }}"
       class="quick-card">

        <h5>📦 Inventario</h5>

        <p>
            Administrar artículos e inventario.
        </p>

    </a>

    <a href="{{ route('usuarios.index') }}"
       class="quick-card">

        <h5>👥 Usuarios</h5>

        <p>
            Gestión de usuarios y permisos.
        </p>

    </a>

    <a href="{{ route('reservaciones.calendar') }}"
       class="quick-card">

        <h5>📆 Calendario</h5>

        <p>
            Ver reservaciones del hotel.
        </p>

    </a>

    <a href="{{ route('pagos.index') }}"
       class="quick-card">

        <h5>💳 Pagos</h5>

        <p>
            Historial y control de pagos.
        </p>

    </a>

    <a href="{{ route('vehiculos.index') }}"
       class="quick-card">

        <h5>🚗 Vehículos</h5>

        <p>
            Control de estacionamiento.
        </p>

    </a>

</div>

@endif

</div>

@endsection