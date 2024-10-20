<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Therapist') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('active-therapists.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Admins: Can create name and phone number -->
                            @if(auth()->user()->role === 'admin')
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Name
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="input input-bordered w-full dark:bg-gray-900 dark:border-gray-600 dark:text-gray-100 focus:ring-2 focus:ring-primary-500">
                                @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="phone_number"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Phone Number
                                </label>
                                <input type="text" id="phone_number" name="phone_number"
                                    value="{{ old('phone_number') }}" required placeholder="+6281234567890"
                                    class="input input-bordered w-full dark:bg-gray-900 dark:border-gray-600 dark:text-gray-100 focus:ring-2 focus:ring-primary-500">
                                @error('phone_number')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <input type="hidden" name="status" value="inactive">
                            @endif

                            <!-- Users: Can set status -->
                            @if(auth()->user()->role === 'user')
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Status
                                </label>
                                <select id="status" name="status" required
                                    class="select select-bordered w-full dark:bg-gray-900 dark:border-gray-600 dark:text-gray-100 focus:ring-2 focus:ring-primary-500">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                                @error('status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif

                            <div class="flex gap-4">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('active-therapists.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>