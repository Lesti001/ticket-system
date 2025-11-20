<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 lg:p-8">

        <h1 class="text-2xl font-semibold text-gray-900">
            Buy Tickets for: {{ $event->title }}
        </h1>

        <div class="mt-8 p-6 bg-white rounded-lg shadow">
            
            <p class="text-gray-900">
                <strong>Event Date:</strong> {{ $event->event_date_at->format('Y-m-d H:i') }}
            </p>
            
            <p class="mt-2 text-lg font-semibold text-blue-600">
                You can buy {{ $remainingTicketsAllowed }} more ticket(s) for this event.
            </p>

            <form action="{{ route('events.purchase', $event) }}" method="POST">
                @csrf

                <h3 class="text-xl font-semibold text-gray-900 mt-6 mb-4">Select your seats:</h3>
                
                <div class="space-y-2">
                    @foreach ($allSeats as $seat)
                        @php
                            // Kiszámoljuk az árat a modellbe írt funkcióval
                            $price = $event->calculatePrice($seat, $priceData);
                            
                            // Ellenőrizzük, hogy a hely foglalt-e
                            $isTaken = in_array($seat->id, $takenSeatIds);
                        @endphp

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg {{ $isTaken ? 'opacity-50' : '' }}">
                            <div>
                                <input type="checkbox" 
                                       name="seats[]" 
                                       value="{{ $seat->id }}" 
                                       id="seat-{{ $seat->id }}"
                                       class="form-checkbox h-5 w-5 text-blue-600"
                                       {{ $isTaken ? 'disabled' : '' }}>
                                
                                <label for="seat-{{ $seat->id }}" class="ml-2 text-lg font-medium text-gray-900">
                                    {{ $seat->seat_number }}
                                </label>
                            </div>
                            
                            <span class="text-lg font-bold text-gray-800">
                                {{ number_format($price, 0, ',', ' ') }} HUF
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    <button type="submit" 
                            class="inline-block px-6 py-3 bg-blue-600 rounded-lg font-semibold hover:bg-blue-200 disabled:opacity-50"
                            {{ $remainingTicketsAllowed <= 0 ? 'disabled' : '' }}>
                        
                        {{ $remainingTicketsAllowed <= 0 ? 'You cannot buy more tickets' : 'Purchase Selected Tickets' }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>