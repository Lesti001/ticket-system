<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 lg:p-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Edit Event: {{ $event->title }}</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $readOnly = $event->sale_start_at->isPast();
        @endphp

        @if ($readOnly)
            <div class="mb-6 p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                <strong>Notice:</strong> Ticket sales have already started. You can only edit the title, description, and cover image.
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow">
            <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT') <div>
                    <label for="title" class="block font-medium text-gray-700">Event Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="description" class="block font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4" required maxlength="1000"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $event->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="event_date_at" class="block font-medium text-gray-700">Event Date</label>
                        <input type="datetime-local" name="event_date_at" id="event_date_at" 
                               value="{{ old('event_date_at', $event->event_date_at->format('Y-m-d\TH:i')) }}" 
                               {{ $readOnly ? 'disabled class=bg-gray-100' : '' }}
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="sale_start_at" class="block font-medium text-gray-700">Sale Start</label>
                        <input type="datetime-local" name="sale_start_at" id="sale_start_at" 
                               value="{{ old('sale_start_at', $event->sale_start_at->format('Y-m-d\TH:i')) }}" 
                               {{ $readOnly ? 'disabled class=bg-gray-100' : '' }}
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="sale_end_at" class="block font-medium text-gray-700">Sale End</label>
                        <input type="datetime-local" name="sale_end_at" id="sale_end_at" 
                               value="{{ old('sale_end_at', $event->sale_end_at->format('Y-m-d\TH:i')) }}" 
                               {{ $readOnly ? 'disabled class=bg-gray-100' : '' }}
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="max_number_allowed" class="block font-medium text-gray-700">Max Tickets per User</label>
                        <input type="number" name="max_number_allowed" id="max_number_allowed" 
                               value="{{ old('max_number_allowed', $event->max_number_allowed) }}" 
                               {{ $readOnly ? 'disabled class=bg-gray-100' : '' }}
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="cover_image" class="block font-medium text-gray-700">Change Cover Image</label>
                        <input type="file" name="cover_image" id="cover_image" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_dynamic_price" id="is_dynamic_price" value="1" 
                           {{ old('is_dynamic_price', $event->is_dynamic_price) ? 'checked' : '' }}
                           {{ $readOnly ? 'disabled' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_dynamic_price" class="ml-2 block text-gray-900">
                        Enable Dynamic Pricing
                    </label>
                </div>

                <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Update Event</button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>