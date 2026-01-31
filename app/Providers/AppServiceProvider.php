<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\ShopInformation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::defaultView('vendor.pagination.tailwind');

        if (!app()->runningInConsole()) {
            try {
                // Use a closure or share directly if query is cheap. 
                // Since it's one row, sharing object is fine.
                // We wrap in try-catch to avoid issues during migration if table doesn't exist.
                if (\Illuminate\Support\Facades\Schema::hasTable('shop_information')) {
                    $shopInfo = ShopInformation::firstOrCreate(
                        [],
                        ['name' => "Yem Do'koni CRM"]
                    );
                    View::share('shopInfo', $shopInfo);
                } else {
                     View::share('shopInfo', new ShopInformation(['name' => "Yem Do'koni CRM"]));
                }
            } catch (\Exception $e) {
                View::share('shopInfo', new ShopInformation(['name' => "Yem Do'koni CRM"]));
            }
        }
    }
}
