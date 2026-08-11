<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OtpLogModel;
use App\Models\FailedAttemptsModel;
use App\Libraries\SmsService;

class OtpController extends BaseController
{
    protected UserModel $userModel;
    protected OtpLogModel $otpLogModel;
    protected FailedAttemptsModel $failedAttemptsModel;
    protected SmsService $SmsService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->otpLogModel = new OtpLogModel();
        $this->failedAttemptsModel = new FailedAttemptsModel();
        $this->SmsService = new SmsService();
    }

    /**
     * نمایش صفحه دریافت کد
     */
    public function index()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        $type = session()->get('otp_type');
        $phone = session()->get('otp_phone');

        if (empty($type) || empty($phone)) {
            return redirect()->to('/auth/register')->with('error', 'لطفاً ابتدا شماره تلفن خود را وارد کنید.');
        }

        $data = [
            'type' => $type,
            'phone' => $phone,
            'display_phone' => '0' . $phone
        ];

        return view('auth/verify_otp', $data);
    }

    // =============================================
    // ✅ متد send - با دیباگ کامل
    // =============================================

    /**
     * ارسال کد OTP (AJAX)
     */
    public function send()
    {
        try {
            // =============================================
            // 1. دریافت داده‌ها
            // =============================================
            $input = $this->request->getJSON(true);
            
            if ($input) {
                $phone = $input['phone'] ?? null;
                $type = $input['type'] ?? 'register';
            } else {
                $phone = $this->request->getPost('phone');
                $type = $this->request->getPost('type') ?? 'register';
            }

            // =============================================
            // 2. لاگ برای دیباگ
            // =============================================
            log_message('debug', '========================================');
            log_message('debug', '📤 Send OTP Request');
            log_message('debug', '📱 Phone: ' . $phone);
            log_message('debug', '📝 Type: ' . $type);
            log_message('debug', '📦 Input: ' . json_encode($input));
            log_message('debug', '========================================');

            // =============================================
            // 3. اعتبارسنجی شماره
            // =============================================
            if (empty($phone)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'شماره تلفن را وارد کنید'
                ])->setStatusCode(400);
            }

            if (!$this->validatePhone($phone)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'شماره تلفن معتبر نیست (مثال: 09123456789)'
                ])->setStatusCode(400);
            }

            // نرمال‌سازی شماره
            $normalizedPhone = $this->normalizePhone($phone);
            log_message('debug', '📱 Normalized Phone: ' . $normalizedPhone);

            // =============================================
            // 4. تولید کد
            // =============================================
            $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = date('Y-m-d H:i:s', time() + 120);
            log_message('debug', '🔑 Generated OTP: ' . $otpCode);

            // =============================================
            // 5. ذخیره در دیتابیس
            // =============================================
            $otpId = $this->otpLogModel->createOtpLog($normalizedPhone, $otpCode, $type, 120);
            
            if (!$otpId) {
                throw new \Exception('Failed to save OTP - returned ID: ' . $otpId);
            }
            
            log_message('debug', '✅ OTP saved with ID: ' . $otpId);

            // =============================================
            // 6. ذخیره در session
            // =============================================
            session()->set([
                'otp_phone' => $normalizedPhone,
                'otp_type' => $type,
                'otp_id' => $otpId,
                'otp_expires_at' => $expiresAt
            ]);

            // =============================================
            // 7. ارسال پیامک
            // =============================================
            $smsPhone = '98' . $normalizedPhone;
            log_message('debug', '📨 Sending SMS to: ' . $smsPhone);
            
            $smsResult = $this->SmsService->sendOtp($smsPhone, $otpCode, 120);
            
            log_message('debug', '📨 SMS Result: ' . json_encode($smsResult));

            if (!$smsResult['success']) {
                log_message('error', '❌ SMS Error: ' . $smsResult['message']);
                $this->otpLogModel->expireOtp($otpId);
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'خطا در ارسال پیامک: ' . $smsResult['message']
                ])->setStatusCode(500);
            }

            // =============================================
            // 8. ارسال موفق
            // =============================================
            return $this->response->setJSON([
                'success' => true,
                'message' => 'کد تأیید با موفقیت ارسال شد',
                'redirect' => '/otp/verify'
            ]);

        } catch (\Exception $e) {
            log_message('error', '❌ Exception in sendOtp: ' . $e->getMessage());
            log_message('error', '❌ Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'خطا در ارسال کد: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * ارسال مجدد OTP (AJAX)
     */
    public function resend()
    {
        try {
            $phone = session()->get('otp_phone');
            $type = session()->get('otp_type');

            if (empty($phone) || empty($type)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'اطلاعات معتبر نیست. لطفاً دوباره تلاش کنید.'
                ]);
            }

            // محدودیت ارسال (۶۰ ثانیه)
            if ($this->otpLogModel->hasRecentOtp($phone, 60)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'لطفاً ۶۰ ثانیه صبر کنید تا کد جدید ارسال شود'
                ]);
            }

            // تولید کد جدید
            $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = date('Y-m-d H:i:s', time() + 120);

            $otpId = $this->otpLogModel->createOtpLog($phone, $otpCode, $type, 120);
            
            if (!$otpId) {
                throw new \Exception('Failed to save OTP');
            }

            session()->set([
                'otp_id' => $otpId,
                'otp_expires_at' => $expiresAt
            ]);

            // ارسال پیامک
            $smsPhone = '98' . $phone;
            $smsResult = $this->SmsService->sendOtp($smsPhone, $otpCode, 120);

            if (!$smsResult['success']) {
                $this->otpLogModel->expireOtp($otpId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'خطا در ارسال پیامک: ' . $smsResult['message']
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'کد جدید با موفقیت ارسال شد'
            ]);

        } catch (\Exception $e) {
            log_message('error', '❌ Error resending OTP: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'خطا در ارسال مجدد کد'
            ]);
        }
    }

    /**
     * تأیید کد OTP (AJAX)
     */
    public function verify()
    {
        try {
            $input = $this->request->getJSON(true);
            
            if ($input) {
                $otpCode = $input['otp_code'] ?? null;
            } else {
                $otpCode = $this->request->getPost('otp_code');
            }

            $phone = session()->get('otp_phone');
            $type = session()->get('otp_type');

            if (empty($phone) || empty($type)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'اطلاعات معتبر نیست. لطفاً دوباره تلاش کنید.'
                ]);
            }

            if (empty($otpCode)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'کد تأیید را وارد کنید'
                ]);
            }

            // تأیید OTP
            $otpRecord = $this->otpLogModel->verifyOtp($phone, $otpCode, $type);

            if (!$otpRecord) {
                $this->failedAttemptsModel->logAttempt($phone, 'verify_otp');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'کد تأیید نامعتبر یا منقضی شده است'
                ]);
            }

            // ذخیره در session که کد تأیید شده
            session()->set('otp_verified', true);

            if ($type === 'register') {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'کد تأیید شد',
                    'redirect' => '/auth/complete-register'
                ]);
            }

            if ($type === 'reset_password') {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'کد تأیید شد',
                    'redirect' => '/auth/reset-password-form'
                ]);
            }

            if ($type === 'login') {
                $user = $this->userModel->findByPhone($phone);
                if ($user) {
                    session()->set([
                        'user_id' => $user->id,
                        'full_name' => $user->full_name,
                        'phone' => $user->phone,
                        'is_logged_in' => true,
                        'login_method' => 'otp'
                    ]);
                    $this->userModel->updateLastLogin($user->id);
                    
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'ورود با موفقیت انجام شد',
                        'redirect' => '/dashboard'
                    ]);
                }
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'خطا در تأیید کد'
            ]);

        } catch (\Exception $e) {
            log_message('error', '❌ Error verifying OTP: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'خطا در تأیید کد'
            ]);
        }
    }

    /**
     * تست OTP Controller
     */
    public function test()
    {
        return $this->response->setJSON([
            'status' => '✅ OtpController is working',
            'session' => [
                'otp_phone' => session()->get('otp_phone'),
                'otp_type' => session()->get('otp_type'),
                'otp_verified' => session()->get('otp_verified')
            ]
        ]);
    }

    // =============================================
    // توابع کمکی
    // =============================================

    private function validatePhone(string $phone): bool
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return preg_match('/^0[0-9]{10}$/', $phone) === 1;
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (substr($phone, 0, 1) === '0') {
            $phone = substr($phone, 1);
        }
        
        if (substr($phone, 0, 2) === '98') {
            $phone = substr($phone, 2);
        }
        
        return $phone;
    }
}