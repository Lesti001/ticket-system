<x-app-layout>
    <div class="max-w-xl mx-auto p-6 lg:p-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Add New Seat</h1>

        <div class="bg-white p-6 rounded-lg shadow">
            <form action="{{ route('admin.seats.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block font-medium text-gray-700">Seat Number (e.g. C005)</label>
                    <input type="text" name="seat_number" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('seat_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block font-medium text-gray-700">Base Price (HUF)</label>
                    <input type="number" name="base_price" required min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('base_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.seats.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>