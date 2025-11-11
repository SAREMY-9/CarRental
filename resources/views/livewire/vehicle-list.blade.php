<div class="min-h-screen bg-gray-50 text-gray-800">

    {{-- HERO SECTION --}}
    <section class="relative bg-gradient-to-r from-indigo-700 to-blue-600 text-white">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1502877338535-766e1452684a" alt="Car rental background"
                 class="w-full h-full object-cover opacity-30">
        </div>
        <div class="relative max-w-6xl mx-auto px-6 py-20 text-center">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-4 drop-shadow-lg">Drive Your Freedom</h1>
            <p class="text-lg md:text-xl opacity-90 mb-8">Rent from a wide range of reliable cars — from city cruisers to SUVs — anywhere, anytime.</p>
            <a href="#available-vehicles" class="bg-white text-indigo-700 px-8 py-3 rounded-full font-semibold shadow-lg hover:bg-gray-100 transition">
                Browse Vehicles
            </a>
        </div>
    </section>

    {{-- SEARCH & FILTER BAR --}}
    <section class="max-w-6xl mx-auto -mt-8 bg-white rounded-2xl shadow-lg p-6 relative z-10 mb-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input 
                wire:model.live="search"
                type="text"
                placeholder="🔍 Search make, model, or reg..."
                class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500"
            >

            <select 
                wire:model.live="type"
                class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500"
            >
                <option value="">🚗 All Types</option>
                @foreach($types as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
            </select>

            <select 
                wire:model.live="location"
                class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500"
            >
                <option value="">📍 All Locations</option>
                @foreach($locations as $l)
                    <option value="{{ $l->id }}">{{ $l->name }}</option>
                @endforeach
            </select>

            <button 
                wire:click="resetFilters"
                class="bg-indigo-600 text-white font-semibold rounded-lg px-4 py-3 hover:bg-indigo-700 transition"
            >
                Reset Filters
            </button>
        </div>
    </section>

    {{-- AVAILABLE VEHICLES --}}
    <section id="available-vehicles" class="max-w-6xl mx-auto p-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Available Vehicles</h2>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($vehicles as $vehicle)
                <div class="bg-white rounded-2xl shadow hover:shadow-xl transform hover:-translate-y-1 transition overflow-hidden">
                    <div class="relative h-48 bg-gray-100">
                        @if($vehicle->image_url ?? false)
                            <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->make }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400 text-sm">No Image</div>
                        @endif
                        <div class="absolute top-3 left-3 bg-indigo-600 text-white text-xs px-3 py-1 rounded-full">
                            {{ $vehicle->type->name }}
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="text-xl font-bold">{{ $vehicle->make }} {{ $vehicle->model }}</h3>
                        <p class="text-gray-500 text-sm">Reg: {{ $vehicle->registration_number }}</p>
                        <p class="text-gray-500 text-sm">Location: {{ $vehicle->location->name }}</p>

                        <p class="text-indigo-600 font-bold text-lg mt-3">KES {{ number_format($vehicle->price_per_day) }} / day</p>

                        <a href="{{ route('bookings.create', $vehicle) }}" 
                           class="inline-block mt-4 bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 font-medium transition">
                           Book Now
                        </a>
                    </div>
                </div>
            @empty
                <p class="col-span-3 text-center text-gray-500 text-lg">No vehicles found matching your criteria.</p>
            @endforelse
        </div>

        <div class="mt-10 flex justify-center">
            {{ $vehicles->links() }}
        </div>
    </section>

    {{-- WHY CHOOSE US --}}
    <section class="bg-white py-16 mt-12">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-10">Why Choose Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 border rounded-xl hover:shadow-md transition">
                    <div class="text-indigo-600 text-4xl mb-4">🚙</div>
                    <h3 class="font-semibold text-lg mb-2">Wide Vehicle Range</h3>
                    <p class="text-gray-500 text-sm">From budget-friendly sedans to luxurious SUVs — find exactly what suits your trip.</p>
                </div>
                <div class="p-6 border rounded-xl hover:shadow-md transition">
                    <div class="text-indigo-600 text-4xl mb-4">💳</div>
                    <h3 class="font-semibold text-lg mb-2">Transparent Pricing</h3>
                    <p class="text-gray-500 text-sm">No hidden costs or surprises — pay only what you see on the screen.</p>
                </div>
                <div class="p-6 border rounded-xl hover:shadow-md transition">
                    <div class="text-indigo-600 text-4xl mb-4">🕒</div>
                    <h3 class="font-semibold text-lg mb-2">24/7 Availability</h3>
                    <p class="text-gray-500 text-sm">Book, modify, or cancel anytime — our team’s got your back round the clock.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CUSTOMER TRUST SECTION --}}
    <section class="bg-indigo-600 text-white py-16">
        <div class="max-w-6xl mx-auto text-center px-6">
            <h2 class="text-3xl font-bold mb-6">Trusted by Thousands of Drivers</h2>
            <p class="text-indigo-100 max-w-2xl mx-auto mb-8">
                Over 10,000 happy customers have chosen our service for their business trips, weekend getaways, and family vacations.
            </p>
            <div class="flex flex-wrap justify-center gap-8 opacity-90">
                <div class="text-4xl font-extrabold">10K+</div>
                <div class="text-4xl font-extrabold">5★ Rated</div>
                <div class="text-4xl font-extrabold">100+ Cars</div>
            </div>
        </div>
    </section>

    {{-- FOOTER CTA --}}
    <footer class="bg-gray-900 text-gray-300 py-10 mt-12">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h3 class="text-2xl font-bold text-white mb-4">Ready to Hit the Road?</h3>
            <p class="mb-6 text-gray-400">Book your perfect car now and enjoy stress-free travel.</p>
            <a href="#available-vehicles" class="bg-indigo-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-indigo-700 transition">
                Find My Car
            </a>
            <p class="mt-8 text-sm text-gray-500">© {{ date('Y') }} Rently. All rights reserved.</p>
        </div>
    </footer>
</div>
