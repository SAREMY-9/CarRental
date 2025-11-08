<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Available Vehicles</h2>

    {{-- Session messages are handled in the parent Blade file, not usually here --}}
    {{-- @if (session('success')) ... @endif --}}
         
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div>
            {{-- Maps to $search public property --}}
            <input wire:model.live="search" type="text" placeholder="Search make/model/reg..." class="w-full border rounded px-3 py-2">
        </div>

        <div>
            {{-- Maps to $type public property (used for vehicle_type_id) --}}
            <select wire:model.live="type" class="w-full border rounded px-3 py-2">
                <option value="">All Types</option>
                @foreach($types as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            {{-- Maps to $location public property (used for location_id) --}}
            <select wire:model.live="location" class="w-full border rounded px-3 py-2">
                <option value="">All Locations</option>
                @foreach($locations as $l)
                    <option value="{{ $l->id }}">{{ $l->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            {{-- Calls the resetFilters method in the component --}}
            <button wire:click="resetFilters" class="bg-gray-200 px-3 py-2 rounded w-full hover:bg-gray-300">Reset Filters</button>
        </div>
    </div>
    

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($vehicles as $vehicle)
            <div class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition">
                <h3 class="font-semibold text-lg">{{ $vehicle->make }} {{ $vehicle->model }}</h3>
                <p class="text-sm text-gray-600">Reg: {{ $vehicle->registration_number }}</p>
                <p class="text-sm text-gray-600">Type: {{ $vehicle->type->name }}</p>
                <p class="text-sm text-gray-600">Location: {{ $vehicle->location->name }}</p>
                <p class="text-blue-600 font-bold mt-2">KES {{ number_format($vehicle->price_per_day) }}/day</p>

                <a href="{{ route('bookings.create', $vehicle) }}" class="inline-block mt-3 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Book Now
                </a>
            </div>
        @empty
            <p class="col-span-3 text-center text-gray-500">No vehicles found matching your criteria.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{-- Livewire pagination links --}}
        {{ $vehicles->links() }}
    </div>
</div>