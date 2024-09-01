<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Wealthness Spa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('timer-cards.store') }}" method="POST" class="mb-4">
                @csrf
                <x-secondary-button type="submit">Tambah Loket</x-secondary-button>
            </form>

            <div class="grid grid-cols-4 gap-4 grid-cols-sm-1">
                @foreach($timerCards as $card)
                <x-timer-card :cardName="$card->card_name" :userName="$card->user ? $card->user->name : null"
                    :time="$card->time" :status="$card->status" :id="$card->id" />
                @endforeach
            </div>
        </div>
    </div>

    <x-modal name="edit-modal" maxWidth="lg">
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Loket</h3>
                <div class="mt-2">
                    <x-input-label for="card_name" value="Nama Loket" />
                    <x-text-input id="card_name" name="card_name" placeholder="Nama Loket" class="mb-2 w-full" />

                    <x-input-label for="userSelect" value="Pilih User" />
                    <select name="user_id" id="userSelect"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mb-2 w-full">
                        <option value="" disabled selected>Pilih User</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>

                    <!-- Kolom waktu dihapus atau dikomentari -->
                    <!-- <x-input-label for="time" value="Waktu" />
                <x-text-input id="time" name="time" placeholder="00:00:00" value="00:00:00" class="mb-2 w-full" /> -->
                    <!-- If using a hidden field to store the time -->
                    <input type="hidden" id="time" name="time" value="00:45:00">

                    <!-- Tombol "Sesi 1" untuk menambahkan 45 menit -->
                    <x-primary-button id="session1Button" class="mb-2 w-full">
                        {{ __('Sesi 1 (Tambah 45 Menit)') }}
                    </x-primary-button>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <x-primary-button class="ms-3">
                    {{ __('Save') }}</x-primary-button>
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>
            </div>
        </form>

        <script>
        document.getElementById('session1Button').addEventListener('click', function(event) {
            event.preventDefault();

            let timeInput = document.getElementById('time');
            let currentTime = timeInput.value;

            let [hours, minutes, seconds] = currentTime.split(':').map(Number);
            minutes += 45;

            if (minutes >= 60) {
                hours += Math.floor(minutes / 60);
                minutes = minutes % 60;
            }

            timeInput.value =
                `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            alert('90 minutes added to the time field.');
        });
        </script>
    </x-modal>

</x-app-layout>

<script>
function openEditModal(id, cardName, userId, currentTime) {
    document.getElementById('editForm').action = `/timer-cards/${id}`;
    document.getElementById('card_name').value = cardName;

    const userSelect = document.getElementById('userSelect');
    userSelect.selectedIndex = 0;
    if (userId) {
        userSelect.value = userId;
    }

    document.getElementById('time').value = currentTime || '01:30:00';

    window.dispatchEvent(new CustomEvent('open-modal', {
        detail: 'edit-modal'
    }));
}

document.getElementById('session1Button').addEventListener('click', function(event) {
    event.preventDefault();

    const timeInput = document.getElementById('time');
    let [hours, minutes, seconds] = timeInput.value.split(':').map(Number);

    // Add 90 minutes to the current time
    minutes += 90;

    // Handle minutes overflow
    if (minutes >= 60) {
        hours += Math.floor(minutes / 60);
        minutes = minutes % 60;
    }

    // Format the new time value
    const newTimeValue =
        `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    timeInput.value = newTimeValue;

    console.log("Updated Time:", newTimeValue);

    // Optionally, submit the form or proceed with other actions
    // document.getElementById('editForm').submit();
});


function toggleModal() {
    window.dispatchEvent(new CustomEvent('close-modal', {
        detail: 'edit-modal'
    }));
}
</script>