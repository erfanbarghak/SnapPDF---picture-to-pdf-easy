<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پروفایل کاربری | SnapPDF</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Vazirmatn', sans-serif; }
        body { direction: rtl; background: #f9f9ff; }
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
        .form-input.success { border-color: #006e2c; background: #f0faf2; }
        
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
    <!-- Navbar -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-[#e0e2ec]/50">
        <div class="max-w-[1200px] mx-auto px-4 md:px-6">
            <div class="flex items-center justify-between h-[72px]">
                <a href="/" class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[32px] text-[#005bbf]">picture_as_pdf</span>
                    <span class="text-xl font-bold text-[#005bbf]">SnapPDF</span>
                </a>
                <a href="dashboard" class="text-sm text-[#414754] hover:text-[#005bbf] flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    بازگشت به داشبورد
                </a>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="max-w-[600px] mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-sm border border-[#e0e2ec] p-6 md:p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-24 h-24 rounded-full bg-[#005bbf] text-white flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                    <?= mb_substr(session()->get('full_name'), 0, 1) ?>
                </div>
                <h2 class="text-2xl font-bold text-[#191c23]"><?= session()->get('full_name') ?></h2>
                <p class="text-sm text-[#727785]"><?= session()->get('phone') ?></p>
            </div>

            <!-- Profile Form -->
            <form id="profile-form" onsubmit="updateProfile(event)">
                <div class="space-y-4">
                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-bold text-[#414754] mb-1.5">نام و نام خانوادگی</label>
                        <input type="text" id="full-name" class="form-input" value="<?= session()->get('full_name') ?>" required minlength="3">
                    </div>

                    <!-- Phone (Read-only) -->
                    <div>
                        <label class="block text-sm font-bold text-[#414754] mb-1.5">شماره تلفن</label>
                        <input type="text" class="form-input bg-[#ecedf7] cursor-not-allowed" value="<?= session()->get('phone') ?>" disabled>
                        <p class="text-xs text-[#727785] mt-1">شماره تلفن قابل تغییر نیست</p>
                    </div>

                    <!-- Update Button -->
                    <button type="submit" class="w-full py-3 bg-[#005bbf] text-white font-bold rounded-xl hover:bg-[#004493] transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">save</span>
                        به‌روزرسانی پروفایل
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <hr class="my-8 border-[#e0e2ec]">

            <!-- Change Password -->
            <h3 class="text-lg font-bold text-[#191c23] mb-4">تغییر رمز عبور</h3>
            
            <form id="password-form" onsubmit="changePassword(event)">
                <div class="space-y-4">
                    <!-- Current Password -->
                    <div>
                        <label class="block text-sm font-bold text-[#414754] mb-1.5">رمز عبور فعلی</label>
                        <input type="password" id="current-password" class="form-input" placeholder="••••••••" required minlength="8">
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-bold text-[#414754] mb-1.5">رمز عبور جدید</label>
                        <input type="password" id="new-password" class="form-input" placeholder="•••••••• (حداقل ۸ کاراکتر)" required minlength="8">
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-bold text-[#414754] mb-1.5">تکرار رمز عبور جدید</label>
                        <input type="password" id="confirm-password" class="form-input" placeholder="••••••••" required minlength="8">
                    </div>

                    <!-- Change Password Button -->
                    <button type="submit" class="w-full py-3 border-2 border-[#005bbf] text-[#005bbf] font-bold rounded-xl hover:bg-[#005bbf] hover:text-white transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">lock_reset</span>
                        تغییر رمز عبور
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <hr class="my-8 border-[#e0e2ec]">

            <!-- Danger Zone -->
            <!--<div class="bg-[#fff5f5] rounded-xl p-4 border border-[#ba1a1a]/20">-->
            <!--    <h4 class="text-sm font-bold text-[#ba1a1a] mb-2">⚠️ منطقه خطر</h4>-->
            <!--    <p class="text-sm text-[#727785] mb-3">حذف حساب کاربری به معنای از دست دادن تمام داده‌های شماست.</p>-->
            <!--    <button onclick="deleteAccount()" class="px-4 py-2 bg-[#ba1a1a] text-white text-sm font-bold rounded-xl hover:bg-[#93000a] transition-all">-->
            <!--        حذف حساب کاربری-->
            <!--    </button>-->
            <!--</div>-->
        </div>
    </main>

    <div id="toast-container"></div>

    <script>
        // =============================================
        // UPDATE PROFILE
        // =============================================
        function updateProfile(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            const name = document.getElementById('full-name').value.trim();

            if (name.length < 3) {
                showToast('نام و نام خانوادگی باید حداقل ۳ کاراکتر باشد', 'error');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> در حال به‌روزرسانی...';

            const formData = new FormData();
            formData.append('full_name', name);

            fetch('/profile/update', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => showToast('خطا در ارتباط با سرور', 'error'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<span class="material-symbols-outlined">save</span> به‌روزرسانی پروفایل';
            });
        }

        // =============================================
        // CHANGE PASSWORD
        // =============================================
        function changePassword(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            const current = document.getElementById('current-password').value;
            const newPass = document.getElementById('new-password').value;
            const confirm = document.getElementById('confirm-password').value;

            if (current.length < 8 || newPass.length < 8 || confirm.length < 8) {
                showToast('رمز عبور باید حداقل ۸ کاراکتر باشد', 'error');
                return;
            }

            if (newPass !== confirm) {
                showToast('رمز عبور و تکرار آن مطابقت ندارند', 'error');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> در حال تغییر...';

            const formData = new FormData();
            formData.append('current_password', current);
            formData.append('new_password', newPass);
            formData.append('confirm_password', confirm);

            fetch('/profile/change-password', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    document.getElementById('current-password').value = '';
                    document.getElementById('new-password').value = '';
                    document.getElementById('confirm-password').value = '';
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => showToast('خطا در ارتباط با سرور', 'error'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<span class="material-symbols-outlined">lock_reset</span> تغییر رمز عبور';
            });
        }

        // =============================================
        // DELETE ACCOUNT
        // =============================================
        function deleteAccount() {
            if (!confirm('آیا از حذف حساب کاربری خود مطمئن هستید؟ این عمل غیرقابل بازگشت است!')) return;
            if (!confirm('برای تأیید، مجدداً بله را انتخاب کنید.')) return;

            fetch('/profile/delete', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.href = '/', 2000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => showToast('خطا در ارتباط با سرور', 'error'));
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