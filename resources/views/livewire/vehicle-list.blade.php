<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Available Vehicles</h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div>
            <input wire:model="search" type="text" placeholder="Search make/model..." class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <select wire:model="type" class="w-full border rounded px-3 py-2">
                <option value="">All Types</option>
                @foreach($types as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <select wire:model="location" class="w-full border rounded px-3 py-2">
                <option value="">All Locations</option>
                @foreach($locations as $l)
                    <option value="{{ $l->id }}">{{ $l->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <button wire:click="$set('type','');$set('location','');$set('search','')" class="bg-gray-200 px-3 py-2 rounded w-full">Reset</button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($vehicles as $vehicle)
            <div class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition">
                <h3 class="font-semibold text-lg">{{ $vehicle->make }} {{ $vehicle->model }}</h3>
                <p class="text-sm text-gray-600">Type: {{ $vehicle->type->name }}</p>
                <p class="text-sm text-gray-600">Location: {{ $vehicle->location->name }}</p>
                <p class="text-blue-600 font-bold mt-2">KES {{ number_format($vehicle->price_per_day) }}/day</p>

                <a href="{{ route('bookings.create', $vehicle) }}" class="inline-block mt-3 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Book Now
                </a>
            </div>
        @empty
            <p class="col-span-3 text-center text-gray-500">No vehicles found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $vehicles->links() }}
    </div>
</div>
