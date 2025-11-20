<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Manage Seats</h1>
            <a href="{{ route('admin.seats.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                + Add New Seat
            </a>
        </div>

        @if (session('status'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">{{ session('error') }}</div>
        @endif

        <div class="bg-white shadow overflow-hidden rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seat Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Base Price</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($seats as $seat)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $seat->seat_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ number_format($seat->base_price, 0, ',', ' ') }} HUF</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.seats.edit', $seat) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>

                                <form action="{{ route('admin.seats.destroy', $seat) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this seat?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">
                {{ $seats->links() }}
            </div>
        </div>
    </div>
</x-app-layout>