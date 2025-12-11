<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Room;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Hotel;

class ReportController extends Controller
{
    // Reporte de habitaciones (filtro opcional por hotel / estado)
    public function roomsPdf(Request $request)
    {
        $qHotel = $request->get('hotel_id');
        $qStatus = $request->get('status');

        $rooms = Room::with('hotel')
            ->when($qHotel, fn($q) => $q->where('hotel_id', $qHotel))
            ->when($qStatus, fn($q) => $q->where('status', $qStatus))
            ->orderBy('number')
            ->get();

        $hotel = $qHotel ? Hotel::find($qHotel) : null;

        $pdf = Pdf::loadView('reports.rooms_pdf', compact('rooms','hotel','qStatus'))
                  ->setPaper('a4', 'portrait');

        // descargar
        return $pdf->download('reporte_habitaciones_'.now()->format('Ymd_His').'.pdf');
    }

    // Reporte de inventarios
    public function inventoriesPdf(Request $request)
    {
        $qHotel = $request->get('hotel_id');

        $items = Inventory::with('hotel')
            ->when($qHotel, fn($q) => $q->where('hotel_id', $qHotel))
            ->orderBy('item')
            ->get();

        $hotel = $qHotel ? Hotel::find($qHotel) : null;

        $pdf = Pdf::loadView('reports.inventories_pdf', compact('items','hotel'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('reporte_inventario_'.now()->format('Ymd_His').'.pdf');
    }

    // Reporte de usuarios
    public function usersPdf(Request $request)
    {
        $users = User::orderBy('name')->get();

        $pdf = Pdf::loadView('reports.users_pdf', compact('users'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('reporte_usuarios_'.now()->format('Ymd_His').'.pdf');
    }

    // Reporte general (resumen)
    public function generalPdf(Request $request)
    {
        $hotel = Hotel::first();
        $roomsCount = Room::count();
        $available = Room::where('status','available')->count();
        $inventories = Inventory::count();
        $users = User::count();

        $pdf = Pdf::loadView('reports.general_pdf', compact(
            'hotel','roomsCount','available','inventories','users'
        ))->setPaper('a4');

        return $pdf->download('reporte_general_'.now()->format('Ymd_His').'.pdf');
    }
}
