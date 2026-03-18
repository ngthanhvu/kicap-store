<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keycap = Category::updateOrCreate(
            ['slug' => 'keycap'],
            [
                'name' => 'Keycap',
                'description' => 'Keycap cho ban phim co, nhieu profile va chat lieu.',
                'image' => 'https://placehold.co/600x400?text=Keycap',
                'parent_id' => null,
            ]
        );

        $keyboard = Category::updateOrCreate(
            ['slug' => 'ban-phim-co'],
            [
                'name' => 'Ban phim co',
                'description' => 'Ban phim co va cac combo san choi gear.',
                'image' => 'https://placehold.co/600x400?text=Mechanical+Keyboard',
                'parent_id' => null,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'keycap-pbt'],
            [
                'name' => 'Keycap PBT',
                'description' => 'Keycap PBT ben, it bong va phu hop de dung lau dai.',
                'image' => 'https://placehold.co/600x400?text=PBT+Keycap',
                'parent_id' => $keycap->id,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'switch-lubed'],
            [
                'name' => 'Switch Lubed',
                'description' => 'Switch da duoc lubed san, toi uu cho trai nghiem go.',
                'image' => 'https://placehold.co/600x400?text=Switch+Lubed',
                'parent_id' => $keyboard->id,
            ]
        );
    }
}
