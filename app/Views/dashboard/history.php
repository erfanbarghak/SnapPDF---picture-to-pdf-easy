<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تاریخچه تبدیل‌ها | SnapPDF</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Vazirmatn', sans-serif; }
        body { direction: rtl; background: #f9f9ff; min-height: 100vh; }
        
        .material-symbols-outlined { 
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; 
            vertical-align: middle; 
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .status-completed { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-failed { background: #fee2e2; color: #991b1b; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-deleted { background: #f3f4f6; color: #4b5563; }
        
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
        
        .history-card {
            transition: all 0.3s ease;
        }
        .history-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e0e2ec; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #c1c6d6; }
    </style>
</head>

<body>
    <!-- ============================================ -->
    <!-- NAVBAR -->
    <!-- ============================================ -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-[#e0e2ec]/50">
        <div class="max-w-[1200px] mx-auto px-4 md:px-6">
            <div class="flex items-center justify-between h-[72px]">
                <a href="/" class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[32px] text-[#005bbf]">picture_as_pdf</span>
                    <span class="text-xl font-bold text-[#005bbf]">SnapPDF</span>
                </a>
                
                <div class="flex items-center gap-3">
                    <a href="/dashboard" class="flex items-center gap-1 text-sm text-[#414754] hover:text-[#005bbf] transition-colors">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                        بازگشت
                    </a>
                    
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-[#f2f3fd] transition-colors">
                            <div class="w-8 h-8 rounded-full bg-[#005bbf] text-white flex items-center justify-center text-sm font-bold">
                                <?= mb_substr(session()->get('full_name'), 0, 1) ?>
                            </div>
                            <span class="material-symbols-outlined text-[20px] text-[#727785]">expand_more</span>
                        </button>
                        <div id="dropdown-menu" class="hidden absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-[#e0e2ec] overflow-hidden">
                            <div class="px-4 py-3 border-b border-[#e0e2ec]">
                                <p class="text-sm font-bold text-[#191c23]"><?= session()->get('full_name') ?></p>
                                <p class="text-xs text-[#727785]"><?= session()->get('phone') ?></p>
                            </div>
                            <a href="/profile" class="flex items-center gap-3 px-4 py-3 text-sm text-[#414754] hover:bg-[#f2f3fd] transition-colors">
                                <span class="material-symbols-outlined text-[20px]">person</span> پروفایل
                            </a>
                            <a href="/dashboard/history" class="flex items-center gap-3 px-4 py-3 text-sm text-[#005bbf] bg-[#f2f3fd] transition-colors">
                                <span class="material-symbols-outlined text-[20px]">history</span> تاریخچه
                            </a>
                            <hr class="border-[#e0e2ec]">
                            <a href="/auth/logout" class="flex items-center gap-3 px-4 py-3 text-sm text-[#ba1a1a] hover:bg-[#fff5f5] transition-colors">
                                <span class="material-symbols-outlined text-[20px]">logout</span> خروج
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ============================================ -->
    <!-- MAIN CONTENT -->
    <!-- ============================================ -->
    <main class="max-w-[1200px] mx-auto px-4 md:px-6 py-6 md:py-8">
        
        <!-- Header -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-[#191c23]">
                        📋 تاریخچه تبدیل‌ها
                    </h1>
                    <p class="text-sm text-[#727785] mt-1">
                        لیست تمام فایل‌های PDF که تاکنون تبدیل کرده‌اید.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="refreshHistory()" class="flex items-center gap-2 px-4 py-2 bg-white border border-[#e0e2ec] rounded-xl text-sm text-[#414754] hover:bg-[#f2f3fd] transition-colors">
                        <span class="material-symbols-outlined text-[18px]">refresh</span>
                        بروزرسانی
                    </button>
                    <a href="/dashboard" class="flex items-center gap-2 px-4 py-2 bg-[#005bbf] text-white rounded-xl text-sm font-bold hover:bg-[#004493] transition-colors shadow-md hover:shadow-lg">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        تبدیل جدید
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-sm border border-[#e0e2ec]">
                <div class="text-[#727785] text-sm">کل تبدیل‌ها</div>
                <div class="text-2xl font-bold text-[#191c23]" id="total-count">0</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-[#e0e2ec]">
                <div class="text-[#727785] text-sm">موفق</div>
                <div class="text-2xl font-bold text-[#006e2c]" id="completed-count">0</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-[#e0e2ec]">
                <div class="text-[#727785] text-sm">دانلودها</div>
                <div class="text-2xl font-bold text-[#005bbf]" id="downloads-count">0</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-[#e0e2ec]">
                <div class="text-[#727785] text-sm">امروز</div>
                <div class="text-2xl font-bold text-[#191c23]" id="today-count">0</div>
            </div>
        </div>

        <!-- History List -->
        <?php if (empty($conversions)): ?>
            <!-- Empty State -->
            <div class="bg-white rounded-xl p-12 text-center border border-[#e0e2ec]">
                <span class="material-symbols-outlined text-[64px] text-[#e0e2ec]">history</span>
                <p class="text-[#727785] mt-2">هیچ تبدیلی انجام نشده است</p>
                <p class="text-sm text-[#727785]">اولین PDF خود را با کلیک روی دکمه تبدیل جدید بسازید.</p>
                <a href="/dashboard" class="inline-flex items-center gap-2 mt-4 px-6 py-3 bg-[#005bbf] text-white rounded-xl font-bold hover:bg-[#004493] transition-colors shadow-md hover:shadow-lg">
                    <span class="material-symbols-outlined">add</span>
                    شروع تبدیل
                </a>
            </div>
        <?php else: ?>
            <!-- History Cards -->
            <div class="space-y-4">
                <?php foreach ($conversions as $conversion): ?>
                    <div class="history-card bg-white rounded-xl p-4 md:p-6 border border-[#e0e2ec] shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <!-- Left -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-[#f2f3fd] flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-[28px] text-[#005bbf]">description</span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-bold text-[#191c23] truncate max-w-[200px] md:max-w-[300px]">
                                        <?= esc($conversion->pdf_file_name) ?>
                                    </h4>
                                    <div class="flex flex-wrap items-center gap-2 mt-1">
                                        <!-- ============================================ -->
                                        <!-- ✅ وضعیت به صورت دستی -->
                                        <!-- ============================================ -->
                                        <span class="status-badge status-<?= $conversion->status ?>">
                                            <?php if ($conversion->status === 'completed'): ?>
                                                <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                                موفق
                                            <?php elseif ($conversion->status === 'pending'): ?>
                                                <span class="material-symbols-outlined text-[14px]">pending</span>
                                                در انتظار
                                            <?php elseif ($conversion->status === 'processing'): ?>
                                                <span class="material-symbols-outlined text-[14px] animate-spin">sync</span>
                                                در حال پردازش
                                            <?php elseif ($conversion->status === 'failed'): ?>
                                                <span class="material-symbols-outlined text-[14px]">error</span>
                                                ناموفق
                                            <?php else: ?>
                                                <span class="material-symbols-outlined text-[14px]">delete</span>
                                                حذف شده
                                            <?php endif; ?>
                                        </span>
                                        <span class="text-xs text-[#727785]">
                                            <?= $conversion->original_images_count ?> تصویر
                                        </span>
                                        <span class="text-xs text-[#727785]">
                                            <?= number_format($conversion->pdf_file_size, 2) ?> MB
                                        </span>
                                        <?php if ($conversion->is_compressed): ?>
                                            <span class="text-xs text-[#727785] bg-[#f2f3fd] px-2 py-0.5 rounded">فشرده</span>
                                        <?php endif; ?>
                                        <?php if ($conversion->has_page_numbers): ?>
                                            <span class="text-xs text-[#727785] bg-[#f2f3fd] px-2 py-0.5 rounded">شماره صفحات</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Right -->
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="text-xs text-[#727785] whitespace-nowrap">
                                    <?= date('Y/m/d H:i', strtotime($conversion->created_at)) ?>
                                </span>
                                
                                <?php if ($conversion->status === 'completed'): ?>
                                    <?php 
                                        $isExpired = strtotime($conversion->expires_at) < time();
                                    ?>
                                    <?php if (!$isExpired): ?>
                                        <a href="/dashboard/download/<?= $conversion->id ?>" 
                                           class="flex items-center gap-1 px-4 py-2 bg-[#005bbf] text-white text-sm font-bold rounded-xl hover:bg-[#004493] transition-colors shadow-sm hover:shadow-md">
                                            <span class="material-symbols-outlined text-[18px]">download</span>
                                            دانلود
                                        </a>
                                    <?php else: ?>
                                        <span class="flex items-center gap-1 px-4 py-2 bg-[#f3f4f6] text-[#727785] text-sm rounded-xl">
                                            <span class="material-symbols-outlined text-[18px]">block</span>
                                            منقضی
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php if ($conversion->status === 'processing'): ?>
                                    <span class="flex items-center gap-1 px-4 py-2 bg-[#dbeafe] text-[#1e40af] text-sm rounded-xl">
                                        <span class="material-symbols-outlined text-[18px] animate-spin">sync</span>
                                        در حال پردازش
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($conversion->status === 'failed'): ?>
                                    <span class="flex items-center gap-1 px-4 py-2 bg-[#fee2e2] text-[#991b1b] text-sm rounded-xl">
                                        <span class="material-symbols-outlined text-[18px]">error</span>
                                        ناموفق
                                    </span>
                                <?php endif; ?>

                                <button onclick="deleteConversion(<?= $conversion->id ?>)" 
                                        class="p-2 text-[#727785] hover:text-[#ba1a1a] transition-colors rounded-lg hover:bg-[#fff5f5]">
                                    <span class="material-symbols-outlined text-[18px]">delete_outline</span>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- ============================================ -->
    <!-- TOAST CONTAINER -->
    <!-- ============================================ -->
    <div id="toast-container"></div>

    <!-- ============================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================ -->
    <script>
        // =============================================
        // DROPDOWN
        // =============================================
        function toggleDropdown() {
            document.getElementById('dropdown-menu').classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('dropdown-menu');
            const btn = e.target.closest('[onclick="toggleDropdown()"]');
            if (!btn && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        // =============================================
        // REFRESH HISTORY
        // =============================================
        function refreshHistory() {
            showToast('در حال بروزرسانی...', 'info');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }

        // =============================================
        // DELETE CONVERSION
        // =============================================
        function deleteConversion(id) {
            if (!confirm('آیا از حذف این تبدیل مطمئن هستید؟')) return;
            
            fetch('/dashboard/delete-conversion/' + id, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('خطا در ارتباط با سرور', 'error');
                console.error('Error:', error);
            });
        }

        // =============================================
        // TOAST
        // =============================================
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            const icons = { success: 'check_circle', error: 'error', info: 'info', warning: 'warning' };
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

        // =============================================
        // LOAD STATS
        // =============================================
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/dashboard/stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('total-count').textContent = data.total_conversions || 0;
                        document.getElementById('completed-count').textContent = data.completed || 0;
                        document.getElementById('downloads-count').textContent = data.total_downloads || 0;
                        document.getElementById('today-count').textContent = data.today_conversions || 0;
                    }
                })
                .catch(error => console.error('Error loading stats:', error));
        });

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