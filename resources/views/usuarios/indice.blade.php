@extends('layouts.app')
@section('title','Usuarios')

@section('content')
<style>
  :root{
    --r-red: #b23a3a;
    --muted: #6c6c6c;
  }

  .users-header {
    display:flex;
    gap:12px;
    align-items:center;
    justify-content:space-between;
    margin-bottom:14px;
    flex-wrap:wrap;
  }

  .users-title {
    display:flex;
    align-items:center;
    gap:10px;
    font-weight:700;
    color:var(--r-red);
    font-size:1.15rem;
  }

  .search-box { min-width:260px; max-width:420px; display:flex; gap:6px; }
  .search-box .form-control { border-radius:999px 0 0 999px; border-right:0; }
  .search-box .btn { border-radius:0 999px 999px 0; }

  .card-wrap {
    background: #fff;
    border-radius:12px;
    padding:12px;
    border:1px solid rgba(178,58,58,0.04);
    box-shadow: 0 8px 20px rgba(37,6,6,0.03);
  }

  /* Table style */
  .table-modern thead th {
    background: linear-gradient(90deg, rgba(178,58,58,0.04), rgba(255,102,102,0.02));
    color:#5c2b2b;
    font-weight:700;
    border-bottom: none;
  }
  .table-modern tbody tr:hover { background: rgba(178,58,58,0.02); }

  .btn-add {
    background: linear-gradient(90deg,#b23a3a,#ff6b6b);
    color:#fff; border:none;
    box-shadow: 0 8px 22px rgba(178,58,58,0.08);
  }

  /* Mobile cards */
  .user-card {
    border-radius:12px;
    padding:12px;
    margin-bottom:10px;
    background:#fff;
    border:1px solid rgba(178,58,58,0.04);
    box-shadow: 0 8px 18px rgba(37,6,6,0.03);
    display:flex;
    justify-content:space-between;
    gap:10px;
    align-items:center;
  }

  .user-meta { display:flex; flex-direction:column; gap:4px; }
  .user-name { font-weight:700; color:var(--r-red); }
  .user-email { color:var(--muted); font-size:.95rem; }

  .actions .btn { margin-left:6px; }

  @media (min-width: 768px) {
    .user-card { display:none; }
  }
  @media (max-width: 767px) {
    .table-modern { display:none; }
    .search-box { width:100%; }
  }

  .empty-state {
    text-align:center;
    padding:20px;
    border-radius:10px;
    background: linear-gradient(180deg,#fffaf9,#fff6f6);
    border:1px dashed rgba(178,58,58,0.06);
    color:#8a5656;
  }
</style>

<div class="users-header">
  <div class="users-title">
    <span style="font-size:1.1rem">👥</span>
    Usuarios
    <small class="text-muted ms-2">({{ $users->total() ?? 0 }})</small>
  </div>

  <div class="d-flex align-items-center gap-2" style="flex-wrap:wrap;">
    <form method="GET" class="search-box" role="search">
      <input name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="🔎 Buscar por nombre o email...">
      <button class="btn btn-sm btn-outline-secondary">Buscar</button>
    </form>

    <a href="{{ route('usuarios.create') }}" class="btn btn-sm btn-add">+ Nuevo</a>
  </div>
</div>

<div class="card-wrap">

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- Tabla para escritorio --}}
  <table class="table table-borderless table-modern mb-0">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Email</th>
        <th class="text-end">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $u)
        <tr>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td class="text-end">
            <a href="{{ route('usuarios.edit', $u) }}" class="btn btn-sm btn-outline-secondary">✏️ Editar</a>

            <form action="{{ route('usuarios.destroy', $u) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event, this, '{{ $u->name }}')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-danger">Eliminar</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="3">
            <div class="empty-state">
              <strong>No hay usuarios aún</strong>
              <p class="mb-2">Crea el primer usuario para empezar a usar el sistema.</p>
              <a href="{{ route('usuarios.create') }}" class="btn btn-sm btn-add">➕ Crear usuario</a>
            </div>
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{-- Cards para móvil --}}
  <div class="d-md-none mt-2">
    @foreach($users as $u)
      <div class="user-card">
        <div class="user-meta">
          <div class="user-name">{{ $u->name }}</div>
          <div class="user-email">{{ $u->email }}</div>
        </div>
        <div class="actions">
          <a href="{{ route('usuarios.edit', $u) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
          <form action="{{ route('usuarios.destroy', $u) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event, this, '{{ $u->name }}')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger">Eliminar</button>
          </form>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Paginación --}}
  <div class="d-flex justify-content-between align-items-center mt-3">
    <div class="text-muted small">Mostrando {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} de {{ $users->total() }}</div>
    <div>{{ $users->withQueryString()->links() }}</div>
  </div>
</div>

<script>
  function confirmDelete(e, form, name) {
    e.preventDefault();
    if (confirm('¿Eliminar usuario "' + name + '"? Esta acción no se puede deshacer.')) {
      form.submit();
    }
    return false;
  }
</script>

@endsection
