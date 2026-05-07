<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class AuthController extends Controller
{
    // Mostrar formulario de registro
    public function showRegister()
    {
        return view('autenticacion.registrar');
    }

    // Procesar registro
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

        return redirect()->route('inicio')->with('success', 'Cuenta creada. Bienvenido(a)!');
    }

    // Mostrar formulario de login
    public function showLogin()
    {
        return view('autenticacion.iniciar-sesion');
    }

    // Procesar login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        Log::info('Intento de login', ['email' => $credentials['email']]);

        $user = Usuario::where('email', $credentials['email'])->first();
        
        if (!$user) {
            Log::warning('Usuario no encontrado', ['email' => $credentials['email']]);
            return back()->withErrors(['email' => 'Credenciales inválidas'])->onlyInput('email');
        }

        Log::info('Usuario encontrado', ['email' => $user->email, 'name' => $user->name]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            Log::info('Login exitoso', ['email' => $credentials['email']]);
            $request->session()->regenerate();
            return redirect()->intended(route('inicio'));
        }

        Log::warning('Fallo en autenticación', ['email' => $credentials['email']]);
        return back()->withErrors(['email' => 'Credenciales inválidas'])->onlyInput('email');
    }

    // Logout
    public function logout(Request $request)
    {
        Log::info('Logout iniciado para usuario', ['user' => Auth::user()?->email]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Logout completado');
        return redirect()->route('login')->with('success', 'Has cerrado sesión.');
    }

    // Mostrar formulario de "Olvidé Contraseña"
    public function showForgotPassword()
    {
        return view('autenticacion.olvide-contraseña');
    }

    // Procesar solicitud de reset de contraseña
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'action' => 'required|in:send_code,reset_password',
        ]);

        $user = Usuario::where('email', $request->email)->first();

        if (!$user) {
            Log::warning('Intento de reset con email no registrado', ['email' => $request->email]);
            return back()->with('status', 'Si ese email existe, recibirás un código de recuperación.');
        }

        // Si es para enviar código
        if ($request->action === 'send_code') {
            // Generar código numérico de 6 dígitos
            $code = random_int(100000, 999999);
            
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['email' => $user->email, 'token' => hash('sha256', (string)$code), 'created_at' => now()]
            );

            Log::info('Código de reset enviado', ['email' => $user->email, 'code' => $code]);

            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\PasswordResetMail($user, $code));

            return back()->with('status', 'Hemos enviado un código de recuperación a tu correo electrónico. Revisa tu bandeja de entrada.');
        }

        // Si es para resetear contraseña
        if ($request->action === 'reset_password') {
            $request->validate([
                'token' => 'required',
                'password' => 'required|confirmed|min:6',
            ]);

            $reset = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$reset || !hash_equals($reset->token, hash('sha256', $request->token))) {
                Log::warning('Intento de reset con token inválido', ['email' => $request->email]);
                return back()->withErrors(['email' => 'El código de verificación es inválido o ha expirado.']);
            }

            // Actualizar contraseña
            $user->update(['password' => Hash::make($request->password)]);

            // Eliminar token de reset
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            Log::info('Contraseña reseteada exitosamente', ['email' => $user->email]);

            return redirect()->route('inicio')->with('status', 'Tu contraseña ha sido reseteada exitosamente. ¡Bienvenido de nuevo!');
        }
    }

    // Mostrar formulario de reset de contraseña
    public function showResetForm($token)
    {
        return view('autenticacion.reset-contraseña', ['token' => $token]);
    }

    // Procesar reset de contraseña
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset || !hash_equals($reset->token, hash('sha256', $request->token))) {
            Log::warning('Intento de reset con token inválido', ['email' => $request->email]);
            return back()->withErrors(['email' => 'El enlace de reset es inválido o ha expirado.']);
        }

        $user = Usuario::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Usuario no encontrado.']);
        }

        // Actualizar contraseña
        $user->update(['password' => Hash::make($request->password)]);

        // Eliminar token de reset
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        Log::info('Contraseña reseteada exitosamente', ['email' => $user->email]);

        return redirect()->route('login')->with('status', 'Tu contraseña ha sido reseteada. Inicia sesión con tu nueva contraseña.');
    }
}
