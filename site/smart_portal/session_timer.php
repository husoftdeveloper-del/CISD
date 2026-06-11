<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
// Make sure session is already started in auth_check.php
if (!isset($timeout)) {
    $timeout = file_exists("session_timeout.txt") ? intval(file_get_contents("session_timeout.txt")) : 15;
}
?>

<script>
    const timeoutMinutes = <?= $timeout ?>;
    const lastWarning = 60; // Show countdown when only 60 seconds remain
    const logoutTime = timeoutMinutes * 60 * 1000;
    const warningTime = logoutTime - (lastWarning * 1000);

    setTimeout(() => {
        const timerBox = document.createElement('div');
        timerBox.innerHTML = `
            <div id="timeout-warning" style="
                position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
                background: #ffecec; color: #e74c3c; padding: 30px 40px;
                border-radius: 15px; text-align: center; z-index: 10000;
                font-size: 22px; font-weight: bold; box-shadow: 0 10px 25px rgba(0,0,0,0.3);
                animation: fade-in 0.5s ease;
            ">
                ⏳ Session will expire in <span id="countdown">60</span> seconds!
            </div>
        `;
        document.body.appendChild(timerBox);

        let countdown = lastWarning;
        const countdownInterval = setInterval(() => {
            countdown--;
            document.getElementById("countdown").textContent = countdown;
        }, 1000);

        setTimeout(() => {
            clearInterval(countdownInterval);
            window.location.href = "logout.php";
        }, lastWarning * 1000);
    }, warningTime);
</script>