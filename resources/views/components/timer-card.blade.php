<div id="card_{{ $id }}" class="bg-base-100 shadow-md rounded-lg p-4 mt-4 max-w-sm mx-auto">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold text-base-500">{{ $cardName }}</h2>
        <div class="flex space-x-2">
            <!-- ikon edit -->
            <span class="cursor-pointer text-base hover:text-warning" onclick="openEditModal('{{ $id }}', '{{ $cardName }}', '{{ $userName }}', '{{ $time }}')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                    <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                </svg>
            </span>
        </div>
    </div>

    <div class="mt-2 text-sm text-base-400">
        <p>{{ $userName }}</p>
    </div>

    <div class="text-center mt-2">
        <label id="statusDisplay_{{ $id }}" class="block text-lg font-bold text-accent">{{ $status }}</label>
        <div class="text-2xl font-mono countdown mt-4">
            <span id="hours_{{ $id }}" style="--value:1;"></span>
            :
            <span id="minutes_{{ $id }}" style="--value:30;"></span>
            :
            <span id="seconds_{{ $id }}" style="--value:0;"></span>
        </div>
    </div>

    <div class="mt-4">
        <input type="text" id="customer_{{ $id }}" name="customer" class="w-full text-center p-2 input rounded" placeholder="Customer" required>
    </div>


    <div class="flex justify-center space-x-2 items-center mt-4">
        <button id="startStopButton_{{ $id }}" class="btn btn-primary btn-sm px-4 py-2 rounded">Mulai</button>

        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost m-1">Option</div>
            <ul tabindex="0" class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                <li><a data-session="45">+1 Session</a></li>
                <li><a data-session="90">+2 Sessions</a></li>
            </ul>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeTimerCard("{{ $id }}", "01:30:00");
    });

    function initializeTimerCard(cardId, initialTime) {
        let timerInterval;
        let totalSeconds = parseTimeInput(initialTime);

        function parseTimeInput(time) {
            const [hrs, mins, secs] = time.split(':').map(Number);
            return (hrs * 3600) + (mins * 60) + secs;
        }

        function updateTimerDisplay() {
            const hrs = Math.floor(totalSeconds / 3600);
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
