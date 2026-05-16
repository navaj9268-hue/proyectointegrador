<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioAdminController extends Controller
{
    // Todos los permisos del sistema — agrega los que necesites
    private array $permisosDisponibles = [
        'ver_usuarios'           => 'Ver usuarios',
        'editar_usuarios'        => 'Editar usuarios',
        'ver_reservaciones'      => 'Ver reservaciones',
        'editar_reservaciones'   => 'Editar reservaciones',
        'ver_inventario'         => 'Ver inventario',
        'editar_inventario'      => 'Editar inventario',
        'ver_reportes'           => 'Ver reportes',
        'ver_pagos'              => 'Ver pagos',
        'ver_habitaciones'       => 'Ver habitaciones',
        'editar_habitaciones'    => 'Editar habitaciones',
    ];

    public function index()
    {
        $usuarios = Usuario::orderBy('name')->paginate(20);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function editarPermisos(Usuario $usuario)
    {
        $permisosDisponibles = $this->permisosDisponibles;
        $permisosActivos     = $usuario->permisos ?? [];
        return view('admin.usuarios.permisos', compact('usuario', 'permisosDisponibles', 'permisosActivos'));
    }

    public function actualizarPermisos(Request $request, Usuario $usuario)
    {
        $usuario->update([
            'permisos' => $request->input('permisos', []),
        ]);
        return back()->with('success', "Permisos de {$usuario->name} actualizados correctamente.");
    }

    public function cambiarRol(Request $request, Usuario $usuario)
    {
        $request->validate(['role' => 'required|in:admin,cliente']);
        $usuario->update(['role' => $request->role]);
        return back()->with('success', "Rol de {$usuario->name} cambiado a {$request->role}.");
    }
}