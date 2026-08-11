<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تکمیل ثبت‌نام | SnapPDF</title>
    
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
        .material-symbols-outlined { 
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; 
            vertical-align: middle; 
        }
        
        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: #f2f3fd;
            border: 2px solid #e0e2ec;
            border-radius: 12px;
            transition: all 0.2s ease;
            font-size: 16px;
        }
        .form-input:focus {
            border-color: #005bbf;
            box-shadow: 0 0 0 4px rgba(0, 91, 191, 0.1);
            outline: none;
            background: white;
        }
        .form-input.error { border-color: #ba1a1a; background: #fff5f5; }
        .form-input.success { border-color: #006e2c; background: #f0faf2; }
        
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
            <h2 class="text-2xl font-bold text-[#191c23] mt-4">تکمیل ثبت‌نام</h2>
            <p class="text-sm text-[#727785]">
                شماره تلفن شما تأیید شد. لطفاً اطلاعات زیر را تکمیل کنید.
            </p>
        </div>

        <!-- Info Box -->
        <div class="bg-[#f0faf2] rounded-xl p-4 mb-6 text-center border border-[#006e2c]/20">
            <span class="material-symbols-outlined text-[#006e2c] text-[32px]">check_circle</span>
            <p class="text-sm text-[#006e2c] font-bold mt-1">
                شماره تلفن <?= $display_phone ?? '' ?> تأیید شد
            </p>
        </div>

        <!-- Complete Register Form -->
        <form id="complete-register-form" onsubmit="completeRegister(event)" novalidate>
            
            <!-- Full Name -->
            <div class="mb-4">
                <label class="block text-sm font-bold text-[#414754] mb-1.5">
                    نام و نام خانوادگی <span class="text-[#ba1a1a]">*</span>
                </label>
                <input 
                    type="text" 
                    id="full-name" 
                    class="form-input" 
                    placeholder="مثال: علی رضایی" 
                    value="<?= $full_name ?? '' ?>"
                    required 
                    minlength="3"
                >
                <div id="name-error" class="text-xs text-[#ba1a1a] mt-1 hidden"></div>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-sm font-bold text-[#414754] mb-1.5">
                    رمز عبور <span class="text-[#ba1a1a]">*</span>
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        class="form-input" 
                        placeholder="•••••••• (حداقل ۸ کاراکتر)" 
                        required 
                        minlength="8"
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword('password')" 
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-[#727785] hover:text-[#191c23] transition-colors"
                    >
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </button>
                </div>
                <div id="password-error" class="text-xs text-[#ba1a1a] mt-1 hidden"></div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-[#414754] mb-1.5">
                    تکرار رمز عبور <span class="text-[#ba1a1a]">*</span>
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="confirm-password" 
                        class="form-input" 
                        placeholder="••••••••" 
                        required 
                        minlength="8"
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword('confirm-password')" 
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-[#727785] hover:text-[#191c23] transition-colors"
                    >
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </button>
                </div>
                <div id="confirm-error" class="text-xs mt-1 hidden"></div>
            </div>

            <!-- Submit -->
            <button type="submit" id="complete-btn" class="btn-primary">
                <span class="material-symbols-outlined">person_add</span>
                تکمیل ثبت‌نام
            </button>
        </form>

        <!-- Back Link -->
        <p class="text-center text-sm text-[#727785] mt-4">
            <a href="/auth/register" class="text-[#005bbf] hover:underline transition-colors">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                بازگشت
            </a>
        </p>
    </div>

    <div id="toast-container"></div>

    <script>
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

        function completeRegister(e) {
            e.preventDefault();
            
            const btn = document.getElementById('complete-btn');
            const name = document.getElementById('full-name').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            // Validation
            if (name.length < 3) {
                showToast('نام و نام خانوادگی حداقل ۳ کاراکتر باید باشد', 'error');
                document.getElementById('full-name').classList.add('error');
                document.getElementById('name-error').textContent = 'حداقل ۳ کاراکتر وارد کنید';
                document.getElementById('name-error').classList.remove('hidden');
                return;
            }
            document.getElementById('full-name').classList.remove('error');
            document.getElementById('name-error').classList.add('hidden');

            if (password.length < 8) {
                showToast('رمز عبور حداقل ۸ کاراکتر باید باشد', 'error');
                document.getElementById('password').classList.add('error');
                document.getElementById('password-error').textContent = 'حداقل ۸ کاراکتر وارد کنید';
                document.getElementById('password-error').classList.remove('hidden');
                return;
            }
            document.getElementById('password').classList.remove('error');
            document.getElementById('password-error').classList.add('hidden');

            if (password !== confirmPassword) {
                showToast('رمز عبور و تکرار آن مطابقت ندارند', 'error');
                document.getElementById('confirm-password').classList.add('error');
                document.getElementById('confirm-error').innerHTML = '<span class="text-[#ba1a1a]">✗ رمز عبور مطابقت ندارد</span>';
                document.getElementById('confirm-error').classList.remove('hidden');
                return;
            }
            document.getElementById('confirm-password').classList.remove('error');
            document.getElementById('confirm-error').classList.add('hidden');

            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> در حال ثبت‌نام...';

            const formData = new FormData();
            formData.append('full_name', name);
            formData.append('password', password);
            formData.append('confirm_password', confirmPassword);

            fetch('/auth/complete-register', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || '/dashboard';
                    }, 1500);
                } else {
                    showToast(data.message, 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<span class="material-symbols-outlined">person_add</span> تکمیل ثبت‌نام';
                }
            })
            .catch(error => {
                console.error('❌ Complete Register Error:', error);
                showToast('خطا در ارتباط با سرور', 'error');
                btn.disabled = false;
                btn.innerHTML = '<span class="material-symbols-outlined">person_add</span> تکمیل ثبت‌نام';
            });
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            const icons = { success: 'check_circle', error: 'error', info: 'info', warning: 'warning' };
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
    </script>
</body>
</html>