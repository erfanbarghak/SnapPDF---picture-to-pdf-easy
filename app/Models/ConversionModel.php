<?php

namespace App\Models;

use CodeIgniter\Model;

class ConversionModel extends Model
{
    protected $table            = 'conversions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    protected $allowedFields = [
        'user_id',
        'temp_file_id',
        'original_images_count',
        'pdf_file_name',
        'pdf_file_size',
        'pdf_file_path',
        'is_compressed',
        'has_page_numbers',
        'status',
        'download_count',
        'expires_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = false;
    protected $deletedField  = 'deleted_at';

    /**
     * ذخیره تبدیل جدید
     */
    public function storeConversion(int $userId, array $data, int $expirySeconds = 120): int
    {
        $insertData = [
            'user_id'               => $userId,
            'temp_file_id'          => $data['temp_file_id'] ?? null,
            'original_images_count' => $data['images_count'] ?? 0,
            'pdf_file_name'         => $data['pdf_name'] ?? 'SnapPDF_Export.pdf',
            'pdf_file_size'         => $data['pdf_size'] ?? 0,
            'pdf_file_path'         => $data['pdf_path'] ?? '',
            'is_compressed'         => (int)($data['is_compressed'] ?? 0),
            'has_page_numbers'      => (int)($data['has_page_numbers'] ?? 0),
            'status'                => 'completed',
            'download_count'        => 0,
            'expires_at'            => date('Y-m-d H:i:s', time() + $expirySeconds)
        ];

        log_message('debug', 'Conversion Insert Data: ' . json_encode($insertData));

        $builder = $this->db->table($this->table);
        $result = $builder->insert($insertData);

        if ($result) {
            $id = $this->db->insertID();
            log_message('debug', "storeConversion Success - ID: $id");
            return (int)$id;
        }

        log_message('error', 'storeConversion Failed: ' . json_encode($insertData));
        return 0;
    }

    public function incrementDownloadCount(int $conversionId): bool
    {
        return $this->db->table($this->table)
                        ->where('id', $conversionId)
                        ->set('download_count', 'download_count + 1', false)
                        ->update();
    }

    public function getActiveConversion(int $conversionId)
    {
        return $this->where('id', $conversionId)
                    ->where('status', 'completed')
                    ->where('expires_at >', date('Y-m-d H:i:s'))
                    ->where('deleted_at IS NULL')
                    ->first();
    }

    public function getUserConversions(int $userId, int $limit = 10)
    {
        return $this->where('user_id', $userId)
                    ->where('deleted_at IS NULL')
                    ->orderBy('id', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function deleteExpiredConversions(): int
    {
        $expired = $this->where('expires_at <', date('Y-m-d H:i:s'))
                        ->where('deleted_at IS NULL')
                        ->findAll();

        $deleted = 0;
        foreach ($expired as $conversion) {
            if (file_exists($conversion->pdf_file_path ?? '')) {
                unlink($conversion->pdf_file_path);
            }
            $this->db->table($this->table)->where('id', $conversion->id)->delete();
            $deleted++;
        }
        return $deleted;
    }

    public function getStats(): array
    {
        $total = $this->countAllResults();
        $completed = $this->where('status', 'completed')->countAllResults();
        $today = $this->where('DATE(created_at)', date('Y-m-d'))->countAllResults();
        
        return [
            'total' => $total,
            'completed' => $completed,
            'today' => $today,
        ];
    }

    // سایر متدها (در صورت نیاز بعدا اصلاح می‌کنیم)
    public function updateConversion(int $id, array $data): bool
    {
        $filteredData = [];
        foreach ($this->allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $filteredData[$field] = $data[$field];
            }
        }
        return $this->update($id, $filteredData);
    }

    public function getConversion(int $id)
    {
        return $this->where('id', $id)
                    ->where('deleted_at IS NULL')
                    ->first();
    }
}