<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    // =============================================
    // ✅ لیست فیلدهای مجاز
    // =============================================
    protected $allowedFields = [
        'full_name',
        'phone',
        'password_hash',
        'is_verified',
        'status',
        'last_login_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // =============================================
    // ✅ متدهای اصلی
    // =============================================

    public function findByPhone(string $phone)
    {
        return $this->where('phone', $phone)->first();
    }

    public function createUser(array $data): int
    {
        // فیلتر کردن داده‌ها
        $filteredData = [];
        foreach ($this->allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $filteredData[$field] = $data[$field];
            }
        }
        
        // هش کردن رمز عبور
        if (isset($filteredData['password_hash'])) {
            $filteredData['password_hash'] = password_hash(
                $filteredData['password_hash'],
                PASSWORD_DEFAULT
            );
        }
        
        return $this->insert($filteredData);
    }

    public function updateUser(int $id, array $data): bool
    {
        $filteredData = [];
        foreach ($this->allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $filteredData[$field] = $data[$field];
            }
        }
        
        if (isset($filteredData['password_hash'])) {
            $filteredData['password_hash'] = password_hash(
                $filteredData['password_hash'],
                PASSWORD_DEFAULT
            );
        }
        
        return $this->update($id, $filteredData);
    }

    public function changePassword(int $userId, string $newPassword): bool
    {
        return $this->update($userId, [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }

    public function verifyUser(int $userId): bool
    {
        return $this->update($userId, ['is_verified' => 1]);
    }

    public function updateLastLogin(int $userId): bool
    {
        return $this->update($userId, ['last_login_at' => date('Y-m-d H:i:s')]);
    }

    public function phoneExists(string $phone): bool
    {
        return $this->where('phone', $phone)->countAllResults() > 0;
    }

    public function verifyPassword(string $plainPassword, string $hashedPassword): bool
    {
        return password_verify($plainPassword, $hashedPassword);
    }
}