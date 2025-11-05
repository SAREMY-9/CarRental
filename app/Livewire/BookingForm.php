<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Vehicle;
use App\Models\Booking;
use Illuminate\Support\Carbon;

class BookingForm extends Component
{
    public $vehicle;
    public $start_date;
    public $end_date;
    public $days = 0;
    public $total = 0;

    protected $rules = [
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after:start_date',
    ];

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    public function updated($property)
    {
        $this->validateOnly($property);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        if ($this->start_date && $this->end_date) {
            $start = Carbon::parse($this->start_date);
            $end = Carbon::parse($this->end_date);
            $this->days = max(1, $start->diffInDays($end));
            $this->total = $this->days * $this->vehicle->price_per_day;
        }
    }

    public function submit()
    {
        $this->validate();

        // Check if the vehicle is already booked in that range
        $overlap = Booking::where('vehicle_id', $this->vehicle->id)
            ->where(function ($q) {
                $q->whereBetween('start_date', [$this->start_date, $this->end_date])
                  ->orWhereBetween('end_date', [$this->start_date, $this->end_date]);
            })
            ->exists();

        if ($overlap) {
            session()->flash('error', 'This vehicle is already booked for those dates.');
            return;
        }

        Booking::create([
            'user_id' => auth()->id(),
            'vehicle_id' => $this->vehicle->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total_price' => $this->total,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Booking created successfully! Awaiting confirmation.');
        return redirect()->route('bookings.index');
    }

    public function render()
    {
        return view('livewire.booking-form');
    }
}
