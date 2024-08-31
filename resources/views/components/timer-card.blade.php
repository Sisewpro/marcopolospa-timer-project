<div id="card_{{ $id }}" class="bg-white shadow-md rounded-lg p-4 mt-4 max-w-sm mx-auto">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">{{ $cardName }}</h2>
        <div class="flex space-x-2">
            <button class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600" onclick="openEditModal('{{ $id }}', '{{ $cardName }}', '{{ $time }}', '{{ $userName }}')">Ubah</button>
            <form action="{{ route('timer-cards.destroy', $id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Hapus</button>
            </form>
        </div>
    </div>

    <div class="mt-2 text-sm text-gray-600">
        <p>{{ $userName }}</p>
    </div>

    <div class="text-center">
        <label id="statusDisplay_{{ $id }}" class="block text-lg font-bold text-green-500">{{ $status }}</label>
        <div class="grid auto-cols-max grid-flow-col gap-5 text-center mt-4">
            <div class="bg-neutral rounded-box text-neutral-content flex flex-col p-2">
                <span class="countdown font-mono text-5xl">
                    <span id="hours_{{ $id }}" style="--value:0;"></span>
                </span>
                hours
            </div>
            <div class="bg-neutral rounded-box text-neutral-content flex flex-col p-2">
                <span class="countdown font-mono text-5xl">
                    <span id="minutes_{{ $id }}" style="--value:0;"></span>
                </span>
                min
            </div>
            <div class="bg-neutral rounded-box text-neutral-content flex flex-col p-2">
                <span class="countdown font-mono text-5xl">
                    <span id="seconds_{{ $id }}" style="--value:0;"></span>
                </span>
                sec
            </div>
        </div>
    </div>

    <div class="mt-4 flex justify-center space-x-2">
        <button id="startTimer_{{ $id }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Mulai</button>
        <button id="pauseTimer_{{ $id }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Jeda</button>
        <button id="resetTimer_{{ $id }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Reset</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeTimerCard("{{ $id }}", "{{ $time }}");
    });

    function initializeTimerCard(cardId, initialTime) {
        let timerInterval;
        let totalSeconds = parseTimeInput(initialTime);

        function parseTimeInput(time) {
            const [hrs, mins, secs] = time.split(':').map(Number);
            return (hrs * 3600) + (mins * 60) + secs;
        }

        function updateTimerDisplay() {
            const days = Math.floor(totalSeconds / 86400);
            const hrs = Math.floor((totalSeconds % 86400) / 3600);
            const mins = Math.floor((totalSeconds % 3600) / 60);
            const secs = totalSeconds % 60;

            document.getElementById('hours_' + cardId).style.setProperty('--value', hrs);
            document.getElementById('minutes_' + cardId).style.setProperty('--value', mins);
            document.getElementById('seconds_' + cardId).style.setProperty('--value', secs);
        }

        function updateStatus(status) {
            const statusDisplay = document.getElementById('statusDisplay_' + cardId);
            statusDisplay.textContent = status;
            statusDisplay.classList.toggle('text-green-500', status === "Ready");
            statusDisplay.classList.toggle('text-gray-500', status === "Running");
        }

        document.getElementById('startTimer_' + cardId).addEventListener('click', () => {
            if (!timerInterval && totalSeconds > 0) {
                timerInterval = setInterval(() => {
                    totalSeconds--;
                    updateTimerDisplay();
                    updateStatus("Running");
                    if (totalSeconds <= 0) {
                        clearInterval(timerInterval);
                        timerInterval = null;
                        updateStatus("Ready");
                        alert("Waktu Habis!");
                    }
                }, 1000);
            }
        });

        document.getElementById('pauseTimer_' + cardId).addEventListener('click', () => {
            clearInterval(timerInterval);
            timerInterval = null;
            updateStatus("Ready");
        });

        document.getElementById('resetTimer_' + cardId).addEventListener('click', () => {
            clearInterval(timerInterval);
            timerInterval = null;
            totalSeconds = parseTimeInput(initialTime);
            updateTimerDisplay();
            updateStatus("Ready");
        });

        updateTimerDisplay(); // Update display when page loads
    }
</script>
