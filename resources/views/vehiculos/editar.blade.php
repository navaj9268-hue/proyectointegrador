@extends('layouts.app')
@section('title','Editar Vehículo')

@section('content')

<style>

:root{
    --primary:#b23a3a;
    --primary-light:#ff6b6b;
    --card:#ffffff;
    --border:#ececec;
}

.main-card{
    background:var(--card);
    border-radius:22px;
    padding:30px;
    box-shadow:0 10px 35px rgba(0,0,0,0.06);
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    flex-wrap:wrap;
    gap:15px;
}

.page-title{
    font-size:28px;
    font-weight:700;
}

.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}

.form-group label{
    font-weight:600;
    margin-bottom:8px;
    display:block;
}

.form-control,
.form-select{
    height:48px;
    border-radius:12px;
    border:1px solid var(--border);
}

textarea.form-control{
    height:auto;
}

.btn-modern{
    border:none;
    border-radius:12px;
    padding:12px 22px;
    font-weight:600;
}

.btn-primary-modern{
    background:linear-gradient(90deg,var(--primary),var(--primary-light));
    color:#fff;
}

</style>

<div class="page-header">

    <div class="page-title">
        ✏️ Editar Vehículo
    </div>

    <a href="{{ route('vehiculos.index') }}"
       class="btn btn-secondary">

        Volver

    </a>

</div>

<div class="main-card">

    @if($errors->any())

        <div class="alert alert-danger mb-4">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form action="{{ route('vehiculos.update',$vehicle) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">

                <label>Placa</label>

                <input
                    type="text"
                    name="placa"
                    value="{{ old('placa',$vehicle->placa) }}"
                    class="form-control"
                    required
                >

            </div>

            <div class="form-group">

                <label>Marca</label>

                <input
                    type="text"
                    name="marca"
                    value="{{ old('marca',$vehicle->marca) }}"
                    class="form-control"
                    required
                >

            </div>

            <div class="form-group">

                <label>Modelo</label>

                <input
                    type="text"
                    name="modelo"
                    value="{{ old('modelo',$vehicle->modelo) }}"
                    class="form-control"
                    required
                >

            </div>

            <div class="form-group">

                <label>Color</label>

                <input
                    type="color"
                    name="color"
                    value="{{ old('color',$vehicle->color) }}"
                    class="form-control form-control-color"
                >

            </div>

            <div class="form-group">

                <label>Tipo</label>

                <select name="tipo" class="form-select">

                    @foreach($tipos as $key => $value)

                        <option
                            value="{{ $key }}"
                            @selected(old('tipo',$vehicle->tipo) == $key)
                        >

                            {{ $value }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="form-group">

                <label>Lugar Estacionamiento</label>

                <input
                    type="text"
                    name="lugar_estacionamiento"
                    value="{{ old('lugar_estacionamiento',$vehicle->lugar_estacionamiento) }}"
                    class="form-control"
                >

            </div>

            <div class="form-group">

                <label>Tarifa por Hora</label>

                <input
                    type="number"
                    step="0.01"
                    name="tarifa_por_hora"
                    value="{{ old('tarifa_por_hora',$vehicle->tarifa_por_hora) }}"
                    class="form-control"
                >

            </div>

            <div class="form-group">

                <label>Reservación</label>

                <select
                    name="reservation_id"
                    class="form-select"
                >

                    <option value="">
                        Sin reservación
                    </option>

                    @foreach($reservaciones as $r)

                        <option
                            value="{{ $r->id }}"
                            @selected(old('reservation_id',$vehicle->reservation_id) == $r->id)
                        >

                            #{{ $r->id }}
                            -
                            {{ $r->guest->name ?? 'Sin huésped' }}

                        </option>

                    @endforeach

                </select>

            </div>

        </div>

        <div class="mt-4">

            <label>Notas</label>

            <textarea
                name="notas"
                class="form-control"
                rows="4"
            >{{ old('notas',$vehicle->notas) }}</textarea>

        </div>

        <div class="mt-4 d-flex gap-2">

            <button class="btn-modern btn-primary-modern">

                Actualizar Vehículo

            </button>

            <a
                href="{{ route('vehiculos.index') }}"
                class="btn btn-light"
            >

                Cancelar

            </a>

        </div>

    </form>

</div>

@endsection