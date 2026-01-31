<?php

namespace App\Services;

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
     * Make API request
     */
    private function makeRequest($method, $data)
    {
        $url = $this->apiUrl . $method;
        
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        return json_decode($result, true);
    }
}
