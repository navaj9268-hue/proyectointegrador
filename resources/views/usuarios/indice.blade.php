@extends('layouts.app')
@section('title','Usuarios')

@section('content')

<style>
:root{
    --primary:#b23a3a;
    --primary-light:#ff6b6b;
    --bg:#f6f7fb;
    --card:#ffffff;
    --text:#2c2c2c;
    --muted:#7c7c7c;
    --border:#ececec;
}

body{
    background: var(--bg);
}

/* HEADER */
.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    flex-wrap:wrap;
    gap:15px;
}

.page-title{
    font-size:30px;
    font-weight:700;
    color:var(--text);
    display:flex;
    align-items:center;
    gap:12px;
}

.page-title span{
    color:var(--primary);
}

/* CARD */
.main-card{
    background:var(--card);
    border-radius:22px;
    padding:25px;
    box-shadow:0 10px 35px rgba(0,0,0,0.06);
    border:1px solid rgba(0,0,0,0.03);
}

/* SEARCH */
.top-actions{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
    gap:15px;
    flex-wrap:wrap;
}

.search-group{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.search-input{
    min-width:280px;
    height:48px;
    border-radius:14px;
    border:1px solid var(--border);
    padding:0 16px;
    transition:0.3s;
}

.search-input:focus{
    border-color:var(--primary);
    box-shadow:0 0 0 0.2rem rgba(178,58,58,0.10);
    outline:none;
}

.select-modern{
    height:48px;
    border-radius:14px;
    border:1px solid var(--border);
    padding:0 14px;
}

/* BUTTONS */
.btn-modern{
    height:48px;
    padding:0 22px;
    border:none;
    border-radius:14px;
    font-weight:600;
    transition:0.3s;
    cursor:pointer;
}

.btn-primary-modern{
    background:linear-gradient(90deg,var(--primary),var(--primary-light));
    color:#fff;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
}

.btn-primary-modern:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(178,58,58,0.20);
    color:#fff;
}

.btn-edit{
    border-radius:12px;
    padding:8px 16px;
    border:none;
    background:#f1f3f9;
    color:#444;
    font-weight:600;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:4px;
    transition:0.2s;
}

.btn-edit:hover{
    background:#e2e6f0;
    color:#222;
}


/* TABLE */
.table-modern{
    width:100%;
    border-collapse:separate;
    border-spacing:0 12px;
}

.table-modern thead th{
    color:#888;
    font-size:14px;
    font-weight:600;
    padding-bottom:10px;
}

.table-modern tbody tr{
    background:#fff;
    transition:0.3s;
    box-shadow:0 4px 15px rgba(0,0,0,0.03);
}

.table-modern tbody tr:hover{
    transform:scale(1.01);
}

.table-modern tbody td{
    padding:18px 14px;
    vertical-align:middle;
    border-top:1px solid #f1f1f1;
    border-bottom:1px solid #f1f1f1;
}

.table-modern tbody td:first-child{
    border-left:1px solid #f1f1f1;
    border-radius:14px 0 0 14px;
}

.table-modern tbody td:last-child{
    border-right:1px solid #f1f1f1;
    border-radius:0 14px 14px 0;
}

/* BADGES DE ESTATUS */
.badge-active{
    background:#e7fff1;
    color:#11a75c;
    padding:7px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
}

.badge-inactive{
    background:#ffe5e5;
    color:#d11a2a;
    padding:7px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
}

.badge-blacklist{
    background:#fff3cd;
    color:#856404;
    padding:7px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
}

/* EMPTY */
.empty-box{
    text-align:center;
    padding:60px 20px;
}

/* PAGINATION simple */
.pagination-simple{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-top:20px;
    flex-wrap:wrap;
    gap:10px;
}

.pagination-simple .text-muted{
    font-size:14px;
    color:#888;
}

.btn-page{
    height:40px;
    padding:0 18px;
    border:1px solid var(--border);
    border-radius:12px;
    background:#fff;
    color:var(--text);
    font-weight:600;
    cursor:pointer;
    transition:0.2s;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:6px;
}

.btn-page:hover{
    background:var(--primary);
    color:#fff;
    border-color:var(--primary);
}

.btn-page.disabled{
    opacity:0.4;
    pointer-events:none;
}

/* RESPONSIVE */
@media(max-width:768px){
    .page-title{ font-size:24px; }
    .top-actions{ flex-direction:column; align-items:stretch; }
    .search-group{ flex-direction:column; }
    .search-input{ width:100%; min-width:100%; }
    .table-responsive{ overflow:auto; }
}
</style>

<div class="page-header">

    <div class="page-title">
        <span>👥</span>
        Usuarios
        <small class="text-muted" style="font-size:16px;font-weight:400">
            ({{ $users->total() }})
        </small>
    </div>

    <a href="{{ route('usuarios.create') }}"
       class="btn-modern btn-primary-modern">
       + Nuevo Usuario
    </a>

</div>

<div class="main-card">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- SEARCH -->
    <form method="GET" class="top-actions">

        <div class="search-group">

            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                class="search-input"
                placeholder="🔎 Buscar usuario..."
            >

            <select name="status" class="select-modern">
                <option value="">Todos los estados</option>
                @foreach(\App\Models\Usuario::statuses() as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn-modern btn-primary-modern">
                Buscar
            </button>

        </div>

    </form>

    <!-- TABLE -->
    <div class="table-responsive">

        <table class="table-modern">

            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Código</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Estatus</th>
                    <th>Categoría</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($users as $u)
                    <tr>

                        <td><strong>{{ $u->name }}</strong></td>

                        <td>{{ $u->client_code ?? '-' }}</td>

                        <td>{{ $u->email }}</td>

                        <td>{{ $u->phone ?? '-' }}</td>

                        <td>
                            @if($u->status === 'active')
                                <span class="badge-active">Activo</span>
                            @elseif($u->status === 'inactive')
                                <span class="badge-inactive">Inactivo</span>
                            @else
                                <span class="badge-blacklist">Lista negra</span>
                            @endif
                        </td>

                        <td>
                            {{ \App\Models\Usuario::categories()[$u->category] ?? ucfirst($u->category ?? '-') }}
                        </td>

                        <td class="text-end">

                            <a href="{{ route('usuarios.edit', $u) }}" class="btn btn-edit">
                                ✏️ Editar
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7">
                            <div class="empty-box">
                                <h4>No hay usuarios</h4>
                                <p class="text-muted">Crea el primer usuario para comenzar.</p>
                            </div>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <!-- PAGINACIÓN SIMPLE (sin números) -->
    <div class="pagination-simple">

        <span class="text-muted">
            Mostrando {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }}
            de {{ $users->total() }}
        </span>

        <div style="display:flex;gap:8px;">

            @if($users->onFirstPage())
                <span class="btn-page disabled">← Anterior</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="btn-page">← Anterior</a>
            @endif

            @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="btn-page">Siguiente →</a>
            @else
                <span class="btn-page disabled">Siguiente →</span>
            @endif

        </div>

    </div>

</div>



@endsection