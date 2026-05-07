@extends('layouts.app')
@section('title','Resetear Contraseña')

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
</style>

<div class="row justify-content-center">
  <div class="col-lg-10 col-md-11">
    <div class="card auth-card d-flex flex-row">
      <!-- IZQUIERDA -->
      <div class="col-md-5 auth-left">
        <div>
          <h3 class="text-red" style="color:#b23a3a"><strong>Crear Nueva Contraseña ✨</strong></h3>
          <p class="small-muted mb-3">Ingresa una nueva contraseña segura. Asegúrate de que sea diferente a la anterior y que solo tú la conozcas.</p>

          <ul class="mb-3">
            <li>Mínimo 6 caracteres</li>
            <li>Combina letras y números</li>
            <li>Mantén tu contraseña segura</li>
          </ul>
        </div>

        <hr>

        <div>
          <small class="text-muted">💡 Consejo:</small>
          <p class="small-muted mb-0">Usa un administrador de contraseñas para generar contraseñas fuertes y únicas.</p>
        </div>
      </div>

      <!-- DERECHA -->
      <div class="col-md-7 auth-right">
        <h4 class="mb-3" style="color:#b23a3a"><strong>Resetear Contraseña</strong></h4>

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

        <form method="POST" action="{{ route('password.update') }}">
          @csrf

          <div class="mb-3">
            <label class="form-label">📧 Correo Electrónico</label>
            <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="tu@ejemplo.com" required autofocus>
            @error('email')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">🔢 Código de Verificación</label>
            <input name="token" type="text" class="form-control @error('token') is-invalid @enderror" placeholder="123456" value="{{ $token ?? old('token') }}" required maxlength="6" pattern="[0-9]{6}" style="letter-spacing: 8px; font-size: 20px; text-align: center; font-family: 'Courier New', monospace;">
            @error('token')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <small class="text-muted">Ingresa el código de 6 dígitos que recibiste por email</small>
          </div>

          <div class="mb-3">
            <label class="form-label">🔒 Nueva Contraseña</label>
            <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
            @error('password')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">🔒 Confirmar Contraseña</label>
            <input name="password_confirmation" type="password" class="form-control" placeholder="••••••••" required>
          </div>

          <p class="small-muted mb-3">Asegúrate de que ambas contraseñas sean idénticas.</p>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-red flex-grow-1">Resetear Contraseña</button>
            <a href="{{ route('login') }}" class="btn btn-red-outline">Cancelar</a>
          </div>

          <div class="text-center mt-3 small-muted">
            ¿Ya tienes acceso? <a href="{{ route('login') }}" style="color:#b23a3a;">Inicia sesión aquí</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
