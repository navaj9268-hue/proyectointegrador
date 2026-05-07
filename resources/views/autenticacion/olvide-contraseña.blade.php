@extends('layouts.app')
@section('title','Olvidé Contraseña')

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
          <h3 class="text-red" style="color:#b23a3a"><strong>¿Olvidaste tu contraseña? 🔐</strong></h3>
          <p class="small-muted mb-3">No te preocupes, es algo que pasa a todos. Ingresa tu email y te enviaremos un código de verificación para que puedas resetear tu contraseña.</p>

          <ul class="mb-3">
            <li>Proceso rápido y seguro</li>
            <li>Solo necesitas tu email</li>
            <li>Recibirás un código de 6 dígitos</li>
          </ul>

          <a href="{{ route('login') }}" class="btn btn-red btn-lg">Volver al login</a>
        </div>

        <hr>

        <div>
          <small class="text-muted">Seguridad:</small>
          <p class="small-muted mb-0">Nunca compartimos tus datos personales. Tu email es seguro con nosotros.</p>
        </div>
      </div>

      <!-- DERECHA -->
      <div class="col-md-7 auth-right">
        <h4 class="mb-3" style="color:#b23a3a"><strong>Recuperar Contraseña</strong></h4>

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

        {{-- Mostrar mensajes de status --}}
        @if (session('status'))
          <div class="alert alert-info">
            {!! session('status') !!}
          </div>
        @endif

        <!-- Sección 1: Enviar Código -->
        <div id="section-send-code">
          <h5 class="mb-3" style="color:#b23a3a">📧 Correo Electrónico</h5>
          <form method="POST" action="{{ route('password.email') }}" id="form-send-code">
            @csrf
            <input type="hidden" name="action" value="send_code">

            <div class="mb-3">
              <input name="email" value="{{ old('email') }}" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="tu@ejemplo.com" required>
              @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-red flex-grow-1">Enviar código de recuperación</button>
              <a href="{{ route('login') }}" class="btn btn-red-outline">Cancelar</a>
            </div>
          </form>
        </div>

        <!-- Sección 2: Guardar Contraseña -->
        <div id="section-save-password" style="margin-top: 30px;">
          <form method="POST" action="{{ route('password.email') }}" id="form-save-password">
            @csrf
            <input type="hidden" name="action" value="reset_password">
            <input type="hidden" name="email" id="email-hidden" value="{{ old('email') }}">

            <h5 class="mb-3" style="color:#b23a3a">🔢 Código de Verificación</h5>
            <div class="mb-3">
              <input name="token" type="text" class="form-control @error('token') is-invalid @enderror" placeholder="123456" value="{{ old('token') }}" maxlength="6" pattern="[0-9]{6}" style="letter-spacing: 8px; font-size: 20px; text-align: center; font-family: 'Courier New', monospace;">
              @error('token')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
              <small class="text-muted">Ingresa el código de 6 dígitos que recibirás por email</small>
            </div>

            <h5 class="mb-3" style="color:#b23a3a">🔒 Nueva Contraseña</h5>
            <div class="mb-3">
              <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" value="{{ old('password') }}">
              @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <h5 class="mb-3" style="color:#b23a3a">🔒 Confirmar Contraseña</h5>
            <div class="mb-3">
              <input name="password_confirmation" type="password" class="form-control" placeholder="••••••••" value="{{ old('password_confirmation') }}">
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-red flex-grow-1">Guardar contraseña</button>
              <a href="{{ route('login') }}" class="btn btn-red-outline">Cancelar</a>
            </div>
          </form>
        </div>

        <script>
          // Copiar email del formulario 1 al formulario 2
          const emailInput1 = document.querySelector('#section-send-code input[name="email"]');
          const emailHidden = document.getElementById('email-hidden');
          const formSendCode = document.getElementById('form-send-code');

          if (emailInput1 && emailHidden) {
            // Cuando se envía el primer formulario, copiar el email
            formSendCode.addEventListener('submit', function() {
              emailHidden.value = emailInput1.value;
            });

            // Copiar email al cargar la página si ya hay un valor
            if (emailInput1.value) {
              emailHidden.value = emailInput1.value;
            }
          }

          // Mostrar sección de guardar contraseña cuando se ingresa un código
          const tokenInput = document.querySelector('#section-save-password input[name="token"]');
          const sectionSendCode = document.getElementById('section-send-code');
          const sectionSavePassword = document.getElementById('section-save-password');

          if (tokenInput) {
            tokenInput.addEventListener('input', function() {
              if (this.value.length === 6) {
                sectionSendCode.style.display = 'none';
                sectionSavePassword.style.display = 'block';
              }
            });

            // Verificar estado inicial
            if (tokenInput.value.length === 6) {
              sectionSendCode.style.display = 'none';
              sectionSavePassword.style.display = 'block';
            }
          }
        </script>

        <div class="text-center mt-3 small-muted">
          ¿Recuerdas tu contraseña? <a href="{{ route('login') }}" style="color:#b23a3a;">Inicia sesión aquí</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection