<!-- resources/views/export.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <!-- Optional header content -->
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 dark:bg-base-300 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content dark:text-base-content">

                    <!-- Export to PDF Button -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('export.pdf') }}">
                            <button type="submit" class="btn btn-secondary">Export to PDF</button>
                        </form>
                    </div>

                    <!-- Timer Cards Table for Export -->
                    <div class="overflow-x-auto">
                        <table class="table w-full text-base-content dark:text-base-content">
                            <thead>
                                <tr>
                                    <th>Card Name</th>
                                    <th>Locker</th>
                                    <th>Therapist</th>
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
                                    <td>{{ optional($timerCard->user)->name ?? 'No Staff Assigned' }}</td>
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