<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnapPDF | تبدیل سریع تصاویر به PDF</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;400;700;900&display=swap');
        
        * {
            font-family: 'Vazirmatn', sans-serif;
        }

        body {
            scroll-behavior: smooth;
            direction: rtl;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(224, 226, 236, 0.5);
        }

        .hero-pattern {
            background-image: radial-gradient(#005bbf 0.8px, transparent 0.8px);
            background-size: 32px 32px;
            opacity: 0.06;
        }

        .gradient-text {
            background: linear-gradient(135deg, #005bbf 0%, #1a73e8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .animate-pulse-slow {
            animation: pulseSlow 3s ease-in-out infinite;
        }

        @keyframes pulseSlow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 91, 191, 0.12);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e0e2ec;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #c1c6d6;
        }
    </style>
</head>

<body class="bg-[#f9f9ff] text-[#191c23] antialiased">

    <!-- ============================================ -->
    <!-- HEADER / NAVBAR -->
    <!-- ============================================ -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-[#e0e2ec]/50">
        <div class="max-w-[1200px] mx-auto px-4 md:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[72px] md:h-[80px]">
                
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[40px] text-[#005bbf]">picture_as_pdf</span>
                    <span class="text-2xl font-bold text-[#005bbf] tracking-tight">SnapPDF</span>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-sm font-medium text-[#414754] hover:text-[#005bbf] transition-colors duration-200">ویژگی‌ها</a>
                    <a href="#how-it-works" class="text-sm font-medium text-[#414754] hover:text-[#005bbf] transition-colors duration-200">راهنما</a>
                    <a href="#pricing" class="text-sm font-medium text-[#414754] hover:text-[#005bbf] transition-colors duration-200">تعرفه‌ها</a>
                    <a href="#faq" class="text-sm font-medium text-[#414754] hover:text-[#005bbf] transition-colors duration-200">سوالات</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3">
                    <?php if (session()->get('is_logged_in')): ?>
                        <!-- کاربر وارد شده -->
                        <a href="dashboard" class="flex items-center gap-2 px-5 py-2.5 bg-[#1a73e8] text-white text-sm font-bold rounded-xl hover:bg-[#005bbf] transition-all duration-200 shadow-md hover:shadow-lg active:scale-95">
                            <span class="material-symbols-outlined text-[18px]">dashboard</span>
                            داشبورد
                        </a>
                        <a href="auth/logout" class="px-5 py-2.5 border border-[#e0e2ec] text-[#414754] text-sm font-medium rounded-xl hover:bg-[#f2f3fd] transition-all duration-200 active:scale-95">
                            خروج
                        </a>
                    <?php else: ?>
                        <!-- کاربر مهمان -->
                        <a href="auth/login" class="hidden md:block px-5 py-2.5 text-sm font-medium text-[#414754] hover:text-[#005bbf] transition-colors duration-200">
                            ورود
                        </a>
                        <a href="auth/register" class="px-5 py-2.5 bg-[#1a73e8] text-white text-sm font-bold rounded-xl hover:bg-[#005bbf] transition-all duration-200 shadow-md hover:shadow-lg active:scale-95">
                            <span class="md:hidden">عضویت</span>
                            <span class="hidden md:inline">شروع رایگان</span>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-[#f2f3fd] transition-colors">
                    <span class="material-symbols-outlined text-[#191c23]">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-[#e0e2ec] shadow-lg">
            <div class="max-w-[1200px] mx-auto px-4 py-4 space-y-3">
                <a href="#features" class="block py-2 text-sm font-medium text-[#414754] hover:text-[#005bbf] transition-colors">ویژگی‌ها</a>
                <a href="#how-it-works" class="block py-2 text-sm font-medium text-[#414754] hover:text-[#005bbf] transition-colors">راهنما</a>
                <a href="#pricing" class="block py-2 text-sm font-medium text-[#414754] hover:text-[#005bbf] transition-colors">تعرفه‌ها</a>
                <a href="#faq" class="block py-2 text-sm font-medium text-[#414754] hover:text-[#005bbf] transition-colors">سوالات</a>
                <?php if (!session()->get('is_logged_in')): ?>
                    <a href="auth/login" class="block py-2 text-sm font-medium text-[#005bbf]">ورود</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- ============================================ -->
    <!-- HERO SECTION -->
    <!-- ============================================ -->
    <section class="relative pt-[120px] md:pt-[140px] pb-16 md:pb-24 overflow-hidden">
        <!-- Background Pattern -->
        <div class="hero-pattern absolute inset-0 -z-10"></div>
        
        <!-- Gradient Blobs -->
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-[#005bbf]/5 rounded-full blur-3xl -z-10"></div>
        <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] bg-[#1a73e8]/5 rounded-full blur-3xl -z-10"></div>

        <div class="max-w-[1200px] mx-auto px-4 md:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                
                <!-- Hero Content -->
                <div class="order-2 lg:order-1 text-center lg:text-right">
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#d8e2ff] text-[#004493] rounded-full text-sm font-bold mb-6">
                        <span class="material-symbols-outlined text-[18px]">bolt</span>
                        پردازش ابری &bull; کاملاً رایگان
                    </div>

                    <!-- Title -->
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-4">
                        تبدیل سریع تصاویر به
                        <span class="gradient-text">PDF</span>
                    </h1>

                    <!-- Description -->
                    <p class="text-base md:text-lg text-[#414754] leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0">
                        بدون نیاز به نصب نرم‌افزار، تصاویر خود را با حفظ کیفیت به یک فایل PDF حرفه‌ای تبدیل کنید.
                        امن، سریع و کاملاً رایگان.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <?php if (session()->get('is_logged_in')): ?>
                            <a href="/dashboard" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#005bbf] text-white text-lg font-bold rounded-2xl hover:bg-[#004493] transition-all duration-300 shadow-lg hover:shadow-xl active:scale-95">
                                <span class="material-symbols-outlined">dashboard</span>
                                ورود به داشبورد
                            </a>
                        <?php else: ?>
                            <a href="/auth/register" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#005bbf] text-white text-lg font-bold rounded-2xl hover:bg-[#004493] transition-all duration-300 shadow-lg hover:shadow-xl active:scale-95">
                                <span class="material-symbols-outlined">rocket_launch</span>
                                شروع رایگان
                            </a>
                        <?php endif; ?>
                        
                        <a href="#how-it-works" class="inline-flex items-center justify-center gap-2 px-8 py-4 border-2 border-[#e0e2ec] text-[#191c23] text-lg font-bold rounded-2xl hover:bg-[#f2f3fd] transition-all duration-300 active:scale-95">
                            <span class="material-symbols-outlined">play_circle</span>
                            مشاهده دمو
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="flex flex-wrap gap-8 justify-center lg:justify-start mt-10 pt-8 border-t border-[#e0e2ec]">
                        <div>
                            <div class="text-2xl font-bold text-[#005bbf]">۵۰K+</div>
                            <div class="text-sm text-[#727785]">کاربر فعال</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-[#005bbf]">۱۲۰K</div>
                            <div class="text-sm text-[#727785]">PDF تولید شده</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-[#005bbf]">۹۸%</div>
                            <div class="text-sm text-[#727785]">رضایت کاربران</div>
                        </div>
                    </div>
                </div>

                <!-- Hero Image / Demo Card -->
                <!-- <div class="order-1 lg:order-2 relative flex justify-center"> -->
                    <!-- Floating Animation -->
                    <!-- <div class="absolute inset-0 bg-[#005bbf]/10 blur-[100px] rounded-full animate-pulse-slow"></div>
                    
                    <div class="relative glass-card rounded-3xl shadow-2xl p-4 w-full max-w-[480px] animate-float">
                        <div class="bg-white rounded-2xl border-2 border-dashed border-[#005bbf]/30 p-8 md:p-12 flex flex-col items-center justify-center text-center gap-4 min-h-[320px]">
                            <div class="w-20 h-20 bg-[#d8e2ff] rounded-2xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-[48px] text-[#005bbf]">upload_file</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-[#191c23]">تصاویر را اینجا بکشید</h3>
                                <p class="text-sm text-[#727785] mt-1">یا برای انتخاب فایل کلیک کنید</p>
                            </div>
                            <div class="flex flex-wrap gap-2 justify-center">
                                <span class="px-3 py-1 bg-[#f2f3fd] text-[#414754] text-xs rounded-full">JPG</span>
                                <span class="px-3 py-1 bg-[#f2f3fd] text-[#414754] text-xs rounded-full">PNG</span>
                                <span class="px-3 py-1 bg-[#f2f3fd] text-[#414754] text-xs rounded-full">WEBP</span>
                                <span class="px-3 py-1 bg-[#f2f3fd] text-[#414754] text-xs rounded-full">BMP</span>
                            </div>
                            <button class="w-full py-3 bg-[#005bbf] text-white text-sm font-bold rounded-xl hover:bg-[#004493] transition-all active:scale-95 shadow-md">
                                شروع تبدیل
                            </button>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- TRUST BADGES SECTION -->
    <!-- ============================================ -->
    <section class="py-12 bg-[#f2f3fd]/50 border-y border-[#e0e2ec]">
        <div class="max-w-[1200px] mx-auto px-4 md:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                <div class="flex flex-col items-center text-center">
                    <span class="material-symbols-outlined text-[36px] text-[#005bbf]">security</span>
                    <span class="text-sm font-bold mt-2">امنیت بالا</span>
                    <span class="text-xs text-[#727785]">حذف خودکار فایل‌ها</span>
                </div>
                <div class="flex flex-col items-center text-center">
                    <span class="material-symbols-outlined text-[36px] text-[#005bbf]">speed</span>
                    <span class="text-sm font-bold mt-2">سرعت بالا</span>
                    <span class="text-xs text-[#727785]">تبدیل در چند ثانیه</span>
                </div>
                <div class="flex flex-col items-center text-center">
                    <span class="material-symbols-outlined text-[36px] text-[#005bbf]">devices</span>
                    <span class="text-sm font-bold mt-2">همه‌جا در دسترس</span>
                    <span class="text-xs text-[#727785]">موبایل و دسکتاپ</span>
                </div>
                <div class="flex flex-col items-center text-center">
                    <span class="material-symbols-outlined text-[36px] text-[#005bbf]">workspace_premium</span>
                    <span class="text-sm font-bold mt-2">کاملاً رایگان</span>
                    <span class="text-xs text-[#727785]">بدون واترمارک</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- FEATURES SECTION -->
    <!-- ============================================ -->
    <section id="features" class="py-20 md:py-28">
        <div class="max-w-[1200px] mx-auto px-4 md:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-[#d8e2ff] text-[#004493] text-sm font-bold rounded-full mb-4">✨ ویژگی‌ها</span>
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">چرا SnapPDF را انتخاب کنید؟</h2>
                <p class="text-lg text-[#414754] max-w-2xl mx-auto">بهترین ابزار آنلاین برای مدیریت و تبدیل فایل‌های تصویری</p>
            </div>

            <!-- Features Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                
                <!-- Feature 1 -->
                <div class="group bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 hover-lift border border-[#e0e2ec]/20">
                    <div class="w-14 h-14 bg-[#d8e2ff] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-[32px] text-[#005bbf]">shield_lock</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">امنیت و حریم خصوصی</h3>
                    <p class="text-[#414754] leading-relaxed">
                        حریم خصوصی شما اولویت ماست. تمامی فایل‌ها به‌صورت خودکار پس از ۲ دقیقه از سرورهای ما حذف می‌شوند.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="group bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 hover-lift border border-[#e0e2ec]/20">
                    <div class="w-14 h-14 bg-[#86f898]/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-[32px] text-[#006e2c]">bolt</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">سرعت بی‌نظیر</h3>
                    <p class="text-[#414754] leading-relaxed">
                        بدون اتلاف وقت و در چند ثانیه، خروجی باکیفیت تحویل بگیرید. موتور پردازش ابری ما همیشه آماده است.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="group bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 hover-lift border border-[#e0e2ec]/20">
                    <div class="w-14 h-14 bg-[#ffdbcb] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-[32px] text-[#9e4300]">touch_app</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">رابط کاربری ساده</h3>
                    <p class="text-[#414754] leading-relaxed">
                        نیازی به دانش فنی ندارید. فقط با کشیدن و رها کردن ساده تصاویر، فایل PDF خود را بسازید.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="group bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 hover-lift border border-[#e0e2ec]/20">
                    <div class="w-14 h-14 bg-[#d8e2ff] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-[32px] text-[#005bbf]">auto_awesome</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">کیفیت حفظ شده</h3>
                    <p class="text-[#414754] leading-relaxed">
                        تبدیل با حفظ کیفیت اصلی تصاویر. خروجی شما با بالاترین دقت ممکن تولید می‌شود.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="group bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 hover-lift border border-[#e0e2ec]/20">
                    <div class="w-14 h-14 bg-[#86f898]/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-[32px] text-[#006e2c]">drag_indicator</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">اولویت‌بندی تصاویر</h3>
                    <p class="text-[#414754] leading-relaxed">
                        ترتیب صفحات را با کشیدن و رها کردن به‌راحتی جابجا کنید. کنترل کامل بر چیدمان فایل PDF.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="group bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 hover-lift border border-[#e0e2ec]/20">
                    <div class="w-14 h-14 bg-[#ffdbcb] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-[32px] text-[#9e4300]">cloud_done</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">دسترسی از هر جا</h3>
                    <p class="text-[#414754] leading-relaxed">
                        بدون نیاز به نصب نرم‌افزار، از هر دستگاهی (موبایل، تبلت، دسکتاپ) به ابزار دسترسی دارید.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- HOW IT WORKS SECTION -->
    <!-- ============================================ -->
    <section id="how-it-works" class="py-20 md:py-28 bg-[#f2f3fd]">
        <div class="max-w-[1200px] mx-auto px-4 md:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-[#d8e2ff] text-[#004493] text-sm font-bold rounded-full mb-4">📋 راهنما</span>
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">چگونه کار می‌کند؟</h2>
                <p class="text-lg text-[#414754] max-w-2xl mx-auto">سه گام ساده تا داشتن یک فایل PDF عالی</p>
            </div>

            <!-- Steps -->
            <div class="relative">
                <!-- Connector Line (Desktop) -->
                <div class="hidden md:block absolute top-1/2 left-0 w-full h-0.5 bg-[#c1c6d6] -translate-y-1/2 z-0"></div>
                
                <div class="grid md:grid-cols-3 gap-8 md:gap-12 relative z-10">
                    
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 bg-[#005bbf] text-white rounded-full flex items-center justify-center text-2xl font-bold shadow-lg mb-6 ring-8 ring-white">۱</div>
                        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-md w-full hover:shadow-xl transition-shadow duration-300">
                            <div class="w-12 h-12 bg-[#d8e2ff] rounded-xl flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-outlined text-[28px] text-[#005bbf]">upload_file</span>
                            </div>
                            <h3 class="text-lg font-bold mb-2">آپلود تصاویر</h3>
                            <p class="text-sm text-[#414754]">تصاویر JPG، PNG یا WEBP خود را انتخاب یا بکشید.</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 bg-[#005bbf] text-white rounded-full flex items-center justify-center text-2xl font-bold shadow-lg mb-6 ring-8 ring-white">۲</div>
                        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-md w-full hover:shadow-xl transition-shadow duration-300">
                            <div class="w-12 h-12 bg-[#86f898]/30 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-outlined text-[28px] text-[#006e2c]">drag_indicator</span>
                            </div>
                            <h3 class="text-lg font-bold mb-2">چیدمان</h3>
                            <p class="text-sm text-[#414754]">ترتیب صفحات را با کشیدن و رها کردن جابجا کنید.</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 bg-[#005bbf] text-white rounded-full flex items-center justify-center text-2xl font-bold shadow-lg mb-6 ring-8 ring-white">۳</div>
                        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-md w-full hover:shadow-xl transition-shadow duration-300">
                            <div class="w-12 h-12 bg-[#ffdbcb] rounded-xl flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-outlined text-[28px] text-[#9e4300]">download</span>
                            </div>
                            <h3 class="text-lg font-bold mb-2">دانلود</h3>
                            <p class="text-sm text-[#414754]">روی دکمه تبدیل کلیک کنید و فایل را دریافت کنید.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- PRICING SECTION -->
    <!-- ============================================ -->
    <section id="pricing" class="py-20 md:py-28">
        <div class="max-w-[1200px] mx-auto px-4 md:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-[#d8e2ff] text-[#004493] text-sm font-bold rounded-full mb-4">💰 تعرفه‌ها</span>
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">انتخاب پلن مناسب</h2>
                <p class="text-lg text-[#414754] max-w-2xl mx-auto">با پلن‌های انعطاف‌پذیر ما، تبدیل تصاویر به PDF را آسانتر کنید</p>
            </div>

            <!-- Pricing Grid -->
            <div class="grid md:grid-cols-3 gap-6 md:gap-8 max-w-5xl mx-auto">
                
                <!-- Free Plan -->
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border border-[#e0e2ec]/20 hover-lift">
                    <div class="text-center">
                        <span class="inline-block px-4 py-1 bg-[#f2f3fd] text-[#414754] text-xs font-bold rounded-full mb-4">رایگان</span>
                        <div class="text-4xl font-extrabold mb-2">۰</div>
                        <div class="text-sm text-[#727785] mb-6">تومان / ماهانه</div>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-[#006e2c] text-[18px]">check_circle</span>
                            ۵ تبدیل در روز
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-[#006e2c] text-[18px]">check_circle</span>
                            ۱۰ تصویر در هر تبدیل
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-[#006e2c] text-[18px]">check_circle</span>
                            کیفیت استاندارد
                        </li>
                        <li class="flex items-center gap-3 text-sm text-[#727785]">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                            پشتیبانی اختصاصی
                        </li>
                    </ul>
                    <a href="/auth/register" class="block w-full py-3 text-center border-2 border-[#005bbf] text-[#005bbf] font-bold rounded-xl hover:bg-[#005bbf] hover:text-white transition-all duration-200">
                        شروع رایگان
                    </a>
                </div>

                <!-- Pro Plan (Highlighted) -->
                <div class="bg-[#005bbf] p-8 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 relative hover-lift scale-105 border-2 border-[#005bbf]">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#ffdbcb] text-[#9e4300] px-4 py-1 rounded-full text-xs font-bold">
                        محبوب‌ترین
                    </div>
                    <div class="text-center">
                        <span class="inline-block px-4 py-1 bg-white/20 text-white text-xs font-bold rounded-full mb-4">حرفه‌ای</span>
                        <div class="text-4xl font-extrabold text-white mb-2">۹۹</div>
                        <div class="text-sm text-white/70 mb-6">هزار تومان / ماهانه</div>
                    </div>
                    <ul class="space-y-3 mb-8 text-white">
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                            تبدیل نامحدود
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                            ۵۰ تصویر در هر تبدیل
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                            کیفیت بالا (HD)
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                            پشتیبانی ۲۴/۷
                        </li>
                    </ul>
                    <a href="/auth/register" class="block w-full py-3 text-center bg-white text-[#005bbf] font-bold rounded-xl hover:bg-[#f2f3fd] transition-all duration-200">
                        شروع کنید
                    </a>
                </div>

                <!-- Enterprise Plan -->
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border border-[#e0e2ec]/20 hover-lift">
                    <div class="text-center">
                        <span class="inline-block px-4 py-1 bg-[#f2f3fd] text-[#414754] text-xs font-bold rounded-full mb-4">سازمانی</span>
                        <div class="text-4xl font-extrabold mb-2">تماس</div>
                        <div class="text-sm text-[#727785] mb-6">با تیم فروش</div>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-[#006e2c] text-[18px]">check_circle</span>
                            تبدیل نامحدود
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-[#006e2c] text-[18px]">check_circle</span>
                            تصاویر نامحدود
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-[#006e2c] text-[18px]">check_circle</span>
                            API اختصاصی
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-outlined text-[#006e2c] text-[18px]">check_circle</span>
                            تیم پشتیبانی ویژه
                        </li>
                    </ul>
                    <a href="/auth/register" class="block w-full py-3 text-center border-2 border-[#005bbf] text-[#005bbf] font-bold rounded-xl hover:bg-[#005bbf] hover:text-white transition-all duration-200">
                        تماس بگیرید
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- FAQ SECTION -->
    <!-- ============================================ -->
    <section id="faq" class="py-20 md:py-28 bg-[#f2f3fd]">
        <div class="max-w-[1200px] mx-auto px-4 md:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-[#d8e2ff] text-[#004493] text-sm font-bold rounded-full mb-4">❓ سوالات متداول</span>
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">پاسخ به سوالات شما</h2>
                <p class="text-lg text-[#414754] max-w-2xl mx-auto">هر آنچه که باید درباره SnapPDF بدانید</p>
            </div>

            <!-- FAQ Accordion -->
            <div class="max-w-3xl mx-auto space-y-4" id="faq-container">
                
                <!-- FAQ Item 1 -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-[#e0e2ec]/20">
                    <button class="faq-btn w-full px-6 py-4 text-right flex items-center justify-between hover:bg-[#f2f3fd] transition-colors duration-200" data-target="faq-1">
                        <span class="font-bold text-[#191c23]">آیا استفاده از SnapPDF رایگان است؟</span>
                        <span class="material-symbols-outlined text-[#414754] transition-transform duration-300">expand_more</span>
                    </button>
                    <div id="faq-1" class="faq-content px-6 pb-4 hidden">
                        <p class="text-[#414754]">بله، پلن رایگان SnapPDF شامل ۵ تبدیل در روز با کیفیت استاندارد است. برای تبدیل بیشتر و کیفیت بالاتر، پلن‌های حرفه‌ای نیز موجود هستند.</p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-[#e0e2ec]/20">
                    <button class="faq-btn w-full px-6 py-4 text-right flex items-center justify-between hover:bg-[#f2f3fd] transition-colors duration-200" data-target="faq-2">
                        <span class="font-bold text-[#191c23]">چگونه فایل‌های من حذف می‌شوند؟</span>
                        <span class="material-symbols-outlined text-[#414754] transition-transform duration-300">expand_more</span>
                    </button>
                    <div id="faq-2" class="faq-content px-6 pb-4 hidden">
                        <p class="text-[#414754]">تمامی فایل‌های آپلود شده و PDF تولید شده به‌صورت خودکار پس از ۲ دقیقه از سرورهای ما حذف می‌شوند. حریم خصوصی شما برای ما بسیار مهم است.</p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-[#e0e2ec]/20">
                    <button class="faq-btn w-full px-6 py-4 text-right flex items-center justify-between hover:bg-[#f2f3fd] transition-colors duration-200" data-target="faq-3">
                        <span class="font-bold text-[#191c23]">چه فرمت‌های تصویری پشتیبانی می‌شوند؟</span>
                        <span class="material-symbols-outlined text-[#414754] transition-transform duration-300">expand_more</span>
                    </button>
                    <div id="faq-3" class="faq-content px-6 pb-4 hidden">
                        <p class="text-[#414754]">SnapPDF از فرمت‌های JPG، JPEG، PNG، WEBP، BMP و TIFF پشتیبانی می‌کند. حداکثر حجم هر فایل ۱۰ مگابایت است.</p>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-[#e0e2ec]/20">
                    <button class="faq-btn w-full px-6 py-4 text-right flex items-center justify-between hover:bg-[#f2f3fd] transition-colors duration-200" data-target="faq-4">
                        <span class="font-bold text-[#191c23]">آیا می‌توانم ترتیب تصاویر را تغییر دهم؟</span>
                        <span class="material-symbols-outlined text-[#414754] transition-transform duration-300">expand_more</span>
                    </button>
                    <div id="faq-4" class="faq-content px-6 pb-4 hidden">
                        <p class="text-[#414754]">بله، در داشبورد می‌توانید تصاویر را با کشیدن و رها کردن مرتب کنید. همچنین دکمه‌های بالا و پایین برای جابجایی آسان در دسترس هستند.</p>
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-[#e0e2ec]/20">
                    <button class="faq-btn w-full px-6 py-4 text-right flex items-center justify-between hover:bg-[#f2f3fd] transition-colors duration-200" data-target="faq-5">
                        <span class="font-bold text-[#191c23]">آیا نیاز به ثبت‌نام دارم؟</span>
                        <span class="material-symbols-outlined text-[#414754] transition-transform duration-300">expand_more</span>
                    </button>
                    <div id="faq-5" class="faq-content px-6 pb-4 hidden">
                        <p class="text-[#414754]">بله، برای استفاده از SnapPDF نیاز به ثبت‌نام با شماره تلفن دارید. این کار به ما کمک می‌کند تا خدمات بهتری به شما ارائه دهیم و فایل‌های شما را مدیریت کنیم.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- CTA SECTION -->
    <!-- ============================================ -->
    <section class="py-20 md:py-28">
        <div class="max-w-[1200px] mx-auto px-4 md:px-6 lg:px-8">
            <div class="relative overflow-hidden bg-[#191c23] rounded-[40px] p-8 md:p-16 lg:p-20 text-center">
                <!-- Abstract Blobs -->
                <div class="absolute -top-32 -left-32 w-80 h-80 bg-[#005bbf]/20 blur-[100px] rounded-full"></div>
                <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-[#006e2c]/20 blur-[100px] rounded-full"></div>
                
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-4">
                        آماده‌اید تصاویرتان را به PDF تبدیل کنید؟
                    </h2>
                    <p class="text-lg text-[#c1c6d6] max-w-2xl mx-auto mb-8">
                        همین حالا به هزاران کاربر فعال ما بپیوندید و از سریع‌ترین ابزار تبدیل تصویر به PDF استفاده کنید.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <?php if (session()->get('is_logged_in')): ?>
                            <a href="/dashboard" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#1a73e8] text-white text-lg font-bold rounded-2xl hover:bg-[#005bbf] transition-all duration-300 shadow-lg hover:shadow-xl active:scale-95">
                                <span class="material-symbols-outlined">dashboard</span>
                                ورود به داشبورد
                            </a>
                        <?php else: ?>
                            <a href="/auth/register" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#1a73e8] text-white text-lg font-bold rounded-2xl hover:bg-[#005bbf] transition-all duration-300 shadow-lg hover:shadow-xl active:scale-95">
                                <span class="material-symbols-outlined">rocket_launch</span>
                                شروع رایگان
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Trust Badges -->
                    <div class="flex flex-wrap items-center justify-center gap-6 mt-8 pt-8 border-t border-white/10">
                        <div class="flex items-center gap-2 text-sm text-[#c1c6d6]">
                            <span class="material-symbols-outlined text-[#006e2c]">check_circle</span>
                            بدون ثبت‌نام
                        </div>
                        <div class="flex items-center gap-2 text-sm text-[#c1c6d6]">
                            <span class="material-symbols-outlined text-[#006e2c]">check_circle</span>
                            بدون واترمارک
                        </div>
                        <div class="flex items-center gap-2 text-sm text-[#c1c6d6]">
                            <span class="material-symbols-outlined text-[#006e2c]">check_circle</span>
                            کاملاً رایگان
                        </div>
                        <div class="flex items-center gap-2 text-sm text-[#c1c6d6]">
                            <span class="material-symbols-outlined text-[#006e2c]">check_circle</span>
                            پشتیبانی ۲۴/۷
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- FOOTER -->
    <!-- ============================================ -->
    <footer class="bg-white border-t border-[#e0e2ec] py-12">
        <div class="max-w-[1200px] mx-auto px-4 md:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-[32px] text-[#005bbf]">picture_as_pdf</span>
                        <span class="text-xl font-bold text-[#005bbf]">SnapPDF</span>
                    </div>
                    <p class="text-sm text-[#414754] max-w-sm">
                        سریع‌ترین و امن‌ترین ابزار آنلاین برای تبدیل تصاویر به PDF.
                        با استفاده از SnapPDF، فایل‌های خود را در چند ثانیه تبدیل کنید.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-sm font-bold mb-4">دسترسی سریع</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="text-[#414754] hover:text-[#005bbf] transition-colors">ویژگی‌ها</a></li>
                        <li><a href="#how-it-works" class="text-[#414754] hover:text-[#005bbf] transition-colors">راهنما</a></li>
                        <li><a href="#pricing" class="text-[#414754] hover:text-[#005bbf] transition-colors">تعرفه‌ها</a></li>
                        <li><a href="#faq" class="text-[#414754] hover:text-[#005bbf] transition-colors">سوالات</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="text-sm font-bold mb-4">پشتیبانی</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-[#414754] hover:text-[#005bbf] transition-colors">تماس با ما</a></li>
                        <li><a href="#" class="text-[#414754] hover:text-[#005bbf] transition-colors">حریم خصوصی</a></li>
                        <li><a href="#" class="text-[#414754] hover:text-[#005bbf] transition-colors">شرایط استفاده</a></li>
                        <li><a href="#" class="text-[#414754] hover:text-[#005bbf] transition-colors">مستندات API</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-[#e0e2ec] flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-[#727785]">© ۲۰۲۴ SnapPDF. تمامی حقوق محفوظ است.</p>
                <div class="flex items-center gap-6 text-sm text-[#727785]">
                    <span>ساخته شده با ❤️ در ایران</span>
                    <span class="hidden md:inline">|</span>
                    <span>نسخه ۱.۰.۰</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- ============================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================ -->
    <script>
        // =============================================
        // Mobile Menu Toggle
        // =============================================
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu on link click
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });

        // =============================================
        // FAQ Accordion
        // =============================================
        document.querySelectorAll('.faq-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const content = document.getElementById(targetId);
                const icon = this.querySelector('.material-symbols-outlined');

                // Close other FAQs
                document.querySelectorAll('.faq-content').forEach(item => {
                    if (item.id !== targetId) {
                        item.classList.add('hidden');
                        const otherIcon = item.closest('.bg-white').querySelector('.faq-btn .material-symbols-outlined');
                        if (otherIcon) {
                            otherIcon.style.transform = 'rotate(0deg)';
                        }
                    }
                });

                // Toggle current FAQ
                content.classList.toggle('hidden');
                icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            });
        });

        // =============================================
        // Smooth Scroll for anchor links
        // =============================================
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    const headerOffset = 80;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // =============================================
        // Active Nav Link on Scroll
        // =============================================
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('nav a[href^="#"]');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                if (scrollY >= sectionTop) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('text-[#005bbf]');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('text-[#005bbf]');
                }
            });
        });

        // =============================================
        // Toast Notification for Session Messages
        // =============================================
        <?php if (session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
            document.addEventListener('DOMContentLoaded', function() {
                const message = '<?= session()->getFlashdata('success') ?? session()->getFlashdata('error') ?>';
                const type = '<?= session()->getFlashdata('success') ? 'success' : 'error' ?>';
                showToast(message, type);
            });
        <?php endif; ?>

        function showToast(message, type = 'success') {
            const colors = {
                success: 'bg-[#006e2c]',
                error: 'bg-[#ba1a1a]',
                info: 'bg-[#005bbf]'
            };

            const icons = {
                success: 'check_circle',
                error: 'error',
                info: 'info'
            };

            const toast = document.createElement('div');
            toast.className = `fixed bottom-6 right-6 z-[999] flex items-center gap-3 px-6 py-4 ${colors[type]} text-white rounded-2xl shadow-2xl transform translate-y-0 opacity-100 transition-all duration-500 max-w-sm`;
            toast.innerHTML = `
                <span class="material-symbols-outlined">${icons[type]}</span>
                <span class="text-sm font-medium">${message}</span>
                <button onclick="this.parentElement.remove()" class="mr-auto">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }
    </script>

</body>
</html>