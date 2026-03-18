<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Rating;
use App\Models\RatingLike;
use App\Models\User;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userOne = User::where('email', 'user1@example.com')->firstOrFail();
        $userTwo = User::where('email', 'user2@example.com')->firstOrFail();
        $retroKeycap = Product::where('slug', 'gmk-clone-retro')->firstOrFail();
        $akkoSwitch = Product::where('slug', 'akko-v3-cream-yellow-pro')->firstOrFail();

        $ratings = [
            [
                'user_id' => $userOne->id,
                'product_id' => $retroKeycap->id,
                'rating' => '5',
                'comment' => 'Mau dep, legend on va len layout 65 rat vua.',
                'admin_reply' => 'Cam on ban da ung ho shop va chia se trai nghiem.',
            ],
            [
                'user_id' => $userTwo->id,
                'product_id' => $akkoSwitch->id,
                'rating' => '4',
                'comment' => 'Switch go em, dung build phong lam viec kha hop.',
                'admin_reply' => null,
            ],
        ];

        foreach ($ratings as $ratingSeed) {
            $rating = Rating::updateOrCreate(
                [
                    'user_id' => $ratingSeed['user_id'],
                    'product_id' => $ratingSeed['product_id'],
                ],
                $ratingSeed
            );

            RatingLike::firstOrCreate([
                'user_id' => $ratingSeed['user_id'] === $userOne->id ? $userTwo->id : $userOne->id,
                'rating_id' => $rating->id,
            ]);
        }
    }
}
