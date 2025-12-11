@extends('layouts.app')
@section('title','Registro')

@section('content')
<style>
  :root{
    --r-red: #b23a3a;
    --muted: #6c6c6c;
    --card-radius: 12px;
  }

  .auth-wrap {
    max-width:1000px;
    margin:28px auto;
    padding:18px;
  }

  .auth-card {
    display:grid;
    grid-template-columns: 1fr 420px;
    gap:18px;
    align-items:stretch;
  }

  /* left: form */
  .form-panel {
    background:#fff;
    border-radius:var(--card-radius);
    padding:22px;
    box-shadow: 0 10px 30px rgba(37,6,6,0.06);
    border:1px solid rgba(178,58,58,0.03);
  }
  .form-panel h3 { color:var(--r-red); font-weight:800; margin-bottom:6px; }
  .form-panel p.lead { margin:0 0 14px 0; color:var(--muted); }

  .pw-meter-wrap{
    margin-top:8px;
  }
  .pw-meter{
    height:8px;
    border-radius:8px;
    background:#eee;
    overflow:hidden;
  }
  .pw-fill{
    height:100%;
    width:0%;
    background:#ff4d4d;
    transition: width .28s ease, background .28s ease;
  }

  .pw-reqs { margin-top:8px; display:flex; gap:10px; flex-wrap:wrap; font-size:.92rem; color:var(--muted); }
  .pw-req { display:flex; gap:8px; align-items:center; min-width:160px; }
  .pw-req .dot { width:10px; height:10px; border-radius:50%; background:#ddd; display:inline-block; }
  .pw-req.ok .dot { background:#00c853; box-shadow: 0 4px 8px rgba(0,200,83,0.08); }
  .pw-req.warn .dot { background:#f7a531; }

  .pw-text{
    font-size:0.95rem;
    margin-top:6px;
    font-weight:700;
  }

  .match-indicator { display:inline-flex; gap:8px; align-items:center; margin-left:8px; font-weight:700; }

  /* tooltip simple */
  .info-tooltip { cursor:help; display:inline-block; border-radius:6px; padding:4px 6px; font-size:.9rem; color:var(--muted); }
  .tt-box {
    display:none;
    position:absolute;
    z-index:50;
    background:#fff;
    border:1px solid rgba(0,0,0,0.08);
    padding:10px;
    border-radius:8px;
    box-shadow:0 8px 20px rgba(0,0,0,0.06);
    width:260px;
    font-size:.9rem;
    color:#333;
  }

  /* shake animation */
  @keyframes shake {
    0% { transform: translateX(0); }
    20% { transform: translateX(-6px); }
    40% { transform: translateX(6px); }
    60% { transform: translateX(-4px); }
    80% { transform: translateX(4px); }
    100% { transform: translateX(0); }
  }
  .shake { animation: shake .5s cubic-bezier(.36,.07,.19,.97); }

  /* right: visual */
  .visual-panel {
    border-radius:var(--card-radius);
    overflow:hidden;
    background: linear-gradient(180deg, #fff8f8, #fff4f4);
    border:1px solid rgba(178,58,58,0.03);
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    padding:18px;
  }
  .visual-top { display:flex; gap:12px; align-items:center; }
  .visual-top img { width:68px; height:68px; object-fit:contain; border-radius:10px; background:#fff; padding:8px; }
  .visual-hero { margin-top:12px; font-weight:700; color:var(--r-red); font-size:1.05rem; }
  .visual-sub { color:var(--muted); margin-top:6px; font-size:.95rem; }

  .visual-footer { margin-top:18px; font-size:.9rem; color:var(--muted); }

  .btn-primary-red {
    background: linear-gradient(90deg,#b23a3a,#ff6b6b);
    color:#fff;
    border:none;
    padding:10px 14px;
    border-radius:10px;
    box-shadow: 0 8px 22px rgba(178,58,58,0.08);
  }

  .separator { height:1px; background: linear-gradient(90deg, transparent, rgba(0,0,0,0.06), transparent); margin:14px 0; border-radius:4px; }

  @media (max-width: 991px) {
    .auth-card { grid-template-columns: 1fr; }
    .visual-panel { order: -1; }
  }

  .error-text { color:#b23a3a; font-size:.92rem; }
  .pw-toggle { cursor:pointer; user-select:none; color:var(--muted); font-size:.95rem; }
</style>

<div class="auth-wrap">
  <div class="auth-card">

    <!-- FORM PANEL -->
    <div class="form-panel" id="formPanel">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
          <h3>Crear cuenta</h3>
          <p class="lead">Regístrate para administrar el hotel y comenzar a gestionar reservas.</p>
        </div>
        <div class="text-end">
          <small class="help-small">¿Ya tienes cuenta?</small><br>
          <a href="{{ route('login') }}" class="small" style="color:var(--r-red); font-weight:700;">Inicia sesión →</a>
        </div>
      </div>

      {{-- Global validation errors --}}
      @if($errors->any())
        <div class="alert alert-danger">
          <strong>Corrige los siguientes errores:</strong>
          <ul class="mb-0 mt-1">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ url('register') }}" id="registerForm" novalidate>
        @csrf

        <div class="mb-3">
          <label class="form-label">Nombre completo</label>
          <input name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
          @error('name') <div class="error-text mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input name="email" value="{{ old('email') }}" type="email" class="form-control @error('email') is-invalid @enderror" required>
          @error('email') <div class="error-text mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- CONTRASEÑA CON MEDIDOR --}}
        <div class="row g-2 mb-2">
          <div class="col-md-6">
            <label class="form-label d-flex align-items-center">
              Contraseña
              <span class="info-tooltip ms-2" id="infoTip">ℹ️</span>
            </label>

            <div class="input-group" id="pwdGroup">
              <input id="password" name="password" type="password" autocomplete="new-password" class="form-control @error('password') is-invalid @enderror" required>
              <span class="input-group-text pw-toggle" id="togglePassword" title="Mostrar/ocultar">👁️</span>
              <span class="match-indicator" id="matchIcon" style="display:none;"></span>
            </div>

            {{-- Barra de fortaleza --}}
            <div class="pw-meter-wrap">
              <div class="pw-meter"><div id="pwFill" class="pw-fill"></div></div>
              <div id="pwText" class="pw-text"> </div>
            </div>

            {{-- Requisitos visibles --}}
            <div class="pw-reqs" id="pwReqs">
              <div class="pw-req" id="req-length"><span class="dot"></span> Mín. 6 caracteres</div>
              <div class="pw-req" id="req-upper"><span class="dot"></span> Contiene mayúscula</div>
              <div class="pw-req" id="req-lower"><span class="dot"></span> Contiene minúscula</div>
              <div class="pw-req" id="req-number"><span class="dot"></span> Contiene número</div>
              <div class="pw-req" id="req-symbol"><span class="dot"></span> Contiene símbolo</div>
            </div>

            @error('password') <div class="error-text mt-1">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">Confirmar contraseña</label>
            <div class="input-group">
              <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="form-control" required>
              <span class="input-group-text" id="pwMatchText" style="display:none;">✔️</span>
            </div>
            <div id="pwMatchMsg" style="margin-top:6px; font-weight:700;"></div>
          </div>
        </div>

        <div class="mb-3 form-check">
          <input class="form-check-input" type="checkbox" value="1" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }}>
          <label class="form-check-label" for="terms">Acepto los <a href="#">términos y condiciones</a>.</label>
        </div>

        <div style="display:flex; gap:12px; align-items:center;">
          <button class="btn-primary-red" type="submit" id="submitBtn">Crear cuenta</button>
          <a href="{{ url('/') }}" class="btn btn-outline-secondary">Volver al inicio</a>
        </div>
      </form>
    </div>

    <!-- VISUAL PANEL -->
    <aside class="visual-panel">
      <div>
        <div class="visual-top">
          <img src="{{ asset('img/logo.png') }}" onerror="this.src='{{ asset('logo.png') }}'" alt="Logo">
          <div>
            <div class="visual-hero">Únete a <strong>{{ $hotel->name ?? 'HOTEL MUÑOZ' }}</strong></div>
            <div class="visual-sub">Gestiona reservas, habitaciones e inventario desde un solo panel.</div>
          </div>
        </div>

        <div style="margin-top:18px;">
          <h6 style="margin-bottom:8px;">Ventajas del sistema</h6>
          <ul class="muted" style="padding-left:18px; margin-bottom:0;">
            <li>Acceso seguro al panel administrativo.</li>
            <li>Gestión completa de habitaciones e inventarios.</li>
            <li>Reportes PDF profesionales.</li>
          </ul>
        </div>
      </div>

      <div class="visual-footer">
        <div class="small">¿Dudas? Escríbenos: <br><strong>{{ $hotel->email ?? 'info@hotel.demo' }}</strong></div>
      </div>
    </aside>

  </div>
</div>

{{-- Tooltip box (positioned via JS) --}}
<div id="ttBox" class="tt-box" role="note" aria-hidden="true">
  <strong>Requisitos de contraseña</strong>
  <ul style="margin:8px 0 0 16px; padding:0;">
    <li>Mínimo 6 caracteres</li>
    <li>Al menos una letra mayúscula</li>
    <li>Al menos una letra minúscula</li>
    <li>Al menos un número</li>
    <li>Al menos un símbolo (ej. !@#$%)</li>
  </ul>
</div>

<script>
  // Elementos
  const pwd = document.getElementById('password');
  const pwdConf = document.getElementById('password_confirmation');
  const pwFill = document.getElementById('pwFill');
  const pwText = document.getElementById('pwText');
  const reqs = {
    length: document.getElementById('req-length'),
    upper: document.getElementById('req-upper'),
    lower: document.getElementById('req-lower'),
    number: document.getElementById('req-number'),
    symbol: document.getElementById('req-symbol')
  };
  const pwMatchMsg = document.getElementById('pwMatchMsg');
  const pwMatchText = document.getElementById('pwMatchText');
  const registerForm = document.getElementById('registerForm');
  const submitBtn = document.getElementById('submitBtn');
  const formPanel = document.getElementById('formPanel');
  const infoTip = document.getElementById('infoTip');
  const ttBox = document.getElementById('ttBox');

  // Toggle password visibility
  document.getElementById('togglePassword').addEventListener('click', function(){
    pwd.type = pwd.type === 'password' ? 'text' : 'password';
    this.textContent = pwd.type === 'password' ? '👁️' : '🙈';
  });

  // Tooltip behavior (simple)
  infoTip.addEventListener('mouseenter', function(e){
    ttBox.style.display = 'block';
    ttBox.setAttribute('aria-hidden','false');
    const rect = infoTip.getBoundingClientRect();
    // position below the icon
    ttBox.style.left = (rect.left + window.scrollX - 120) + 'px';
    ttBox.style.top = (rect.bottom + window.scrollY + 8) + 'px';
  });
  infoTip.addEventListener('mouseleave', function(){
    ttBox.style.display = 'none';
    ttBox.setAttribute('aria-hidden','true');
  });

  // Password evaluation function
  function evaluatePassword(value){
    let score = 0;
    const checks = {
      length: value.length >= 6,
      upper: /[A-Z]/.test(value),
      lower: /[a-z]/.test(value),
      number: /[0-9]/.test(value),
      symbol: /[^A-Za-z0-9]/.test(value)
    };

    // Score weights: length + others
    if (checks.length) score++;
    if (checks.upper) score++;
    if (checks.lower) score++;
    if (checks.number) score++;
    if (checks.symbol) score++;

    // Update reqs UI
    reqs.length.classList.toggle('ok', checks.length);
    reqs.upper.classList.toggle('ok', checks.upper);
    reqs.lower.classList.toggle('ok', checks.lower);
    reqs.number.classList.toggle('ok', checks.number);
    reqs.symbol.classList.toggle('ok', checks.symbol);

    // Fill bar and text
    pwFill.style.width = (score * 20) + '%';
    if (score <= 1){
      pwFill.style.background = "#ff4d4d";
      pwText.textContent = "Débil";
      pwText.style.color = "#ff4d4d";
    } else if (score <= 3){
      pwFill.style.background = "#f7a531";
      pwText.textContent = "Media";
      pwText.style.color = "#f7a531";
    } else {
      pwFill.style.background = "#00c853";
      pwText.textContent = "Fuerte";
      pwText.style.color = "#00c853";
    }

    return { score, checks };
  }

  // Match checking
  function checkMatch(){
    const a = pwd.value;
    const b = pwdConf.value;
    if (!b) {
      pwMatchMsg.textContent = '';
      pwMatchText.style.display = 'none';
      return false;
    }
    if (a === b){
      pwMatchMsg.textContent = 'Las contraseñas coinciden ✔️';
      pwMatchMsg.style.color = '#00c853';
      pwMatchText.style.display = 'inline-block';
      return true;
    } else {
      pwMatchMsg.textContent = 'No coinciden ❌';
      pwMatchMsg.style.color = '#ff4d4d';
      pwMatchText.style.display = 'none';
      return false;
    }
  }

  // Live events
  pwd.addEventListener('input', function(){
    evaluatePassword(pwd.value);
    checkMatch();
    // remove shake class if added
    formPanel.classList.remove('shake');
  });

  pwdConf.addEventListener('input', function(){
    checkMatch();
  });

  // On submit: prevent if weak or no match
  registerForm.addEventListener('submit', function(e){
    const result = evaluatePassword(pwd.value);
    const isStrong = result.score >= 4; // require at least 4/5 to allow
    const isMatch = checkMatch();

    if (!isStrong || !isMatch){
      e.preventDefault();
      // Visual feedback
      formPanel.classList.remove('shake');
      // Reflow to restart animation
      void formPanel.offsetWidth;
      formPanel.classList.add('shake');

      // Messages
      if (!isMatch){
        pwMatchMsg.textContent = 'Las contraseñas no coinciden. Corrige antes de enviar.';
        pwMatchMsg.style.color = '#ff4d4d';
      }
      if (!isStrong){
        pwText.textContent = 'Contraseña demasiado débil';
        pwText.style.color = '#ff4d4d';
      }
      // Focus first problematic field
      if (!isMatch) pwdConf.focus();
      else pwd.focus();
      return false;
    }
    // otherwise allow submit
  });

  // On blur, re-evaluate (helpful)
  pwd.addEventListener('blur', function(){
    evaluatePassword(pwd.value);
  });

  // If server returned validation errors, ensure UI states reflect them
  // (for example, if Laravel returned and filled old values)
  document.addEventListener('DOMContentLoaded', function(){
    if (pwd.value.length) {
      evaluatePassword(pwd.value);
    }
    if (pwdConf.value.length) {
      checkMatch();
    }
  });
</script>

@endsection
