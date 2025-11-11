@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Add New Vehicle</h2>

    <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label>Make</label>
            <input type="text" name="make" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label>Model</label>
            <input type="text" name="model" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label>Registration Number</label>
            <input type="text" name="registration_number" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label>Year</label>
            <input type="number" name="year" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label>Price per Day</label>
            <input type="number" name="price_per_day" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label>Status</label>
            <select name="status" class="w-full border rounded p-2">
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>
        </div>

        <div class="mb-4">
            <label>Type</label>
            <select name="vehicle_type_id" class="w-full border rounded p-2">
                @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label>Location</label>
            <select name="location_id" class="w-full border rounded p-2">
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>

        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Vehicle Image</label>
            <input type="file" name="image" class="w-full border rounded px-3 py-2 mt-1">
            @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Save Vehicle</button>
    </form>
</div>
@endsection
