<x-app-layout>
    <div class="max-w-xl mx-auto p-6 lg:p-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Ticket Admission</h1>

        @if (session('status'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                <p class="font-bold">Success!</p>
                <p>{{ session('status') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                <p class="font-bold">Error!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow">
            <form action="{{ route('admin.tickets.admit') }}" method="POST">
                @csrf
                
                <div>
                    <label for="barcode" class="block font-medium text-gray-700 mb-2">Scan Barcode</label>
                    
                    <input type="text" name="barcode" id="barcode" required autofocus placeholder="Enter 9-digit code..."
                           class="w-full text-2xl tracking-widest text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">
                        Validate Ticket
                    </button>
                </div>
            </form>
        </div>
        
        <div class="mt-6 text-center">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900 hover:underline">
                &larr; Back to Dashboard
            </a>
        </div>
    </div>
</x-app-layout>