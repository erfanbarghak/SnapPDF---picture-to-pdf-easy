<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ConversionModel;

class Profile extends BaseController
{
    protected UserModel $userModel;
    protected ConversionModel $conversionModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->conversionModel = new ConversionModel();
    }

    /**
     * نمایش پروفایل
     */
    public function index()
    {
        $userId = session()->get('user_id');
        
        $data = [
            'user' => $this->userModel->find($userId),
            'conversions' => $this->conversionModel->getUserConversions($userId, 5),
        ];

        return view('profile', $data);
    }

    /**
     * به‌روزرسانی پروفایل (AJAX)
     */
    public function update()
    {
        $userId = session()->get('user_id');
        $fullName = $this->request->getPost('full_name');

        if (strlen($fullName) < 3) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'نام و نام خانوادگی باید حداقل ۳ کاراکتر باشد'
            ]);
        }

        $this->userModel->update($userId, ['full_name' => $fullName]);
        
        // به‌روزرسانی سشن
        session()->set('full_name', $fullName);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'پروفایل با موفقیت به‌روزرسانی شد'
        ]);
    }

    /**
     * تغییر رمز عبور (AJAX)
     */
    public function changePassword()
    {
        $userId = session()->get('user_id');
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // اعتبارسنجی
        if (strlen($newPassword) < 8) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'رمز عبور جدید باید حداقل ۸ کاراکتر باشد'
            ]);
        }

        if ($newPassword !== $confirmPassword) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'رمز عبور جدید و تکرار آن مطابقت ندارند'
            ]);
        }

        $user = $this->userModel->find($userId);
        
        if (!password_verify($currentPassword, $user->password_hash)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'رمز عبور فعلی اشتباه است'
            ]);
        }

        $this->userModel->changePassword($userId, $newPassword);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'رمز عبور با موفقیت تغییر کرد'
        ]);
    }

    /**
     * حذف حساب کاربری (AJAX)
     */
    public function delete()
    {
        $userId = session()->get('user_id');

        // حذف تمام فایل‌های کاربر
        $tempFileModel = new \App\Models\TempFileModel();
        $tempFileModel->clearUserFiles($userId);

        // حذف کاربر
        $this->userModel->delete($userId);

        // خروج از سیستم
        session()->destroy();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'حساب کاربری با موفقیت حذف شد'
        ]);
    }
}