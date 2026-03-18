<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'discount' => 10,
                'type' => 'percent',
                'min_order_amount' => 100000,
                'max_usage' => 100,
                'used_count' => 0,
                'start_date' => now()->subDays(7),
                'end_date' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP30',
                'discount' => 30000,
                'type' => 'fixed',
                'min_order_amount' => 250000,
                'max_usage' => 50,
                'used_count' => 3,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(15),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(
                ['code' => $coupon['code']],
                $coupon
            );
        }
    }
}
