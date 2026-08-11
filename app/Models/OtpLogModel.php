<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\SmsService;

class OtpLogModel extends Model
{
    protected $table            = 'otp_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // =============================================
    // ✅ لیست فیلدهای مجاز
    // =============================================
    protected $allowedFields = [
        'user_id',
        'phone',
        'otp_code',
        'type',
        'status',
        'attempts',
        'ip_address',
        'user_agent',
        'expires_at',
        'verified_at'
    ];

    // =============================================
    // ✅ غیرفعال کردن timestamps (چون در جدول وجود ندارد)
    // =============================================
    protected $useTimestamps = false;
    protected $createdField  = false;
    protected $updatedField  = false;

    protected $smsService;

    public function __construct()
    {
        parent::__construct();
        $this->smsService = new SmsService();
    }

    /**
     * تولید و ارسال کد OTP
     */
    public function generateAndSendCode(string $phone, string $type = 'register'): array
    {
        try {
            // حذف کدهای قبلی
            $this->where('phone', $phone)->where('status', 'pending')->delete();

            // تولید کد ۶ رقمی
            $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));

            // =============================================
            // ✅ ساخت داده با کلیدهای رشته‌ای
            // =============================================
            $data = [
                'phone'      => $phone,
                'otp_code'   => $otpCode,
                'type'       => $type,
                'status'     => 'pending',
                'attempts'   => 0,
                'ip_address' => service('request')->getIPAddress(),
                'user_agent' => service('request')->getUserAgent(),
                'expires_at' => $expiresAt,
            ];

            // =============================================
            // ✅ درج با آرایه صحیح
            // =============================================
            $result = $this->insert($data);
            
            if (!$result) {
                log_message('error', '❌ Failed to insert OTP: ' . json_encode($data));
                return [
                    'success' => false,
                    'otp_code' => null,
                    'error' => 'Failed to insert OTP'
                ];
            }

            log_message('debug', '✅ OTP inserted: ' . $otpCode . ' for phone: ' . $phone);

            // ارسال پیامک
            $smsResult = $this->smsService->sendVerificationCode($phone, $otpCode);

            return [
                'success' => $smsResult['success'] ?? false,
                'otp_code' => $otpCode,
                'sms_result' => $smsResult,
                'expires_at' => $expiresAt
            ];

        } catch (\Exception $e) {
            log_message('error', '❌ Error in generateAndSendCode: ' . $e->getMessage());
            log_message('error', '❌ Stack trace: ' . $e->getTraceAsString());
            return [
                'success' => false,
                'otp_code' => null,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * تولید کد بدون ارسال پیامک (برای تست)
     */
    public function generateCodeOnly(string $phone, string $type = 'register'): string
    {
        $this->where('phone', $phone)->where('status', 'pending')->delete();

        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        $data = [
            'phone'      => $phone,
            'otp_code'   => $otpCode,
            'type'       => $type,
            'status'     => 'pending',
            'attempts'   => 0,
            'ip_address' => service('request')->getIPAddress(),
            'user_agent' => service('request')->getUserAgent(),
            'expires_at' => $expiresAt,
        ];

        $this->insert($data);

        return $otpCode;
    }

    /**
     * بررسی کد OTP
     */
    public function verifyCode(string $phone, string $otpCode, string $type = 'register'): bool
    {
        $record = $this->where('phone', $phone)
                       ->where('otp_code', $otpCode)
                       ->where('type', $type)
                       ->where('status', 'pending')
                       ->where('expires_at >=', date('Y-m-d H:i:s'))
                       ->first();

        if ($record) {
            $this->update($record['id'], [
                'status' => 'used',
                'verified_at' => date('Y-m-d H:i:s')
            ]);
            return true;
        }

        return false;
    }

    /**
     * ارسال مجدد کد
     */
    public function resendCode(string $phone, string $type = 'register'): array
    {
        // حذف کدهای قبلی
        $this->where('phone', $phone)->where('status', 'pending')->delete();

        // تولید کد جدید
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        $data = [
            'phone'      => $phone,
            'otp_code'   => $otpCode,
            'type'       => $type,
            'status'     => 'pending',
            'attempts'   => 0,
            'ip_address' => service('request')->getIPAddress(),
            'user_agent' => service('request')->getUserAgent(),
            'expires_at' => $expiresAt,
        ];

        $this->insert($data);

        // ارسال پیامک
        $smsResult = $this->smsService->sendVerificationCode($phone, $otpCode);

        return [
            'success' => $smsResult['success'] ?? false,
            'otp_code' => $otpCode,
            'sms_result' => $smsResult,
            'expires_at' => $expiresAt
        ];
    }

    /**
     * دریافت کد فعال برای تلفن
     */
    public function getActiveCode(string $phone): ?array
    {
        return $this->where('phone', $phone)
                    ->where('status', 'pending')
                    ->where('expires_at >=', date('Y-m-d H:i:s'))
                    ->first();
    }

    /**
     * پاک کردن کدهای منقضی
     */
    public function cleanExpiredCodes(): int
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))
                    ->where('status', 'pending')
                    ->delete();
    }
}