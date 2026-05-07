<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Hotel;

class ControladorInventario extends Controller
{
    // Listar inventarios con búsqueda
    public function index(Request $request)
    {
        $q = $request->get('q');
        $inventories = Inventario::with('hotel')
            ->when($q, fn($query) => $query->where('articulo','like', "%{$q}%")
                                           ->orWhere('ubicacion','like', "%{$q}%"))
            ->orderBy('id','desc')
            ->paginate(10);

        return view('inventarios.indice', compact('inventories'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $hotels = Hotel::all();
        return view('inventarios.crear', compact('hotels'));
    }

    // Guardar nuevo inventario
    public function store(Request $request)
    {
        $data = $request->validate([
            'hotel_id' => 'required|exists:hoteles,id', // obligatorio
            'articulo' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
            'notas' => 'nullable|string',
        ]);

        Inventario::create($data);

        return redirect()->route('inventarios.index')
                         ->with('success', 'Artículo de inventario creado correctamente.');
    }

    // Mostrar formulario de edición
    public function edit(Inventario $inventory)
    {
        $hotels = Hotel::all();
        return view('inventarios.editar', compact('inventory', 'hotels'));
    }

    // Actualizar inventario existente
    public function update(Request $request, Inventario $inventory)
    {
        $data = $request->validate([
            'hotel_id' => 'required|exists:hoteles,id', // obligatorio
            'articulo' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
            'notas' => 'nullable|string',
        ]);

        $inventory->update($data);

        return redirect()->route('inventarios.index')
                         ->with('success', 'Artículo de inventario actualizado correctamente.');
    }

    // Eliminar inventario
    public function destroy(Inventario $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventarios.index')
                         ->with('success', 'Artículo de inventario eliminado correctamente.');
    }
}