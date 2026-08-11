<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OtpLogModel;
use App\Models\FailedAttemptsModel;
use App\Libraries\SmsService;

class Auth extends BaseController
{
    protected UserModel $userModel;
    protected OtpLogModel $otpLogModel;
    protected FailedAttemptsModel $failedAttemptsModel;
    protected SmsService $smsService;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->otpLogModel = new OtpLogModel();
        $this->failedAttemptsModel = new FailedAttemptsModel();
        $this->smsService = new SmsService();
        $this->session = session();
    }

    // =============================================
    // نمایش صفحات
    // =============================================

    /**
     * نمایش صفحه ورود
     */
    public function login()
    {
        if ($this->session->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    /**
     * نمایش صفحه ثبت‌نام
     */
    public function register()
    {
        if ($this->session->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/register');
    }

    /**
     * نمایش صفحه بازیابی رمز عبور
     */
    public function resetPassword()
    {
        if ($this->session->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/reset_password');
    }

    /**
     * نمایش صفحه تأیید شماره تلفن
     */
    public function verifyPhone()
    {
        $phone = $this->session->get('otp_phone');
        $type = $this->session->get('otp_type');

        if (empty($phone) || empty($type)) {
            return redirect()->to('/auth/register')->with('error', 'لطفاً ابتدا شماره تلفن خود را وارد کنید.');
        }

        // اگر کد وجود ندارد، ارسال کن
        $activeCode = $this->otpLogModel->getActiveCode($phone);
        if (!$activeCode) {
            $this->otpLogModel->generateAndSendCode($phone, $type);
        }

        return view('auth/verify_phone', [
            'phone' => '0' . $phone,
            'type' => $type
        ]);
    }

    // =============================================
    // پردازش فرم‌ها (AJAX و Non-AJAX)
    // =============================================

    /**
     * پردازش ورود
     */
    public function doLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        // اعتبارسنجی
        if (empty($username) || empty($password)) {
            return redirect()->back()->with('error', 'لطفاً شماره تلفن و رمز عبور را وارد کنید.');
        }

        // نرمال‌سازی شماره
        $normalizedPhone = $this->normalizePhone($username);

        // جستجوی کاربر با شماره
        $user = $this->userModel->findByPhone($normalizedPhone);

        if (!$user) {
            // ثبت تلاش ناموفق
            $this->failedAttemptsModel->logAttempt($normalizedPhone, 'login');
            return redirect()->back()->with('error', 'شماره تلفن یا رمز عبور اشتباه است.');
        }

        // بررسی رمز عبور
        if (!password_verify($password, $user->password_hash)) {
            $this->failedAttemptsModel->logAttempt($normalizedPhone, 'login');
            return redirect()->back()->with('error', 'شماره تلفن یا رمز عبور اشتباه است.');
        }

        // بررسی وضعیت کاربر
        if ($user->status === 'blocked') {
            return redirect()->back()->with('error', 'حساب کاربری شما مسدود شده است. لطفاً با پشتیبانی تماس بگیرید.');
        }

        // اگر شماره تأیید نشده، به صفحه تأیید هدایت شود
        if ($user->is_verified == 0) {
            $this->session->set('otp_phone', $user->phone);
            $this->session->set('otp_type', 'login');
            $this->session->set('temp_user_id', $user->id);

            // ارسال کد OTP
            $this->otpLogModel->generateAndSendCode($user->phone, 'login');

            return redirect()->to('/auth/verify-phone')->with('info', 'لطفاً شماره همراه خود را تأیید کنید.');
        }

        // تکمیل ورود
        return $this->completeLogin($user, $remember);
    }

  /**
 * پردازش ثبت‌نام (مرحله اول - دریافت اطلاعات و ارسال OTP)
 */
public function doRegister()
{
    $fullName = $this->request->getPost('full_name');
    $phone = $this->request->getPost('phone');
    $password = $this->request->getPost('password');
    $passwordConfirm = $this->request->getPost('password_confirm');

    // =============================================
    // اعتبارسنجی
    // =============================================
    $rules = [
        'full_name' => 'required|min_length[3]|max_length[100]',
        'phone' => 'required|min_length[11]|max_length[15]|is_unique[users.phone]',
        'password' => 'required|min_length[8]',
        'password_confirm' => 'required|matches[password]',
        'terms' => 'required',
    ];

    $messages = [
        'full_name' => [
            'required' => 'نام و نام خانوادگی الزامی است.',
            'min_length' => 'نام و نام خانوادگی حداقل ۳ کاراکتر باشد.',
        ],
        'phone' => [
            'required' => 'شماره همراه الزامی است.',
            'min_length' => 'شماره همراه حداقل ۱۱ رقم باشد.',
            'is_unique' => 'این شماره همراه قبلاً ثبت شده است.',
        ],
        'password' => [
            'required' => 'رمز عبور الزامی است.',
            'min_length' => 'رمز عبور حداقل ۸ کاراکتر باشد.',
        ],
        'password_confirm' => [
            'required' => 'تکرار رمز عبور الزامی است.',
            'matches' => 'رمز عبور و تکرار آن مطابقت ندارند.',
        ],
        'terms' => [
            'required' => 'پذیرش قوانین الزامی است.',
        ],
    ];

    if (!$this->validate($rules, $messages)) {
        return redirect()->back()
            ->with('validation', $this->validator)
            ->with('error', 'لطفاً اطلاعات را به‌درستی وارد کنید.')
            ->withInput();
    }

    // نرمال‌سازی شماره (حذف ۰)
    $normalizedPhone = $this->normalizePhone($phone);

    // ذخیره موقت در session
    $this->session->set('temp_register_data', [
        'full_name' => $fullName,
        'phone' => $normalizedPhone,
        'password' => $password,
    ]);

    // ارسال کد OTP
    $result = $this->otpLogModel->generateAndSendCode($normalizedPhone, 'register');

    if (!$result['success']) {
        return redirect()->back()->with('error', 'خطا در ارسال کد تأیید: ' . ($result['sms_result']['message'] ?? 'خطای ناشناخته'));
    }

    // ذخیره شماره در session برای صفحه تأیید
    $this->session->set('otp_phone', $normalizedPhone);
    $this->session->set('otp_type', 'register');

    return redirect()->to('/auth/verify-phone')->with('info', 'کد تأیید به شماره شما ارسال شد.');
}

    /**
     * تأیید کد OTP
     */
    public function doVerifyPhone()
    {
        $codeInput = $this->request->getPost('code');
        $phone = $this->session->get('otp_phone');
        $type = $this->session->get('otp_type');

        // اعتبارسنجی
        if (empty($phone) || empty($type)) {
            return redirect()->to('/auth/register')->with('error', 'لطفاً ابتدا شماره تلفن خود را وارد کنید.');
        }

        // تبدیل کد به رشته
        if (is_array($codeInput)) {
            $code = implode('', $codeInput);
        } else {
            $code = trim($codeInput);
        }

        // بررسی کد
        $isValid = $this->otpLogModel->verifyCode($phone, $code, $type);

        if (!$isValid) {
            return redirect()->back()->with('error', 'کد وارد شده صحیح نیست یا منقضی شده است.');
        }

        // =============================================
        // ثبت‌نام (اگر نوع register باشد)
        // =============================================
        if ($type === 'register') {
            $tempData = $this->session->get('temp_register_data');

            if (empty($tempData)) {
                return redirect()->to('/auth/register')->with('error', 'اطلاعات ثبت‌نام یافت نشد. لطفاً دوباره تلاش کنید.');
            }

            // ثبت کاربر
            $userId = $this->userModel->insert([
                'full_name' => $tempData['full_name'],
                'phone' => $phone,
                'password_hash' => password_hash($tempData['password'], PASSWORD_DEFAULT),
                'is_verified' => 1,
                'status' => 'active',
            ]);

            if (!$userId) {
                return redirect()->to('/auth/register')->with('error', 'خطا در ثبت‌نام. لطفاً دوباره تلاش کنید.');
            }

            // پاک کردن session
            $this->session->remove(['otp_phone', 'otp_type', 'temp_register_data']);

            // ورود خودکار
            $user = $this->userModel->find($userId);
            $this->completeLogin($user);

            return redirect()->to('/dashboard')->with('success', 'ثبت‌نام با موفقیت انجام شد. خوش آمدید!');
        }

        // =============================================
        // ورود (اگر نوع login باشد)
        // =============================================
        if ($type === 'login') {
            $userId = $this->session->get('temp_user_id');

            if (!$userId) {
                return redirect()->to('/auth/login')->with('error', 'اطلاعات ورود یافت نشد. لطفاً دوباره تلاش کنید.');
            }

            // تأیید شماره کاربر
            $this->userModel->verifyUser($userId);

            // پاک کردن session
            $this->session->remove(['otp_phone', 'otp_type', 'temp_user_id']);

            // ورود
            $user = $this->userModel->find($userId);
            $this->completeLogin($user);

            return redirect()->to('/dashboard')->with('success', 'شماره همراه تأیید شد. خوش آمدید!');
        }

        // =============================================
        // بازیابی رمز عبور (اگر نوع reset_password باشد)
        // =============================================
        if ($type === 'reset_password') {
            $this->session->set('otp_verified', true);
            return redirect()->to('/auth/reset-password-form')->with('success', 'کد تأیید شد. لطفاً رمز عبور جدید را وارد کنید.');
        }

        return redirect()->to('/auth/login')->with('error', 'خطا در تأیید کد.');
    }

    /**
     * پردازش بازیابی رمز عبور
     */
    public function doResetPassword()
    {
        $phone = $this->session->get('otp_phone');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // اعتبارسنجی
        if (empty($phone)) {
            return redirect()->to('/auth/reset-password')->with('error', 'لطفاً ابتدا شماره تلفن خود را وارد کنید.');
        }

        if (empty($newPassword) || strlen($newPassword) < 8) {
            return redirect()->back()->with('error', 'رمز عبور باید حداقل ۸ کاراکتر باشد.');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'رمز عبور و تکرار آن مطابقت ندارند.');
        }

        // پیدا کردن کاربر
        $user = $this->userModel->findByPhone($phone);

        if (!$user) {
            return redirect()->back()->with('error', 'کاربری با این شماره یافت نشد.');
        }

        // به‌روزرسانی رمز عبور
        $this->userModel->changePassword($user->id, $newPassword);

        // پاک کردن session
        $this->session->remove(['otp_phone', 'otp_type', 'otp_verified']);

        return redirect()->to('/auth/login')->with('success', 'رمز عبور با موفقیت تغییر کرد. لطفاً وارد شوید.');
    }

    /**
     * ارسال مجدد کد (AJAX)
     */
    public function resendCode()
    {
        $phone = $this->session->get('otp_phone');
        $type = $this->session->get('otp_type');

        if (empty($phone) || empty($type)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'نشست منقضی شده است. لطفاً دوباره تلاش کنید.'
            ]);
        }

        // ارسال مجدد کد
        $result = $this->otpLogModel->resendCode($phone, $type);

        return $this->response->setJSON([
            'success' => $result['success'],
            'message' => $result['success'] ? 'کد جدید ارسال شد.' : 'خطا در ارسال کد. لطفاً دوباره تلاش کنید.',
            'expires_at' => $result['expires_at'] ?? null
        ]);
    }

    // =============================================
    // خروج
    // =============================================

    /**
     * خروج از حساب کاربری
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/')->with('success', 'با موفقیت خارج شدید.');
    }

    // =============================================
    // توابع کمکی
    // =============================================

    /**
     * تکمیل فرآیند ورود
     */
    private function completeLogin($user, $remember = false)
    {
        // تنظیم session
        $this->session->set([
            'user_id' => $user->id,
            'full_name' => $user->full_name,
            'phone' => $user->phone,
            'is_logged_in' => true,
            'last_activity' => time(),
        ]);

        // به‌روزرسانی آخرین ورود
        $this->userModel->updateLastLogin($user->id);

        // اگر مرا به خاطر بسپار فعال باشد
        if ($remember) {
            // تنظیم کوکی یادآوری (اختیاری)
            // می‌توانید از service('cookie') استفاده کنید
        }

        return redirect()->to('/dashboard');
    }

    /**
     * نرمال‌سازی شماره تلفن (حذف ۰ و ۹۸)
     * مثال: 09123456789 → 9123456789
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (substr($phone, 0, 2) === '98') {
            $phone = substr($phone, 2);
        }

        if (substr($phone, 0, 1) === '0') {
            $phone = substr($phone, 1);
        }

        return $phone;
    }

    /**
     * فرمت شماره برای نمایش (با ۰)
     * مثال: 9123456789 → 09123456789
     */
    private function formatDisplayPhone(string $phone): string
    {
        if (substr($phone, 0, 1) !== '0') {
            $phone = '0' . $phone;
        }
        return $phone;
    }

    // =============================================
    // متدهای تست (برای دیباگ)
    // =============================================

    /**
     * تست سرویس پیامک (AJAX)
     */
    public function testSms()
    {
        $phone = $this->request->getGet('phone') ?? '09123456789';
        $normalizedPhone = $this->normalizePhone($phone);

        $result = $this->otpLogModel->generateAndSendCode($normalizedPhone, 'test');

        return $this->response->setJSON([
            'phone' => $phone,
            'normalized' => $normalizedPhone,
            'otp_code' => $result['otp_code'] ?? null,
            'sms_result' => $result['sms_result'] ?? null,
            'success' => $result['success'] ?? false,
        ]);
    }

    /**
     * تست اتصال به سرویس بله
     */
    public function testBale()
    {
        $result = $this->smsService->testConnection();

        return $this->response->setJSON([
            'status' => '✅ سرویس بله',
            'result' => $result
        ]);
    }
}