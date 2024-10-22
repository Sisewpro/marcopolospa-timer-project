<form action="{{ route('timer-cards.start', $id) }}" method="POST" onsubmit="event.preventDefault();" class="bg-base-100 shadow-md rounded-lg pt-4 pb-2 px-3 max-w-sm mx-auto">
    @csrf
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold text-base-500">{{ $cardName }}</h2>
        <div class="flex space-x-2">
            <span id="editModal_{{ $id }}" class="cursor-pointer text-base hover:text-primary" onclick="openEditModal('{{ $id }}', '{{ $cardName }}', '{{ $therapistName }}', '{{ $time }}')">
                <!-- Ikon Edit -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path d="M5.433 13.917l1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                    <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                </svg>
            </span>
        </div>
    </div>

    <div class="mt-2 text-sm text-base-400">
        <p>{{ $therapistName }}</p>
    </div>

    <div class="text-center mt-1">
        <label id="statusDisplay_{{ $id }}" class="block text-xl font-bold text-primary px-2">{{ $status }}</label>
        <div class="text-3xl font-mono countdown mt-1 pt-1 px-2">
            <span id="hours_{{ $id }}"></span> :
            <span id="minutes_{{ $id }}"></span> :
            <span id="seconds_{{ $id }}"></span>
        </div>
        <div class="-mt-2">
            <progress id="progressBar_{{ $id }}" class="progress progress-warning w-3/5 h-1" value="100" max="100"></progress>
        </div>
    </div>

    <div class="text-lg text-center">
        <p>{{ $customer }}</p>
    </div>

    <div class="flex justify-center space-x-2 items-center mt-1">
        <button id="startStopButton_{{ $id }}" type="button" class="btn btn-primary btn-sm px-4 py-2 rounded">Mulai</button>

        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-sm btn-ghost m-1">Option</div>
            <ul tabindex="0" class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                <li><a href="#" class="session-link" data-card-id="{{ $id }}" data-session="45">+1 Session</a></li>
                <li><a href="#" class="session-link" data-card-id="{{ $id }}" data-session="90">+2 Sessions</a></li>
            </ul>
        </div>
    </div>
</form>

