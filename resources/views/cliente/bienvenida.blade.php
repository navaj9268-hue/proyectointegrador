@extends('layouts.app')

@section('title', 'Bienvenido')

@section('content')

<style>

.client-page{
    min-height:85vh;
    display:flex;
    align-items:center;
    justify-content:center;
}

.client-card{
    width:100%;
    max-width:900px;
    background:#fff;
    border-radius:28px;
    overflow:hidden;
    box-shadow:0 15px 45px rgba(0,0,0,0.08);
}

.client-header{
    background:linear-gradient(90deg,#b23a3a,#ff6b6b);
    padding:70px;
    text-align:center;
    color:#fff;
}

.client-header h1{
    font-size:52px;
    font-weight:800;
    margin-bottom:10px;
}

.client-header p{
    font-size:20px;
    opacity:0.95;
}

.client-body{
    padding:50px;
}

.info-box{
    background:#fff5f5;
    border-radius:18px;
    padding:30px;
    border:1px solid #ffe1e1;
}

.info-box h4{
    color:#b23a3a;
    font-weight:700;
}

.action-btn{
    display:inline-block;
    padding:14px 28px;
    border-radius:14px;
    font-weight:700;
    text-decoration:none;
    margin:10px;
    transition:0.3s;
}

.btn-main{
    background:linear-gradient(90deg,#b23a3a,#ff6b6b);
    color:#fff;
}

.btn-main:hover{
    transform:translateY(-2px);
    opacity:0.92;
    color:#fff;
}

.btn-light-custom{
    background:#fff;
    border:1px solid #ddd;
    color:#333;
}

.btn-light-custom:hover{
    background:#f8f8f8;
    color:#333;
}

</style>

<div class="client-page">

    <div class="client-card">

        <div class="client-header">

            <h1>
                👋 Bienvenido
            </h1>

            <p>
                Hotel Muñoz
            </p>

        </div>

        <div class="client-body">

            <div class="info-box">

                <h4>
                    Hola, {{ auth()->user()->name }}
                </h4>

                <p class="mb-0">

                    Gracias por utilizar nuestro sistema.

                    Desde aquí podrás consultar habitaciones,
                    revisar reservaciones y gestionar tu estancia
                    en Hotel Muñoz.

                </p>

            </div>

            <div class="text-center mt-5">

                <a href="{{ route('habitaciones.index') }}"
                   class="action-btn btn-main">

                    🛏 Ver habitaciones

                </a>

                <a href="{{ route('calendario.index') }}"
                   class="action-btn btn-light-custom">

                    📅 Calendario

                </a>

            </div>

        </div>

    </div>

</div>

@endsection