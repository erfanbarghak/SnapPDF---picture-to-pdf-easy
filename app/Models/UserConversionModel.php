<?php

namespace App\Models;

use CodeIgniter\Model;

class UserConversionModel extends Model
{
    protected $table            = 'conversions';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [];

    // =============================================
    // متدهای روابط
    // =============================================

    /**
     * دریافت تبدیل‌های کاربر با اطلاعات کاربر
     */
    public function getConversionsWithUser(int $userId, int $limit = 10)
    {
        return $this->select('conversions.*, users.full_name, users.phone')
                    ->join('users', 'users.id = conversions.user_id')
                    ->where('conversions.user_id', $userId)
                    ->where('conversions.deleted_at IS NULL')
                    ->orderBy('conversions.id', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * دریافت آخرین تبدیل کاربر
     */
    public function getLastConversionWithUser(int $userId)
    {
        return $this->select('conversions.*, users.full_name, users.phone')
                    ->join('users', 'users.id = conversions.user_id')
                    ->where('conversions.user_id', $userId)
                    ->where('conversions.status', 'completed')
                    ->where('conversions.deleted_at IS NULL')
                    ->orderBy('conversions.id', 'DESC')
                    ->first();
    }

    /**
     * دریافت آمار کاربر به‌همراه تبدیل‌ها
     */
    public function getUserStatsWithConversions(int $userId): object
    {
        $userModel = new UserModel();
        $conversionModel = new ConversionModel();

        $user = $userModel->find($userId);
        $stats = $conversionModel->getStats();

        return (object) [
            'user' => $user,
            'conversion_stats' => $stats
        ];
    }
}