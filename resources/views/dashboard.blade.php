<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-base-content dark:text-gray-200 leading-tight">
                {{ __('Wealthness Spa') }}
            </h2>
        </div>
    </x-slot>

    <div class="mt-6">
        <div class="mx-auto sm:px-8 lg:px-10">
            <form action="{{ route('timer-cards.store') }}" method="POST" class="mb-4">
                @csrf
                <!-- <x-secondary-button type="submit">+ Add Locker</x-secondary-button> -->
                <button class="link no-underline hover:text-primary font-semibold text-xs  uppercase tracking-widest shadow-sm disabled:opacity-25 transition ease-in-out duration-150" type="submit">+ Add Locker</button>
            </form>

            <div
                class="grid xl:grid-cols-8 xl:gap-4 lg:grid-cols-8 lg:gap-4 md:grid-cols-4 md:gap-8 sm:grid-cols-2 sm:gap-10 xs:grid-cols-1 xs:gap-11">
                @foreach($timerCards as $card)
                <x-timer-card 
                    :id="$card->id" 
                    :cardName="$card->card_name"
                    :therapistName="$card->therapist ? $card->therapist->name : 'None'"
                    :time="$card->time"
                    :status="$card->status"
                    :customer="$card->customer ? $card->customer : 'Customer'"
                    :startTime="$card->start_time"
                    :endTime="$card->end_time"
                />
                @endforeach
            </div>
        </div>
    </div>

    <x-modal name="edit-modal" maxWidth="lg">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Locker</h3>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <x-danger-button class="w-full my-3">
                    {{ __('DELETE LOCKER') }}
                </x-danger-button>
            </form>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PATCH')

                <!-- Input Nama Locker -->
                <div class="mt-2">
                    <x-input-label for="card_name" value="Nama Locker" />
                    <x-text-input id="card_name" name="card_name" placeholder="Nama Locker" class="input input-primary mb-2 w-full" />
                </div>

                <!-- Pilih Therapist -->
                <div class="mt-2">
                    <x-input-label for="therapistSelect" value="Pilih Therapist" />
                    <select name="therapist_id" id="therapistSelect" class="select select-primary border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mb-2 w-full">
                        <option value="" selected>None</option>
                        @foreach($therapists as $therapist)
                        <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-2">
                    <x-input-label for="customer" value="Input Customer" />
                    <x-text-input id="customer" name="customer" placeholder="Customer" class="input input-primary mb-2 w-full" />
                </div>

                <!-- Atur Ulang dan Penambahan Waktu -->
                <div class="mt-2">
                    <x-input-label for="time" value="Waktu" />
                    <x-text-input id="time" name="time" placeholder="01:30:00" value="01:30:00" class="mb-2 w-full" readonly />
                    <x-secondary-button id="resetTime" class="capitalize">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2z"/>
                            <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466"/>
                        </svg>
                        Reset Timer
                    </x-secondary-button>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                    <x-primary-button class="ms-3">Save</x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>
    <div class="flex w-full flex-col">
        <div class="bg-base-200 grid h-10 place-items-center mt-2"></div>
    </div>
</x-app-layout>

<script>
    // Fungsi untuk membuka modal edit
    function openEditModal(id, cardName, therapistName, time) {
        document.getElementById('editForm').action = `/timer-cards/${id}`;
        document.getElementById('deleteForm').action = `/timer-cards/${id}`;
        document.getElementById('card_name').value = cardName;
        document.getElementById('time').value = time || '01:30:00'; // Default ke 90 menit

        // Reset opsi staff
        const therapistSelect = document.getElementById('therapistSelect');
        therapistSelect.selectedIndex = 0;
        if (therapistName) {
            const option = Array.from(therapistSelect.options).find(option => option.text === therapistName);
            if (option) {
                option.selected = true;
            }
        }

        // Reset time
        document.getElementById('resetTime').addEventListener('click', function() {
            document.getElementById('time').value = '01:30:00'; // Reset ke 90 menit
        });

        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: 'edit-modal'
        }));
    }

    // Fungsi untuk memperbarui tampilan waktu pada komponen timer-card
    function updateTimerCardDisplay(cardId, newTime) {
        const [hours, minutes, seconds] = newTime.split(':').map(Number);

        document.getElementById('hours_' + cardId).style.setProperty('--value', hours);
        document.getElementById('minutes_' + cardId).style.setProperty('--value', minutes);
        document.getElementById('seconds_' + cardId).style.setProperty('--value', seconds);
    }
</script>