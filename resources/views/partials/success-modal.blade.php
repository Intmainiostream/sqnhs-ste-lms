@if (session('success'))
    <div id="successModal" class="success-modal-backdrop">
        <div class="success-modal-box">
            <div class="success-modal-icon">
                <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <p class="success-modal-text">{{ session('success') }}</p>
        </div>
    </div>

    <style>
        .success-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 9997;
            background: rgba(0, 0, 0, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: modal-fade-in 0.2s ease;
        }

        .success-modal-backdrop.hide {
            animation: modal-fade-out 0.3s ease forwards;
        }

        .success-modal-box {
            background: white;
            border-radius: 16px;
            padding: 32px 40px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            animation: modal-pop-in 0.25s ease;
        }

        .success-modal-icon {
            width: 56px;
            height: 56px;
            background: #16a34a;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .success-modal-text {
            font-size: 15px;
            font-weight: 600;
            color: #14532d;
        }

        @keyframes modal-fade-in {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes modal-fade-out {
            from { opacity: 1; }
            to   { opacity: 0; }
        }

        @keyframes modal-pop-in {
            from { transform: scale(0.85); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }
    </style>

    <script>
        setTimeout(function () {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.classList.add('hide');
                setTimeout(function () {
                    modal.remove();
                }, 300);
            }
        }, 1700);
    </script>
@endif