@extends('layouts.app')
@section('title','Recuperar Contraseña')

@section('content')

<style>

.auth-container{
    min-height:80vh;
    display:flex;
    align-items:center;
    justify-content:center;
}

.auth-card{
    width:100%;
    max-width:520px;
    border:none;
    border-radius:24px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,0.08);
}

.auth-header{
    background:linear-gradient(90deg,#b23a3a,#ff6b6b);
    color:#fff;
    padding:35px;
    text-align:center;
}

.auth-body{
    padding:35px;
    background:#fff;
}

.btn-red{
    background:linear-gradient(90deg,#b23a3a,#ff6b6b);
    border:none;
    color:#fff;
    height:48px;
    border-radius:12px;
    font-weight:600;
}

.form-control{
    height:50px;
    border-radius:12px;
}

</style>

<div class="auth-container">

    <div class="card auth-card">

        <div class="auth-header">

            <h2 class="mb-2">
                🔐 Recuperar contraseña
            </h2>

            <p class="mb-0">
                Ingresa tu correo y te enviaremos un enlace seguro.
            </p>

        </div>

        <div class="auth-body">

            @if(session('status'))

                <div class="alert alert-success">

                    {{ session('status') }}

                </div>

            @endif

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form method="POST"
                  action="{{ route('password.email') }}">

                @csrf

                <div class="mb-4">

                    <label class="form-label">

                        📧 Correo electrónico

                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control"
                        placeholder="correo@ejemplo.com"
                        required
                    >

                </div>

                <button type="submit"
                        class="btn btn-red w-100">

                    Enviar enlace de recuperación

                </button>

            </form>

            <div class="text-center mt-4">

                <a href="{{ route('login') }}"
                   style="color:#b23a3a;text-decoration:none;">

                    ← Volver al login

                </a>

            </div>

        </div>

    </div>

</div>

@endsection