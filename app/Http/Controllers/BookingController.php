<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    /**
     * Show current user's bookings
     */
    public function index()
    {
        $bookings = Booking::with('vehicle')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show booking form for selected vehicle
     */
    public function create(Vehicle $vehicle)
    {
        return view('bookings.create', compact('vehicle'));
    }

    /**
     * Store a new booking request
     */
    public function store(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Check for existing bookings overlap
        $overlap = Booking::where('vehicle_id', $vehicle->id)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                  ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            })
            ->exists();

        if ($overlap) {
            return back()->with('error', 'This vehicle is already booked for the selected dates.');
        }

        $days = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) ?: 1;
        $total = $days * $vehicle->price_per_day;

        Booking::create([
            'user_id' => auth()->id(),
            'vehicle_id' => $vehicle->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $total,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking created successfully. Awaiting approval.');
    }

    /**
     * Admin: approve booking
     */
    public function approve(Booking $booking)
    {
        $booking->update(['status' => 'approved']);
        $booking->vehicle->update(['status' => 'unavailable']);
        return back()->with('success', 'Booking approved successfully!');
    }

    /**
     * Admin: mark returned
     */
    public function markReturned(Booking $booking)
    {
        $booking->update(['status' => 'returned']);
        $booking->vehicle->update(['status' => 'available']);
        return back()->with('success', 'Vehicle marked as returned.');
    }
}
