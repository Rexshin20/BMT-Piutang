<x-app-layout>
    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow sm:rounded-lg border border-gray-200">
            <div class="bg-gray-50 px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-800">500 - Server Error</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Something went wrong on our end. Please try again later.</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6 sm:flex sm:flex-row-reverse">
                <a href="{{ route('dashboard') }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-white font-medium text-sm hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>