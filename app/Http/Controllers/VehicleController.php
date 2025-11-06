<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Location;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with(['type', 'location'])->get();
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        $types = VehicleType::all();
        $locations = Location::all();
        return view('admin.vehicles.create', compact('types', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_type_id' => 'required',
            'location_id' => 'required',
            'make' => 'required|string',
            'model' => 'required|string',
            'registration_number' => 'required|string|unique:vehicles',
            'year' => 'required|integer',
            'price_per_day' => 'required|numeric',
            'status' => 'required|string',
        ]);

        Vehicle::create($validated);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle added successfully.');
    }

    public function edit(Vehicle $vehicle)
    {
        $types = VehicleType::all();
        $locations = Location::all();
        return view('admin.vehicles.edit', compact('vehicle', 'types', 'locations'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'vehicle_type_id' => 'required',
            'location_id' => 'required',
            'make' => 'required|string',
            'model' => 'required|string',
            'registration_number' => 'required|string|unique:vehicles,registration_number,' . $vehicle->id,
            'year' => 'required|integer',
            'price_per_day' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted successfully.');
    }
}
