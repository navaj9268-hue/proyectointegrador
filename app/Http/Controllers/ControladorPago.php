<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Reservacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ControladorPago extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO DE PAGOS
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $q = Pago::with([
                'reservation.guest',
                'reservation.room',
                'user'
            ])
            ->orderBy('created_at','desc');

        /*
        |--------------------------------------------------------------------------
        | CLIENTE
        |--------------------------------------------------------------------------
        | Solo puede ver SUS pagos
        */

        if(auth()->user()->role === 'cliente'){

            $q->where(
                'user_id',
                auth()->id()
            );

        }

        /*
        |--------------------------------------------------------------------------
        | BUSCADOR
        |--------------------------------------------------------------------------
        */

        if ($request->filled('q')) {

            $term = $request->q;

            $q->where(function($sub) use ($term){

                $sub->where(
                        'id_transaccion',
                        'like',
                        "%{$term}%"
                    )

                    ->orWhere(
                        'metodo',
                        'like',
                        "%{$term}%"
                    )

                    ->orWhere(
                        'nombre_pagador',
                        'like',
                        "%{$term}%"
                    );

            });

        }

        /*
        |--------------------------------------------------------------------------
        | FILTRO POR RESERVACIÓN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('reservation_id')) {

            $q->where(
                'reservation_id',
                $request->reservation_id
            );

        }

        /*
        |--------------------------------------------------------------------------
        | PAGINACIÓN
        |--------------------------------------------------------------------------
        */

        $payments = $q->paginate(12);

        /*
        |--------------------------------------------------------------------------
        | RESERVACIONES
        |--------------------------------------------------------------------------
        */

        if(auth()->user()->role === 'cliente'){

            /*
            |--------------------------------------------------------------------------
            | SOLO RESERVACIONES RELACIONADAS A SUS PAGOS
            |--------------------------------------------------------------------------
            */

            $reservations = Reservacion::whereHas(
                    'payments',
                    function($sub){

                        $sub->where(
                            'user_id',
                            auth()->id()
                        );

                    }
                )
                ->orderBy('fecha_entrada','desc')
                ->limit(50)
                ->get();

        }else{

            /*
            |--------------------------------------------------------------------------
            | ADMIN VE TODAS
            |--------------------------------------------------------------------------
            */

            $reservations = Reservacion::orderBy(
                    'fecha_entrada',
                    'desc'
                )
                ->limit(50)
                ->get();

        }

        return view(
            'pagos.indice',
            compact(
                'payments',
                'reservations'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM CREAR
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | SOLO ADMIN
        |--------------------------------------------------------------------------
        */

        if(auth()->user()->role !== 'admin'){
            abort(403);
        }

        $reservations = Reservacion::with('guest')
            ->orderBy('fecha_entrada','desc')
            ->get();

        return view(
            'pagos.crear',
            compact('reservations')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | SOLO ADMIN
        |--------------------------------------------------------------------------
        */

        if(auth()->user()->role !== 'admin'){
            abort(403);
        }

        $data = $request->only([
            'reservation_id',
            'monto',
            'metodo',
            'id_transaccion',
            'nombre_pagador',
            'notas'
        ]);

        $validator = Validator::make($data, [

            'reservation_id' => [
                'nullable',
                'exists:reservaciones,id'
            ],

            'monto' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'metodo' => [
                'required',
                'string',
                'max:100'
            ],

            'id_transaccion' => [
                'nullable',
                'string',
                'max:255'
            ],

            'nombre_pagador' => [
                'nullable',
                'string',
                'max:255'
            ],

            'notas' => [
                'nullable',
                'string'
            ],

        ]);

        if ($validator->fails()) {

            return back()
                ->withErrors($validator)
                ->withInput();

        }

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $data['user_id'] = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | CREAR PAGO
        |--------------------------------------------------------------------------
        */

        $payment = Pago::create($data);

        return redirect()
            ->route('pagos.show', $payment)
            ->with(
                'success',
                '✅ Pago registrado correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR
    |--------------------------------------------------------------------------
    */

    public function show(Pago $payment)
    {
        /*
        |--------------------------------------------------------------------------
        | CLIENTE SOLO VE SUS PAGOS
        |--------------------------------------------------------------------------
        */

        if(
            auth()->user()->role === 'cliente'
            &&
            $payment->user_id !== auth()->id()
        ){
            abort(403);
        }

        $payment->load(
            'reservation.guest',
            'reservation.room',
            'user'
        );

        return view(
            'pagos.mostrar',
            compact('payment')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDITAR
    |--------------------------------------------------------------------------
    */

    public function edit(Pago $payment)
    {
        /*
        |--------------------------------------------------------------------------
        | SOLO ADMIN
        |--------------------------------------------------------------------------
        */

        if(auth()->user()->role !== 'admin'){
            abort(403);
        }

        $reservations = Reservacion::with('guest')
            ->orderBy('fecha_entrada','desc')
            ->get();

        return view(
            'pagos.crear',
            compact(
                'payment',
                'reservations'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Pago $payment)
    {
        /*
        |--------------------------------------------------------------------------
        | SOLO ADMIN
        |--------------------------------------------------------------------------
        */

        if(auth()->user()->role !== 'admin'){
            abort(403);
        }

        $data = $request->only([
            'reservation_id',
            'monto',
            'metodo',
            'id_transaccion',
            'nombre_pagador',
            'notas'
        ]);

        $validator = Validator::make($data, [

            'reservation_id' => [
                'nullable',
                'exists:reservaciones,id'
            ],

            'monto' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'metodo' => [
                'required',
                'string',
                'max:100'
            ],

            'id_transaccion' => [
                'nullable',
                'string',
                'max:255'
            ],

            'nombre_pagador' => [
                'nullable',
                'string',
                'max:255'
            ],

            'notas' => [
                'nullable',
                'string'
            ],

        ]);

        if ($validator->fails()) {

            return back()
                ->withErrors($validator)
                ->withInput();

        }

        $payment->update($data);

        return redirect()
            ->route('pagos.show', $payment)
            ->with(
                'success',
                '✅ Pago actualizado correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | ELIMINAR
    |--------------------------------------------------------------------------
    */

    public function destroy(Pago $payment)
    {
        /*
        |--------------------------------------------------------------------------
        | SOLO ADMIN
        |--------------------------------------------------------------------------
        */

        if(auth()->user()->role !== 'admin'){
            abort(403);
        }

        $payment->delete();

        return redirect()
            ->route('pagos.index')
            ->with(
                'success',
                '✅ Pago eliminado.'
            );
    }
}