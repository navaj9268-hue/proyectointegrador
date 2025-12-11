@extends('layouts.app')
@section('title','Login')

@section('content')
<style>
  .auth-card {
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(37,6,6,0.06);
  }
  .auth-left {
    background: linear-gradient(180deg, #fff6f6, #fff1f1);
    padding: 28px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 14px;
  }
  .auth-right {
    background: #ffffff;
    padding: 28px;
  }
  .brand-logo {
    max-width: 140px;
    height: auto;
    display: block;
    margin-left: auto;
    margin-bottom: 10px;
  }
  .btn-red {
    background: linear-gradient(90deg,#b23a3a,#ff6b6b);
    color: #fff;
    border: none;
    box-shadow: 0 6px 18px rgba(178,58,58,0.12);
  }
  .btn-red-outline {
    background: transparent;
    border: 1px solid rgba(178,58,58,0.12);
    color: #b23a3a;
  }
  .small-muted { color: #7a6a6a; }

  /* Responsive: hide image panel on small screens */
  @media (max-width: 767px) {
    .auth-image-panel { display: none; }
  }
</style>

<div class="row justify-content-center">
  <div class="col-lg-10 col-md-11">
    <div class="card auth-card d-flex flex-row">
      <!-- IZQUIERDA: Invitación / Registro (según pediste colocar registro a la izquierda) -->
      <div class="col-md-5 auth-left">
        <div>
          <h3 class="text-red" style="color:#b23a3a"><strong>¿Aún no tienes cuenta? 🤝</strong></h3>
          <p class="small-muted mb-3">Regístrate y gestiona reservas, habitaciones e inventario desde un panel sencillo y rápido.</p>

          <ul class="mb-3">
            <li>Crear y editar habitaciones</li>
            <li>Control de inventario</li>
            <li>Gestión de usuarios</li>
          </ul>

          <a href="{{ route('register') }}" class="btn btn-red btn-lg">¡Crear cuenta ahora! ✨</a>
        </div>

        <hr>

        <div>
          <small class="text-muted">Consejo:</small>
          <p class="small-muted mb-0">Usa una contraseña segura y guarda tus credenciales en un administrador de contraseñas.</p>
        </div>
      </div>

      <!-- DERECHA: Formulario de login + logo -->
      <div class="col-md-7 auth-right">
        <div class="d-flex justify-content-end auth-image-panel">
          <!-- Logo, usa public/logo.png -->
          <img src="{{ asset('img/logo.png') }}" alt="Logo" class="brand-logo">
        </div>

        <h4 class="mb-3" style="color:#b23a3a"><strong>Iniciar sesión</strong></h4>

        {{-- Mostrar errores de validación --}}
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ url('login') }}">
          @csrf

          <div class="mb-3">
            <label class="form-label">📧 Email</label>
            <input name="email" value="{{ old('email') }}" type="email" class="form-control" placeholder="tu@ejemplo.com" required autofocus>
          </div>

          <div class="mb-3">
            <label class="form-label">🔒 Contraseña</label>
            <input name="password" type="password" class="form-control" placeholder="••••••••" required>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
              <label class="form-check-label small-muted" for="remember">Recordarme</label>
            </div>

            <div>
              <a href="#" class="small-muted">¿Olvidaste tu contraseña?</a>
            </div>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-red flex-grow-1">Entrar</button>
            <a href="{{ route('register') }}" class="btn btn-red-outline">Registro</a>
          </div>

          <div class="text-center mt-3 small-muted">
            ¿No quieres registrarte ahora? Usa:
            <div class="mt-2">
              <strong>hotelMuñoz.com</strong>  <strong></strong>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
