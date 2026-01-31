<?php

namespace App\Http\Controllers;

use App\Models\TelegramOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exports\TelegramOrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class TelegramOrderController extends Controller
{
    public function index()
    {
        $orders = TelegramOrder::pending()->orderBy('created_at', 'desc')->paginate(20);
        return view('telegram-orders.index', compact('orders'));
    }

    public function show(TelegramOrder $telegramOrder)
    {
        return view('telegram-orders.show', compact('telegramOrder'));
    }

    public function destroy(TelegramOrder $telegramOrder)
    {
        $telegramOrder->delete();
        return redirect()->route('telegram-orders.index')->with('success', 'Buyurtma o\'chirildi');
    }

    public function simulate()
    {
        $products = Product::inRandomOrder()->limit(3)->get();
        
        if ($products->isEmpty()) {
            return back()->with('error', 'Mahsulotlar yo\'q, simulyatsiya qilib bo\'lmaydi');
        }

        $items = [];
        $total = 0;

        foreach ($products as $product) {
            $qty = rand(1, 5);
            $price = $product->price;
            $subtotal = $qty * $price;
            
            $items[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $qty,
                'price' => $price,
                'subtotal' => $subtotal,
            ];
            
            $total += $subtotal;
        }

        TelegramOrder::create([
            'telegram_user_id' => rand(100000, 999999),
            'telegram_username' => 'user_' . rand(100, 999),
            'customer_name' => 'Telegram User ' . rand(1, 50),
            'customer_phone' => '+99890' . rand(1000000, 9999999),
            'customer_address' => 'Tashkent, Street ' . rand(1, 100),
            'items' => $items,
            'total' => $total,
            'status' => 'pending',
            'customer_notes' => 'Tezroq yetkazib bering',
            'telegram_message_id' => rand(1, 10000),
        ]);

        return redirect()->route('telegram-orders.index')->with('success', 'Test buyurtma yaratildi');
    }

    public function webhook(Request $request)
    {
        $update = $request->all();
        \Log::info('Telegram Webhook:', $update);

        // Handle callback queries (inline button clicks)
        if (isset($update['callback_query'])) {
            return $this->handleCallbackQuery($update['callback_query']);
        }

        // Handle messages
        if (isset($update['message'])) {
            $message = $update['message'];
            
            // Handle contact shared
            if (isset($message['contact'])) {
                return $this->handleContact($message);
            }
            
            // Handle text commands
            if (isset($message['text'])) {
                return $this->handleTextMessage($message);
            }
        }

        return response('OK', 200);
    }

    private function handleTextMessage($message)
    {
        $text = $message['text'];
        $chatId = $message['chat']['id'];
        $telegramUserId = $message['from']['id'];
        $bot = new \App\Services\TelegramBotService();

        // Handle commands
        if ($text === '/start') {
            $session = \App\Models\TelegramBotSession::getOrCreate($telegramUserId);
            $session->clearCart();
            
            $categories = \App\Models\Category::all();
            $buttons = [];
            
            foreach ($categories as $category) {
                $buttons[] = [
                    ['text' => $category->name, 'callback_data' => 'category_' . $category->id]
                ];
            }
            
            $buttons[] = [
                ['text' => '🛒 Savat', 'callback_data' => 'view_cart'],
            ];
            
            $bot->sendInlineKeyboard($chatId, "🛍 Xush kelibsiz! Kategoriyani tanlang:", $buttons);
            return response('OK', 200);
        }

        if ($text === '/cart') {
            return $this->showCart($telegramUserId, $chatId);
        }

        if ($text === '/cancel') {
            $session = \App\Models\TelegramBotSession::getOrCreate($telegramUserId);
            $session->clearCart();
            $bot->sendMessage($chatId, "❌ Savat tozalandi. /start buyrug'ini yuboring.");
            return response('OK', 200);
        }

        // Handle address input (when state is waiting_for_address)
        $session = \App\Models\TelegramBotSession::getOrCreate($telegramUserId);
        if ($session->state === 'waiting_for_address') {
            return $this->handleAddress($message, $session);
        }

        // Default: unknown message
        $bot->sendMessage($chatId, "Buyruqni tushunmadim. /start ni bosing.");
        return response('OK', 200);
    }

    private function handleCallbackQuery($callbackQuery)
    {
        $data = $callbackQuery['data'];
        $chatId = $callbackQuery['message']['chat']['id'];
        $messageId = $callbackQuery['message']['message_id'];
        $telegramUserId = $callbackQuery['from']['id'];
        $bot = new \App\Services\TelegramBotService();

        // Answer callback query (non-blocking - don't let it fail the webhook)
        try {
            $bot->answerCallbackQuery($callbackQuery['id']);
        } catch (\Exception $e) {
            \Log::warning('Failed to answer callback query', [
                'callback_id' => $callbackQuery['id'],
                'error' => $e->getMessage()
            ]);
        }

        // Category selected
        if (str_starts_with($data, 'category_')) {
            $categoryId = str_replace('category_', '', $data);
            return $this->showProducts($categoryId, $chatId, $messageId, $telegramUserId);
        }

        // Product selected
        if (str_starts_with($data, 'product_')) {
            $productId = str_replace('product_', '', $data);
            return $this->showProductDetails($productId, $chatId, $messageId, $telegramUserId);
        }

        // Add to cart
        if (str_starts_with($data, 'add_')) {
            // Format: add_{productId}_{quantity}
            $parts = explode('_', $data);
            $productId = $parts[1];
            $quantity = $parts[2];
            return $this->addToCart($productId, $quantity, $chatId, $messageId, $telegramUserId);
        }

        // Remove from cart
        if (str_starts_with($data, 'remove_')) {
            $productId = str_replace('remove_', '', $data);
            return $this->removeFromCart($productId, $chatId, $messageId, $telegramUserId);
        }

        // View cart
        if ($data === 'view_cart') {
            return $this->showCart($telegramUserId, $chatId, $messageId);
        }

        // Back to categories
        if ($data === 'back_to_categories') {
            return $this->showCategories($chatId, $messageId, $telegramUserId);
        }

        // Checkout
        if ($data === 'checkout') {
            return $this->initiateCheckout($chatId, $telegramUserId);
        }

        return response('OK', 200);
    }

    private function showCategories($chatId, $messageId, $telegramUserId)
    {
        $bot = new \App\Services\TelegramBotService();
        $categories = \App\Models\Category::all();
        $buttons = [];
        
        foreach ($categories as $category) {
            $buttons[] = [
                ['text' => $category->name, 'callback_data' => 'category_' . $category->id]
            ];
        }
        
        $buttons[] = [
            ['text' => '🛒 Savat', 'callback_data' => 'view_cart'],
        ];
        
        $bot->editMessageText($chatId, $messageId, "🛍 Kategoriyani tanlang:", ['inline_keyboard' => $buttons]);
        return response('OK', 200);
    }

    private function showProducts($categoryId, $chatId, $messageId, $telegramUserId)
    {
        $bot = new \App\Services\TelegramBotService();
        $category = \App\Models\Category::find($categoryId);
        $products = \App\Models\Product::where('category_id', $categoryId)
            ->where('is_active', true)
            ->whereHas('stock', function($query) {
                $query->where('quantity', '>', 0);
            })
            ->get();
        
        if ($products->isEmpty()) {
            $bot->editMessageText($chatId, $messageId, "❌ Bu kategoriyada mahsulotlar yo'q.", [
                'inline_keyboard' => [[['text' => '« Orqaga', 'callback_data' => 'back_to_categories']]]
            ]);
            return response('OK', 200);
        }
        
        $buttons = [];
        foreach ($products as $product) {
            $buttons[] = [
                ['text' => "{$product->name} - " . number_format($product->price, 0, '.', ' ') . " so'm", 'callback_data' => 'product_' . $product->id]
            ];
        }
        
        $buttons[] = [
            ['text' => '« Orqaga', 'callback_data' => 'back_to_categories'],
            ['text' => '🛒 Savat', 'callback_data' => 'view_cart'],
        ];
        
        $bot->editMessageText($chatId, $messageId, "📦 {$category->name}\n\nMahsulotni tanlang:", ['inline_keyboard' => $buttons]);
        return response('OK', 200);
    }

    private function showProductDetails($productId, $chatId, $messageId, $telegramUserId)
    {
        $bot = new \App\Services\TelegramBotService();
        $product = \App\Models\Product::with('stock')->find($productId);
        
        if (!$product) {
            // Product not found, just return
            return response('OK', 200);
        }
        
        $stockQty = $product->stock ? (int)$product->stock->quantity : 0;
        
        $text = "📦 <b>{$product->name}</b>\n\n";
        $text .= "💰 Narx: " . number_format($product->price, 0, '.', ' ') . " so'm\n";
        $text .= "📊 Omborda: {$stockQty} dona\n\n";
        $text .= "Miqdorni tanlang:";
        
        $buttons = [
            [
                ['text' => '1', 'callback_data' => "add_{$productId}_1"],
                ['text' => '2', 'callback_data' => "add_{$productId}_2"],
                ['text' => '3', 'callback_data' => "add_{$productId}_3"],
            ],
            [
                ['text' => '5', 'callback_data' => "add_{$productId}_5"],
                ['text' => '10', 'callback_data' => "add_{$productId}_10"],
            ],
            [
                ['text' => '« Orqaga', 'callback_data' => 'category_' . $product->category_id],
            ],
        ];
        
        $bot->editMessageText($chatId, $messageId, $text, ['inline_keyboard' => $buttons]);
        return response('OK', 200);
    }

    private function addToCart($productId, $quantity, $chatId, $messageId, $telegramUserId)
    {
        $bot = new \App\Services\TelegramBotService();
        $product = \App\Models\Product::find($productId);
        $session = \App\Models\TelegramBotSession::getOrCreate($telegramUserId);
        
        $session->updateCart($productId, $product->name, $quantity, $product->price);
        
        // Show updated cart
        return $this->showCart($telegramUserId, $chatId, $messageId);
    }

    private function removeFromCart($productId, $chatId, $messageId, $telegramUserId)
    {
        $session = \App\Models\TelegramBotSession::getOrCreate($telegramUserId);
        $session->removeFromCart($productId);
        
        return $this->showCart($telegramUserId, $chatId, $messageId);
    }

    private function showCart($telegramUserId, $chatId, $messageId = null)
    {
        $bot = new \App\Services\TelegramBotService();
        $session = \App\Models\TelegramBotSession::getOrCreate($telegramUserId);
        $cart = $session->cart ?? [];
        
        if (empty($cart)) {
            $text = "🛒 Savatingiz bo'sh.\n\n/start buyrug'ini yuboring.";
            $buttons = [[['text' => '🛍 Xarid qilish', 'callback_data' => 'back_to_categories']]];
        } else {
            $text = "🛒 <b>Savatingiz:</b>\n\n";
            foreach ($cart as $item) {
                $text .= "• {$item['name']}\n";
                $text .= "  {$item['quantity']} x " . number_format($item['price'], 0, '.', ' ') . " = " . number_format($item['subtotal'], 0, '.', ' ') . " so'm\n\n";
            }
            $text .= "💰 <b>Jami: " . number_format($session->getCartTotal(), 0, '.', ' ') . " so'm</b>";
            
            $buttons = [];
            foreach ($cart as $item) {
                $buttons[] = [
                    ['text' => "❌ {$item['name']}", 'callback_data' => 'remove_' . $item['product_id']]
                ];
            }
            $buttons[] = [
                ['text' => '➕ Yana qo\'shish', 'callback_data' => 'back_to_categories'],
                ['text' => '✅ Buyurtma berish', 'callback_data' => 'checkout'],
            ];
        }
        
        if ($messageId) {
            $bot->editMessageText($chatId, $messageId, $text, ['inline_keyboard' => $buttons]);
        } else {
            $bot->sendInlineKeyboard($chatId, $text, $buttons);
        }
        
        return response('OK', 200);
    }

    private function initiateCheckout($chatId, $telegramUserId)
    {
        $bot = new \App\Services\TelegramBotService();
        $session = \App\Models\TelegramBotSession::getOrCreate($telegramUserId);
        
        if (empty($session->cart)) {
            $bot->sendMessage($chatId, "❌ Savatingiz bo'sh!");
            return response('OK', 200);
        }
        
        $session->state = 'waiting_for_contact';
        $session->save();
        
        $bot->requestContact($chatId, "📞 Iltimos, telefon raqamingizni yuboring:");
        return response('OK', 200);
    }

    private function handleContact($message)
    {
        $bot = new \App\Services\TelegramBotService();
        $chatId = $message['chat']['id'];
        $telegramUserId = $message['from']['id'];
        $contact = $message['contact'];
        $session = \App\Models\TelegramBotSession::getOrCreate($telegramUserId);
        
        $context = $session->context ?? [];
        $context['phone'] = $contact['phone_number'];
        $session->context = $context;
        $session->state = 'waiting_for_address';
        $session->save();
        
        $bot->removeKeyboard($chatId, "✅ Telefon raqam qabul qilindi.\n\n📍 Endi manzilni yozing:");
        return response('OK', 200);
    }

    private function handleAddress($message, $session)
    {
        $bot = new \App\Services\TelegramBotService();
        $chatId = $message['chat']['id'];
        $address = $message['text'];
        $telegramUserId = $message['from']['id'];
        
        $context = $session->context ?? [];
        $context['address'] = $address;
        $session->context = $context;
        $session->save();
        
        // Create order
        $customerName = $message['from']['first_name'] . ' ' . ($message['from']['last_name'] ?? '');
        
        TelegramOrder::create([
            'telegram_user_id' => $telegramUserId,
            'telegram_username' => $message['from']['username'] ?? null,
            'customer_name' => $customerName,
            'customer_phone' => $context['phone'],
            'customer_address' => $address,
            'items' => $session->cart,
            'total' => $session->getCartTotal(),
            'status' => 'pending',
            'customer_notes' => null,
            'telegram_message_id' => $message['message_id'],
        ]);
        
        $session->clearCart();
        
        $bot->sendMessage($chatId, "✅ Buyurtma qabul qilindi!\n\nTez orada siz bilan bog'lanamiz.\n\n/start - Yangi buyurtma");
        return response('OK', 200);
    }

    public function export()
    {
        // Replicating index logic: pending orders
        $orders = TelegramOrder::pending()->orderBy('created_at', 'desc')->take(50)->get();
        return Excel::download(new TelegramOrdersExport($orders), 'telegram_orders.xlsx');
    }
}
