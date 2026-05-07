<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Habitacion;
use App\Models\Inventario;
use App\Models\Usuario;
use App\Models\Hotel;

class ControladorReporte extends Controller
{
    // Reporte de habitaciones (filtro opcional por hotel / estado)
    public function roomsPdf(Request $request)
    {
        $qHotel = $request->get('hotel_id');
        $qStatus = $request->get('status');

        $rooms = Habitacion::with('hotel')
            ->when($qHotel, fn($q) => $q->where('hotel_id', $qHotel))
            ->when($qStatus, fn($q) => $q->where('status', $qStatus))
            ->orderBy('numero')
            ->get();

        $hotel = $qHotel ? Hotel::find($qHotel) : null;

        $pdf = Pdf::loadView('reportes.habitaciones_pdf', compact('rooms','hotel','qStatus'))
                  ->setPaper('a4', 'portrait');

        // descargar
        return $pdf->download('reporte_habitaciones_'.now()->format('Ymd_His').'.pdf');
    }

    // Reporte de inventarios
    public function inventoriesPdf(Request $request)
    {
        $qHotel = $request->get('hotel_id');

        $items = Inventario::with('hotel')
            ->when($qHotel, fn($q) => $q->where('hotel_id', $qHotel))
            ->orderBy('articulo')
            ->get();

        $hotel = $qHotel ? Hotel::find($qHotel) : null;

        $pdf = Pdf::loadView('reportes.inventarios_pdf', compact('items','hotel'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('reporte_inventario_'.now()->format('Ymd_His').'.pdf');
    }

    // Reporte de usuarios
    public function usersPdf(Request $request)
    {
        $users = Usuario::orderBy('name')->get();

        $pdf = Pdf::loadView('reportes.usuarios_pdf', compact('users'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('reporte_usuarios_'.now()->format('Ymd_His').'.pdf');
    }

    // Reporte general (resumen)
    public function generalPdf(Request $request)
    {
        $hotel = Hotel::first();
        $roomsCount = Habitacion::count();
        $available = Habitacion::where('status','available')->count();
        $inventories = Inventario::count();
        $users = Usuario::count();

        $pdf = Pdf::loadView('reportes.general_pdf', compact(
            'hotel','roomsCount','available','inventories','users'
        ))->setPaper('a4');

        return $pdf->download('reporte_general_'.now()->format('Ymd_His').'.pdf');
    }
}
