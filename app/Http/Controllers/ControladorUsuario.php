<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class ControladorUsuario extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $status = $request->get('status');

        $users = Usuario::when($q, fn($query) => $query->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('client_code', 'like', "%{$q}%");
            }))
            ->when($status, fn($query) => $query->where('status', $status))
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('usuarios.indice', compact('users'));
    }

    public function create()
    {
        return view('usuarios.crear');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('usuarios.perfil', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

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

        return redirect()->route('usuarios.profile')->with('success', 'Perfil actualizado correctamente.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'rfc_id' => 'nullable|string|max:100',
            'client_code' => 'nullable|string|max:100|unique:users,client_code',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,blacklist',
            'category' => 'required|in:minorista,mayorista,vip',
            'credit_limit' => 'required|numeric|min:0',
            'role' => 'required|in:admin,cliente',
            'password' => 'required|confirmed|min:6',
        ]);

        $data['password'] = Hash::make($data['password']);

        Usuario::create($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(Usuario $user)
    {
        return view('usuarios.editar', compact('user'));
    }

    public function update(Request $request, Usuario $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'rfc_id' => 'nullable|string|max:100',
            'client_code' => 'nullable|string|max:100|unique:users,client_code,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,blacklist',
            'category' => 'required|in:minorista,mayorista,vip',
            'credit_limit' => 'required|numeric|min:0',
            'role' => 'required|in:admin,cliente',
            'password' => 'nullable|confirmed|min:6',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Usuario $user)
    {
        if (auth()->check() && auth()->id() === $user->id) {
            return redirect()->route('usuarios.index')->with('error', 'No puedes desactivar tu propia cuenta desde aquí.');
        }

        $user->update(['status' => 'inactive']);

        return redirect()->route('usuarios.index')->with('success', 'Usuario desactivado correctamente.');
    }
}
