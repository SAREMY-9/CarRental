<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Location;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Show the booking form.
     */
    public function create(Vehicle $vehicle)
    {
        $locations = Location::all();

        return view('bookings.create', [
            'vehicle' => $vehicle,
            'locations' => $locations,
        ]);
    }

    /**
     * Store a new booking.
     */
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

        // Convert input from datetime-local to Carbon objects
        $start = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $validated['start_date']);
        $end = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $validated['end_date']);

        // Check overlap
        $overlap = \App\Models\Booking::where('vehicle_id', $vehicle->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end]);
            })
            ->exists();

        if ($overlap) {
            return back()
                ->withErrors(['start_date' => 'Vehicle is not available for the selected dates.'])
                ->withInput();
        }

        // Calculate price
        $days = max($start->diffInDays($end), 1);
        $totalPrice = $days * ($vehicle->price_per_day ?? 0);

        // Save booking
        \App\Models\Booking::create([
            'vehicle_id' => $vehicle->id,
            'user_id' => auth()->id(),
            'pickup_location_id' => $validated['pickup_location_id'],
            'dropoff_location_id' => $validated['dropoff_location_id'] ?? $validated['pickup_location_id'],
            'start_date' => $start,
            'end_date' => $end,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

       return redirect('/')
    ->with('success', 'Vehicle booked successfully!');
            
    }

    /**
     * Show all bookings.
     */
        public function index()
    {
        $bookings = \App\Models\Booking::with(['user', 'vehicle', 'pickupLocation', 'dropoffLocation'])
            ->orderBy('created_at', 'desc') // show newest first
            ->get();

        return view('bookings.index', compact('bookings'));
    }

}
