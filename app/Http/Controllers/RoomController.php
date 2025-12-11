<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Hotel;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $rooms = Room::with('hotel')
            ->when($q, fn($query) => $query->where('number','like', "%{$q}%")
                                           ->orWhere('type','like', "%{$q}%"))
            ->orderBy('id','desc')
            ->paginate(10);

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        $hotels = Hotel::all();
        return view('rooms.create', compact('hotels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hotel_id' => 'nullable|exists:hotels,id',
            'number' => 'required|string|max:50',
            'type' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance',
            'notes' => 'nullable|string',
        ]);

        Room::create($data);

        return redirect()->route('rooms.index')->with('success', 'Habitación creada correctamente.');
    }

    public function show(Room $room)
    {
        // Opcional — no lo usamos, pero Laravel resource lo incluye.
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $hotels = Hotel::all();
        return view('rooms.edit', compact('room', 'hotels'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'hotel_id' => 'nullable|exists:hotels,id',
            'number' => 'required|string|max:50',
            'type' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance',
            'notes' => 'nullable|string',
        ]);

        $room->update($data);

        return redirect()->route('rooms.index')->with('success', 'Habitación actualizada.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Habitación eliminada.');
    }
}
