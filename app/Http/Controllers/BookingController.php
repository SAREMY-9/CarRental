<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Show booking form
    public function create(Vehicle $vehicle)
    {
        $locations = Location::all();

        return view('bookings.create', [
            'vehicle' => $vehicle,
            'locations' => $locations,
        ]);
    }

    // Store booking and redirect to payment
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'pickup_location_id' => 'required|exists:locations,id',
            'dropoff_location_id' => 'nullable|exists:locations,id',
            'start_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'end_date' => 'required|date_format:Y-m-d\TH:i|after:start_date',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);

        // Convert to Carbon
        $start = Carbon::createFromFormat('Y-m-d\TH:i', $validated['start_date']);
        $end = Carbon::createFromFormat('Y-m-d\TH:i', $validated['end_date']);

        // Check overlap
        $overlap = Booking::where('vehicle_id', $vehicle->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                      ->orWhereBetween('end_date', [$start, $end]);
            })->exists();

        if ($overlap) {
            return back()->withErrors(['start_date' => 'Vehicle not available for selected dates.'])->withInput();
        }

        // Calculate price
        $days = max($start->diffInDays($end), 1);
        $totalPrice = $days * ($vehicle->price_per_day ?? 0);

        // Create booking with pending payment
        $booking = Booking::create([
            'vehicle_id' => $vehicle->id,
            'user_id' => auth()->id(),
            'pickup_location_id' => $validated['pickup_location_id'],
            'dropoff_location_id' => $validated['dropoff_location_id'] ?? $validated['pickup_location_id'],
            'start_date' => $start,
            'end_date' => $end,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Redirect to payment
        return redirect()->route('bookings.pay', $booking->id);
    }

    // Initiate Paystack payment
        public function initiatePayment(Booking $booking)
    {
        $reference = 'BOOKING_' . $booking->id . '_' . time(); // unique reference

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
            'Accept' => 'application/json',
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => $booking->user->email,
            'amount' => (int) ($booking->total_price * 100), // kobo
            'reference' => $reference,
            'callback_url' => route('bookings.verify', ['booking' => $booking]),
        ]);

        if ($response->successful() && $response['status']) {
            $booking->update(['payment_reference' => $reference]);
            return redirect($response['data']['authorization_url']);
        }

        // dump for debugging
        dd($response->body());
    }

    // Verify Paystack payment
    public function verifyPayment(Booking $booking)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
            'Accept' => 'application/json',
        ])->get("https://api.paystack.co/transaction/verify/{$booking->payment_reference}");

        if ($response->successful() && $response['data']['status'] === 'success') {
            $booking->update(['status' => 'confirmed']);
            return redirect('/')->with('success', 'Payment successful and booking confirmed!');
        }

        
        return redirect('/')->with('error', 'Payment verification failed.');
    }

    // List all bookings
    public function index()
    {
        $bookings = Booking::with(['user', 'vehicle', 'pickupLocation', 'dropoffLocation'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bookings.index', compact('bookings'));
    }
}
