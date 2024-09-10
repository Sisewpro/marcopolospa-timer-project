<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Wealthness Spa') }}
        </h2>
        <input type="checkbox" value="dark" class="toggle theme-controller" />
    </x-slot>

    <div class="mt-6">
        <div class="mx-auto sm:px-8 lg:px-10">
            <form action="{{ route('timer-cards.store') }}" method="POST" class="mb-4">
                @csrf
                <x-secondary-button type="submit">Add Locker</x-secondary-button>
            </form>

            <div
                class="grid lg:grid-cols-8 lg:gap-4 md:grid-cols-4 md:gap-8 sm:grid-cols-2 sm:gap-10 xs:grid-cols-1 xs:gap-11">
                @foreach($timerCards as $card)
                <x-timer-card :cardName="$card->card_name" :userName="$card->user ? $card->user->name : 'None'"
                    :time="$card->time" :status="$card->status" :id="$card->id" />
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
                    {{ __('DELETE LOCKET') }}
                </x-danger-button>
            </form>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="mt-2">
                    <x-input-label for="card_name" value=" Nama Locker" />
                    <x-text-input id="card_name" name="card_name" placeholder="Nama Loket" class="mb-2 w-full" />

                    <x-input-label for="userSelect" value="Therapist" />
                    <select name="user_id" id="userSelect"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mb-2 w-full">
                        <option value="" selected>Pilih Therapist</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <x-primary-button class="ms-3">
                {{ __('Save') }}</x-primary-button>
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>
        </div>
        </form>
    </x-modal>
</x-app-layout>

<script>
function openEditModal(id, cardName, time, userId) {
    document.getElementById('editForm').action = `/timer-cards/${id}`;
    document.getElementById('deleteForm').action = `/timer-cards/${id}`;
    document.getElementById('card_name').value = cardName;

    const userSelect = document.getElementById('userSelect');
    userSelect.selectedIndex = 0;
    if (userId) {
        userSelect.value = userId;
    }

    window.dispatchEvent(new CustomEvent('open-modal', {
        detail: 'edit-modal'
    }));
}

function toggleModal() {
    window.dispatchEvent(new CustomEvent('close-modal', {
        detail: 'edit-modal'
    }));
}
</script>