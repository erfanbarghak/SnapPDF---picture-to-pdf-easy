<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود | SnapPDF</title>
    
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
        
        @media (max-width: 640px) {
            .auth-container { padding: 24px; }
            .form-input { height: 48px; font-size: 15px; }
            .btn-primary, .btn-outline { height: 48px; font-size: 15px; }
        }
    </style>
</head>

<body>
    <div class="w-full max-w-[440px] bg-white rounded-[32px] shadow-xl p-6 md:p-8 auth-container">
        
        <!-- ============================================ -->
        <!-- HEADER -->
        <!-- ============================================ -->
        <div class="text-center mb-6">
            <a href="/" class="inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-[40px] text-[#005bbf]">picture_as_pdf</span>
                <span class="text-2xl font-extrabold text-[#005bbf]">SnapPDF</span>
            </a>
            <h2 class="text-2xl font-bold text-[#191c23] mt-4">ورود</h2>
            <p class="text-sm text-[#727785]">به حساب کاربری خود وارد شوید</p>
        </div>

        <!-- ============================================ -->
        <!-- FLASH MESSAGES -->
        <!-- ============================================ -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-3 bg-red-50 border-r-4 border-red-500 text-red-700 rounded-lg text-sm">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 p-3 bg-green-50 border-r-4 border-green-500 text-green-700 rounded-lg text-sm">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('info')): ?>
            <div class="mb-4 p-3 bg-blue-50 border-r-4 border-blue-500 text-blue-700 rounded-lg text-sm">
                <?= session()->getFlashdata('info') ?>
            </div>
        <?php endif; ?>

        <!-- ============================================ -->
        <!-- LOGIN FORM -->
        <!-- ============================================ -->
        <form action="<?= site_url('auth/doLogin') ?>" method="POST" id="login-form">
            <?= csrf_field() ?>
            
            <!-- Phone -->
            <div class="mb-4">
                <label class="block text-sm font-bold text-[#414754] mb-1.5">
                    شماره تلفن
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-[#727785] text-[20px]">phone</span>
                    <input 
                        type="tel" 
                        id="login-phone" 
                        name="username" 
                        class="form-input" 
                        placeholder="0912 345 6789" 
                        maxlength="11" 
                        required 
                        autocomplete="tel"
                        value="<?= old('username') ?>"
                    >
                </div>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <div class="flex justify-between items-center mb-1.5">
                    <label class="block text-sm font-bold text-[#414754]">
                        رمز عبور
                    </label>
                    <a href="/auth/reset-password" class="text-xs text-[#005bbf] hover:underline font-medium">رمز را فراموش کرده‌اید؟</a>
                </div>
                <div class="relative">
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-[#727785] text-[20px]">lock</span>
                    <input 
                        type="password" 
                        id="login-password" 
                        name="password" 
                        class="form-input" 
                        placeholder="••••••••" 
                        required 
                        minlength="8"
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword('login-password')" 
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-[#727785] hover:text-[#191c23] transition-colors"
                    >
                        <span class="material-symbols-outlined text-[20px]" id="password-icon">visibility</span>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        value="1"
                        class="w-4 h-4 rounded border-[#e0e2ec] text-[#005bbf] focus:ring-[#005bbf] cursor-pointer"
                    >
                    <span class="text-sm text-[#727785]">مرا به خاطر بسپار</span>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-primary" id="login-btn">
                <span class="material-symbols-outlined">login</span>
                ورود
            </button>
        </form>

        <!-- ============================================ -->
        <!-- DIVIDER -->
        <!-- ============================================ -->
        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-text">یا</span>
            <div class="divider-line"></div>
        </div>

        <!-- ============================================ -->
        <!-- REGISTER LINK -->
        <!-- ============================================ -->
        <a href="<?= site_url('auth/register') ?>" class="btn-outline">
            <span class="material-symbols-outlined">person_add</span>
            ثبت‌نام در سامانه
        </a>

        <!-- ============================================ -->
        <!-- SECURITY NOTICE -->
        <!-- ============================================ -->
        <div class="mt-6 flex items-center justify-center gap-2 p-3 bg-[#f2f3fd] rounded-xl border border-[#e0e2ec]/30">
            <span class="material-symbols-outlined text-[#006e2c] text-[20px]" style="font-variation-settings: 'FILL' 1;">verified_user</span>
            <span class="text-xs text-[#727785]">اتصال امن و رمزنگاری شده</span>
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
            const icon = document.getElementById('password-icon');
            
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
        document.getElementById('login-phone').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
        });

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

        console.log('✅ Login page loaded successfully');
    </script>

</body>
</html>