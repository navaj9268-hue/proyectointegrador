<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $q = Payment::with(['reservation.guest','user'])->orderBy('created_at','desc');

        if ($request->filled('q')) {
            $term = $request->q;
            $q->where(function($sub) use ($term){
                $sub->where('transaction_id','like',"%{$term}%")
                    ->orWhere('method','like',"%{$term}%")
                    ->orWhere('payer_name','like',"%{$term}%");
            });
        }

        if ($request->filled('reservation_id')) {
            $q->where('reservation_id', $request->reservation_id);
        }

        $payments = $q->paginate(12);
        $reservations = Reservation::orderBy('checkin_at','desc')->limit(50)->get();

        return view('payments.index', compact('payments','reservations'));
    }

    public function create()
    {
        $reservations = Reservation::with('guest')->orderBy('checkin_at','desc')->get();
        return view('payments.create', compact('reservations'));
    }

    public function store(Request $request)
    {
        $data = $request->only(['reservation_id','amount','method','transaction_id','payer_name','notes']);
        $validator = Validator::make($data, [
            'reservation_id' => ['nullable','exists:reservations,id'],
            'amount' => ['required','numeric','min:0.01'],
            'method' => ['required','string','max:100'],
            'transaction_id' => ['nullable','string','max:255'],
            'payer_name' => ['nullable','string','max:255'],
            'notes' => ['nullable','string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data['user_id'] = Auth::id();

        $payment = Payment::create($data);

        // Opcional: actualizar estado de la reserva si está completamente pagada
        // if ($payment->reservation_id) {
        //     $res = Reservation::find($payment->reservation_id);
        //     if ($res) {
        //         $paid = $res->payments()->sum('amount');
        //         if ($paid >= $res->total) {
        //             $res->update(['status' => 'checked_in']); // o algún campo 'paid'
        //         }
        //     }
        // }

        return redirect()->route('payments.show', $payment)->with('success','Pago registrado correctamente.');
    }

    public function show(Payment $payment)
    {
        $payment->load('reservation.guest','user');
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $reservations = Reservation::with('guest')->orderBy('checkin_at','desc')->get();
        return view('payments.create', compact('payment','reservations'));
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->only(['reservation_id','amount','method','transaction_id','payer_name','notes']);
        $validator = Validator::make($data, [
            'reservation_id' => ['nullable','exists:reservations,id'],
            'amount' => ['required','numeric','min:0.01'],
            'method' => ['required','string','max:100'],
            'transaction_id' => ['nullable','string','max:255'],
            'payer_name' => ['nullable','string','max:255'],
            'notes' => ['nullable','string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $payment->update($data);

        return redirect()->route('payments.show', $payment)->with('success','Pago actualizado correctamente.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success','Pago eliminado.');
    }
}
