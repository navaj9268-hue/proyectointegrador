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

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre</label>
              <input name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">RFC / ID</label>
              <input name="rfc_id" value="{{ old('rfc_id', $user->rfc_id) }}" class="form-control" placeholder="RFC o identificación" />
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input name="email" value="{{ old('email', $user->email) }}" type="email" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Código de cliente</label>
              <input name="client_code" value="{{ old('client_code', $user->client_code) }}" class="form-control" placeholder="Código único del cliente" />
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono</label>
              <input name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" placeholder="Ej: 55 1234 5678" />
            </div>
            <div class="col-md-6">
              <label class="form-label">Categoría</label>
              <select name="category" class="form-select" required>
                @foreach(\App\Models\Usuario::categories() as $key => $label)
                  <option value="{{ $key }}" @selected(old('category', $user->category) === $key)>{{ $label }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Dirección</label>
              <textarea name="address" class="form-control" rows="2" placeholder="Dirección completa">{{ old('address', $user->address) }}</textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Estatus</label>
              <select name="status" class="form-select" required>
                @foreach(\App\Models\Usuario::statuses() as $key => $label)
                  <option value="{{ $key }}" @selected(old('status', $user->status) === $key)>{{ $label }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Límite de crédito</label>
              <input name="credit_limit" value="{{ old('credit_limit', $user->credit_limit) }}" type="number" step="0.01" min="0" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Rol</label>
              <select name="role" class="form-select" required>
                <option value="cliente" @selected(old('role', $user->role) === 'cliente')>Cliente</option>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>Administrador</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nueva contraseña <small class="text-muted">(opcional)</small></label>
              <input name="password" type="password" class="form-control" placeholder="Dejar en blanco para mantener la actual">
            </div>
            <div class="col-md-6">
              <label class="form-label">Confirmar contraseña</label>
              <input name="password_confirmation" type="password" class="form-control" placeholder="Confirmar contraseña">
            </div>
          </div>

          <div class="d-flex gap-2 mt-3">
            <button class="btn" style="background:linear-gradient(90deg,#b23a3a,#ff6b6b); color:#fff; border:none;">Guardar cambios</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
