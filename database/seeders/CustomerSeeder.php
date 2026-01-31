<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Alisher Karimov',
                'phone' => '+998901234567',
                'address' => 'Toshkent sh., Chilonzor tumani, 12-mavze, 45-uy',
                'is_regular' => true,
                'total_debt' => 0,
                'total_purchases' => 2500000,
                'total_orders' => 15,
                'telegram_notifications' => true,
                'notes' => 'Doimiy mijoz, har oyda buyurtma beradi',
            ],
            [
                'name' => 'Nodira Yusupova',
                'phone' => '+998909876543',
                'address' => 'Samarqand sh., Registon ko\'chasi, 23-uy',
                'is_regular' => true,
                'total_debt' => 150000,
                'total_purchases' => 1800000,
                'total_orders' => 8,
                'telegram_notifications' => true,
                'notes' => 'Qarz: 150,000 so\'m',
            ],
            [
                'name' => 'Bobur Rahimov',
                'phone' => '+998931112233',
                'address' => 'Andijon sh., Navoi ko\'chasi, 67-uy',
                'is_regular' => false,
                'total_debt' => 0,
                'total_purchases' => 450000,
                'total_orders' => 3,
                'telegram_notifications' => false,
                'notes' => null,
            ],
            [
                'name' => 'Malika Toshmatova',
                'phone' => '+998945556677',
                'address' => 'Namangan sh., Kosonsoy ko\'chasi, 89-uy',
                'is_regular' => true,
                'total_debt' => 300000,
                'total_purchases' => 3200000,
                'total_orders' => 20,
                'telegram_notifications' => true,
                'notes' => 'Katta mijoz, qarz: 300,000 so\'m',
            ],
            [
                'name' => 'Sardor Abdullayev',
                'phone' => '+998977778899',
                'address' => 'Farg\'ona sh., Mustaqillik ko\'chasi, 12-uy',
                'is_regular' => false,
                'total_debt' => 0,
                'total_purchases' => 680000,
                'total_orders' => 4,
                'telegram_notifications' => false,
                'notes' => null,
            ],
            [
                'name' => 'Dilnoza Ergasheva',
                'phone' => '+998901239999',
                'telegram_username' => '@dilnoza_e',
                'address' => 'Buxoro sh., Alpomish ko\'chasi, 34-uy',
                'is_regular' => true,
                'total_debt' => 0,
                'total_purchases' => 1950000,
                'total_orders' => 12,
                'telegram_notifications' => true,
                'notes' => 'Telegram orqali buyurtma beradi',
            ],
            [
                'name' => 'Jamshid Tursunov',
                'phone' => '+998935554444',
                'address' => 'Qo\'qon sh., Furqat ko\'chasi, 56-uy',
                'is_regular' => false,
                'total_debt' => 75000,
                'total_purchases' => 320000,
                'total_orders' => 2,
                'telegram_notifications' => false,
                'notes' => 'Kichik qarz bor',
            ],
            [
                'name' => 'Zarina Ismoilova',
                'phone' => '+998946667788',
                'telegram_username' => '@zarina_i',
                'address' => 'Jizzax sh., Sharof Rashidov ko\'chasi, 78-uy',
                'is_regular' => true,
                'total_debt' => 0,
                'total_purchases' => 2800000,
                'total_orders' => 18,
                'telegram_notifications' => true,
                'notes' => 'A\'lo mijoz, to\'liq to\'laydi',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
