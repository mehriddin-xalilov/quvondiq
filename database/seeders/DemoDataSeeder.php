<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\WarehouseStock;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding demo data...');

        // Create Categories
        $categories = [
            [
                'name' => 'Tovuq Yemi',
                'description' => 'Tovuqlar uchun maxsus yemlari',
                'icon' => '🐔',
                'sort_order' => 1,
            ],
            [
                'name' => 'Qoramol Yemi',
                'description' => 'Qoramollar uchun yemlari',
                'icon' => '🐄',
                'sort_order' => 2,
            ],
            [
                'name' => 'Baliq Yemi',
                'description' => 'Baliqlar uchun yemlari',
                'icon' => '🐟',
                'sort_order' => 3,
            ],
            [
                'name' => 'Qo\'y Yemi',
                'description' => 'Qo\'ylar uchun yemlari',
                'icon' => '🐑',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        $this->command->info('Categories created!');

        // Create Products
        $tovuqCategory = Category::where('name', 'Tovuq Yemi')->first();
        $qoramolCategory = Category::where('name', 'Qoramol Yemi')->first();
        $baliqCategory = Category::where('name', 'Baliq Yemi')->first();

        $products = [
            // Tovuq yemlari
            [
                'category_id' => $tovuqCategory->id,
                'name' => 'Tovuq Yemi PK-1 (Starter)',
                'code' => 'TY-PK1',
                'description' => '0-3 haftalik jo\'jalar uchun',
                'unit' => 'kg',
                'price' => 8500,
                'cost_price' => 7000,
                'min_stock_level' => 100,
                'optimal_stock_level' => 500,
            ],
            [
                'category_id' => $tovuqCategory->id,
                'name' => 'Tovuq Yemi PK-2 (Grower)',
                'code' => 'TY-PK2',
                'description' => '4-8 haftalik tovuqlar uchun',
                'unit' => 'kg',
                'price' => 8000,
                'cost_price' => 6500,
                'min_stock_level' => 100,
                'optimal_stock_level' => 500,
            ],
            [
                'category_id' => $tovuqCategory->id,
                'name' => 'Tovuq Yemi PK-3 (Finisher)',
                'code' => 'TY-PK3',
                'description' => '9+ haftalik tovuqlar uchun',
                'unit' => 'kg',
                'price' => 7500,
                'cost_price' => 6000,
                'min_stock_level' => 150,
                'optimal_stock_level' => 600,
            ],
            // Qoramol yemlari
            [
                'category_id' => $qoramolCategory->id,
                'name' => 'Qoramol Yemi Konsentrat',
                'code' => 'QY-KON',
                'description' => 'Sut beruvchi sigirlar uchun',
                'unit' => 'kg',
                'price' => 5500,
                'cost_price' => 4500,
                'min_stock_level' => 200,
                'optimal_stock_level' => 800,
            ],
            [
                'category_id' => $qoramolCategory->id,
                'name' => 'Qoramol Yemi Standart',
                'code' => 'QY-STD',
                'description' => 'Oddiy qoramollar uchun',
                'unit' => 'kg',
                'price' => 4500,
                'cost_price' => 3500,
                'min_stock_level' => 200,
                'optimal_stock_level' => 800,
            ],
            // Baliq yemlari
            [
                'category_id' => $baliqCategory->id,
                'name' => 'Baliq Yemi Premium',
                'code' => 'BY-PREM',
                'description' => 'Barcha turdagi baliqlar uchun',
                'unit' => 'kg',
                'price' => 12000,
                'cost_price' => 10000,
                'min_stock_level' => 50,
                'optimal_stock_level' => 300,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);
            
            // Create warehouse stock for each product
            WarehouseStock::create([
                'product_id' => $product->id,
                'quantity' => rand(100, 500),
                'reserved_quantity' => 0,
            ]);
        }

        $this->command->info('Products and warehouse stocks created!');

        // Create Customers
        $customers = [
            [
                'name' => 'Alisher Karimov',
                'phone' => '+998901111111',
                'address' => 'Toshkent shahar, Yunusobod tumani',
                'is_regular' => true,
                'total_purchases' => 5000000,
                'total_orders' => 15,
            ],
            [
                'name' => 'Dilshod Rahimov',
                'phone' => '+998902222222',
                'address' => 'Samarqand viloyati, Samarqand shahar',
                'is_regular' => true,
                'total_purchases' => 3500000,
                'total_orders' => 10,
            ],
            [
                'name' => 'Nodira Toshmatova',
                'phone' => '+998903333333',
                'address' => 'Andijon viloyati, Andijon shahar',
                'is_regular' => false,
                'total_purchases' => 1200000,
                'total_orders' => 3,
            ],
            [
                'name' => 'Rustam Ergashev',
                'phone' => '+998904444444',
                'address' => 'Namangan viloyati, Namangan shahar',
                'is_regular' => true,
                'total_purchases' => 4200000,
                'total_orders' => 12,
                'total_debt' => 500000,
            ],
            [
                'name' => 'Shoira Abdullayeva',
                'phone' => '+998905555555',
                'address' => 'Farg\'ona viloyati, Farg\'ona shahar',
                'is_regular' => false,
                'total_purchases' => 800000,
                'total_orders' => 2,
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }

        $this->command->info('Customers created!');
        $this->command->info('Demo data seeding completed! 🎉');
    }
}
