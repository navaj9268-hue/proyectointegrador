@extends('layouts.app')
@section('title','Editar usuario')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card" style="border-radius:12px; box-shadow:0 8px 20px rgba(37,6,6,0.04);">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 style="color:#b23a3a; font-weight:700;">✏️ Editar usuario</h5>
          <a href="{{ route('usuarios.index') }}" class="btn btn-sm btn-outline-secondary">← Volver</a>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('usuarios.update', $user) }}">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" value="{{ old('email', $user->email) }}" type="email" class="form-control" required>
          </div>

          <div class="row g-2">
            <div class="col-md-6 mb-3">
              <label class="form-label">Nueva contraseña <small class="text-muted">(opcional)</small></label>
              <input name="password" type="password" class="form-control" placeholder="Dejar en blanco para mantener la actual">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Confirmar contraseña</label>
              <input name="password_confirmation" type="password" class="form-control" placeholder="Confirmar contraseña">
            </div>
          </div>

          <div class="d-flex gap-2">
            <button class="btn" style="background:linear-gradient(90deg,#b23a3a,#ff6b6b); color:#fff; border:none;">Guardar cambios</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
