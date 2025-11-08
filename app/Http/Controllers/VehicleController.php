<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Location;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
        public function index(Request $request)
    {
        $query = Vehicle::with(['type', 'location']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('make', 'like', '%' . $request->search . '%')
                ->orWhere('model', 'like', '%' . $request->search . '%')
                ->orWhere('registration_number', 'like', '%' . $request->search . '%');
            });
        }

        
        if ($request->filled('vehicle_type_id')) {
            $query->where('vehicle_type_id', $request->vehicle_type_id);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vehicles = $query->get();
        $types = VehicleType::all();
        $locations = Location::all();

        return view('admin.vehicles.index', compact('vehicles', 'types', 'locations'));
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
