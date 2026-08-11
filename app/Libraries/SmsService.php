<?php

namespace App\Libraries;

class SmsService
{
    // ✅ تنظیمات مستقیم
    protected $apiKey = 'JUD690sNK2lDB1vn';
    protected $botId = 696498696;
    protected $baseUrl = 'https://safir.bale.ai/api/v3';
    protected $request;

    public function __construct()
    {
        $this->request = service('request');
        
        if (empty($this->apiKey)) {
            throw new \Exception('API Key سرویس بله تنظیم نشده است.');
        }
        if (empty($this->botId)) {
            throw new \Exception('Bot ID سرویس بله تنظیم نشده است.');
        }
    }

    /**
     * ارسال پیامک ساده (متن)
     */
    public function send($phoneNumber, $message)
    {
        // فرمت شماره تلفن: 989xxxxxxxxx
        $phone = $this->formatPhoneNumber($phoneNumber);
        
        $data = [
            'request_id' => $this->generateRequestId(),
            'bot_id' => (int) $this->botId,
            'phone_number' => $phone,
            'message_data' => [
                'message' => [
                    'text' => $message
                ]
            ]
        ];

        return $this->sendRequest('/send_message', $data);
    }

    /**
     * ارسال پیام با قالب (OTP - رمز یکبار مصرف)
     */
    public function sendTemplate($phoneNumber, $template, $params = [])
    {
        // در سرویس بله، OTP از طریق otp_message ارسال می‌شود
        $phone = $this->formatPhoneNumber($phoneNumber);
        $otp = $params[0] ?? null;
        
        if (!$otp) {
            return [
                'success' => false,
                'message' => 'کد OTP ارسال نشده است.'
            ];
        }

        $data = [
            'request_id' => $this->generateRequestId(),
            'bot_id' => (int) $this->botId,
            'phone_number' => $phone,
            'message_data' => [
                'otp_message' => [
                    'otp' => $otp
                ]
            ]
        ];

        return $this->sendRequest('/send_message', $data);
    }

    /**
     * ارسال کد تایید (OTP)
     */
    public function sendVerificationCode($phone, $code)
    {
        try {
            $phone = $this->formatPhoneNumber($phone);
            
            $data = [
                'request_id' => $this->generateRequestId(),
                'bot_id' => (int) $this->botId,
                'phone_number' => $phone,
                'message_data' => [
                    'otp_message' => [
                        'otp' => $code
                    ]
                ]
            ];

            return $this->sendRequest('/send_message', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'SmsService sendVerificationCode Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'خطا در ارسال کد تایید: ' . $e->getMessage()
            ];
        }
    }

    /**
     * ارسال پیام اطلاع‌رسانی
     */
    public function sendNotification($phone, $name, $documentNumber)
    {
        $message = "{$name} گرامی\nنامه شما با شماره {$documentNumber} صادر شد.\nبرای استعلام به سامانه عدالت‌سامان مراجعه کنید.";
        return $this->send($phone, $message);
    }

    /**
     * ارسال پیام تأیید گزارش جعل
     */
    public function sendReportConfirmation($phone, $reportNumber)
    {
        $message = "گزارش شما با شماره {$reportNumber} ثبت شد.\nکارشناسان ما در اسرع وقت بررسی می‌کنند.\nعدالت‌سامان";
        return $this->send($phone, $message);
    }

