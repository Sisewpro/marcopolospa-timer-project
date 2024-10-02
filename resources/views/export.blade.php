<x-app-layout>
    <x-slot name="header">
        <!-- Optional header content -->
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-base-100 dark:bg-base-300 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-base-content dark:text-base-content">

                    <!-- Export to PDF and Date Filters -->
                    <div class="mb-6">
                        <!-- Filter Form for Display and Export -->
                        <form method="GET" action="{{ route('export.pdf') }}">
                            <div class="form-control mb-4">
                                <label for="start_date" class="label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="input input-bordered"
                                    value="{{ request('start_date') }}">
                            </div>

                            <div class="form-control mb-4">
                                <label for="end_date" class="label">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="input input-bordered"
                                    value="{{ request('end_date') }}">
                            </div>
                        </form>

                        <!-- Export to PDF Button (includes date filters) -->
                        <form method="GET" action="{{ route('export.pdf') }}">
                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                            <button type="submit" class="btn btn-secondary mt-2">Export to PDF</button>
                        </form>
                    </div>

                    <!-- Rekap Table for Display -->
                    <div class="overflow-x-auto">
                        <table class="table w-full text-base-content dark:text-base-content">
                            <thead>
                                <tr>
                                    <th>Locker</th>
                                    <th>Therapist</th>
                                    <th>Customer</th>
                                    <th>Session</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rekaps as $rekap)
                                <tr>
                                    <td>{{ $rekap->timerCard->card_name ?? 'No Locker' }}</td>
                                    <td>{{ $rekap->therapist_name }}</td>
                                    <td>{{ $rekap->customer }}</td>
                                    <td>{{ $rekap->time }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $rekap->status == 'Ready' ? 'bg-success text-success-content' : 'bg-success text-warning-content' }}">
                                            {{ $rekap->status }}
                                        </span>
                                    </td>
                                    <!-- Date format dd/mm/yyyy -->
                                    <td>{{ $rekap->created_at->format('d/m/Y') }}</td>
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