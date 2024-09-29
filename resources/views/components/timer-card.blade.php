<form action="{{ route('timer-cards.start', $id) }}" method="POST"
    class="bg-base-100 shadow-md rounded-lg p-4 mt-4 max-w-sm mx-auto">
    @csrf
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold text-base-500">{{ $cardName }}</h2>
        <div class="flex space-x-2">
            <span id="editButton_{{ $id }}" class="cursor-pointer text-base hover:text-warning"
                onclick="openEditModal('{{ $id }}', '{{ $cardName }}', '{{ $therapistName }}', '{{ $time }}')">
                <!-- Edit Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path
                        d="M5.433 13.917l1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                    <path
                        d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                </svg>
            </span>
        </div>
    </div>

    <div class="mt-2 text-sm text-base-400">
        <p>{{ $therapistName }}</p>
    </div>

    <div class="text-center mt-2">
        <label id="statusDisplay_{{ $id }}" class="block text-lg font-bold text-accent">{{ $status }}</label>
        <div class="text-2xl font-mono countdown mt-4">
            <span id="hours_{{ $id }}" style="--value:1;"></span> :
            <span id="minutes_{{ $id }}" style="--value:30;"></span> :
            <span id="seconds_{{ $id }}" style="--value:0;"></span>
        </div>
    </div>

    <div>
        <input type="text" id="customer_{{ $id }}" name="customer" class="w-full text-center p-2 input rounded"
            placeholder="Customer" required>
    </div>

    <div class="flex justify-center space-x-2 items-center">
        <button id="startStopButton_{{ $id }}" type="button"
            class="btn btn-primary btn-sm px-4 py-2 rounded">Mulai</button>

        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-sm btn-ghost m-1">Option</div>
            <ul data-card-id="{{ $id }}" tabindex="0"
                class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                <li><a href="#" data-session="45">+1 Session</a></li>
                <li><a href="#" data-session="90">+2 Sessions</a></li>
            </ul>
        </div>

    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeTimerCard("{{ $id }}", "01:30:00", false);
});

