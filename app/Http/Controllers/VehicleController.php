<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Location;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    
    /**
     * Public listing + search
     */
    public function index(Request $request)
    {
        $query = Vehicle::with(['type', 'location'])->where('status', 'available');

        if ($request->filled('type')) {
            $query->where('vehicle_type_id', $request->type);
        }

        if ($request->filled('location')) {
            $query->where('location_id', $request->location);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('make', 'like', "%{$request->search}%")
                  ->orWhere('model', 'like', "%{$request->search}%")
                  ->orWhere('registration_number', 'like', "%{$request->search}%");
            });
        }

        $vehicles = $query->paginate(10);
        $types = VehicleType::all();
        $locations = Location::all();

        return view('vehicles.index', compact('vehicles', 'types', 'locations'));
    }

    /**
     * Admin: create vehicle form
     */
    public function create()
    {
        $types = VehicleType::all();
        $locations = Location::all();
        return view('vehicles.create', compact('types', 'locations'));
    }

    /**
     * Admin: store new vehicle
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'location_id' => 'required|exists:locations,id',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'registration_number' => 'required|unique:vehicles',
            'year' => 'required|digits:4',
            'price_per_day' => 'required|numeric|min:0',
        ]);

        Vehicle::create($request->all());
        return redirect()->route('vehicles.index')->with('success', 'Vehicle added successfully!');
    }

    /**
     * Admin: edit form
     */
    public function edit(Vehicle $vehicle)
    {
        $types = VehicleType::all();
        $locations = Location::all();
        return view('vehicles.edit', compact('vehicle', 'types', 'locations'));
    }

    /**
     * Admin: update vehicle
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'location_id' => 'required|exists:locations,id',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'registration_number' => 'required|unique:vehicles,registration_number,' . $vehicle->id,
            'year' => 'required|digits:4',
            'price_per_day' => 'required|numeric|min:0',
            'status' => 'required|in:available,unavailable',
        ]);

        $vehicle->update($request->all());
        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully!');
    }

    /**
     * Admin: delete vehicle
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return back()->with('success', 'Vehicle deleted successfully!');
    }
}
