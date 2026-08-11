<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد | SnapPDF</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <style>
        * { font-family: 'Vazirmatn', sans-serif; }
        body { direction: rtl; background: #f9f9ff; min-height: 100vh; }
        
        .material-symbols-outlined { 
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; 
            vertical-align: middle; 
        }
        
        .drop-zone {
            border: 3px dashed #e0e2ec;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: white;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .drop-zone.dragover {
            border-color: #005bbf;
            background: rgba(0, 91, 191, 0.04);
            transform: scale(1.01);
        }
        .drop-zone:hover {
            border-color: #005bbf;
            background: rgba(0, 91, 191, 0.02);
        }
        
        .image-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .image-card:hover {
            border-color: #005bbf;
            box-shadow: 0 8px 24px rgba(0, 91, 191, 0.08);
            transform: translateY(-2px);
        }
        .image-card .drag-handle {
            cursor: grab;
            color: #727785;
            transition: all 0.2s ease;
        }
        .image-card .drag-handle:hover { color: #005bbf; }
        .image-card .drag-handle:active { cursor: grabbing; }
        
        .sortable-ghost {
            opacity: 0.4;
            background: #d8e2ff;
            border: 2px dashed #005bbf;
        }
        .sortable-chosen {
            box-shadow: 0 12px 32px rgba(0, 91, 191, 0.15);
        }
        
        .progress-bar {
            height: 4px;
            background: #e0e2ec;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-bar .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #005bbf, #1a73e8);
            border-radius: 4px;
            transition: width 0.3s ease;
            width: 0%;
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
        
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            z-index: 9998;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .modal-overlay.active { display: flex; }
        .modal-content {
            background: white;
            border-radius: 24px;
            padding: 32px;
            max-width: 480px;
            width: 100%;
            animation: modalIn 0.3s ease;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e0e2ec; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #c1c6d6; }
        
        @media (max-width: 640px) {
            .drop-zone { min-height: 150px; padding: 20px; }
            .modal-content { padding: 24px; }
        }
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
                    <a href="dashboard/history" class="hidden md:flex items-center gap-1 text-sm text-[#414754] hover:text-[#005bbf] transition-colors">
                        <span class="material-symbols-outlined text-[18px]">history</span>
                        تاریخچه
                    </a>
                    
                    <span class="hidden md:inline text-sm text-[#414754]">
                        <?= session()->get('full_name') ?>
                    </span>
                    
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
                            <a href="profile" class="flex items-center gap-3 px-4 py-3 text-sm text-[#414754] hover:bg-[#f2f3fd] transition-colors">
                                <span class="material-symbols-outlined text-[20px]">person</span> پروفایل
                            </a>
                            <a href="dashboard/history" class="flex items-center gap-3 px-4 py-3 text-sm text-[#414754] hover:bg-[#f2f3fd] transition-colors">
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
        
        <!-- Welcome -->
        <div class="mb-6 md:mb-8">
            <h1 class="text-2xl md:text-3xl font-extrabold text-[#191c23]">
                👋 خوش آمدید، <?= session()->get('full_name') ?>
            </h1>
            <p class="text-sm text-[#727785] mt-1">
                تصاویر خود را آپلود کنید، مرتب کنید و به PDF تبدیل کنید.
            </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 md:mb-8">
            <div class="bg-white rounded-xl p-4 shadow-sm border border-[#e0e2ec]">
                <div class="text-[#727785] text-sm">تصاویر</div>
                <div class="text-2xl font-bold text-[#191c23]" id="stats-images">0</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-[#e0e2ec]">
                <div class="text-[#727785] text-sm">حجم کل</div>
                <div class="text-2xl font-bold text-[#191c23]" id="stats-size">0 MB</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-[#e0e2ec]">
                <div class="text-[#727785] text-sm">تبدیل‌ها</div>
                <div class="text-2xl font-bold text-[#191c23]" id="stats-conversions">0</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-[#e0e2ec]">
                <div class="text-[#727785] text-sm">امروز</div>
                <div class="text-2xl font-bold text-[#191c23]" id="stats-today">0</div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Left: Upload & Images -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Drop Zone -->
                <div class="drop-zone p-8" id="drop-zone">
                    <input type="file" id="file-input" accept="image/*" multiple hidden>
                    <span class="material-symbols-outlined text-[56px] text-[#005bbf]">cloud_upload</span>
                    <p class="text-lg font-bold text-[#191c23] mt-2">تصاویر را اینجا بکشید</p>
                    <p class="text-sm text-[#727785]">یا برای انتخاب فایل کلیک کنید</p>
                    <div class="flex flex-wrap gap-2 mt-3 justify-center">
                        <span class="px-3 py-1 bg-[#f2f3fd] text-xs rounded-full">JPG</span>
                        <span class="px-3 py-1 bg-[#f2f3fd] text-xs rounded-full">PNG</span>
                        <span class="px-3 py-1 bg-[#f2f3fd] text-xs rounded-full">WEBP</span>
                        <span class="px-3 py-1 bg-[#f2f3fd] text-xs rounded-full">BMP</span>
                        <span class="px-3 py-1 bg-[#f2f3fd] text-xs rounded-full">TIFF</span>
                    </div>
                    <p class="text-xs text-[#727785] mt-2">حداکثر حجم هر فایل: ۱۰ مگابایت | حداکثر ۵۰ تصویر</p>
                </div>

                <!-- Progress -->
                <div class="progress-bar hidden" id="progress-bar">
                    <div class="progress-fill" id="progress-fill"></div>
                </div>

                <!-- Image List -->
                <div id="image-list-container" class="hidden">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-bold text-[#191c23]">
                            تصاویر شما
                            <span class="text-sm font-normal text-[#727785]" id="image-count">(۰)</span>
                        </h3>
                        <div class="flex items-center gap-3">
                            <button onclick="undoOrder()" class="text-sm text-[#727785] hover:text-[#005bbf] transition-colors flex items-center gap-1" title="Ctrl+Z">
                                <span class="material-symbols-outlined text-[18px]">undo</span>
                                بازگردانی
                            </button>
                            <button onclick="clearAllImages()" class="text-sm text-[#ba1a1a] hover:underline font-medium flex items-center gap-1">
                                <span class="material-symbols-outlined text-[18px]">delete_sweep</span>
                                حذف همه
                            </button>
                        </div>
                    </div>
                    <div class="space-y-3 max-h-[500px] overflow-y-auto custom-scrollbar pr-1" id="image-list">
                    </div>
                </div>

                <!-- Empty State -->
                <div id="empty-state" class="bg-white rounded-xl p-12 text-center border border-[#e0e2ec]">
                    <span class="material-symbols-outlined text-[64px] text-[#e0e2ec]">image</span>
                    <p class="text-[#727785] mt-2">هیچ تصویری آپلود نشده است</p>
                    <p class="text-sm text-[#727785]">تصاویر خود را بکشید یا کلیک کنید</p>
                </div>
            </div>

            <!-- Right: Settings -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-[#e0e2ec] sticky top-24">
                    <h3 class="text-lg font-bold text-[#191c23] mb-4">⚙️ تنظیمات PDF</h3>
                    
                    <div class="space-y-4">
                        <!-- File Name -->
                        <div>
                            <label class="block text-sm font-medium text-[#414754] mb-1.5">
                                نام فایل
                            </label>
                            <input type="text" id="pdf-name" value="SnapPDF_Export" 
                                   class="w-full px-4 py-2.5 bg-[#f2f3fd] border-2 border-[#e0e2ec] rounded-xl focus:border-[#005bbf] focus:ring-2 focus:ring-[#005bbf]/20 outline-none transition-all">
                        </div>

                        <!-- Options -->
                        <div>
                            <label class="block text-sm font-medium text-[#414754] mb-2">
                                گزینه‌ها
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" id="compress" checked
                                           class="rounded border-[#e0e2ec] text-[#005bbf] focus:ring-[#005bbf]">
                                    <span class="text-sm text-[#414754] group-hover:text-[#005bbf] transition-colors">فشرده‌سازی تصاویر</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" id="page-numbers"
                                           class="rounded border-[#e0e2ec] text-[#005bbf] focus:ring-[#005bbf]">
                                    <span class="text-sm text-[#414754] group-hover:text-[#005bbf] transition-colors">شماره صفحات</span>
                                </label>
                            </div>
                        </div>

                        <!-- Total Size -->
                        <div class="flex justify-between items-center py-3 border-t border-[#e0e2ec]">
                            <span class="text-sm text-[#727785]">حجم کل</span>
                            <span class="text-sm font-bold text-[#191c23]" id="total-size">۰ MB</span>
                        </div>

                        <!-- Convert Button -->
                        <button onclick="convertToPdf()" id="convert-btn" disabled
                                class="w-full py-3.5 bg-[#005bbf] text-white font-bold rounded-xl hover:bg-[#004493] transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">picture_as_pdf</span>
                            تبدیل به PDF
                        </button>

                        <!-- Shortcuts -->
                        <div class="text-center text-xs text-[#727785] border-t border-[#e0e2ec] pt-3">
                            <span class="inline-flex items-center gap-1 mx-2">
                                <kbd class="px-2 py-0.5 bg-[#f2f3fd] rounded text-[10px] font-bold">Ctrl</kbd>
                                <span>+</span>
                                <kbd class="px-2 py-0.5 bg-[#f2f3fd] rounded text-[10px] font-bold">Z</kbd>
                                <span>بازگردانی</span>
                            </span>
                            <span class="inline-flex items-center gap-1 mx-2">
                                <kbd class="px-2 py-0.5 bg-[#f2f3fd] rounded text-[10px] font-bold">Ctrl</kbd>
                                <span>+</span>
                                <kbd class="px-2 py-0.5 bg-[#f2f3fd] rounded text-[10px] font-bold">Y</kbd>
                                <span>جلو رفتن</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        // BASE_URL
        // =============================================
        const BASE_URL = '<?= base_url() ?>';
        console.log('📍 Base URL:', BASE_URL);

        // =============================================
        // STATE
        // =============================================
        let images = [];
        let isConverting = false;
        
        // تاریخچه برای Undo/Redo
        let history = [];
        let historyIndex = -1;
        const MAX_HISTORY = 50;

        // =============================================
        // DOM ELEMENTS
        // =============================================
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        const imageList = document.getElementById('image-list');
        const imageListContainer = document.getElementById('image-list-container');
        const emptyState = document.getElementById('empty-state');
        const imageCount = document.getElementById('image-count');
        const totalSize = document.getElementById('total-size');
        const convertBtn = document.getElementById('convert-btn');
        const progressBar = document.getElementById('progress-bar');
        const progressFill = document.getElementById('progress-fill');

        // =============================================
        // SAVE STATE FOR UNDO/REDO
        // =============================================
        function saveState() {
            const currentState = images.map(img => img.id);
            if (history.length > 0 && history[historyIndex] === JSON.stringify(currentState)) {
                return;
            }
            history = history.slice(0, historyIndex + 1);
            history.push(JSON.stringify(currentState));
            historyIndex++;
            if (history.length > MAX_HISTORY) {
                history.shift();
                historyIndex--;
            }
        }

        function undoOrder() {
            if (historyIndex > 0) {
                historyIndex--;
                applyState(JSON.parse(history[historyIndex]));
                showToast('🔙 تغییرات بازگردانی شد (Ctrl+Z)', 'info');
            } else {
                showToast('⚠️ هیچ تغییری برای بازگردانی وجود ندارد', 'warning');
            }
        }

        function redoOrder() {
            if (historyIndex < history.length - 1) {
                historyIndex++;
                applyState(JSON.parse(history[historyIndex]));
                showToast('🔜 تغییرات دوباره اعمال شد (Ctrl+Y)', 'info');
            } else {
                showToast('⚠️ هیچ تغییری برای اعمال مجدد وجود ندارد', 'warning');
            }
        }

        function applyState(state) {
            const reordered = [];
            state.forEach(id => {
                const img = images.find(i => i.id === id);
                if (img) reordered.push(img);
            });
            images = reordered;
            renderImages();
            updateStats();
        }

        // =============================================
        // KEYBOARD SHORTCUTS
        // =============================================
        document.addEventListener('keydown', function(e) {
            // Ctrl+Z = Undo
            if ((e.ctrlKey || e.metaKey) && e.key === 'z' && !e.shiftKey) {
                e.preventDefault();
                undoOrder();
            }
            // Ctrl+Y = Redo
            if ((e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.key === 'z' && e.shiftKey))) {
                e.preventDefault();
                redoOrder();
            }
        });

        // =============================================
        // DROP ZONE
        // =============================================
        dropZone.addEventListener('click', () => fileInput.click());
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
            fileInput.value = '';
        });

        // =============================================
        // HANDLE FILES
        // =============================================
        function handleFiles(files) {
            const validFiles = Array.from(files).filter(file => {
                const type = file.type.startsWith('image/');
                const size = file.size <= 10 * 1024 * 1024;
                if (!type) showToast('فرمت فایل پشتیبانی نمی‌شود', 'error');
                if (!size) showToast('حجم فایل بیشتر از ۱۰ مگابایت است', 'error');
                return type && size;
            });

            if (validFiles.length === 0) return;
            if (images.length + validFiles.length > 50) {
                showToast('حداکثر ۵۰ تصویر مجاز است', 'error');
                return;
            }

            progressBar.classList.remove('hidden');
            let loaded = 0;
            const total = validFiles.length;

            validFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    images.push({
                        id: 'img_' + Date.now() + '_' + index,
                        name: file.name,
                        size: (file.size / 1024 / 1024).toFixed(2),
                        data: e.target.result,
                        file: file
                    });
                    loaded++;
                    const progress = (loaded / total) * 100;
                    progressFill.style.width = progress + '%';
                    if (loaded === total) {
                        setTimeout(() => {
                            progressBar.classList.add('hidden');
                            progressFill.style.width = '0%';
                            saveState();
                            renderImages();
                            updateStats();
                        }, 500);
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        // =============================================
        // RENDER IMAGES
        // =============================================
        function renderImages() {
            if (images.length === 0) {
                imageListContainer.classList.add('hidden');
                emptyState.classList.remove('hidden');
                convertBtn.disabled = true;
                return;
            }

            imageListContainer.classList.remove('hidden');
            emptyState.classList.add('hidden');
            convertBtn.disabled = false;

            imageList.innerHTML = '';
            images.forEach((img, index) => {
                const card = document.createElement('div');
                card.className = 'image-card bg-white rounded-xl p-3 flex items-center gap-3 border border-[#e0e2ec]';
                card.setAttribute('data-id', img.id);
                card.innerHTML = `
                    <div class="drag-handle"><span class="material-symbols-outlined">drag_indicator</span></div>
                    <div class="w-14 h-14 rounded-lg bg-[#f2f3fd] overflow-hidden flex-shrink-0">
                        <img src="${img.data}" alt="${img.name}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-[#191c23] truncate">${img.name}</p>
                        <p class="text-xs text-[#727785]">${img.size} MB</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <button onclick="moveImage('${img.id}', -1)" class="p-1 rounded hover:bg-[#f2f3fd] transition-colors">
                            <span class="material-symbols-outlined text-[18px]">arrow_upward</span>
                        </button>
                        <button onclick="moveImage('${img.id}', 1)" class="p-1 rounded hover:bg-[#f2f3fd] transition-colors">
                            <span class="material-symbols-outlined text-[18px]">arrow_downward</span>
                        </button>
                        <button onclick="removeImage('${img.id}')" class="p-1 rounded hover:bg-[#fff5f5] transition-colors">
                            <span class="material-symbols-outlined text-[18px] text-[#ba1a1a]">close</span>
                        </button>
                    </div>
                `;
                imageList.appendChild(card);
            });

            // Sortable
            new Sortable(imageList, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function() {
                    const items = imageList.querySelectorAll('.image-card');
                    const newOrder = [];
                    items.forEach(item => {
                        const id = item.getAttribute('data-id');
                        const img = images.find(i => i.id === id);
                        if (img) newOrder.push(img);
                    });
                    images = newOrder;
                    saveState();
                    updateStats();
                }
            });

            imageCount.textContent = `(${images.length})`;
            updateStats();
        }

        // =============================================
        // IMAGE OPERATIONS
        // =============================================
        function removeImage(id) {
            images = images.filter(img => img.id !== id);
            saveState();
            renderImages();
            updateStats();
            showToast('تصویر حذف شد', 'info');
        }

        function moveImage(id, direction) {
            const index = images.findIndex(img => img.id === id);
            if (index === -1) return;
            const newIndex = index + direction;
            if (newIndex < 0 || newIndex >= images.length) return;
            const temp = images[index];
            images[index] = images[newIndex];
            images[newIndex] = temp;
            saveState();
            renderImages();
        }

        function clearAllImages() {
            if (images.length === 0) return;
            if (!confirm('آیا از حذف همه تصاویر مطمئن هستید؟')) return;
            images = [];
            saveState();
            renderImages();
            updateStats();
            showToast('همه تصاویر حذف شدند', 'info');
        }

        // =============================================
        // UPDATE STATS
        // =============================================
        function updateStats() {
            const count = images.length;
            const size = images.reduce((sum, img) => sum + parseFloat(img.size), 0);
            document.getElementById('stats-images').textContent = count;
            totalSize.textContent = size.toFixed(2) + ' MB';
        }

        // =============================================
        // CONVERT TO PDF
        // =============================================
        function convertToPdf() {
            if (images.length === 0) {
                showToast('هیچ تصویری برای تبدیل وجود ندارد', 'error');
                return;
            }
            if (isConverting) return;

            isConverting = true;
            convertBtn.disabled = true;
            convertBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> در حال تبدیل...';

            const pdfName = document.getElementById('pdf-name').value.trim() || 'SnapPDF_Export';
            const compress = document.getElementById('compress').checked;
            const pageNumbers = document.getElementById('page-numbers').checked;

            const formData = new FormData();
            formData.append('pdf_name', pdfName);
            formData.append('compress', compress ? '1' : '0');
            formData.append('page_numbers', pageNumbers ? '1' : '0');

            images.forEach((img, index) => {
                try {
                    const blob = dataURLtoBlob(img.data);
                    formData.append('images[]', blob, img.name);
                } catch (error) {
                    console.error('❌ Error converting image:', error);
                }
            });

            const url = BASE_URL + 'dashboard/convert';
            console.log('📤 Sending to:', url);

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('📥 Response Status:', response.status);
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'HTTP error! status: ' + response.status);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('✅ Response Data:', data);
                if (data.success) {
                    showToast(data.message, 'success');
                    // هدایت به صفحه PDF Ready
                    setTimeout(() => {
                        window.location.href = data.redirect || '/dashboard';
                    }, 1000);
                } else {
                    showToast(data.message || 'خطا در تبدیل', 'error');
                }
            })
            .catch(error => {
                console.error('❌ Fetch Error:', error);
                showToast('خطا در ارتباط با سرور: ' + error.message, 'error');
            })
            .finally(() => {
                isConverting = false;
                convertBtn.disabled = false;
                convertBtn.innerHTML = '<span class="material-symbols-outlined">picture_as_pdf</span> تبدیل به PDF';
            });
        }

        function dataURLtoBlob(dataURL) {
            try {
                const arr = dataURL.split(',');
                const mime = arr[0].match(/:(.*?);/)[1];
                const bstr = atob(arr[1]);
                let n = bstr.length;
                const u8arr = new Uint8Array(n);
                while (n--) u8arr[n] = bstr.charCodeAt(n);
                return new Blob([u8arr], { type: mime });
            } catch (error) {
                console.error('❌ dataURLtoBlob error:', error);
                throw error;
            }
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
        // DROPDOWN
        // =============================================
        function toggleDropdown() {
            document.getElementById('dropdown-menu').classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('dropdown-menu');
            const btn = e.target.closest('[onclick="toggleDropdown()"]');
            if (!btn && !menu.contains(e.target)) menu.classList.add('hidden');
        });

        // =============================================
        // LOAD STATS
        // =============================================
        document.addEventListener('DOMContentLoaded', function() {
            const statsUrl = BASE_URL + 'dashboard/stats';
            console.log('📊 Loading stats from:', statsUrl);
            
            fetch(statsUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('HTTP error! status: ' + response.status);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('stats-conversions').textContent = data.total_conversions || 0;
                    document.getElementById('stats-today').textContent = data.today_conversions || 0;
                }
            })
            .catch(error => {
                console.error('Error loading stats:', error);
            });
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