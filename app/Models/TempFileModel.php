<?php

namespace App\Models;

use CodeIgniter\Model;

class TempFileModel extends Model
{
    protected $table            = 'temp_files';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    protected $allowedFields = [
        'user_id', 'session_id', 'file_name', 'file_path', 'file_size',
        'file_mime', 'file_type', 'order_index', 'pdf_converted_at',
        'pdf_file_path', 'pdf_file_name', 'expires_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = false;
    protected $deletedField  = 'deleted_at';

    public function storeFile(int $userId, array $fileData, int $expirySeconds = 120): int
    {
        $data = [
            'user_id'     => $userId,
            'session_id'  => session_id(),
            'file_name'   => $fileData['name'] ?? 'unknown.jpg',
            'file_path'   => $fileData['path'] ?? '',
            'file_size'   => $fileData['size'] ?? 0,
            'file_mime'   => $fileData['mime'] ?? 'image/jpeg',
            'file_type'   => $fileData['type'] ?? 'image',
            'order_index' => $fileData['order'] ?? 0,
            'expires_at'  => date('Y-m-d H:i:s', time() + $expirySeconds)
        ];

        $builder = $this->db->table($this->table);
        $result = $builder->insert($data);

        if ($result) {
            $id = $this->db->insertID();
            log_message('debug', "storeFile Success - ID: $id for file: " . ($fileData['name'] ?? 'unknown'));
            return (int)$id;
        }

        log_message('error', 'storeFile Failed: ' . json_encode($data));
        return 0;
    }

    // بقیه متدها بدون تغییر (یا با Builder امن)
    public function getUserFiles(int $userId, string $fileType = 'image')
    {
        return $this->where('user_id', $userId)
                    ->where('file_type', $fileType)
                    ->where('deleted_at IS NULL')
                    ->orderBy('order_index', 'ASC')
                    ->findAll();
    }

    public function clearUserFiles(int $userId): int
    {
        $files = $this->where('user_id', $userId)
                    ->where('deleted_at IS NULL')
                    ->findAll();

        $deleted = 0;
        foreach ($files as $file) {
            // حذف فایل تصویر
            if (!empty($file->file_path) && file_exists($file->file_path)) {
                unlink($file->file_path);
            }
            // حذف فایل PDF (اگر وجود داشته باشه)
            if (!empty($file->pdf_file_path) && file_exists($file->pdf_file_path)) {
                unlink($file->pdf_file_path);
            }
            
            // حذف رکورد از دیتابیس
            $this->db->table($this->table)
                    ->where('id', $file->id)
                    ->delete();
            
            $deleted++;
        }

        log_message('debug', "Cleared $deleted temp files for user $userId");
        return $deleted;
    }

    // بقیه متدها را همان‌طور نگه دار
    public function updateOrder(int $fileId, int $newOrder): bool
    {
        return $this->update($fileId, ['order_index' => $newOrder]);
    }

    public function savePdfInfo(int $fileId, string $pdfPath, string $pdfName): bool
    {
        return $this->update($fileId, [
            'pdf_file_path' => $pdfPath,
            'pdf_file_name' => $pdfName,
            'pdf_converted_at' => date('Y-m-d H:i:s'),
            'file_type' => 'pdf'
        ]);
    }

    public function deleteExpiredFiles(): int
    {
        $expiredFiles = $this->where('expires_at <', date('Y-m-d H:i:s'))
                             ->where('deleted_at IS NULL')
                             ->findAll();

        $deleted = 0;
        foreach ($expiredFiles as $file) {
            if (file_exists($file->file_path ?? '')) unlink($file->file_path);
            if (!empty($file->pdf_file_path) && file_exists($file->pdf_file_path)) {
                unlink($file->pdf_file_path);
            }
            $this->db->table($this->table)->where('id', $file->id)->delete();
            $deleted++;
        }
        return $deleted;
    }
}