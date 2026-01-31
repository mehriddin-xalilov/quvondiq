<?php
/**
 * Telegram Webhook Checker
 * Bu faylni ishga tushiring: php check_webhook.php
 */

$botToken = '8149709810:AAFUGFUIQtoOIo7mWYav4aWFbKfXRohx9G0';
$apiUrl = "https://api.telegram.org/bot{$botToken}/";

echo "🔍 Telegram Webhook holatini tekshiryapman...\n\n";

// Get webhook info
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $apiUrl . 'getWebhookInfo',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
]);

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "❌ Xato: Telegram API ga ulanib bo'lmadi (HTTP {$httpCode})\n";
    exit(1);
}

$response = json_decode($result, true);

if (!$response['ok']) {
    echo "❌ Xato: " . ($response['description'] ?? 'Noma\'lum xato') . "\n";
    exit(1);
}

$info = $response['result'];

echo "📊 Webhook Ma'lumotlari:\n";
echo str_repeat("=", 60) . "\n";
echo "URL: " . ($info['url'] ?: '❌ O\'rnatilmagan') . "\n";
echo "Pending Updates: " . ($info['pending_update_count'] ?? 0) . "\n";
echo "Max Connections: " . ($info['max_connections'] ?? 'N/A') . "\n";

if (isset($info['last_error_date'])) {
    echo "\n⚠️  Oxirgi Xato:\n";
    echo "Vaqt: " . date('Y-m-d H:i:s', $info['last_error_date']) . "\n";
    echo "Xabar: " . ($info['last_error_message'] ?? 'N/A') . "\n";
}

if (isset($info['last_synchronization_error_date'])) {
    echo "\n⚠️  Sinxronizatsiya Xatosi:\n";
    echo "Vaqt: " . date('Y-m-d H:i:s', $info['last_synchronization_error_date']) . "\n";
}

echo str_repeat("=", 60) . "\n";

if (empty($info['url'])) {
    echo "\n❌ MUAMMO: Webhook o'rnatilmagan!\n";
    echo "\n📝 Yechim:\n";
    echo "Webhook o'rnatish uchun quyidagi buyruqni bajaring:\n\n";
    echo "curl -X POST \"https://api.telegram.org/bot{$botToken}/setWebhook\" \\\n";
    echo "  -d \"url=https://SIZNING_DOMEN.uz/telegram/webhook\"\n\n";
} elseif ($info['pending_update_count'] > 0) {
    echo "\n⚠️  DIQQAT: {$info['pending_update_count']} ta kutilayotgan yangilanish bor!\n";
    echo "Bu serveringiz so'rovlarni qabul qilmayotganini anglatadi.\n";
} else {
    echo "\n✅ Webhook to'g'ri ishlayapti!\n";
}
