<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REGISTRO
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('autenticacion.registrar');
    }

    public function register(Request $request)
    {
        $data = $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email',

            'password' => 'required|confirmed|min:6',

        ]);

        $user = Usuario::create([

            'name' => $data['name'],

            'email' => $data['email'],

            'password' => Hash::make($data['password']),

        ]);

        Auth::login($user);

        return redirect()
            ->route('inicio')
            ->with('success', 'Cuenta creada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('autenticacion.iniciar-sesion');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([

            'email' => 'required|email',

            'password' => 'required',

        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {

            $request->session()->regenerate();

            Log::info('Login exitoso', [

                'email' => $credentials['email']

            ]);

            return redirect()->intended(
                route('inicio')
            );
        }

        Log::warning('Credenciales inválidas', [

            'email' => $credentials['email']

        ]);

        return back()
            ->withErrors([

                'email' => 'Credenciales inválidas.'

            ])
            ->onlyInput('email');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Has cerrado sesión.');
    }

    /*
    |--------------------------------------------------------------------------
    | OLVIDÉ CONTRASEÑA
    |--------------------------------------------------------------------------
    */

    public function showForgotPassword()
    {
        return view('autenticacion.olvide-contraseña');
    }

    /*
    |--------------------------------------------------------------------------
    | ENVIAR LINK DE RESET
    |--------------------------------------------------------------------------
    */

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([

            'email' => 'required|email'

        ]);

        $status = Password::sendResetLink(

            $request->only('email')

        );

        return $status === Password::RESET_LINK_SENT

            ? back()->with(
                'status',
                '✅ Te enviamos un enlace de recuperación a tu correo.'
            )

            : back()->withErrors([

                'email' => 'No encontramos una cuenta con ese correo.'

            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR FORMULARIO RESET
    |--------------------------------------------------------------------------
    */

    public function showResetForm(
        Request $request,
        $token = null
    )
    {
        return view(
            'autenticacion.reset-contraseña',
            [

                'token' => $token,

                'email' => $request->email

            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVA CONTRASEÑA
    |--------------------------------------------------------------------------
    */

    public function resetPassword(Request $request)
    {
        $request->validate([

            'token' => 'required',

            'email' => 'required|email',

            'password' => 'required|min:6|confirmed',

        ]);

        $status = Password::reset(

            $request->only(

                'email',

                'password',

                'password_confirmation',

                'token'

            ),

            function ($user, $password) {

                $user->forceFill([

                    'password' => Hash::make($password)

                ])->setRememberToken(

                    Str::random(60)

                );

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET

            ? redirect()
                ->route('login')
                ->with(
                    'status',
                    '✅ Contraseña actualizada correctamente.'
                )

            : back()->withErrors([

                'email' => [__($status)]

            ]);
    }
}