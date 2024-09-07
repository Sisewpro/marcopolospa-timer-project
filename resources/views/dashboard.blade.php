<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-base-content dark:text-gray-200 leading-tight">
                {{ __('Wealthness Spa') }}
            </h2>
            <label class="grid cursor-pointer place-items-center">
                <input
                    type="checkbox"
                    value="dark"
                    class="toggle theme-controller bg-base-content col-span-2 col-start-1 row-start-1" />
                <svg
                    class="stroke-base-100 fill-base-100 col-start-1 row-start-1"
                    xmlns="http://www.w3.org/2000/svg"
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="12" cy="12" r="5" />
                    <path
                        d="M12 1v2M12 21v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M1 12h2M21 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4" />
                </svg>
                <svg
                    class="stroke-base-100 fill-base-100 col-start-2 row-start-1"
                    xmlns="http://www.w3.org/2000/svg"
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </label>
        </div>
    </x-slot>

    <div class="mt-6">
        <div class="mx-auto sm:px-8 lg:px-10">
            <form action="{{ route('timer-cards.store') }}" method="POST" class="mb-4">
                @csrf
                <x-secondary-button type="submit">Add Locker</x-secondary-button>
            </form>

            <div class="grid xl:grid-cols-8 xl:gap-4 lg:grid-cols-8 lg:gap-4 md:grid-cols-4 md:gap-8 sm:grid-cols-2 sm:gap-10 xs:grid-cols-1 xs:gap-11">
                @foreach($timerCards as $card)
                    <x-timer-card 
                        :id="$card->id" 
                        :cardName="$card->card_name" 
                        :userName="$card->user ? $card->user->name : 'None'" 
                        :time="$card->getFormattedTimeAttribute()" 
                        :status="$card->status" 
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
                @method('PUT')

                <!-- Input Nama Locker -->
                <div class="mt-2">
                    <x-input-label for="card_name" value="Nama Locker" />
                    <x-text-input id="card_name" name="card_name" placeholder="Nama Locker" class="mb-2 w-full" />
                </div>

                <!-- Pilih Staff -->
                <div class="mt-2">
                    <x-input-label for="userSelect" value="Pilih Staff" />
                    <select name="user_id" id="userSelect" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mb-2 w-full">
                        <option value="" selected>Pilih Staff</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Atur Ulang dan Penambahan Waktu -->
                <div class="mt-2">
                    <x-input-label for="time" value="Waktu" />
                    <x-text-input id="time" name="time" placeholder="01:30:00" value="01:30:00" class="mb-2 w-full" disabled />
                    
                    <x-primary-button id="resetTime" class="my-2">Atur Ulang</x-primary-button>

                    <!-- Tombol Penambahan Sesi -->
                    <div class="dropdown mb-2">
                        <button type="button" class="btn btn-ghost w-full">Tambah Sesi</button>
                        <ul class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                            <li><a href="#" id="addSession1">+1 Sesi (45 menit)</a></li>
                            <li><a href="#" id="addSession2">+2 Sesi (90 menit)</a></li>
                        </ul>
                    </div>
                </div>

                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <x-primary-button class="ms-3">Save</x-primary-button>
                    <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>

<script>
    // Fungsi untuk membuka modal edit
    function openEditModal(id, cardName, userName, time) {
        document.getElementById('editForm').action = `/timer-cards/${id}`;
        document.getElementById('deleteForm').action = `/timer-cards/${id}`;
        document.getElementById('card_name').value = cardName;
        document.getElementById('time').value = time || '01:30:00';  // Default ke 90 menit

        // Pilih Staff
        const userSelect = document.getElementById('userSelect');
        userSelect.selectedIndex = 0;  // Reset ke opsi default
        if (userName) {
            const option = Array.from(userSelect.options).find(option => option.text === userName);
            if (option) {
                option.selected = true;
            }
        }

        // Fungsi Reset Time
        document.getElementById('resetTime').addEventListener('click', function() {
            document.getElementById('time').value = '01:30:00';  // Reset ke 90 menit
        });

        // Fungsi Penambahan Waktu (+45 menit atau +90 menit)
        document.getElementById('addSession1').addEventListener('click', function() {
            addSessionTime(45);  // Tambah 45 menit
        });
        document.getElementById('addSession2').addEventListener('click', function() {
            addSessionTime(90);  // Tambah 90 menit
        });

        // Fungsi Penambahan Waktu (Mengupdate input time)
        function addSessionTime(minutesToAdd) {
            const timeInput = document.getElementById('time').value;
            const [hours, minutes, seconds] = timeInput.split(':').map(Number);

            let totalMinutes = hours * 60 + minutes + minutesToAdd;
            const newHours = Math.floor(totalMinutes / 60);
            const newMinutes = totalMinutes % 60;

            document.getElementById('time').value = `${String(newHours).padStart(2, '0')}:${String(newMinutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }

        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-modal' }));
    }
</script>



