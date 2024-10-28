<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Active Therapists') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Display success messages -->
                    @if (session('success'))
                    <div
                        class="alert alert-success shadow-lg bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">
                        <div>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                    @endif

                    <!-- Button to create a new therapist -->
                    @if(auth()->user()->role === 'admin')
                    <div class="mb-6">
                        <a href="{{ route('active-therapists.create') }}"
                            class="btn btn-primary bg-blue-500 dark:bg-blue-700 text-white dark:text-gray-200">Add New
                            Therapist</a>
                    </div>
                    @endif

                    <!-- Therapists Table -->
                    <div class="overflow-x-auto">
                        <table
                            class="table w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">Name</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">Phone Number
                                    </th>
                                    @if(auth()->user()->role !== 'admin')
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">Status</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">Available Status
                                    </th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">Actions</th>
                                    @endif
                                    @if(auth()->user()->role === 'admin')
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($therapists as $therapist)
                                <tr>
                                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                        {{ $therapist->name }}
                                    </td>
                                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                        {{ $therapist->phone_number }}
                                    </td>
                                    @if(auth()->user()->role !== 'admin')
                                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                        <span
                                            class="inline-flex items-center justify-center {{ $therapist->status === 'active' ? 'text-green-500' : 'text-red-700' }} font-bold">
                                            {{ $therapist->status }}
                                        </span>
                                    </td>
                                    @endif
                                    @if(auth()->user()->role !== 'admin')
                                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                        <span
                                            class="inline-flex items-center justify-center {{ $therapist->availability_status === 'available' ? 'text-green-500' : 'text-red-500' }} font-bold">
                                            {{ $therapist->availability_status }}
                                        </span>
                                    </td>
                                    @endif
                                    @if(auth()->user()->role === 'admin')
                                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                        <a href="{{ route('active-therapists.edit', $therapist) }}"
                                            class="btn btn-warning bg-yellow-500 dark:bg-yellow-700 text-white dark:text-gray-200">Edit</a>
                                        <form action="{{ route('active-therapists.destroy', $therapist) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-danger bg-red-500 dark:bg-red-700 text-white dark:text-gray-200">Delete</button>
                                        </form>
                                    </td>
                                    @elseif(auth()->user()->role === 'user')
                                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                                        <a href="{{ route('active-therapists.edit', $therapist) }}"
                                            class="btn btn-warning bg-yellow-500 dark:bg-yellow-700 text-white dark:text-gray-200">Edit</a>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role === 'admin' ? '4' : '3' }}"
                                        class="py-2 px-4 text-center border-b border-gray-200 dark:border-gray-600">
                                        No therapists found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>