<x-app-layout>
    <x-slot name="header">
        <!-- Optional header content -->
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 dark:bg-base-300 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content dark:text-base-content">
                    <!-- Display success messages -->
                    @if (session('success'))
                    <div class="alert alert-success shadow-lg bg-success text-success-content">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6"
                                fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2l4-4m0 0h6m-6 0v6"></path>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                    @endif

                    <!-- Date Range Filter Form -->
                    <form method="GET" action="{{ route('master') }}" class="mb-6">
                        <div class="flex gap-4 items-end">
                            <div>
                                <label for="start_date"
                                    class="block text-sm font-medium text-base-content dark:text-base-content">Start
                                    Date</label>
                                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                                    class="input input-bordered w-full sm:w-64 text-base-content dark:text-base-content">
                            </div>
                            <div>
                                <label for="end_date"
                                    class="block text-sm font-medium text-base-content dark:text-base-content">End
                                    Date</label>
                                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                                    class="input input-bordered w-full sm:w-64 text-base-content dark:text-base-content">
                            </div>
                            <button type="submit" class="btn btn-primary mt-7">Filter</button>
                        </div>
                    </form>

                    <!-- Export to PDF Button -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('export.pdf') }}">
                            <button type="submit" class="btn btn-secondary">Export to PDF</button>
                        </form>
                    </div>

                    <!-- Timer Cards Table -->
                    <div class="overflow-x-auto">
                        <table class="table w-full text-base-content dark:text-base-content">
                            <thead>
                                <tr>
                                    <th>Card Name</th>
                                    <th>Locker</th>
                                    <th>Customer</th>
                                    <th>Session</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($timerCards as $timerCard)
                                <tr>
                                    <td>{{ $timerCard->id }}</td>
                                    <td>{{ $timerCard->card_name }}</td>
                                    <td>{{ $timerCard->customer }}</td>
                                    <td>{{ $timerCard->time }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $timerCard->status == 'Ready' ? 'bg-success text-success-content' : 'bg-warning text-warning-content' }}">
                                            {{ $timerCard->status }}
                                        </span>
                                    </td>
                                    <td>{{ $timerCard->formatted_date }}</td>
                                    <td>
                                        <!-- Add action buttons if needed -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>