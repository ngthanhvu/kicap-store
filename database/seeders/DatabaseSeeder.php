<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            CouponSeeder::class,
            PostSeeder::class,
            RatingSeeder::class,
            FavoriteSeeder::class,
            CartSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
