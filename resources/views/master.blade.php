<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master View') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 dark:bg-base-300 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content dark:text-base-content">

                    <!-- Sub Navigation -->
                    <div class="mb-6 flex gap-4">
                        <a href="{{ route('export-data') }}" class="btn btn-secondary">Export Data</a>
                        <a href="{{ route('active-therapists.index') }}" class="btn btn-secondary">Active Therapists</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>