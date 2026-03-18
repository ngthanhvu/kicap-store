<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userOne = User::where('email', 'user1@example.com')->firstOrFail();
        $userTwo = User::where('email', 'user2@example.com')->firstOrFail();

        $favorites = [
            [$userOne->id, Product::where('slug', 'gmk-clone-retro')->value('id')],
            [$userOne->id, Product::where('slug', 'akko-v3-cream-yellow-pro')->value('id')],
            [$userOne->id, Product::where('slug', 'xda-9009-keycap-set')->value('id')],
            [$userTwo->id, Product::where('slug', 'kit-gmk67-barebone')->value('id')],
            [$userTwo->id, Product::where('slug', 'gateron-yellow-pro')->value('id')],
        ];

        foreach ($favorites as [$userId, $productId]) {
            Favorite::firstOrCreate([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
        }
    }
}
