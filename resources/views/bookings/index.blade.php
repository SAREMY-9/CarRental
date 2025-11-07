@extends('layouts.booking')

@section('content')
<div class="max-w-6xl mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-4">All Bookings</h2>

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="border p-2">#</th>
                <th class="border p-2">User</th>
                <th class="border p-2">Vehicle</th>
                <th class="border p-2">Vehicle Registration</th>
                <th class="border p-2">Rate (KES)</th>
                <th class="border p-2">Pickup</th>
                <th class="border p-2">Drop-off</th>
                <th class="border p-2">Start</th>
                <th class="border p-2">End</th>
                <th class="border p-2">Total</th>
                <th class="border p-2">Status</th>
                <th class="border p-2">Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="border p-2">{{ $loop->iteration }}</td>
                    <td class="border p-2">{{ $booking->user->name ?? 'N/A' }}</td>
                    <td class="border p-2">
                        {{ $booking->vehicle ? $booking->vehicle->make . ' ' . $booking->vehicle->model : 'N/A' }}
                    </td>
                    {{-- FIXED: Swapped content for Vehicle Registration and Rate (KES) columns --}}
                    {{-- Assuming registration number is directly on $booking->vehicle --}}
                    <td class="border p-2">
                        {{ $booking->vehicle->registration_number ?? 'N/A' }}
                    </td>
                    {{-- Assuming rate is either price_per_day or daily_rate on the vehicle --}}
                    <td class="border p-2">
                        {{ number_format($booking->vehicle->price_per_day ?? $booking->vehicle->daily_rate ?? 0, 2) }}
                    </td>
                    <td class="border p-2">{{ $booking->pickupLocation->name ?? 'N/A' }}</td>
                    <td class="border p-2">{{ $booking->dropoffLocation->name ?? 'Same as pickup' }}</td>
                    <td class="border p-2">{{ \Carbon\Carbon::parse($booking->start_date)->format('Y-m-d H:i') }}</td>
                    <td class="border p-2">{{ \Carbon\Carbon::parse($booking->end_date)->format('Y-m-d H:i') }}</td>
                    <td class="border p-2">KES {{ number_format($booking->total_price, 2) }}</td>
                    <td class="border p-2">
                        @php $status = $booking->status ?? 'pending'; @endphp
                        <span class="px-2 py-1 rounded text-white text-sm 
                            {{ $status === 'pending' ? 'bg-yellow-500' : 
                               ($status === 'confirmed' ? 'bg-green-600' : 
                               ($status === 'completed' ? 'bg-blue-600' : 'bg-gray-400')) }}">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    <td class="border p-2">{{ \Carbon\Carbon::parse($booking->created_at)->diffForHumans() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection