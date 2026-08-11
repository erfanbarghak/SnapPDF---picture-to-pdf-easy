<?php

namespace App\Models;

use CodeIgniter\Model;

class FailedAttemptsModel extends Model
{
    protected $table            = 'failed_attempts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // =============================================
    // ✅ لیست فیلدهای مجاز
    // =============================================
    protected $allowedFields = [
        'phone',
        'ip_address',
        'action'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'attempt_time';
    protected $updatedField  = false;

    // =============================================
    // ✅ متدها
    // =============================================

    /**
     * ثبت تلاش ناموفق
     */
    public function logAttempt(string $phone, string $action): int
    {
        $data = [
            'phone' => $phone,
            'ip_address' => service('request')->getIPAddress(),
            'action' => $action
        ];

        return $this->insert($data);
    }

    /**
     * دریافت تعداد تلاش‌های ناموفق در بازه زمانی
     */
    public function getAttemptCount(string $phone, int $minutes = 15): int
    {
        return $this->where('phone', $phone)
                    ->where('attempt_time >', date('Y-m-d H:i:s', time() - ($minutes * 60)))
                    ->countAllResults();
    }

    /**
     * بررسی قفل بودن شماره
     */
    public function isLocked(string $phone, int $maxAttempts = 5, int $minutes = 15): bool
    {
        return $this->getAttemptCount($phone, $minutes) >= $maxAttempts;
    }

    /**
     * پاکسازی تلاش‌های قدیمی (بیش از ۲۴ ساعت)
     */
    public function cleanupOldAttempts(): int
    {
        return $this->where('attempt_time <', date('Y-m-d H:i:s', strtotime('-24 hours')))
                    ->delete();
    }
}