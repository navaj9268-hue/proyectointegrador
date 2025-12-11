<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Hotel;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $inventories = Inventory::with('hotel')
            ->when($q, fn($query) => $query->where('item','like', "%{$q}%")
                                           ->orWhere('location','like', "%{$q}%"))
            ->orderBy('id','desc')
            ->paginate(10);

        return view('inventories.index', compact('inventories'));
    }

    public function create()
    {
        $hotels = Hotel::all();
        return view('inventories.create', compact('hotels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hotel_id' => 'nullable|exists:hotels,id',
            'item' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Inventory::create($data);

        return redirect()->route('inventories.index')->with('success', 'Artículo de inventario creado.');
    }

    public function edit(Inventory $inventory)
    {
        $hotels = Hotel::all();
        return view('inventories.edit', compact('inventory', 'hotels'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $data = $request->validate([
            'hotel_id' => 'nullable|exists:hotels,id',
            'item' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $inventory->update($data);

        return redirect()->route('inventories.index')->with('success', 'Artículo actualizado.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventories.index')->with('success', 'Artículo eliminado.');
    }
}
