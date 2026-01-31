<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TelegramBotSession extends Model
{
    protected $fillable = [
        'telegram_user_id',
        'state',
        'cart',
        'context',
        'expires_at',
    ];

    protected $casts = [
        'cart' => 'array',
        'context' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Get or create a session for a Telegram user
     */
    public static function getOrCreate($telegramUserId)
    {
        return static::firstOrCreate(
            ['telegram_user_id' => $telegramUserId],
            [
                'state' => 'idle',
                'cart' => [],
                'context' => [],
                'expires_at' => Carbon::now()->addHours(24),
            ]
        );
    }

    /**
     * Update cart with an item
     */
    public function updateCart($productId, $name, $quantity, $price)
    {
        $cart = $this->cart ?? [];
        
        // Check if product already exists in cart
        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] = $quantity;
                $item['subtotal'] = $quantity * $price;
                $found = true;
                break;
            }
        }
        
        // If not found, add new item
        if (!$found) {
            $cart[] = [
                'product_id' => $productId,
                'name' => $name,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $quantity * $price,
            ];
        }
        
        $this->cart = $cart;
        $this->save();
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($productId)
    {
        $cart = $this->cart ?? [];
        $cart = array_filter($cart, fn($item) => $item['product_id'] != $productId);
        $this->cart = array_values($cart); // Re-index
        $this->save();
    }

    /**
     * Clear cart
     */
    public function clearCart()
    {
        $this->cart = [];
        $this->context = [];
        $this->state = 'idle';
        $this->save();
    }

    /**
     * Get cart total
     */
    public function getCartTotal()
    {
        $cart = $this->cart ?? [];
        return array_sum(array_column($cart, 'subtotal'));
    }

    /**
     * Expire session
     */
    public function expire()
    {
        $this->delete();
    }
}
