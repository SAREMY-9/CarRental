<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Location;
use Livewire\WithPagination;

class VehicleList extends Component
{
    use WithPagination;

    public $type = '';
    public $location = '';
    public $search = '';

    protected $queryString = ['type', 'location', 'search'];

    public function render()
    {
        $query = Vehicle::with(['type', 'location'])
            ->where('status', 'available');

        if ($this->type) {
            $query->where('vehicle_type_id', $this->type);
        }

        if ($this->location) {
            $query->where('location_id', $this->location);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('make', 'like', "%{$this->search}%")
                  ->orWhere('model', 'like', "%{$this->search}%")
                  ->orWhere('registration_number', 'like', "%{$this->search}%");
            });
        }

        return view('livewire.vehicle-list', [
            'vehicles' => $query->paginate(6),
            'types' => VehicleType::all(),
            'locations' => Location::all(),
            
        ]) ->layout('layouts.app');
    }
}
