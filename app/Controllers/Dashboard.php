<?php

namespace App\Controllers;

use App\Models\TempFileModel;
use App\Models\ConversionModel;
use App\Models\UserModel;
use App\Libraries\PdfGenerator;

class Dashboard extends BaseController
{
    protected TempFileModel $tempFileModel;
    protected ConversionModel $conversionModel;
    protected UserModel $userModel;
    protected PdfGenerator $pdfGenerator;

    public function __construct()
    {
        $this->tempFileModel = new TempFileModel();
        $this->conversionModel = new ConversionModel();
        $this->userModel = new UserModel();
        $this->pdfGenerator = new PdfGenerator();
    }

    // =============================================
    // نمایش صفحات
    // =============================================

    public function index()
    {
        $userId = session()->get('user_id');
        
        $data = [
            'stats' => $this->conversionModel->getStats(),
            'user' => $this->userModel->find($userId),
        ];

        return view('dashboard', $data);
    }

    public function history()
    {
        $userId = session()->get('user_id');
        $conversions = $this->conversionModel->getUserConversions($userId, 50);

        return view('dashboard/history', [
            'conversions' => $conversions
        ]);
    }

    // =============================================
    // متدهای API
    // =============================================

    public function stats()
    {
        try {
            $stats = $this->conversionModel->getStats();
            
            return $this->response->setJSON([
                'success' => true,
                'total_conversions' => $stats['total'] ?? 0,
                'completed' => $stats['completed'] ?? 0,
                'today_conversions' => $stats['today'] ?? 0,
                'total_downloads' => $stats['total_downloads'] ?? 0,
            ]);
            
        } catch (\Exception $e) {
            log_message('error', '❌ Stats Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'خطا در دریافت آمار: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

public function convert()
{
    try {
        $userId = session()->get('user_id');
        if (empty($userId)) throw new \Exception('لطفا وارد شوید');

        $pdfName     = trim($this->request->getPost('pdf_name') ?? 'SnapPDF_Export');
        $compress    = $this->request->getPost('compress') === '1';
        $pageNumbers = $this->request->getPost('page_numbers') === '1';

        $files = $this->request->getFiles();
        $images = $files['images'] ?? null;
        if (empty($images)) throw new \Exception('هیچ تصویری آپلود نشده');

        $images = is_array($images) ? $images : [$images];
        $validImages = array_filter($images, fn($f) => $f instanceof \CodeIgniter\HTTP\Files\UploadedFile && $f->isValid());

        if (empty($validImages)) throw new \Exception('تصاویر معتبر یافت نشد');

        $uploadPath = WRITEPATH . 'uploads/temp/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        $tempFiles = [];
        foreach ($validImages as $index => $file) {
            $newName = $file->getRandomName();
            $filePath = $uploadPath . $newName;

            log_message('debug', "Attempting to move: " . $file->getClientName() . " to " . $filePath);

            if ($file->move($uploadPath, $newName)) {
                log_message('debug', "Move successful: " . $filePath);

                $tempId = $this->tempFileModel->storeFile($userId, [
                    'name'  => $file->getClientName(),
                    'path'  => $filePath,
                    'size'  => $file->getSize(),
                    'mime'  => $file->getClientMimeType(),
                    'order' => $index,
                    'type'  => 'image'
                ]);

                log_message('debug', "storeFile returned ID: " . $tempId);

                if ($tempId > 0) {
                    $tempFiles[] = ['id' => $tempId, 'path' => $filePath];
                }
            } else {
                log_message('error', "Move FAILED: " . $file->getClientName() . " | Error: " . $file->getErrorString());
            }
        }

        if (empty($tempFiles)) {
            throw new \Exception('فایل‌ها ذخیره نشدند. لاگها را چک کنید.');
        }

        // بقیه کد بدون تغییر
        $imagePaths = array_column($tempFiles, 'path');
        $pdfContent = $this->pdfGenerator->generateFromImages($imagePaths, [
            'compress' => $compress,
            'page_numbers' => $pageNumbers
        ]);

        $pdfDir = WRITEPATH . 'uploads/pdf/';
        if (!is_dir($pdfDir)) mkdir($pdfDir, 0777, true);

        $pdfFileName = $pdfName . '_' . time() . '.pdf';
        $pdfFullPath = $pdfDir . $pdfFileName;

        file_put_contents($pdfFullPath, $pdfContent);
        $pdfSize = round(filesize($pdfFullPath) / 1024 / 1024, 2);

        $conversionId = $this->conversionModel->storeConversion($userId, [
            'images_count'     => count($tempFiles),
            'pdf_name'         => $pdfFileName,
            'pdf_size'         => $pdfSize,
            'pdf_path'         => $pdfFullPath,
            'is_compressed'    => $compress,
            'has_page_numbers' => $pageNumbers,
        ]);

        if (!$conversionId) throw new \Exception('خطا در ثبت تبدیل');

        $this->tempFileModel->clearUserFiles($userId);

        return $this->response->setJSON([
            'success'      => true,
            'message'      => 'PDF با موفقیت ساخته شد',
            'redirect'     => 'dashboard/pdf-ready/' . $conversionId,
            'pdf_name'     => $pdfFileName,
            'pdf_size'     => $pdfSize,
            'download_url' => '/dashboard/download/' . $conversionId
        ]);

    } catch (\Exception $e) {
        log_message('error', '=== Convert Error === ' . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
        return $this->response->setJSON([
            'success' => false,
            'message' => $e->getMessage()
        ])->setStatusCode(500);
    }
}
    /**
     * دانلود PDF
     */
    public function download($conversionId)
    {
        $userId = session()->get('user_id');
        
        $conversion = $this->conversionModel->getActiveConversion($conversionId);

        if (!$conversion || $conversion->user_id != $userId) {
            return redirect()->to('/dashboard')->with('error', 'فایل یافت نشد یا منقضی شده است');
        }

        $this->conversionModel->incrementDownloadCount($conversionId);

        return $this->response->download($conversion->pdf_file_path ?? '', null)
                            ->setFileName($conversion->pdf_file_name ?? 'document.pdf');
    }

    public function deleteConversion($id)
    {
        $userId = session()->get('user_id');
        
        $conversion = $this->conversionModel->where('id', $id)
                                            ->where('user_id', $userId)
                                            ->first();

        if (!$conversion) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'تبدیل یافت نشد'
            ]);
        }

        if (file_exists($conversion->pdf_file_path ?? '')) {
            unlink($conversion->pdf_file_path);
        }

        $this->conversionModel->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'تبدیل با موفقیت حذف شد'
        ]);
    }

    public function cleanup()
    {
        $deletedFiles = $this->tempFileModel->deleteExpiredFiles();
        $deletedConversions = $this->conversionModel->deleteExpiredConversions();

        return $this->response->setJSON([
            'success' => true,
            'deleted_files' => $deletedFiles,
            'deleted_conversions' => $deletedConversions
        ]);
    }

    /**
     * نمایش صفحه PDF Ready
     */
    public function pdfReady($conversionId)
    {
        $userId = session()->get('user_id');
        
        $conversion = $this->conversionModel->getActiveConversion($conversionId);

        if (!$conversion || $conversion->user_id != $userId) {
            return redirect()->to('/dashboard')->with('error', 'فایل یافت نشد یا منقضی شده است');
        }

        $expiresIn = strtotime($conversion->expires_at) - time();
        if ($expiresIn < 0) $expiresIn = 0;

        $data = [
            'conversion_id' => $conversion->id,
            'pdf_name' => $conversion->pdf_file_name,
            'pdf_size' => number_format($conversion->pdf_file_size ?? 0, 2),
            'images_count' => $conversion->original_images_count,
            'is_compressed' => $conversion->is_compressed,
            'has_page_numbers' => $conversion->has_page_numbers,
            'expires_in' => $expiresIn,
            'expires_at' => $conversion->expires_at,
        ];

        return view('dashboard/pdf_ready', $data);
    }
}