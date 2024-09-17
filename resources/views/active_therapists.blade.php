<div class="overflow-x-auto">
    <!-- Therapist Details -->
    @if(!$therapist)
    <p class="text-gray-700 dark:text-gray-300">Therapist not found.</p>
    @else
    <table
        class="table-auto w-full mb-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700">
        <thead class="bg-gray-200 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Name</th>
                <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Phone Number</th>
                @unless(auth()->user()->role === 'admin')
                <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Status</th>
                @endunless
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $therapist->name }}</td>
                <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $therapist->phone_number }}</td>
                @unless(auth()->user()->role === 'admin')
                <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $therapist->status }}</td>
                @endunless
            </tr>
        </tbody>
    </table>
    @endif
</div>