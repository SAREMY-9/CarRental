<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //

        $query = Vehicle::query()->where('status', 'available');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('make', 'like', '%' . $request->search . '%')
                  ->orWhere('model', 'like', '%' . $request->search . '%')
                  ->orWhere('registration_number', 'like', '%' . $request->search . '%');
            });
        }

        $vehicles = $query->paginate(10);

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)

    {
        //

        $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'registration_number' => 'required|unique:cars',
            'year' => 'required|digits:4',
            'price_per_day' => 'required|numeric|min:0',
        ]);

        Car::create($request->all());
        return redirect()->route('vehicles.index')->with('success', 'Vehicle added successfully!');
 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        
        return view('vehicles.edit', compact('vehicle'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        
         $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'registration_number' => 'required|unique:cars,registration_number,' . $vehicle->id,
            'year' => 'required|digits:4',
            'price_per_day' => 'required|numeric|min:0',
            'status' => 'required|in:available,unavailable',
        ]);

        $vehicle->update($request->all());
        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully!');
 
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return back()->with('success', 'Vehicle deleted successfully!');
    }

}


