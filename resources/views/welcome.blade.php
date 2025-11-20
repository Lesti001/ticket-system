<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 lg:p-8"> <h1 class="text-2xl font-semibold text-gray-900">
                    Upcoming Events
                </h1>
        
                <div class="mt-8">
                
                    @forelse ($events as $event)
                        
                        <div class="py-6 border-b border-gray-200 last:border-b-0">
                            
                            <h2 class="text-xl font-semibold text-gray-900">
                                {{ $event->title }}
                            </h2>
                            
                            <p class="mt-2 text-gray-600">
                                Date: {{ $event->event_date_at->format('Y-m-d H:i') }}
                            </p>
                            
                            <p class="mt-2 font-semibold text-gray-600">
                                Available Seats: {{ $totalSeats - $event->tickets_count }} / {{ $totalSeats }}
                            </p>

                            <a href="{{ route('events.show', $event) }}" class="inline-block mt-4 text-blue-500 hover:underline">
                                Details...
                            </a>
                        </div>
                    @empty
                        <p class="text-gray-600">
                            There are no upcoming events at this time.
                        </p>
                    @endforelse
                </div>
        
                <div class="mt-8">
                    {{ $events->links() }}
                </div>

            </div> </div> </div>
</x-app-layout>