<script>
   document.addEventListener('DOMContentLoaded', function() {
        initializeTimerCard("{{ $id }}", "{{ $time }}", "{{ $status }}", "{{ $startTime }}");
    });

    function initializeTimerCard(cardId, initialTime, status, startTime) {
        let timerInterval;
        let totalSeconds = parseTimeInput(initialTime);
        let isRunning = false;
        
        const hoursElement = document.getElementById('hours_' + cardId);
        const minutesElement = document.getElementById('minutes_' + cardId);
        const secondsElement = document.getElementById('seconds_' + cardId);
        const statusDisplay = document.getElementById('statusDisplay_' + cardId);
        const startStopButton = document.getElementById('startStopButton_' + cardId);
        const customerInput = document.getElementById('customer_' + cardId);
        const editModal = document.getElementById('editModal_' + cardId);
        const progressBar = document.getElementById('progressBar_' + cardId);

        // Event Listener untuk dropdown session yang hanya mempengaruhi card yang sesuai
        document.querySelectorAll(`.session-link[data-card-id="${cardId}"]`).forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const additionalMinutes = parseInt(this.dataset.session, 10);
                addSession(additionalMinutes);
            });
        });

        function formatTimeFromSeconds(totalSeconds) {
            const hrs = Math.floor(totalSeconds / 3600);
            const mins = Math.floor((totalSeconds % 3600) / 60);
            const secs = totalSeconds % 60;
            return `${hrs.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        function parseTimeInput(time) {
            const [hrs, mins, secs] = time.split(':').map(Number);
            return (hrs * 3600) + (mins * 60) + secs;
        }

        function updateTimerDisplay() {
            const hrs = Math.floor(totalSeconds / 3600);
            const mins = Math.floor((totalSeconds % 3600) / 60);
            const secs = totalSeconds % 60;

            hoursElement.style.setProperty('--value', hrs);
            minutesElement.style.setProperty('--value', mins);
            secondsElement.style.setProperty('--value', secs);

            if (totalSeconds > 900, statusDisplay.textContent === "Running") {
                // progressBar.setAttribute('value', totalSeconds);
                progressBar.removeAttribute('value');
                progressBar.classList.add('progress-success');
                progressBar.classList.remove('progress-warning');
            } else {
                progressBar.classList.remove('progress-success');
                progressBar.classList.add('progress-warning');
            }
            // Ubah warna timer menjadi oranye jika waktu kurang dari atau sama dengan 15 menit (900 detik)
            if (totalSeconds <= 900 && totalSeconds > 60) {
                hoursElement.classList.add('text-orange-400');
                minutesElement.classList.add('text-orange-400');
                secondsElement.classList.add('text-orange-400');
                progressBar.removeAttribute('value');
                progressBar.classList.add('progress-warning');
                progressBar.classList.remove('progress-success');
                statusDisplay.classList.add('text-warning');
                statusDisplay.classList.remove('text-success');
            } else {
                hoursElement.classList.remove('text-orange-400');
                minutesElement.classList.remove('text-orange-400');
                secondsElement.classList.remove('text-orange-400');
            }

            // Ubah warna timer menjadi merah jika waktu habis (60 detik)
            if (totalSeconds <= 60 && totalSeconds > 10) {
                hoursElement.classList.add('text-error');
                minutesElement.classList.add('text-error');
                secondsElement.classList.add('text-error');
                progressBar.removeAttribute('value');
                progressBar.classList.add('progress-error');
                progressBar.classList.remove('progress-warning');
                statusDisplay.classList.add('text-error');
                statusDisplay.classList.remove('text-success', 'text-warning');
            } else {
                hoursElement.classList.remove('text-error');
                minutesElement.classList.remove('text-error');
                secondsElement.classList.remove('text-error');
            }

            if (totalSeconds <= 10 && totalSeconds > 0) {
                hoursElement.classList.add('text-secondary');
                minutesElement.classList.add('text-secondary');
                secondsElement.classList.add('text-secondary');
                progressBar.removeAttribute('value');
                progressBar.classList.add('progress-secondary');
                progressBar.classList.remove('progress-error', 'progress-warning', 'progress-success');
                statusDisplay.classList.add('text-secondary');
                statusDisplay.classList.remove('text-success', 'text-warning', 'text-error');
            } else {
                hoursElement.classList.remove('text-secondary');
                minutesElement.classList.remove('text-secondary');
                secondsElement.classList.remove('text-secondary');
            }
        }

        startStopButton.addEventListener('click', function (event) {
            event.preventDefault();
            if (isRunning) {
                stopTimer();
            } else {
                if (statusDisplay.textContent === 'Running') {
                    return;
                 }
                startTimer();
            }
        });

        function startTimer() {
            isRunning = true;
            startStopButton.textContent = 'Stop';
            updateStatus('Running');
            
            // timerInterval = setInterval(() => {
            //     if (totalSeconds > 0) {
            //         totalSeconds--;
            //         updateTimerDisplay();
            //     } else {
            //         stopTimer();
            //     }
            // }, 1000);

            fetch(`/timer-cards/${cardId}/start`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    customer: "{{ $customer }}", // Langsung ambil value customer dari backend
                    time: totalSeconds,
                    status: 'Running'
                })
            });
        }


        function stopTimer() {
            clearInterval(timerInterval);
            isRunning = false;
            totalSeconds = parseTimeInput("01:30:00");
            updateTimerDisplay();
            updateStatus('Ready');
            startStopButton.textContent = 'Mulai';

            fetch(`/timer-cards/${cardId}/stop`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: 'Ready'
                })
            }).then(() => {
                // Setelah server merespons, reload halaman
                window.location.reload();
            });
        }

        function addSession(additionalMinutes) {
            totalSeconds += additionalMinutes * 60; // Konversi menit ke detik
            updateTimerDisplay();

            // Jika ingin mengirim perubahan ke server
            fetch(`/timer-cards/${cardId}/add-session`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    additionalMinutes: additionalMinutes,
                    time: totalSeconds
                })
            }).then(() => {
                // Setelah server merespons, reload halaman
                window.location.reload();
            });
        }

        function updateStatus(status) {
            statusDisplay.textContent = status;

            if (status === 'Running') {
                statusDisplay.classList.remove('text-primary');
                statusDisplay.classList.add('text-success');
                startStopButton.classList.add('btn-error', 'btn-outline');
                startStopButton.classList.remove('btn-primary');
                startStopButton.textContent = 'Stop';
                startStopButton.onclick = function () {
                    stopTimer();
                }
                // editSession.classList.add('opacity-70', 'pointer-events-none');
                editModal.classList.add('opacity-70');
                editModal.classList.remove('hover:text-primary');
                editModal.onclick = function () {
                    disable = true;
                }
                timerInterval = setInterval(() => {
                    if (totalSeconds > 0) {
                        totalSeconds--;
                        updateTimerDisplay();
                    }
                }, 1000);
            } else if (status === 'Ready') {
                statusDisplay.classList.remove('text-gray-500');
                statusDisplay.classList.add('text-primary');
                startStopButton.textContent = 'Mulai';
                clearInterval(timerInterval);
            }
        }

        function reloadPage() {
            setInterval(() => {
                window.location.reload();
            }, 67777);
        }
        
        updateStatus(status);
        updateTimerDisplay();
        reloadPage();
    }
</script>
