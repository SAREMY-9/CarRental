@extends('layouts.admin')

@section('title', 'All Vehicles')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Vehicles</h1>
        <a href="{{ route('vehicles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Add Vehicle</a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100 text-left text-gray-700">
                <th class="p-3 border-b">Make</th>
                <th class="p-3 border-b">Model</th>
                <th class="p-3 border-b">Type</th>
                <th class="p-3 border-b">Location</th>
                <th class="p-3 border-b">Price/Day</th>
                <th class="p-3 border-b">Status</th>
                <th class="p-3 border-b text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $vehicle)
            <tr class="hover:bg-gray-50">
                <td class="p-3 border-b">{{ $vehicle->make }}</td>
                <td class="p-3 border-b">{{ $vehicle->model }}</td>
                <td class="p-3 border-b">{{ $vehicle->type->name ?? 'N/A' }}</td>
                <td class="p-3 border-b">{{ $vehicle->location->name ?? 'N/A' }}</td>
                <td class="p-3 border-b">Ksh {{ number_format($vehicle->price_per_day, 2) }}</td>
                <td class="p-3 border-b">
                    <span class="px-2 py-1 rounded text-white {{ $vehicle->status === 'available' ? 'bg-green-500' : 'bg-red-500' }}">
                        {{ ucfirst($vehicle->status) }}
                    </span>
                </td>
                <td class="p-3 border-b text-center">
                    <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="text-blue-600 hover:underline font-medium">Edit</a>
                    <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" class="inline-block ml-3" onsubmit="return confirm('Are you sure you want to delete this vehicle?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline font-medium">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
