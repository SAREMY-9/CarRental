<?php

namespace App\Livewire; // Assuming Livewire 3 setup

use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleIndex extends Component
{
    use WithPagination;
    
    // Public properties mapped to wire:model in the view
    public $search = '';
    public $type = '';
    public $location = '';
    
    // Reset pagination when any filter changes
    public function updated($property)
    {
        if (in_array($property, ['search', 'type', 'location'])) {
            $this->resetPage();
        }
    }
    
    // Method to clear all filters
    public function resetFilters()
    {
        $this->reset(['search', 'type', 'location']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Vehicle::with(['type', 'location']);

        // 🚗 Search Logic
        if (!empty($this->search)) {
            $query->where(function($q) {
                $searchTerm = '%' . $this->search . '%';
                $q->where('make', 'like', $searchTerm)
                  ->orWhere('model', 'like', $searchTerm)
                  ->orWhere('registration_number', 'like', $searchTerm);
            });
        }
        
        // 🎯 Type Filter
        if (!empty($this->type)) {
            $query->where('vehicle_type_id', $this->type);
        }

        // 📍 Location Filter
        if (!empty($this->location)) {
            $query->where('location_id', $this->location);
        }
        
        // Fetch necessary data
        $vehicles = $query->paginate(9); // Using pagination is typical for Livewire
        $types = VehicleType::all();
        $locations = Location::all();

        return view('livewire.vehicle-index', compact('vehicles', 'types', 'locations'));
    }
}