function initializeTimerCard(cardId, initialTime, isRunning) {
    const initialTimeValue = parseTimeInput(initialTime);
    let timerInterval;
    const hoursElement = document.getElementById('hours_' + cardId);
    const minutesElement = document.getElementById('minutes_' + cardId);
    const secondsElement = document.getElementById('seconds_' + cardId);
    const statusDisplay = document.getElementById('statusDisplay_' + cardId);
    const startStopButton = document.getElementById('startStopButton_' + cardId);
    const customerInput = document.getElementById('customer_' + cardId);
    const editButton = document.getElementById('editButton_' + cardId); // Menambahkan referensi ke tombol Edit

    // Definisikan storage key unik untuk setiap timer
    const storageKey = `timer_${cardId}`;
    const savedData = JSON.parse(localStorage.getItem(storageKey)) || {};

    // Inisialisasi variabel dari data yang disimpan atau set ke default
    let endTime = savedData.endTime ? new Date(savedData.endTime) : null;
    let isTimerRunning = savedData.isRunning || false;
    let customer = savedData.customer || "";

    // Update input pelanggan jika sebelumnya disimpan
    customerInput.value = customer;

    // Jika timer sedang berjalan, hitung sisa waktu
    if (isTimerRunning && endTime) {
        const now = new Date();
        const timeLeft = Math.floor((endTime - now) / 1000); // Sisa waktu dalam detik

        if (timeLeft > 0) {
            // Set tombol menjadi "Stop" dan kunci input pelanggan
            startStopButton.textContent = 'Stop';
            customerInput.disabled = true;

            // Disable tombol Edit
            editButton.classList.add('disabled');

            // Mulai interval timer
            startTimerInterval(cardId, timeLeft);
        } else {
            // Timer sudah habis
            isTimerRunning = false;
            updateStatus('Ready');
            startStopButton.textContent = 'Mulai';
            customerInput.disabled = false;

            // Enable tombol Edit
            editButton.classList.remove('disabled');

            updateLocalStorage(cardId, {
                endTime: null,
                isRunning: false,
                customer: customer
            });
            updateTimerDisplay(cardId, 0);
        }
    } else {
        // Inisialisasi tampilan timer dengan initialTime
        const totalSeconds = parseTimeInput(initialTime);
        updateTimerDisplay(cardId, totalSeconds);

        // Pastikan tombol Edit dalam keadaan aktif
        editButton.classList.remove('disabled');
    }

    // Listener untuk tombol Start/Stop
    startStopButton.addEventListener('click', function() {
        if (isTimerRunning) {
            stopTimer(cardId);
        } else {
            startTimer(cardId);
        }
    });

    // Listener untuk penambahan sesi
    const dropdown = document.querySelector(`.dropdown-content[data-card-id="${cardId}"]`);
    if (dropdown) {
        const sessionOptions = dropdown.querySelectorAll('a[data-session]');
        sessionOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const minutesToAdd = parseInt(this.getAttribute('data-session'), 10);
                addSessionTime(cardId, minutesToAdd);
            });
        });
    }

    // Fungsi untuk mengubah format waktu "HH:MM:SS" menjadi total detik
    function parseTimeInput(time) {
        const [hrs, mins, secs] = time.split(':').map(Number);
        return (hrs * 3600) + (mins * 60) + secs;
    }

    // Fungsi untuk memperbarui tampilan timer
    function updateTimerDisplay(cardId, totalSeconds) {
        const hrs = Math.floor(totalSeconds / 3600);
        const mins = Math.floor((totalSeconds % 3600) / 60);
        const secs = totalSeconds % 60;

        hoursElement.style.setProperty('--value', hrs);
        minutesElement.style.setProperty('--value', mins);
        secondsElement.style.setProperty('--value', secs);
    }

    // Fungsi untuk memperbarui label status
    function updateStatus(status) {
        statusDisplay.textContent = status;
    }

    // Fungsi untuk memulai timer
    function startTimer(cardId) {
        if (customerInput.value.trim() === "") {
            customerInput.classList.add('input-error');
            return;
        } else {
            customerInput.classList.remove('input-error');
        }

        isTimerRunning = true;
        updateStatus('Running');
        startStopButton.textContent = 'Stop';
        customerInput.disabled = true; // Kunci input pelanggan saat timer berjalan

        const now = new Date();
        endTime = new Date(now.getTime() + parseTimeInput("01:30:00") * 1000);
        updateLocalStorage(cardId, {
            endTime: endTime.toISOString(),
            isRunning: isTimerRunning,
            customer: customerInput.value
        });

        const timeLeft = Math.floor((endTime - now) / 1000);
        startTimerInterval(cardId, timeLeft);

        // Disable tombol Edit saat timer berjalan
        editButton.classList.add('disabled');

        // Kirim data ke server
        fetch(`/timer-cards/${cardId}/start`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                customer: customerInput.value,
                time: timeLeft,
                status: 'Running'
            })
        }).catch(error => {
            console.error('Error starting timer:', error);
        });
    }

    // Fungsi untuk menghentikan timer
    function stopTimer(cardId) {
        clearInterval(timerInterval);
        isTimerRunning = false;
        updateStatus('Ready');
        startStopButton.textContent = 'Mulai';
        customerInput.disabled = false; // Buka kunci input pelanggan saat timer berhenti

        // Enable tombol Edit saat timer berhenti
        editButton.classList.remove('disabled');

        // Reset tampilan timer ke waktu awal
        updateTimerDisplay(cardId, initialTimeValue);

        // Kosongkan input customer
        customerInput.value = "";

        updateLocalStorage(cardId, {
            endTime: null,
            isRunning: isTimerRunning,
            customer: "" // Reset nama customer
        });

        // Kirim data stop ke server
        fetch(`/timer-cards/${cardId}/stop`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: 'Ready'
            })
        }).catch(error => {
            console.error('Error stopping timer:', error);
        });
    }

    // Fungsi untuk menambahkan sesi waktu ke timer
    function addSessionTime(cardId, minutesToAdd) {
        if (!endTime) {
            // Jika timer belum dimulai, inisialisasi endTime berdasarkan initialTime
            const totalSeconds = parseTimeInput("01:30:00");
            endTime = new Date(new Date().getTime() + totalSeconds * 1000);
        }

        // Tambahkan menit yang ditentukan ke endTime
        endTime = new Date(endTime.getTime() + minutesToAdd * 60000);
        updateLocalStorage(cardId, {
            endTime: endTime.toISOString(),
            isRunning: isTimerRunning,
            customer: customerInput.value
        });

        // Update tampilan timer segera
        const now = new Date();
        const timeLeft = Math.floor((endTime - now) / 1000);
        updateTimerDisplay(cardId, timeLeft);

        // Jika timer sedang berjalan, reset interval untuk memperhitungkan endTime yang baru
        if (isTimerRunning) {
            clearInterval(timerInterval);
            startTimerInterval(cardId, timeLeft);
        }
    }

    // Fungsi untuk memulai interval timer
    function startTimerInterval(cardId, timeLeft) {
        updateTimerDisplay(cardId, timeLeft);

        timerInterval = setInterval(() => {
            const now = new Date();
            const remainingSeconds = Math.floor((endTime - now) / 1000);

            if (remainingSeconds > 0) {
                updateTimerDisplay(cardId, remainingSeconds);
                updateLocalStorage(cardId, {
                    endTime: endTime.toISOString(),
                    isRunning: isTimerRunning,
                    customer: customerInput.value
                });
            } else {
                clearInterval(timerInterval);
                isTimerRunning = false;
                updateStatus('Ready');
                startStopButton.textContent = 'Mulai';
                customerInput.disabled = false;
                editButton.classList.remove('disabled'); // Enable tombol Edit saat timer selesai
                updateLocalStorage(cardId, {
                    endTime: null,
                    isRunning: isTimerRunning,
                    customer: customerInput.value
                });
                updateTimerDisplay(cardId, 0);
                alert('Timer selesai!');
            }
        }, 1000);
    }

    // Fungsi untuk memperbarui state timer di localStorage
    function updateLocalStorage(cardId, data) {
        localStorage.setItem(`timer_${cardId}`, JSON.stringify(data));
    }
}
</script>