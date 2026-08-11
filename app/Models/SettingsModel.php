<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'setting_key',
        'setting_value'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'setting_key' => 'required|max_length[50]|is_unique[settings.setting_key,id,{id}]',
        'setting_value' => 'required',
    ];

    protected $validationMessages = [
        'setting_key' => [
            'required'   => 'کلید تنظیمات الزامی است',
            'max_length' => 'کلید تنظیمات نباید بیشتر از ۵۰ کاراکتر باشد',
            'is_unique'  => 'این کلید تنظیمات قبلاً ثبت شده است',
        ],
        'setting_value' => [
            'required' => 'مقدار تنظیمات الزامی است',
        ],
    ];

    // =============================================
    // متدهای سفارشی
    // =============================================

    /**
     * دریافت مقدار یک تنظیمات
     */
    public function get(string $key, $default = null)
    {
        $setting = $this->where('setting_key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        // تبدیل JSON به آرایه/شیء
        $value = $setting->setting_value;
        if ($this->isJson($value)) {
            return json_decode($value, true);
        }

        return $value;
    }

    /**
     * تنظیم مقدار
     */
    public function set(string $key, $value): bool
    {
        // تبدیل آرایه/شیء به JSON
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        $existing = $this->where('setting_key', $key)->first();

        if ($existing) {
            return $this->update($existing->id, ['setting_value' => $value]);
        }

        return $this->insert([
            'setting_key' => $key,
            'setting_value' => $value
        ]);
    }

    /**
     * دریافت چندین تنظیمات
     */
    public function getMultiple(array $keys): array
    {
        $settings = $this->whereIn('setting_key', $keys)->findAll();
        $result = [];

        foreach ($settings as $setting) {
            $value = $setting->setting_value;
            if ($this->isJson($value)) {
                $result[$setting->setting_key] = json_decode($value, true);
            } else {
                $result[$setting->setting_key] = $value;
            }
        }

        return $result;
    }

    /**
     * حذف یک تنظیمات
     */
    public function deleteSetting(string $key): bool
    {
        return $this->where('setting_key', $key)->delete();
    }

    /**
     * بررسی JSON بودن
     */
    private function isJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * دریافت همه تنظیمات
     */
    public function getAll(): array
    {
        $settings = $this->findAll();
        $result = [];

        foreach ($settings as $setting) {
            $value = $setting->setting_value;
            if ($this->isJson($value)) {
                $result[$setting->setting_key] = json_decode($value, true);
            } else {
                $result[$setting->setting_key] = $value;
            }
        }

        return $result;
    }

    /**
     * دریافت تنظیمات به‌صورت آبجکت
     */
    public function getAllAsObject(): object
    {
        return (object) $this->getAll();
    }

    /**
     * بارگذاری تنظیمات در کش (برای کاهش کوئری)
     */
    public function loadToCache(): array
    {
        $settings = $this->getAll();
        
        // ذخیره در کش (اختیاری)
        $cache = \Config\Services::cache();
        $cache->save('app_settings', $settings, 3600);

        return $settings;
    }

    /**
     * دریافت تنظیمات از کش
     */
    public function getFromCache(): array
    {
        $cache = \Config\Services::cache();
        $settings = $cache->get('app_settings');

        if (!$settings) {
            $settings = $this->loadToCache();
        }

        return $settings;
    }

    /**
     * پاک کردن کش تنظیمات
     */
    public function clearCache(): bool
    {
        $cache = \Config\Services::cache();
        return $cache->delete('app_settings');
    }
}