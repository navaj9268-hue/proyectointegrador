@extends('layouts.app')
@section('title','Nueva Contraseña')

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
    transition:0.3s;
}

.btn-red:hover{
    opacity:0.92;
    transform:translateY(-1px);
}

.form-control{
    height:52px;
    border-radius:14px;
    border:1px solid #ddd;
    font-size:16px;
}

.form-control:focus{
    border-color:#ff6b6b;
    box-shadow:0 0 0 0.2rem rgba(255,107,107,0.15);
}

.password-toggle{
    position:absolute;
    right:16px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:18px;
    user-select:none;
}

</style>

<div class="auth-container">

    <div class="card auth-card">

        <div class="auth-header">

            <h2 class="mb-0">
                🔑 Nueva contraseña
            </h2>

        </div>

        <div class="auth-body">

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
                  action="{{ route('password.update') }}">

                @csrf

                <input type="hidden"
                       name="token"
                       value="{{ $token }}">

                <input type="hidden"
                       name="email"
                       value="{{ $email }}">

                {{-- NUEVA CONTRASEÑA --}}
                <div class="mb-3">

                    <label class="form-label">
                        Nueva contraseña
                    </label>

                    <div class="position-relative">

                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control pe-5"
                               required>

                        <span class="password-toggle"
                              onclick="togglePassword('password', this)">

                            👁️

                        </span>

                    </div>

                </div>

                {{-- CONFIRMAR CONTRASEÑA --}}
                <div class="mb-4">

                    <label class="form-label">
                        Confirmar contraseña
                    </label>

                    <div class="position-relative">

                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="form-control pe-5"
                               required>

                        <span class="password-toggle"
                              onclick="togglePassword('password_confirmation', this)">

                            👁️

                        </span>

                    </div>

                </div>

                <button class="btn btn-red w-100">

                    Guardar contraseña

                </button>

            </form>

        </div>

    </div>

</div>

<script>

function togglePassword(id, element)
{
    const input = document.getElementById(id);

    if(input.type === 'password')
    {
        input.type = 'text';

        element.innerHTML = '🙈';
    }
    else
    {
        input.type = 'password';

        element.innerHTML = '👁️';
    }
}

</script>

@endsection