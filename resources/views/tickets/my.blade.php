<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <h1 class="text-2xl font-semibold text-gray-900">
            My Purchased Tickets
        </h1>

        @if (session('status'))
            <div class="mt-4 p-4 bg-green-100 text-green-800 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-8 space-y-8">
            @forelse ($groupedTickets as $eventId => $tickets)
                @php
                    $event = $tickets->first()->event;
                @endphp

                <section class="p-6 bg-white rounded-lg shadow">
                    <h2 class="text-xl font-bold text-gray-900">{{ $event->title }}</h2>
                    <p class="text-md text-gray-600">Date: {{ $event->event_date_at->format('Y-m-d H:i') }}</p>

                    <div class="mt-4 space-y-4">
                        @foreach ($tickets as $ticket)
                            <div class="p-4 border border-gray-200 rounded-lg flex justify-between items-center">
                                <div>
                                    <p class="text-lg font-semibold">Seat: {{ $ticket->seat->seat_number }}</p>
                                    <p class="text-sm text-gray-600">Barcode (text): {{ $ticket->barcode }}</p>
                                </div>
                                
                                <div class="barcode text-gray-900">
                                    *{{ $ticket->barcode }}*
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @empty
                <p class="text-gray-600">You have not purchased any tickets yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>