    /**
     * ارسال پیام با دکمه شیشه‌ای
     */
    public function sendWithButton($phone, $message, $buttonText, $buttonUrl)
    {
        $phone = $this->formatPhoneNumber($phone);
        
        $data = [
            'request_id' => $this->generateRequestId(),
            'bot_id' => (int) $this->botId,
            'phone_number' => $phone,
            'message_data' => [
                'message' => [
                    'text' => $message,
                    'reply_markup' => [
                        'inline_keyboard' => [
                            [
                                [
                                    'text' => $buttonText,
                                    'url' => $buttonUrl
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $this->sendRequest('/send_message', $data);
    }

    /**
     * ارسال پیام با دکمه کپی متن
     */
    public function sendWithCopyButton($phone, $message, $copyText)
    {
        $phone = $this->formatPhoneNumber($phone);
        
        $data = [
            'request_id' => $this->generateRequestId(),
            'bot_id' => (int) $this->botId,
            'phone_number' => $phone,
            'message_data' => [
                'message' => [
                    'text' => $message,
                    'copy_text' => $copyText,
                    'reply_markup' => [
                        'inline_keyboard' => [
                            [
                                [
                                    'text' => '📋 کپی',
                                    'copy_text' => $copyText
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $this->sendRequest('/send_message', $data);
    }

    /**
     * ارسال پیام چندرسانه‌ای
     */
    public function sendFile($phone, $caption, $fileId)
    {
        $phone = $this->formatPhoneNumber($phone);
        
        $data = [
            'request_id' => $this->generateRequestId(),
            'bot_id' => (int) $this->botId,
            'phone_number' => $phone,
            'message_data' => [
                'message' => [
                    'text' => $caption,
                    'file_id' => $fileId
                ]
            ]
        ];

        return $this->sendRequest('/send_message', $data);
    }

    /**
     * ارسال پیام رمزدار (Secure)
     */
    public function sendSecure($phone, $message)
    {
        $phone = $this->formatPhoneNumber($phone);
        
        $data = [
            'request_id' => $this->generateRequestId(),
            'bot_id' => (int) $this->botId,
            'phone_number' => $phone,
            'message_data' => [
                'is_secure' => true,
                'message' => [
                    'text' => $message
                ]
            ]
        ];

        return $this->sendRequest('/send_message', $data);
    }

    /**
     * بارگذاری فایل در سرویس بله
     */
    public function uploadFile($filePath)
    {
        if (!file_exists($filePath)) {
            return [
                'success' => false,
                'message' => 'فایل یافت نشد.'
            ];
        }

        $url = $this->baseUrl . '/upload_file';
        
        // ایجاد CURLFile
        $file = new \CURLFile($filePath);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'api-access-key: ' . $this->apiKey
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'file' => $file
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $result = json_decode($response, true);
            return [
                'success' => true,
                'file_id' => $result['file_id'] ?? null,
                'data' => $result
            ];
        }

        return [
            'success' => false,
            'message' => 'خطا در بارگذاری فایل: ' . $response,
            'http_code' => $httpCode
        ];
    }

    /**
     * ارسال درخواست به API بله
     */
    private function sendRequest($endpoint, $data)
    {
        try {
            $url = $this->baseUrl . $endpoint;
            
            $jsonData = json_encode($data);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'api-access-key: ' . $this->apiKey,
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10); // ✅ تنظیم timeout
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ✅ برای محیط توسعه
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            // بررسی خطاهای HTTP
            if ($error) {
                log_message('error', 'SmsService Error: ' . $error);
                return [
                    'success' => false,
                    'message' => 'خطا در اتصال به سرور: ' . $error,
                    'http_code' => $httpCode
                ];
            }

            // اگر پاسخ خالی بود
            if (empty($response)) {
                return [
                    'success' => false,
                    'message' => 'پاسخ خالی از سرور دریافت شد.',
                    'http_code' => $httpCode
                ];
            }

            $result = json_decode($response, true);

            // اگر JSON معتبر نبود
            if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'success' => false,
                    'message' => 'پاسخ نامعتبر از سرور: ' . json_last_error_msg(),
                    'http_code' => $httpCode,
                    'raw_response' => $response
                ];
            }

            // بررسی خطاهای API
            if ($httpCode === 200 && isset($result['error_data']) && $result['error_data'] !== null) {
                // خطا در ارسال پیام
                $errorInfo = $result['error_data'];
                if (is_array($errorInfo) && isset($errorInfo[0])) {
                    $errorInfo = $errorInfo[0];
                }
                
                $errorMessage = $this->getErrorMessage($errorInfo['code'] ?? 0, $errorInfo['description'] ?? 'خطای ناشناخته');
                log_message('error', 'SmsService API Error: ' . $errorMessage);
                
                return [
                    'success' => false,
                    'message' => $errorMessage,
                    'code' => $errorInfo['code'] ?? 0,
                    'raw_response' => $result
                ];
            }

            if ($httpCode !== 200) {
                $errorMessage = $result['error'] ?? $response ?? 'خطای ناشناخته';
                log_message('error', 'SmsService HTTP Error: ' . $httpCode . ' - ' . $errorMessage);
                
                return [
                    'success' => false,
                    'message' => 'خطا در ارسال پیام: ' . $errorMessage,
                    'http_code' => $httpCode,
                    'raw_response' => $result
                ];
            }

            return [
                'success' => true,
                'message_id' => $result['message_id'] ?? null,
                'data' => $result
            ];

        } catch (\Exception $e) {
            log_message('error', 'SmsService Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'خطای داخلی در سرویس پیامک: ' . $e->getMessage(),
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * دریافت پیام خطا بر اساس کد
     */
    private function getErrorMessage($code, $description)
    {
        $messages = [
            2 => 'خطای داخلی سرور',
            3 => 'محدودیت ارسال پیام (Rate Limit)',
            4 => 'ورودی JSON نامعتبر',
            8 => 'شماره تلفن نامعتبر',
            17 => 'کاربر اکانت بله ندارد',
            20 => 'اعتبار کافی وجود ندارد',
            21 => 'محدودیت تعداد مخاطبین بازو',
        ];

        return $messages[$code] ?? ($description ?? 'خطای ناشناخته');
    }

    /**
     * فرمت کردن شماره تلفن (989xxxxxxxxx)
     */
    private function formatPhoneNumber($phone)
    {
        // حذف تمام کاراکترهای غیرعددی
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // اگر با 0 شروع شده، حذف کن
        if (substr($phone, 0, 1) === '0') {
            $phone = substr($phone, 1);
        }
        
        // اگر با 98 شروع نشده، اضافه کن
        if (substr($phone, 0, 2) !== '98') {
            $phone = '98' . $phone;
        }
        
        return $phone;
    }

    /**
     * تولید Request ID یکتا
     */
    private function generateRequestId()
    {
        return uniqid('msg_', true) . '_' . bin2hex(random_bytes(8));
    }

    /**
     * دریافت اعتبار حساب (در سرویس بله این قابلیت وجود ندارد)
     */
    public function getBalance()
    {
        return [
            'success' => false,
            'message' => 'سرویس بله اطلاعات اعتبار را ارائه نمی‌دهد.',
        ];
    }

    /**
     * دریافت وضعیت پیام (در سرویس بله این قابلیت وجود ندارد)
     */
    public function getStatus($messageId)
    {
        return [
            'success' => false,
            'message' => 'سرویس بله وضعیت پیام را ارائه نمی‌دهد.',
        ];
    }
}