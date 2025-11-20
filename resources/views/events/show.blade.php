<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 lg:p-8">

        <h1 class="text-2xl font-semibold text-gray-900">
            {{ $event->title }}
        </h1>

        <div class="mt-8 p-6 bg-white rounded-lg shadow">

            <p class="text-gray-900">
                <strong>Date:</strong> {{ $event->event_date_at->format('Y-m-d H:i') }}
            </p>

            <p class="mt-4 text-gray-900">
                <strong>Description:</strong>
            </p>
            <div class="mt-2 text-gray-700">
                {!! nl2br(e($event->description)) !!}
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200 text-sm text-gray-600">
                <p>Sale starts: {{ $event->sale_start_at->format('Y-m-d H:i') }}</p>
                <p>Sale ends: {{ $event->sale_end_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('events.purchase.form', $event) }}"
                class="inline-block my-2 px-4 py-2 bg-blue-600 rounded hover:bg-blue-700">
                Buy Tickets
            </a>
        </div>

    </div>
</x-app-layout>