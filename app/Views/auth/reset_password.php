<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بازیابی رمز عبور | SnapPDF</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Vazirmatn', sans-serif; }
        body { direction: rtl; background: #f9f9ff; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 16px; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        
        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: #f2f3fd;
            border: 2px solid #e0e2ec;
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        .form-input:focus {
            border-color: #005bbf;
            box-shadow: 0 0 0 4px rgba(0, 91, 191, 0.1);
            outline: none;
            background: white;
        }
        .form-input.error { border-color: #ba1a1a; background: #fff5f5; }
        
        .btn-primary {
            width: 100%;
            padding: 12px;
            background: #005bbf;
            color: white;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-primary:hover:not(:disabled) { background: #004493; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0, 91, 191, 0.25); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
        
        .btn-outline {
            padding: 12px 20px;
            background: transparent;
            color: #005bbf;
            font-weight: 700;
            border: 2px solid #005bbf;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-outline:hover:not(:disabled) { background: #005bbf; color: white; }
        .btn-outline:disabled { opacity: 0.6; cursor: not-allowed; }
        
        .otp-input {
            width: 100%;
            aspect-ratio: 1;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            background: #f2f3fd;
            border: 2px solid #e0e2ec;
            border-radius: 12px;
            transition: all 0.2s ease;
            -moz-appearance: textfield;
        }
        .otp-input::-webkit-outer-spin-button,
        .otp-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .otp-input:focus { border-color: #005bbf; box-shadow: 0 0 0 4px rgba(0, 91, 191, 0.1); outline: none; background: white; }
        .otp-input.filled { border-color: #006e2c; background: #f0faf2; }
        
        .toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            padding: 16px 24px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateX(-50%) translateY(20px); }
            to { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
        .toast-success { background: #006e2c; color: white; }
        .toast-error { background: #ba1a1a; color: white; }
        .toast-info { background: #005bbf; color: white; }
    </style>
</head>

<body>
    <div class="w-full max-w-[440px] bg-white rounded-[32px] shadow-xl p-6 md:p-8">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <a href="/" class="inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-[40px] text-[#005bbf]">picture_as_pdf</span>
                <span class="text-2xl font-extrabold text-[#005bbf]">SnapPDF</span>
            </a>
            <h2 class="text-2xl font-bold text-[#191c23] mt-4">بازیابی رمز عبور</h2>
            <p class="text-sm text-[#727785]">رمز عبور خود را با شماره تلفن بازیابی کنید</p>
        </div>

        <!-- Info Box -->
        <div class="bg-[#f2f3fd] rounded-xl p-4 mb-6 text-center">
            <span class="material-symbols-outlined text-[32px] text-[#005bbf]">lock_reset</span>
            <p class="text-sm text-[#414754] mt-1">
                کد تأیید به شماره تلفن شما ارسال خواهد شد.
            </p>
        </div>

        <!-- Reset Form -->
        <form id="reset-form" onsubmit="handleReset(event)">
            <!-- Phone -->
            <div class="mb-4">
                <label class="block text-sm font-bold text-[#414754] mb-1.5">شماره تلفن</label>
                <div class="relative">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[#727785] font-bold text-sm">+98</span>
                    <input type="tel" id="reset-phone" class="form-input pr-14" placeholder="9__ ___ ____" maxlength="10" required oninput="formatPhone(this)">
                </div>
            </div>

            <!-- OTP Section -->
            <div class="mb-4">
                <div class="flex items-end gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-[#414754] mb-1.5">کد تأیید</label>
                        <div class="flex gap-1.5" id="otp-container">
                            <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                            <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                        </div>
                    </div>
                    <button type="button" id="send-otp-btn" class="btn-outline" onclick="sendOtp()">
                        دریافت کد
                    </button>
                </div>
                <div id="otp-timer" class="text-xs text-[#727785] mt-1 hidden">
                    ارسال مجدد کد پس از <span id="timer-countdown">60</span> ثانیه
                </div>
            </div>

            <!-- New Password -->
            <div class="mb-4">
                <label class="block text-sm font-bold text-[#414754] mb-1.5">رمز عبور جدید</label>
                <div class="relative">
                    <input type="password" id="reset-password" class="form-input" placeholder="•••••••• (حداقل ۸ کاراکتر)" required minlength="8">
                    <button type="button" onclick="togglePassword('reset-password')" class="absolute left-4 top-1/2 -translate-y-1/2 text-[#727785] hover:text-[#191c23]">
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </button>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-[#414754] mb-1.5">تکرار رمز عبور جدید</label>
                <div class="relative">
                    <input type="password" id="reset-confirm-password" class="form-input" placeholder="••••••••" required minlength="8">
                    <button type="button" onclick="togglePassword('reset-confirm-password')" class="absolute left-4 top-1/2 -translate-y-1/2 text-[#727785] hover:text-[#191c23]">
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </button>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" id="reset-btn" class="btn-primary">
                <span class="material-symbols-outlined">refresh</span>
                بازنشانی رمز عبور
            </button>
        </form>

        <!-- Back to Login -->
        <p class="text-center text-sm text-[#727785] mt-4">
            <a href="/auth/login" class="text-[#005bbf] font-bold hover:underline">بازگشت به ورود</a>
        </p>
    </div>

    <div id="toast-container"></div>

    <script>
        // =============================================
        // PHONE FORMATTING
        // =============================================
        function formatPhone(input) {
            input.value = input.value.replace(/\D/g, '').slice(0, 10);
        }

        // =============================================
        // PASSWORD TOGGLE
        // =============================================
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.parentElement.querySelector('.material-symbols-outlined');
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        // =============================================
        // OTP AUTO-FOCUS
        // =============================================
        document.querySelectorAll('#otp-container .otp-input').forEach((input, index, arr) => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 1);
                if (e.target.value.length === 1 && index < arr.length - 1) {
                    arr[index + 1].focus();
                    arr[index + 1].classList.add('filled');
                }
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    arr[index - 1].focus();
                    arr[index - 1].classList.remove('filled');
                }
            });
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
                paste.split('').forEach((val, i) => {
                    if (arr[i]) { arr[i].value = val; arr[i].classList.add('filled'); }
                });
                const nextIndex = Math.min(paste.length, arr.length - 1);
                if (arr[nextIndex]) arr[nextIndex].focus();
            });
        });

        function getOtpCode() {
            const inputs = document.querySelectorAll('#otp-container .otp-input');
            return Array.from(inputs).map(i => i.value).join('');
        }

        // =============================================
        // SEND OTP
        // =============================================
        let otpTimer = null;

        function sendOtp() {
            const phone = document.getElementById('reset-phone').value.trim();
            const btn = document.getElementById('send-otp-btn');
            const timerDiv = document.getElementById('otp-timer');
            const countdownSpan = document.getElementById('timer-countdown');

            if (!phone || phone.length < 10) {
                showToast('لطفاً شماره تلفن معتبر وارد کنید', 'error');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'در حال ارسال...';

            fetch('/auth/send-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    phone: phone,
                    type: 'reset_password'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    btn.textContent = 'کد ارسال شد';
                    btn.classList.add('bg-[#006e2c]', 'text-white');
                    btn.classList.remove('btn-outline');
                    
                    let seconds = 60;
                    timerDiv.classList.remove('hidden');
                    countdownSpan.textContent = seconds;

                    if (otpTimer) clearInterval(otpTimer);
                    otpTimer = setInterval(() => {
                        seconds--;
                        countdownSpan.textContent = seconds;
                        if (seconds <= 0) {
                            clearInterval(otpTimer);
                            timerDiv.classList.add('hidden');
                            btn.disabled = false;
                            btn.textContent = 'ارسال مجدد';
                            btn.classList.remove('bg-[#006e2c]', 'text-white');
                            btn.classList.add('btn-outline');
                        }
                    }, 1000);
                } else {
                    showToast(data.message, 'error');
                    btn.disabled = false;
                    btn.textContent = 'دریافت کد';
                }
            })
            .catch(error => {
                showToast('خطا در ارتباط با سرور', 'error');
                btn.disabled = false;
                btn.textContent = 'دریافت کد';
                console.error('Error:', error);
            });
        }

        // =============================================
        // HANDLE RESET
        // =============================================
        function handleReset(e) {
            e.preventDefault();
            const btn = document.getElementById('reset-btn');
            
            const phone = document.getElementById('reset-phone').value.trim();
            const otpCode = getOtpCode();
            const newPassword = document.getElementById('reset-password').value;
            const confirmPassword = document.getElementById('reset-confirm-password').value;

            if (!phone || phone.length < 10) {
                showToast('لطفاً شماره تلفن را وارد کنید', 'error');
                return;
            }
            if (otpCode.length !== 6) {
                showToast('لطفاً کد تأیید ۶ رقمی را وارد کنید', 'error');
                return;
            }
            if (newPassword.length < 8) {
                showToast('رمز عبور باید حداقل ۸ کاراکتر باشد', 'error');
                return;
            }
            if (newPassword !== confirmPassword) {
                showToast('رمز عبور و تکرار آن مطابقت ندارند', 'error');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> در حال بازنشانی...';

            const formData = new FormData();
            formData.append('phone', phone);
            formData.append('otp_code', otpCode);
            formData.append('new_password', newPassword);
            formData.append('confirm_password', confirmPassword);

            fetch('/auth/reset-password', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.href = '/auth/login', 1500);
                } else {
                    showToast(data.message, 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<span class="material-symbols-outlined">refresh</span> بازنشانی رمز عبور';
                }
            })
            .catch(error => {
                showToast('خطا در ارتباط با سرور', 'error');
                btn.disabled = false;
                btn.innerHTML = '<span class="material-symbols-outlined">refresh</span> بازنشانی رمز عبور';
                console.error('Error:', error);
            });
        }

        // =============================================
        // TOAST SYSTEM
        // =============================================
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            const icons = { success: 'check_circle', error: 'error', info: 'info' };
            toast.innerHTML = `
                <span class="material-symbols-outlined">${icons[type]}</span>
                <span>${message}</span>
                <button onclick="this.parentElement.remove()" class="mr-auto bg-transparent border-none text-white/70 hover:text-white cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            `;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-50%) translateY(20px)';
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }

        <?php if (session()->getFlashdata('success')): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= session()->getFlashdata('success') ?>', 'success');
            });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= session()->getFlashdata('error') ?>', 'error');
            });
        <?php endif; ?>
    </script>
</body>
</html>