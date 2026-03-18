<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Orders;
use App\Models\Orders_item;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'user1@example.com')->firstOrFail();
        $product = Product::where('slug', 'gmk-clone-retro')->firstOrFail();
        $variant = Variant::where('sku', 'GMK-RETRO-BASE')->firstOrFail();

        $address = Address::updateOrCreate(
            [
                'user_id' => $user->id,
                'phone' => '0900000001',
            ],
            [
                'name' => $user->name,
                'province' => 'Ho Chi Minh',
                'district' => 'Quan 1',
                'ward' => 'Ben Nghe',
                'street' => '12 Nguyen Hue',
            ]
        );

        $order = Orders::updateOrCreate(
            [
                'user_id' => $user->id,
                'address_id' => $address->id,
                'payment_method' => 'cod',
            ],
            [
                'status' => 'pending',
                'total_price' => 790000,
            ]
        );

        Orders_item::updateOrCreate(
            [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'variant_id' => $variant->id,
            ],
            [
                'quantity' => 1,
                'price' => 790000,
                'subtotal' => 790000,
            ]
        );
    }
}
