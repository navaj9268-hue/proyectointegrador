<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Reservacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ControladorPago extends Controller
{
    public function index(Request $request)
    {
        $q = Pago::with(['reservation.guest','user'])->orderBy('created_at','desc');

        if ($request->filled('q')) {
            $term = $request->q;
            $q->where(function($sub) use ($term){
                $sub->where('id_transaccion','like',"%{$term}%")
                    ->orWhere('metodo','like',"%{$term}%")
                    ->orWhere('nombre_pagador','like',"%{$term}%");
            });
        }

        if ($request->filled('reservation_id')) {
            $q->where('reservation_id', $request->reservation_id);
        }

        $payments = $q->paginate(12);
        $reservations = Reservacion::orderBy('fecha_entrada','desc')->limit(50)->get();

        return view('pagos.indice', compact('payments','reservations'));
    }

    public function create()
    {
        $reservations = Reservacion::with('guest')->orderBy('fecha_entrada','desc')->get();
        return view('pagos.crear', compact('reservations'));
    }

    public function store(Request $request)
    {
        $data = $request->only(['reservation_id','monto','metodo','id_transaccion','nombre_pagador','notas']);
        $validator = Validator::make($data, [
            'reservation_id' => ['nullable','exists:reservaciones,id'],
            'monto' => ['required','numeric','min:0.01'],
            'metodo' => ['required','string','max:100'],
            'id_transaccion' => ['nullable','string','max:255'],
            'nombre_pagador' => ['nullable','string','max:255'],
            'notas' => ['nullable','string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data['user_id'] = Auth::id();

        $payment = Pago::create($data);

        // Opcional: actualizar estado de la reserva si está completamente pagada
        // if ($payment->reservation_id) {
        //     $res = Reservacion::find($payment->reservation_id);
        //     if ($res) {
        //         $paid = $res->payments()->sum('amount');
        //         if ($paid >= $res->total) {
        //             $res->update(['status' => 'checked_in']); // o algún campo 'paid'
        //         }
        //     }
        // }

        return redirect()->route('pagos.show', $payment)->with('success','Pago registrado correctamente.');
    }

    public function show(Pago $payment)
    {
        $payment->load('reservation.guest','user');
        return view('pagos.mostrar', compact('payment'));
    }

    public function edit(Pago $payment)
    {
        $reservations = Reservacion::with('guest')->orderBy('fecha_entrada','desc')->get();
        return view('pagos.crear', compact('payment','reservations'));
    }

    public function update(Request $request, Pago $payment)
    {
        $data = $request->only(['reservation_id','monto','metodo','id_transaccion','nombre_pagador','notas']);
        $validator = Validator::make($data, [
            'reservation_id' => ['nullable','exists:reservaciones,id'],
            'monto' => ['required','numeric','min:0.01'],
            'metodo' => ['required','string','max:100'],
            'id_transaccion' => ['nullable','string','max:255'],
            'nombre_pagador' => ['nullable','string','max:255'],
            'notas' => ['nullable','string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $payment->update($data);

        return redirect()->route('pagos.show', $payment)->with('success','Pago actualizado correctamente.');
    }

    public function destroy(Pago $payment)
    {
        $payment->delete();
        return redirect()->route('pagos.index')->with('success','Pago eliminado.');
    }
}
