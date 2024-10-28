@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-900 shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Update Status</h2>

    <form method="POST" action="{{ route('active-therapists.update', $therapist) }}">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Status
            </label>
            <select id="status" name="status" required
                class="select select-bordered w-full dark:bg-gray-900 dark:border-gray-600 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                <option value="active" {{ old('status', $therapist->status) == 'active' ? 'selected' : '' }}>Active
                </option>
                <option value="inactive" {{ old('status', $therapist->status) == 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
            @error('status')
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