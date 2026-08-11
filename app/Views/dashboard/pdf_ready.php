<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF آماده دانلود | SnapPDF</title>
    
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
        
        .success-check {
            animation: scaleIn 0.6s ease;
        }
        
        @keyframes scaleIn {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .animate-pulse-slow {
            animation: pulse 2s ease-in-out infinite;
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
        
        .timer-warning {
            color: #f59e0b;
        }
        .timer-danger {
            color: #ba1a1a;
            animation: pulse 1s ease-in-out infinite;
        }
        
        @media (max-width: 640px) {
            .auth-container { padding: 24px; }
        }
    </style>
</head>

<body>
    <div class="w-full max-w-[480px] bg-white rounded-[32px] shadow-xl p-6 md:p-8 auth-container">
        
        <!-- ============================================ -->
        <!-- HEADER -->
        <!-- ============================================ -->
        <div class="text-center mb-6">
            <a href="/" class="inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-[40px] text-[#005bbf]">picture_as_pdf</span>
                <span class="text-2xl font-extrabold text-[#005bbf]">SnapPDF</span>
            </a>
        </div>

        <!-- ============================================ -->
        <!-- SUCCESS ICON -->
        <!-- ============================================ -->
        <div class="text-center mb-6">
            <div class="w-24 h-24 rounded-full bg-[#006e2c]/10 flex items-center justify-center mx-auto success-check border-4 border-[#006e2c]">
                <span class="material-symbols-outlined text-[56px] text-[#006e2c]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            </div>
            <h2 class="text-2xl font-extrabold text-[#191c23] mt-4">✅ PDF آماده دانلود است!</h2>
            <p class="text-sm text-[#727785] mt-1">فایل شما با موفقیت تولید شد.</p>
        </div>

        <!-- ============================================ -->
        <!-- FILE INFO -->
        <!-- ============================================ -->
        <div class="bg-[#f2f3fd] rounded-xl p-4 mb-6 flex items-center gap-3 border border-[#e0e2ec]">
            <div class="w-12 h-12 rounded-xl bg-[#005bbf]/10 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[32px] text-[#005bbf]">description</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[#191c23] truncate" id="pdf-name">
                    <?= esc($pdf_name ?? 'SnapPDF_Export.pdf') ?>
                </p>
                <div class="flex items-center gap-3 text-xs text-[#727785]">
                    <span id="pdf-size"><?= esc($pdf_size ?? '0') ?> MB</span>
                    <span>•</span>
                    <span id="images-count"><?= esc($images_count ?? '0') ?> تصویر</span>
                    <?php if ($is_compressed ?? false): ?>
                        <span>•</span>
                        <span class="text-[#006e2c]">فشرده</span>
                    <?php endif; ?>
                    <?php if ($has_page_numbers ?? false): ?>
                        <span>•</span>
                        <span class="text-[#005bbf]">شماره صفحات</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- DOWNLOAD BUTTON -->
        <!-- ============================================ -->
        <a href="<?= site_url('dashboard/download/' . $conversion_id) ?>" 
           id="download-btn"
           class="btn-primary mb-4 shadow-lg hover:shadow-xl"
           onclick="handleDownload(event)">
            <span class="material-symbols-outlined">download</span>
            دانلود PDF
        </a>

        <!-- ============================================ -->
        <!-- TIMER & AUTO-DELETE -->
        <!-- ============================================ -->
        <div class="bg-[#fffbeb] rounded-xl p-4 border border-[#f59e0b]/30 text-center">
            <div class="flex items-center justify-center gap-2 text-sm font-bold timer-warning" id="timer-container">
                <span class="material-symbols-outlined text-[20px]">timer</span>
                <span>فایل تا</span>
                <span id="timer-display" class="text-[#f59e0b] font-extrabold text-lg">۲:۰۰</span>
                <span>دیگر حذف می‌شود</span>
            </div>
            <p class="text-xs text-[#727785] mt-2">
                ⚡ فایل به‌صورت خودکار از سرور حذف می‌شود.
                <br>
                پس از دانلود، می‌توانید صفحه را ببندید.
            </p>
        </div>

        <!-- ============================================ -->
        <!-- ACTIONS -->
        <!-- ============================================ -->
        <div class="flex flex-col gap-3 mt-6">
            <a href="https://snappdf.ebarqak.ir/dashboard" class="btn-outline">
                <span class="material-symbols-outlined">arrow_back</span>
                بازگشت به داشبورد
            </a>
            <!--<a href="history" class="text-center text-sm text-[#727785] hover:text-[#005bbf] transition-colors">-->
            <!--    <span class="material-symbols-outlined text-[16px]">history</span>-->
            <!--    مشاهده تاریخچه-->
            <!--</a>-->
        </div>

        <!-- ============================================ -->
        <!-- SECURITY NOTICE -->
        <!-- ============================================ -->
        <div class="mt-6 pt-6 border-t border-[#e0e2ec] flex items-center justify-center gap-2 p-3 bg-[#f2f3fd] rounded-xl">
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
        // TIMER
        // =============================================
        let seconds = <?= $expires_in ?? 120 ?>;
        const timerDisplay = document.getElementById('timer-display');
        const timerContainer = document.getElementById('timer-container');
        const downloadBtn = document.getElementById('download-btn');

        function formatTime(seconds) {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        }

        function updateTimer() {
            timerDisplay.textContent = formatTime(seconds);
            
            // تغییر رنگ هشدار
            if (seconds <= 30) {
                timerContainer.classList.remove('timer-warning');
                timerContainer.classList.add('timer-danger');
            } else {
                timerContainer.classList.remove('timer-danger');
                timerContainer.classList.add('timer-warning');
            }
        }

        const timer = setInterval(() => {
            seconds--;
            updateTimer();
            
            if (seconds <= 0) {
                clearInterval(timer);
                timerDisplay.textContent = '۰:۰۰';
                timerContainer.classList.remove('timer-warning');
                timerContainer.classList.add('timer-danger');
                
                // غیرفعال کردن دکمه دانلود
                downloadBtn.disabled = true;
                downloadBtn.style.opacity = '0.5';
                downloadBtn.style.cursor = 'not-allowed';
                downloadBtn.innerHTML = '<span class="material-symbols-outlined">block</span> فایل منقضی شد';
                downloadBtn.href = '#';
                
                showToast('⏰ فایل منقضی شد و از سرور حذف گردید', 'warning');
                
                // ارسال درخواست حذف به سرور (اختیاری)
                fetch('<?= site_url('dashboard/delete-conversion/' . $conversion_id) ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).catch(() => {});
            }
        }, 1000);

        // =============================================
        // HANDLE DOWNLOAD
        // =============================================
        function handleDownload(event) {
            if (seconds <= 0) {
                event.preventDefault();
                showToast('فایل منقضی شده است', 'error');
                return;
            }
            
            showToast('📥 دانلود شروع شد...', 'info');
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
        // PREVENT BACK BUTTON AFTER DOWNLOAD
        // =============================================
        window.addEventListener('beforeunload', function (e) {
            // اگر زمان باقی مانده بود و کاربر می‌خواهد خارج شود
            if (seconds > 0) {
                // نیازی به نمایش پیام نیست
            }
        });

        console.log('✅ PDF Ready page loaded');
        console.log('⏱️ Timer started:', seconds, 'seconds');
        console.log('📄 PDF:', '<?= esc($pdf_name ?? 'SnapPDF_Export.pdf') ?>');
    </script>

</body>
</html>