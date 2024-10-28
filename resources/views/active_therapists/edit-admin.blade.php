@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-900 shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Edit Therapist</h2>

    <form method="POST" action="{{ route('active-therapists.update', $therapist) }}">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Name
            </label>
            <input type="text" id="name" name="name" value="{{ old('name', $therapist->name) }}" required
                class="input input-bordered w-full dark:bg-gray-900 dark:border-gray-600 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
            @error('name')
            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-6">
            <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Phone Number
            </label>
            <input type="text" id="phone_number" name="phone_number"
                value="{{ old('phone_number', $therapist->phone_number) }}" required
                class="input input-bordered w-full dark:bg-gray-900 dark:border-gray-600 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
            @error('phone_number')
            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end gap-4">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('active-therapists.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection