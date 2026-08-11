<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت‌نام | SnapPDF</title>
    
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
            padding: 12px 16px 12px 44px;
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
        
        .btn-outline {
            width: 100%;
            padding: 14px;
            background: transparent;
            color: #005bbf;
            font-weight: 700;
            border: 2px solid #005bbf;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 16px;
        }
        .btn-outline:hover { 
            background: #005bbf; 
            color: white; 
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
        
        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 20px 0;
        }
        .divider-line {
            flex: 1;
            height: 1px;
            background: #e0e2ec;
        }
        .divider-text {
            color: #727785;
            font-size: 12px;
            font-weight: 600;
        }
        
        .strength-bar {
            height: 4px;
            border-radius: 4px;
            background: #e0e2ec;
            margin-top: 6px;
            overflow: hidden;
        }
        .strength-bar .fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
            width: 0%;
        }
        
        @media (max-width: 640px) {
            .auth-container { padding: 24px; }
            .form-input { height: 48px; font-size: 15px; }
            .btn-primary, .btn-outline { height: 48px; font-size: 15px; }
        }
    </style>
</head>

<body>
    <div class="w-full max-w-[520px] bg-white rounded-[32px] shadow-xl p-6 md:p-8 auth-container">
        
        <!-- ============================================ -->
        <!-- HEADER -->
        <!-- ============================================ -->
        <div class="text-center mb-6">
            <a href="/" class="inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-[40px] text-[#005bbf]">picture_as_pdf</span>
                <span class="text-2xl font-extrabold text-[#005bbf]">SnapPDF</span>
            </a>
            <h2 class="text-2xl font-bold text-[#191c23] mt-4">ایجاد حساب کاربری</h2>
            <p class="text-sm text-[#727785]">برای استفاده از SnapPDF ثبت‌نام کنید</p>
        </div>

        <!-- ============================================ -->
        <!-- FLASH MESSAGES -->
        <!-- ============================================ -->
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

        <!-- ============================================ -->
        <!-- REGISTER FORM -->
        <!-- ============================================ -->
        <form action="<?= site_url('auth/doRegister') ?>" method="POST" id="register-form">
            <?= csrf_field() ?>
            
            <!-- Full Name -->
            <div class="mb-4">
                <label class="block text-sm font-bold text-[#414754] mb-1.5">
                    نام و نام خانوادگی <span class="text-[#ba1a1a]">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-[#727785] text-[20px]">person</span>
                    <input 
                        type="text" 
                        id="full_name" 
                        name="full_name" 
                        class="form-input" 
                        placeholder="مثال: علی رضایی" 
                        required 
                        minlength="3"
                        value="<?= old('full_name') ?>"
                    >
                </div>
                <?php if (isset($validation) && $validation->hasError('full_name')): ?>
                    <p class="text-error text-xs mt-1"><?= $validation->getError('full_name') ?></p>
                <?php endif; ?>
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label class="block text-sm font-bold text-[#414754] mb-1.5">
                    شماره تلفن <span class="text-[#ba1a1a]">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-[#727785] text-[20px]">phone_iphone</span>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        class="form-input" 
                        placeholder="0912 345 6789" 
                        maxlength="11" 
                        required 
                        autocomplete="tel"
                        value="<?= old('phone') ?>"
                    >
                </div>
                <?php if (isset($validation) && $validation->hasError('phone')): ?>
                    <p class="text-error text-xs mt-1"><?= $validation->getError('phone') ?></p>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="block text-sm font-bold text-[#414754] mb-1.5">
                    رمز عبور <span class="text-[#ba1a1a]">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-[#727785] text-[20px]">lock</span>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="•••••••• (حداقل ۸ کاراکتر)" 
                        required 
                        minlength="8"
                        oninput="checkPasswordStrength(this.value)"
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword('password')" 
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-[#727785] hover:text-[#191c23] transition-colors"
                    >
                        <span class="material-symbols-outlined text-[20px]" id="password-icon">visibility</span>
                    </button>
                </div>
                <?php if (isset($validation) && $validation->hasError('password')): ?>
                    <p class="text-error text-xs mt-1"><?= $validation->getError('password') ?></p>
                <?php endif; ?>
                
                <!-- Password Strength -->
                <div class="strength-bar">
                    <div class="fill" id="strength-fill"></div>
                </div>
                <div id="password-strength" class="text-xs mt-1 hidden"></div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label class="block text-sm font-bold text-[#414754] mb-1.5">
                    تکرار رمز عبور <span class="text-[#ba1a1a]">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-[#727785] text-[20px]">lock_reset</span>
                    <input 
                        type="password" 
                        id="password_confirm" 
                        name="password_confirm" 
                        class="form-input" 
                        placeholder="••••••••" 
                        required 
                        minlength="8"
                        oninput="checkPasswordMatch(this.value)"
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword('password_confirm')" 
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-[#727785] hover:text-[#191c23] transition-colors"
                    >
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </button>
                </div>
                <?php if (isset($validation) && $validation->hasError('password_confirm')): ?>
                    <p class="text-error text-xs mt-1"><?= $validation->getError('password_confirm') ?></p>
                <?php endif; ?>
                <div id="password-match" class="text-xs mt-1 hidden"></div>
            </div>

            <!-- Terms -->
            <div class="flex items-start gap-2 mb-6">
                <input 
                    type="checkbox" 
                    id="terms" 
                    name="terms" 
                    class="mt-1 rounded border-[#e0e2ec] text-[#005bbf] focus:ring-[#005bbf] w-4 h-4 cursor-pointer" 
                    required
                >
                <label for="terms" class="text-xs text-[#727785] cursor-pointer">
                    <span class="text-[#414754] font-medium">شرایط و قوانین</span> را مطالعه کرده و قبول دارم.
                </label>
            </div>
            <?php if (isset($validation) && $validation->hasError('terms')): ?>
                <p class="text-error text-xs mt-1"><?= $validation->getError('terms') ?></p>
            <?php endif; ?>

            <!-- Submit -->
            <button type="submit" class="btn-primary" id="register-btn">
                <span class="material-symbols-outlined">person_add</span>
                ایجاد حساب کاربری
            </button>
        </form>

        <!-- ============================================ -->
        <!-- DIVIDER -->
        <!-- ============================================ -->
        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-text">قبلاً ثبت‌نام کرده‌اید؟</span>
            <div class="divider-line"></div>
        </div>

        <!-- ============================================ -->
        <!-- LOGIN LINK -->
        <!-- ============================================ -->
        <a href="<?= site_url('auth/login') ?>" class="btn-outline">
            <span class="material-symbols-outlined">login</span>
            ورود به حساب موجود
        </a>

        <!-- ============================================ -->
        <!-- FEATURES -->
        <!-- ============================================ -->
        <div class="mt-6 pt-6 border-t border-[#e0e2ec] grid grid-cols-3 gap-4 text-center">
            <div>
                <span class="material-symbols-outlined text-[#005bbf] text-[24px]">security</span>
                <p class="text-[10px] text-[#727785] mt-1">امنیت بالا</p>
            </div>
            <div>
                <span class="material-symbols-outlined text-[#005bbf] text-[24px]">speed</span>
                <p class="text-[10px] text-[#727785] mt-1">سرعت بالا</p>
            </div>
            <div>
                <span class="material-symbols-outlined text-[#005bbf] text-[24px]">workspace_premium</span>
                <p class="text-[10px] text-[#727785] mt-1">کاملاً رایگان</p>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- TOAST CONTAINER -->
    <!-- ============================================ -->
    <div id="toast-container"></div>

    <!-- ============================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================ -->
    <script>
        // =============================================
        // TOGGLE PASSWORD
        // =============================================
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.parentElement.querySelector('#password-icon') || 
                        input.parentElement.querySelector('.material-symbols-outlined:last-child');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        // =============================================
        // AUTO-FORMAT PHONE
        // =============================================
        document.getElementById('phone').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
        });

        // =============================================
        // PASSWORD STRENGTH
        // =============================================
        function checkPasswordStrength(password) {
            const strengthDiv = document.getElementById('password-strength');
            const fill = document.getElementById('strength-fill');
            
            if (password.length < 8) {
                strengthDiv.classList.add('hidden');
                fill.style.width = '0%';
                return;
            }

            strengthDiv.classList.remove('hidden');
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            const labels = ['ضعیف', 'ضعیف', 'متوسط', 'قوی', 'بسیار قوی'];
            const colors = ['#ba1a1a', '#ba1a1a', '#f59e0b', '#006e2c', '#006e2c'];
            const widths = ['20%', '20%', '50%', '75%', '100%'];
            
            strengthDiv.innerHTML = `
                <span class="font-medium" style="color: ${colors[strength]}">
                    ${labels[strength]}
                </span>
                <span class="text-[#727785]"> | ${strength}/5</span>
            `;
            
            fill.style.width = widths[strength];
            fill.style.background = colors[strength];
        }

        function checkPasswordMatch(value) {
            const password = document.getElementById('password').value;
            const matchDiv = document.getElementById('password-match');
            
            if (value.length === 0) {
                matchDiv.classList.add('hidden');
                return;
            }

            matchDiv.classList.remove('hidden');
            
            if (value === password) {
                matchDiv.innerHTML = '<span class="text-[#006e2c]">✓ رمز عبور مطابقت دارد</span>';
            } else {
                matchDiv.innerHTML = '<span class="text-[#ba1a1a]">✗ رمز عبور مطابقت ندارد</span>';
            }
        }

        // =============================================
        // TOAST SYSTEM
        // =============================================
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

        // =============================================
        // SHOW FLASH MESSAGES
        // =============================================
        <?php if (session()->getFlashdata('error')): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= session()->getFlashdata('error') ?>', 'error');
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= session()->getFlashdata('success') ?>', 'success');
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('info')): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= session()->getFlashdata('info') ?>', 'info');
            });
        <?php endif; ?>

        console.log('✅ Register page loaded successfully');
    </script>

</body>
</html>