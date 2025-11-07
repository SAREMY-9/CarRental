@extends('layouts.booking')

@section('content')

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">Book {{ $vehicle->make }} {{ $vehicle->model }} Now</h2>

    <form action="{{ route('bookings.store') }}" method="POST">
        @csrf
        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

        {{-- Pickup Location --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Pickup Location</label>
            <select name="pickup_location_id" class="w-full border rounded px-3 py-2 mt-1" required>
                <option value="">Select location</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
            @error('pickup_location_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Dropoff Location --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Drop-off Location (optional)</label>
            <select name="dropoff_location_id" class="w-full border rounded px-3 py-2 mt-1">
                <option value="">Same as pickup</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
            @error('dropoff_location_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Start & End Dates --}}
        @php
            $now = now()->format('Y-m-d\TH:i');
        @endphp
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                <input 
                    type="datetime-local" 
                    name="start_date" 
                    value="{{ old('start_date', $now) }}" 
                    class="w-full border rounded px-3 py-2 mt-1"
                    required
                >
                <p class="text-xs text-gray-500 mt-1">Select when you want to start your booking (e.g. 2025-11-07 14:30)</p>
                @error('start_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">End Date & Time</label>
                <input 
                    type="datetime-local" 
                    name="end_date" 
                    value="{{ old('end_date', $now) }}" 
                    class="w-full border rounded px-3 py-2 mt-1"
                    required
                >
                <p class="text-xs text-gray-500 mt-1">Must be later than your start date</p>
                @error('end_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Submit --}}
        <div class="text-right">
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700">
                Confirm Booking
            </button>
        </div>
    </form>
</div>

{{-- Auto-set min date/time to now --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const now = new Date();
        const formatted = now.toISOString().slice(0, 16); // "2025-11-07T14:30"

        document.querySelectorAll('input[type="datetime-local"]').forEach(input => {
            input.min = formatted;
        });
    });
</script>

@endsection

