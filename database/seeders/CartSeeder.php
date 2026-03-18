<?php

namespace Database\Seeders;

use App\Models\Carts;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userOne = User::where('email', 'user1@example.com')->firstOrFail();
        $product = Product::where('slug', 'akko-v3-cream-yellow-pro')->firstOrFail();
        $variant = Variant::where('sku', 'AKKO-V3-45PCS')->firstOrFail();

        Carts::updateOrCreate(
            [
                'user_id' => $userOne->id,
                'product_id' => $product->id,
                'variant_id' => $variant->id,
            ],
            [
                'session_id' => null,
                'quantity' => 2,
                'price' => $variant->varriant_price,
            ]
        );

        $guestProduct = Product::where('slug', 'kit-gmk67-barebone')->firstOrFail();
        $guestVariant = Variant::where('sku', 'GMK67-WHITE')->firstOrFail();

        Carts::updateOrCreate(
            [
                'user_id' => null,
                'product_id' => $guestProduct->id,
                'variant_id' => $guestVariant->id,
                'session_id' => 'demo-session-001',
            ],
            [
                'quantity' => 1,
                'price' => $guestVariant->varriant_price,
            ]
        );
    }
}
