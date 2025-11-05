<div class="bg-white p-6 rounded-lg shadow max-w-lg mx-auto">
    <h2 class="text-xl font-semibold mb-4">
        Book {{ $vehicle->make }} {{ $vehicle->model }}
    </h2>

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-2 rounded mb-3">{{ session('error') }}</div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" wire:model="start_date"
                class="mt-1 block w-full border rounded px-3 py-2">
            @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" wire:model="end_date"
                class="mt-1 block w-full border rounded px-3 py-2">
            @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-between items-center border-t pt-3">
            <p class="text-gray-600">Days: <strong>{{ $days }}</strong></p>
            <p class="text-indigo-600 font-bold">KES {{ number_format($total) }}</p>
        </div>

        <div class="pt-4">
            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                Confirm Booking
            </button>
        </div>
    </form>
</div>
