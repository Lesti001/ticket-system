<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 lg:p-8">

        <h1 class="text-2xl font-semibold text-gray-900">
            Admin Dashboard
        </h1>

        @if (session('status'))
            <div class="mt-4 p-4 bg-green-100 text-green-800 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mt-4 p-4 bg-red-100 text-red-800 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-8 flex justify-end">
            <a href="{{ route('admin.tickets.admission') }}"
                class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg transition">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                    </path>
                </svg>
                Scan Tickets
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="p-6 bg-white rounded-lg shadow">
                <h2 class="text-sm font-medium text-gray-600">Total Events</h2>
                <p class="text-3xl font-bold text-gray-900">{{ $totalEvents }}</p>
            </div>
            <div class="p-6 bg-white rounded-lg shadow">
                <h2 class="text-sm font-medium text-gray-600">Total Tickets Sold</h2>
                <p class="text-3xl font-bold text-gray-900">{{ $totalTicketsSold }}</p>
            </div>
            <div class="p-6 bg-white rounded-lg shadow">
                <h2 class="text-sm font-medium text-gray-600">Total Revenue</h2>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalRevenue, 0, ',', ' ') }} HUF</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">

            <div class="lg:col-span-1 p-6 bg-white rounded-lg shadow mb-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Top 3 Popular Seats</h2>
                <div class="space-y-3">
                    @forelse ($topSeats as $seatInfo)
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-700">{{ $seatInfo->seat->seat_number }}</span>
                            <span class="font-bold text-blue-600">{{ $seatInfo->sold_count }} sold</span>
                        </div>
                    @empty
                        <p class="text-gray-500">No tickets sold yet.</p>
                    @endforelse
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.seats.index') }}" class="text-blue-600 hover:underline font-semibold">
                        &rarr; Manage All Seats
                    </a>
                </div>
            </div>

            <div class="lg:col-span-2 p-6 bg-white rounded-lg shadow">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Event Management</h2>
                <div class="flex justify-between items-center mb-4">
                    <a href="{{ route('admin.events.create') }}"
                        class="px-4 py-2 bg-green-600 text-white text-sm font-bold rounded hover:bg-green-700">
                        + New Event
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach ($events as $event)
                        <div class="p-4 border rounded-lg">
                            <h3 class="font-bold text-lg text-gray-800">{{ $event->title }}</h3>
                            <p class="text-sm text-gray-600">Date: {{ $event->event_date_at->format('Y-m-d') }}</p>
                            <div class="flex justify-between items-center mt-2">
                                <div>
                                    <p><strong>Revenue:</strong>
                                        {{ number_format($event->tickets_sum_price ?? 0, 0, ',', ' ') }} HUF</p>
                                    <p><strong>Available:</strong> {{ $totalSeatsCount - $event->tickets_count }} /
                                        {{ $totalSeatsCount }}
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.events.edit', $event) }}"
                                        class="px-3 py-1 bg-yellow-500 text-white text-sm rounded">Edit</a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this event?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $events->links() }}
                </div>
            </div>

        </div>

    </div>
</x-app-layout>