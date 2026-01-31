<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class TelegramBotService
{
    private $botToken;
    private $apiUrl;

    public function __construct()
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}/";
    }

    /**
     * Send a text message
     */
    public function sendMessage($chatId, $text, $replyMarkup = null)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        if ($replyMarkup) {
            $data['reply_markup'] = json_encode($replyMarkup);
        }

        return $this->makeRequest('sendMessage', $data);
    }

    /**
     * Send message with inline keyboard
     */
    public function sendInlineKeyboard($chatId, $text, $buttons)
    {
        $replyMarkup = [
            'inline_keyboard' => $buttons,
        ];

        return $this->sendMessage($chatId, $text, $replyMarkup);
    }

    /**
     * Answer callback query
     */
    public function answerCallbackQuery($callbackQueryId, $text = null, $showAlert = false)
    {
        $data = [
            'callback_query_id' => $callbackQueryId,
        ];

        if ($text) {
            $data['text'] = $text;
            $data['show_alert'] = $showAlert;
        }

        return $this->makeRequest('answerCallbackQuery', $data);
    }

    /**
     * Edit message text
     */
    public function editMessageText($chatId, $messageId, $text, $replyMarkup = null)
    {
        $data = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        if ($replyMarkup) {
            $data['reply_markup'] = json_encode($replyMarkup);
        }

        return $this->makeRequest('editMessageText', $data);
    }

    /**
     * Request contact from user
     */
    public function requestContact($chatId, $text = "Iltimos, telefon raqamingizni yuboring:")
    {
        $replyMarkup = [
            'keyboard' => [
                [
                    ['text' => '📞 Telefon raqamni yuborish', 'request_contact' => true]
                ]
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ];

        return $this->sendMessage($chatId, $text, $replyMarkup);
    }

    /**
     * Remove keyboard
     */
    public function removeKeyboard($chatId, $text)
    {
        $replyMarkup = [
            'remove_keyboard' => true,
        ];

        return $this->sendMessage($chatId, $text, $replyMarkup);
    }

    /**
     * Make API request using cURL with proper timeout handling
     */
    private function makeRequest($method, $data)
    {
        $url = $this->apiUrl . $method;
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,  // 5 seconds to establish connection
            CURLOPT_TIMEOUT => 10,         // 10 seconds total timeout
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        // Log errors for debugging
        if ($result === false || $httpCode !== 200) {
            \Log::error('Telegram API Error', [
                'url' => $url,
                'method' => $method,
                'http_code' => $httpCode,
                'error' => $error,
                'response' => $result,
                'data' => $data,
            ]);
            
            // Return a default response to prevent fatal errors
            return [
                'ok' => false,
                'error_code' => $httpCode,
                'description' => $error ?: 'Unknown error',
            ];
        }
        
        return json_decode($result, true);
    }
}
