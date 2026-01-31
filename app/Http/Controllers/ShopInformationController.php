<?php

namespace App\Http\Controllers;

use App\Models\ShopInformation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ShopInformationController extends Controller
{
    /**
     * Display the shop settings form.
     */
    public function edit(): View
    {
        $shop = ShopInformation::firstOrCreate(
            [],
            ['name' => "Yem Do'koni CRM"]
        );

        return view('settings.shop.edit', compact('shop'));
    }

    /**
     * Update the shop settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'], // 2MB max
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'telegram_bot_token' => ['nullable', 'string', 'max:255'],
            'telegram_chat_id' => ['nullable', 'string', 'max:255'],
        ]);

        $shop = ShopInformation::firstOrFail();

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($shop->logo_path) {
                Storage::disk('public')->delete($shop->logo_path);
            }
            
            $path = $request->file('logo')->store('shop', 'public');
            $shop->logo_path = $path;
        }

        $shop->name = $validated['name'];
        $shop->address = $validated['address'];
        $shop->phone = $validated['phone'];
        $shop->email = $validated['email'];
        $shop->telegram_bot_token = $validated['telegram_bot_token'];
        $shop->telegram_chat_id = $validated['telegram_chat_id'];
        $shop->save();

        return redirect()->route('settings.shop.edit')->with('status', 'shop-updated');
    }
}
