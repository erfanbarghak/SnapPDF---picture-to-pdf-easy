<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGenerator
{
    protected Dompdf $dompdf;
    protected Options $options;  // ✅ تغییر از array به Options

    public function __construct()
    {
        $this->options = new Options();
        $this->options->set('defaultFont', 'Vazirmatn');
        $this->options->set('isHtml5ParserEnabled', true);
        $this->options->set('isRemoteEnabled', true);
        
        $this->dompdf = new Dompdf($this->options);
    }

    /**
     * تولید PDF از تصاویر
     */
    public function generateFromImages(array $images, array $settings = []): string
    {
        $compress = $settings['compress'] ?? true;
        $pageNumbers = $settings['page_numbers'] ?? false;
        $pageSize = $settings['page_size'] ?? 'A4';
        $orientation = $settings['orientation'] ?? 'portrait';

        // ساخت HTML
        $html = $this->buildHtml($images, $pageNumbers, $compress);

        // بارگذاری و تولید
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper($pageSize, $orientation);
        $this->dompdf->render();

        return $this->dompdf->output();
    }

    /**
     * ساخت HTML برای PDF
     */
    protected function buildHtml(array $images, bool $pageNumbers = false, bool $compress = true): string
    {
        $html = '<!DOCTYPE html><html dir="rtl"><head><meta charset="UTF-8">';
        $html .= '<style>
            body { margin: 0; padding: 0; direction: rtl; font-family: Vazirmatn, sans-serif; }
            .page { page-break-after: always; text-align: center; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
            .page img { max-width: 100%; max-height: 100vh; object-fit: contain; ';
        if ($compress) {
            $html .= 'image-rendering: auto; ';
        }
        $html .= '}
            .page-number { position: fixed; bottom: 20px; right: 20px; font-size: 12px; color: #727785; background: rgba(255,255,255,0.8); padding: 4px 12px; border-radius: 4px; }
            .footer { position: fixed; bottom: 10px; left: 0; right: 0; text-align: center; font-size: 10px; color: #aaa; }
        </style></head><body>';

        // اضافه کردن تصاویر
        foreach ($images as $index => $image) {
            $html .= '<div class="page">';
            
            // تبدیل به base64
            $imageData = base64_encode(file_get_contents($image));
            $mime = mime_content_type($image);
            $html .= '<img src="data:' . $mime . ';base64,' . $imageData . '" alt="Page ' . ($index + 1) . '">';
            
            if ($pageNumbers) {
                $html .= '<div class="page-number">صفحه ' . ($index + 1) . ' از ' . count($images) . '</div>';
            }
            
            $html .= '</div>';
        }

        $html .= '</body></html>';

        return $html;
    }

    /**
     * ذخیره فایل PDF
     */
    public function savePdf(string $content, string $filePath): bool
    {
        return file_put_contents($filePath, $content) !== false;
    }

    /**
     * تنظیمات سفارشی
     */
    public function setOption(string $key, $value): self
    {
        $this->options->set($key, $value);
        return $this;
    }

    /**
     * دریافت Dompdf instance
     */
    public function getDompdf(): Dompdf
    {
        return $this->dompdf;
    }
}