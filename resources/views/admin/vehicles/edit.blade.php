@extends('layouts.admin')

@section('title', 'Edit Vehicle')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Edit Vehicle</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-800 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select name="vehicle_type_id" class="w-full border-gray-300 rounded-lg" required>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" {{ $vehicle->vehicle_type_id == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Location</label>
                <select name="location_id" class="w-full border-gray-300 rounded-lg" required>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}" {{ $vehicle->location_id == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Make</label>
                <input type="text" name="make" value="{{ $vehicle->make }}" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Model</label>
                <input type="text" name="model" value="{{ $vehicle->model }}" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Registration Number</label>
                <input type="text" name="registration_number" value="{{ $vehicle->registration_number }}" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Year</label>
                <input type="number" name="year" value="{{ $vehicle->year }}" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Price Per Day</label>
                <input type="number" name="price_per_day" step="0.01" value="{{ $vehicle->price_per_day }}" class="w-full border-gray-300 rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="w-full border-gray-300 rounded-lg" required>
                    <option value="available" {{ $vehicle->status == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ $vehicle->status == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('vehicles.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update Vehicle</button>
        </div>
    </form>
</div>
@endsection
