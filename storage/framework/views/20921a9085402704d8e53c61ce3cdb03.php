<div id="card_<?php echo e($id); ?>" class="bg-base-100 shadow-md rounded-lg p-4 mt-4 max-w-sm mx-auto">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold text-base-500"><?php echo e($cardName); ?></h2>
        <div class="flex space-x-2">
            <!-- ikon edit -->
            <span class="cursor-pointer text-base hover:text-warning" onclick="openEditModal('<?php echo e($id); ?>', '<?php echo e($cardName); ?>', '<?php echo e($userName); ?>', '<?php echo e($time); ?>')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                    <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                </svg>
            </span>
        </div>
    </div>

    <div class="mt-2 text-sm text-base-400">
        <p><?php echo e($userName); ?></p>
    </div>

    <div class="text-center mt-2">
        <label id="statusDisplay_<?php echo e($id); ?>" class="block text-lg font-bold text-accent"><?php echo e($status); ?></label>
        <div class="text-2xl font-mono countdown mt-4">
            <span id="hours_<?php echo e($id); ?>" style="--value:1;"></span>
            :
            <span id="minutes_<?php echo e($id); ?>" style="--value:30;"></span>
            :
            <span id="seconds_<?php echo e($id); ?>" style="--value:0;"></span>
        </div>
    </div>

    <div>
        <input type="text" id="customer_<?php echo e($id); ?>" name="customer" class="w-full text-center p-2 input rounded" placeholder="Customer" required>
    </div>

    <div class="flex justify-center space-x-2 items-center">
        <button id="startStopButton_<?php echo e($id); ?>" class="btn btn-primary btn-sm px-4 py-2 rounded">Mulai</button>

        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-sm btn-ghost m-1">Option</div>
            <ul tabindex="0" class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                <li><a data-session="45">+1 Session</a></li>
                <li><a data-session="90">+2 Sessions</a></li>
            </ul>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeTimerCard("<?php echo e($id); ?>", "01:30:00");
    });

    function initializeTimerCard(cardId, initialTime) {
        let timerInterval;
        let totalSeconds = parseTimeInput(initialTime);
        let isRunning = false;

        const hoursElement = document.getElementById('hours_' + cardId);
        const minutesElement = document.getElementById('minutes_' + cardId);
        const secondsElement = document.getElementById('seconds_' + cardId);
        const statusDisplay = document.getElementById('statusDisplay_' + cardId);
        const startStopButton = document.getElementById('startStopButton_' + cardId);
        const customerInput = document.getElementById('customer_' + cardId);

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

        document.getElementById('startStopButton_' + cardId).addEventListener('click', function () {
            if (isRunning) {
                stopTimer();
            } else {
                startTimer();
            }
        });

        function startTimer() {
            const customerInput = document.getElementById('customer_' + cardId);

            // Validasi input customer
            if (customerInput.value.trim() === "") {
                customerInput.classList.add('border-red-500');
                return;
            } else {
                customerInput.classList.remove('border-red-500');
            }

            if (!isRunning) {
                isRunning = true;
                updateStatus('Running');
                document.getElementById('startStopButton_' + cardId).textContent = 'Stop';

                // Perbarui data ke server (bukan menambah)
                fetch(`/timer-cards/${cardId}/update`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        customer: customerInput.value,
                        time: totalSeconds,
                        status: 'Running'
                    })
                });

                // Mulai interval timer
                timerInterval = setInterval(() => {
                    if (totalSeconds > 0) {
                        totalSeconds--;
                        updateTimerDisplay();
                    } else {
                        stopTimer();
                    }
                }, 1000);
            }
        }

        function stopTimer() {
            clearInterval(timerInterval);
            totalSeconds = parseTimeInput("01:30:00");
            updateTimerDisplay();
            isRunning = false;
            updateStatus('Ready');
            document.getElementById('startStopButton_' + cardId).textContent = 'Mulai';

            // Perbarui status kembali ke "Ready"
            fetch(`/timer-cards/${cardId}/update`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: 'Ready'
                })
            });
        }

        document.querySelectorAll(`#card_${cardId} .dropdown a`).forEach(item => {
            item.addEventListener('click', (event) => {
                event.preventDefault();
                const sessionMinutes = parseInt(event.target.getAttribute('data-session'), 10);
                addSession(sessionMinutes);
            });
        });

        function addSession(sessionMinutes) {
            totalSeconds += sessionMinutes * 60;
            updateTimerDisplay();
            fetch(`/timer-cards/${cardId}/add-session`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    sessionMinutes: sessionMinutes
                })
            });
        }

        function updateStatus(status) {
            document.getElementById('statusDisplay_' + cardId).textContent = status;
        }

        updateTimerDisplay();
    }
</script>


<?php /**PATH D:\laragon\www\spa-counter-rev-ver-1\resources\views\components\timer-card.blade.php ENDPATH**/ ?>