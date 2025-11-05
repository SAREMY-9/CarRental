<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Show payment form (stub)
     */
    public function create(Booking $booking)
    {
        return view('payments.create', compact('booking'));
    }

    /**
     * Store payment (stub)
     */
    public function store(Request $request, Booking $booking)
    {
        $request->validate([
            'method' => 'required|string',
            'reference' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'method' => $request->method,
            'reference' => $request->reference ?? strtoupper(uniqid('PAY')),
            'amount' => $request->amount,
            'status' => 'completed',
        ]);

        $booking->update(['status' => 'paid']);

        return redirect()->route('bookings.index')
            ->with('success', 'Payment recorded successfully.');
    }
}
