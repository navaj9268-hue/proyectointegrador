@extends('layouts.app')
@section('title','Perfil')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card" style="border-radius:12px; box-shadow:0 10px 30px rgba(37,6,6,0.08);">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h4 class="mb-0" style="color:#b23a3a;">Mi perfil</h4>
            <small class="text-muted">Información personal y cambio de contraseña</small>
          </div>
          <a href="{{ route('inicio') }}" class="btn btn-sm btn-outline-secondary">Volver</a>
        </div>

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('usuarios.profile.update') }}">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input name="email" value="{{ old('email', $user->email) }}" type="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Nueva contraseña <small class="text-muted">(opcional)</small></label>
            <input name="password" type="password" class="form-control" placeholder="Dejar en blanco para mantener la contraseña actual">
          </div>

          <div class="mb-3">
            <label class="form-label">Confirmar contraseña</label>
            <input name="password_confirmation" type="password" class="form-control" placeholder="Confirmar nueva contraseña">
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="{{ route('inicio') }}" class="btn btn-outline-secondary">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
