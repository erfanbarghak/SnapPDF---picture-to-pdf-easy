<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأیید شماره همراه | SnapPDF</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Vazirmatn', sans-serif; }
        body { 
            direction: rtl; 
            background: #f9f9ff; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 16px; 
        }
        
        .otp-input {
            width: 52px;
            height: 60px;
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
        .otp-input::-webkit-inner-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        .otp-input:focus { 
            border-color: #005bbf; 
            box-shadow: 0 0 0 4px rgba(0, 91, 191, 0.1); 
            outline: none; 
            background: white; 
        }
        .otp-input.filled { 
            border-color: #006e2c; 
            background: #f0faf2; 
        }
        .otp-input.error { 
            border-color: #ba1a1a; 
            background: #fff5f5; 
        }
        
        .btn-primary {
            width: 100%;
            padding: 14px;
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
            font-size: 16px;
        }
        .btn-primary:hover:not(:disabled) { 
            background: #004493; 
            transform: translateY(-2px); 
            box-shadow: 0 8px 24px rgba(0, 91, 191, 0.25); 
        }
        .btn-primary:disabled { 
            opacity: 0.6; 
            cursor: not-allowed; 
            transform: none !important;
        }
        
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
            max-width: 90%;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateX(-50%) translateY(20px); }
            to { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
        .toast-success { background: #006e2c; color: white; }
        .toast-error { background: #ba1a1a; color: white; }
        .toast-info { background: #005bbf; color: white; }
        .toast-warning { background: #f59e0b; color: white; }
        
        @media (max-width: 640px) {
            .otp-input { width: 44px; height: 52px; font-size: 20px; }
        }
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
            <h2 class="text-2xl font-bold text-[#191c23] mt-4">تأیید شماره همراه</h2>
            <p class="text-sm text-[#727785] mt-1">
                کد تأیید ۶ رقمی به شماره 
                <span class="font-bold text-[#005bbf]" dir="ltr"><?= esc($phone ?? '') ?></span> 
                  در پیام رسان  <span style="size:35px; color:white; background:green; padding:6px; border-radius:15px;">بله</span> ارسال شد.
            </p>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-3 bg-red-50 border-r-4 border-red-500 text-red-700 rounded-lg text-sm">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('info')): ?>
            <div class="mb-4 p-3 bg-blue-50 border-r-4 border-blue-500 text-blue-700 rounded-lg text-sm">
                <?= session()->getFlashdata('info') ?>
            </div>
        <?php endif; ?>

        <!-- OTP Form -->
        <form action="<?= site_url('auth/doVerifyPhone') ?>" method="POST" id="otp-form">
            <?= csrf_field() ?>
            
            <div class="flex justify-center gap-2 mb-8" dir="ltr">
                <input class="otp-input" type="text" maxlength="1" name="code[]" inputmode="numeric" 
                       oninput="moveToNext(this)" onkeydown="moveToPrev(this, event)" autofocus required>
                <input class="otp-input" type="text" maxlength="1" name="code[]" inputmode="numeric" 
                       oninput="moveToNext(this)" onkeydown="moveToPrev(this, event)" required>
                <input class="otp-input" type="text" maxlength="1" name="code[]" inputmode="numeric" 
                       oninput="moveToNext(this)" onkeydown="moveToPrev(this, event)" required>
                <input class="otp-input" type="text" maxlength="1" name="code[]" inputmode="numeric" 
                       oninput="moveToNext(this)" onkeydown="moveToPrev(this, event)" required>
                <input class="otp-input" type="text" maxlength="1" name="code[]" inputmode="numeric" 
                       oninput="moveToNext(this)" onkeydown="moveToPrev(this, event)" required>
                <input class="otp-input" type="text" maxlength="1" name="code[]" inputmode="numeric" 
                       oninput="moveToNext(this)" onkeydown="moveToPrev(this, event)" required>
            </div>

            <button type="submit" class="btn-primary" id="verify-btn">
                <span class="material-symbols-outlined">check_circle</span>
                تأیید و ادامه
            </button>
        </form>

        <!-- Resend -->
        <div class="flex flex-col items-center gap-3 mt-6">
            <div class="flex items-center gap-2 text-sm text-[#727785]">
                <span class="material-symbols-outlined text-sm">schedule</span>
                <span>ارسال مجدد کد تا</span>
                <span class="font-bold text-[#005bbf]" id="countdown">۰۲:۰۰</span>
            </div>
            <button class="text-sm text-[#727785] cursor-not-allowed transition-colors" disabled id="resendBtn">
                ارسال مجدد کد
            </button>
        </div>

        <!-- Back -->
        <div class="mt-6 pt-6 border-t border-[#e0e2ec] text-center">
            <a class="inline-flex items-center gap-2 text-sm text-[#727785] hover:text-[#005bbf] transition-colors" href="<?= site_url('auth/register') ?>">
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                بازگشت به ثبت‌نام
            </a>
        </div>
    </div>

    <div id="toast-container"></div>

    <script>
        // ============================================
        // مدیریت OTP
        // ============================================
        function moveToNext(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value.length === 1) {
                const inputs = document.querySelectorAll('.otp-input');
                let index = Array.from(inputs).indexOf(input);
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                    inputs[index + 1].classList.add('filled');
                }
            }
            checkOTPComplete();
        }

        function moveToPrev(input, event) {
            if (event.key === 'Backspace' && input.value === '') {
                const inputs = document.querySelectorAll('.otp-input');
                let index = Array.from(inputs).indexOf(input);
                if (index > 0) {
                    inputs[index - 1].focus();
                    inputs[index - 1].classList.remove('filled');
                }
            }
        }

        function checkOTPComplete() {
            const inputs = document.querySelectorAll('.otp-input');
            let complete = true;
            let value = '';
            inputs.forEach(input => {
                if (input.value === '') {
                    complete = false;
                }
                value += input.value;
            });
            if (complete && value.length === 6) {
                // ارسال خودکار فرم بعد از 500ms
                setTimeout(() => {
                    document.getElementById('otp-form').submit();
                }, 500);
            }
        }

        // ============================================
        // تایمر ارسال مجدد
        // ============================================
        let timeInSeconds = 120;
        const countdownElement = document.getElementById('countdown');
        const resendBtn = document.getElementById('resendBtn');

        function formatTime(seconds) {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        }

        function startTimer() {
            const timer = setInterval(() => {
                timeInSeconds--;
                countdownElement.textContent = formatTime(timeInSeconds);
                if (timeInSeconds <= 0) {
                    clearInterval(timer);
                    resendBtn.disabled = false;
                    resendBtn.classList.remove('text-[#727785]', 'cursor-not-allowed');
                    resendBtn.classList.add('text-[#005bbf]', 'hover:text-[#004493]', 'cursor-pointer', 'font-bold');
                    resendBtn.textContent = 'ارسال مجدد کد';
                }
            }, 1000);
        }

        // ============================================
        // ارسال مجدد کد (AJAX)
        // ============================================
        resendBtn.addEventListener('click', function() {
            if (this.disabled) return;
            
            this.disabled = true;
            this.textContent = 'در حال ارسال...';
            
            fetch('<?= site_url('auth/resendCode') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    timeInSeconds = 120;
                    startTimer();
                    this.textContent = 'ارسال مجدد کد';
                    showToast('کد جدید ارسال شد.', 'success');
                } else {
                    this.textContent = 'ارسال مجدد کد';
                    this.disabled = false;
                    showToast(data.message || 'خطا در ارسال کد.', 'error');
                }
            })
            .catch(error => {
                this.textContent = 'ارسال مجدد کد';
                this.disabled = false;
                showToast('خطا در ارتباط با سرور.', 'error');
            });
        });

        startTimer();

        // ============================================
        // Toast System
        // ============================================
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            container.innerHTML = '';
            
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const icons = {
                success: 'check_circle',
                error: 'error',
                info: 'info',
                warning: 'warning'
            };
            
            toast.innerHTML = `
                <span class="material-symbols-outlined">${icons[type] || 'info'}</span>
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

        <?php if (session()->getFlashdata('error')): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= session()->getFlashdata('error') ?>', 'error');
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('info')): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= session()->getFlashdata('info') ?>', 'info');
            });
        <?php endif; ?>
    </script>

</body>
</html>