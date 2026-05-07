<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class ControladorUsuario extends Controller
{
    public function index()
    {
        $users = Usuario::orderBy('id','desc')->paginate(10);
        return view('usuarios.indice', compact('users'));
    }

    public function create()
    {
        return view('usuarios.crear');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $data['password'] = Hash::make($data['password']);
        Usuario::create($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado.');
    }

    public function edit(Usuario $user)
    {
        return view('usuarios.editar', compact('user'));
    }

    public function update(Request $request, Usuario $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|confirmed|min:6',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(Usuario $user)
    {
        // Evita que el usuario actual se elimine a sí mismo (opcional)
        if (auth()->check() && auth()->id() === $user->id) {
            return redirect()->route('usuarios.index')->with('error', 'No puedes eliminar tu propia cuenta desde aquí.');
        }

        $user->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
    }
}
