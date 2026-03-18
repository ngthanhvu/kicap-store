<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keycapCategory = Category::where('slug', 'keycap-pbt')->firstOrFail();
        $switchCategory = Category::where('slug', 'switch-lubed')->firstOrFail();

        $products = [
            [
                'slug' => 'gmk-clone-retro',
                'data' => [
                    'name' => 'GMK Clone Retro',
                    'price' => 890000,
                    'discount_price' => 790000,
                    'original_price' => 990000,
                    'quantity' => 40,
                    'count' => 16,
                    'category_id' => $keycapCategory->id,
                    'description' => 'Bo keycap tông retro, profile Cherry, phu hop layout 65-80%.',
                ],
                'variants' => [
                    ['sku' => 'GMK-RETRO-BASE', 'varriant_name' => 'Base Kit', 'varriant_price' => 790000, 'varriant_quantity' => 25],
                    ['sku' => 'GMK-RETRO-NOV', 'varriant_name' => 'Novelty Kit', 'varriant_price' => 290000, 'varriant_quantity' => 15],
                ],
                'images' => [
                    ['sub_image' => 'https://placehold.co/800x600?text=GMK+Clone+Retro', 'is_main' => true],
                    ['sub_image' => 'https://placehold.co/800x600?text=GMK+Retro+Detail', 'is_main' => false],
                ],
            ],
            [
                'slug' => 'akko-v3-cream-yellow-pro',
                'data' => [
                    'name' => 'Akko V3 Cream Yellow Pro',
                    'price' => 125000,
                    'discount_price' => 109000,
                    'original_price' => 139000,
                    'quantity' => 80,
                    'count' => 32,
                    'category_id' => $switchCategory->id,
                    'description' => 'Switch linear da duoc nhieu nguoi choi gear lua chon de build custom.',
                ],
                'variants' => [
                    ['sku' => 'AKKO-V3-45PCS', 'varriant_name' => 'Hop 45 switch', 'varriant_price' => 109000, 'varriant_quantity' => 45],
                    ['sku' => 'AKKO-V3-90PCS', 'varriant_name' => 'Hop 90 switch', 'varriant_price' => 209000, 'varriant_quantity' => 35],
                ],
                'images' => [
                    ['sub_image' => 'https://placehold.co/800x600?text=Akko+V3+Cream+Yellow+Pro', 'is_main' => true],
                    ['sub_image' => 'https://placehold.co/800x600?text=Akko+V3+Switches', 'is_main' => false],
                ],
            ],
            [
                'slug' => 'kit-gmk67-barebone',
                'data' => [
                    'name' => 'GMK67 Barebone Kit',
                    'price' => 1290000,
                    'discount_price' => null,
                    'original_price' => 1290000,
                    'quantity' => 35,
                    'count' => 8,
                    'category_id' => $switchCategory->id,
                    'description' => 'Kit barebone 67 layout, ho tro mod co ban cho nguoi moi choi.',
                ],
                'variants' => [
                    ['sku' => 'GMK67-WHITE', 'varriant_name' => 'Mau trang', 'varriant_price' => 1290000, 'varriant_quantity' => 20],
                    ['sku' => 'GMK67-BLACK', 'varriant_name' => 'Mau den', 'varriant_price' => 1290000, 'varriant_quantity' => 15],
                ],
                'images' => [
                    ['sub_image' => 'https://placehold.co/800x600?text=GMK67+Barebone', 'is_main' => true],
                ],
            ],
            [
                'slug' => 'xda-9009-keycap-set',
                'data' => [
                    'name' => 'XDA 9009 Keycap Set',
                    'price' => 690000,
                    'discount_price' => 620000,
                    'original_price' => 750000,
                    'quantity' => 30,
                    'count' => 11,
                    'category_id' => $keycapCategory->id,
                    'description' => 'Bo keycap XDA tong mau 9009, phu hop cho setup toi gian va retro.',
                ],
                'variants' => [
                    ['sku' => 'XDA-9009-BASE', 'varriant_name' => 'Base Kit', 'varriant_price' => 620000, 'varriant_quantity' => 18],
                    ['sku' => 'XDA-9009-139', 'varriant_name' => 'Bo 139 key', 'varriant_price' => 690000, 'varriant_quantity' => 12],
                ],
                'images' => [
                    ['sub_image' => 'https://placehold.co/800x600?text=XDA+9009+Set', 'is_main' => true],
                    ['sub_image' => 'https://placehold.co/800x600?text=XDA+9009+Layout', 'is_main' => false],
                ],
            ],
            [
                'slug' => 'artisan-mizu-esc',
                'data' => [
                    'name' => 'Artisan Mizu ESC',
                    'price' => 450000,
                    'discount_price' => null,
                    'original_price' => 450000,
                    'quantity' => 12,
                    'count' => 5,
                    'category_id' => $keycapCategory->id,
                    'description' => 'Artisan phim ESC tong mau Mizu, hop de nhan setup xanh trang.',
                ],
                'variants' => [
                    ['sku' => 'ART-MIZU-R1', 'varriant_name' => 'R1 ESC', 'varriant_price' => 450000, 'varriant_quantity' => 12],
                ],
                'images' => [
                    ['sub_image' => 'https://placehold.co/800x600?text=Artisan+Mizu+ESC', 'is_main' => true],
                ],
            ],
            [
                'slug' => 'gateron-yellow-pro',
                'data' => [
                    'name' => 'Gateron Yellow Pro',
                    'price' => 145000,
                    'discount_price' => 129000,
                    'original_price' => 159000,
                    'quantity' => 70,
                    'count' => 19,
                    'category_id' => $switchCategory->id,
                    'description' => 'Switch linear pho bien, de mod, gia mem va on dinh.',
                ],
                'variants' => [
                    ['sku' => 'GAT-YELLOW-45PCS', 'varriant_name' => 'Hop 45 switch', 'varriant_price' => 129000, 'varriant_quantity' => 40],
                    ['sku' => 'GAT-YELLOW-90PCS', 'varriant_name' => 'Hop 90 switch', 'varriant_price' => 245000, 'varriant_quantity' => 30],
                ],
                'images' => [
                    ['sub_image' => 'https://placehold.co/800x600?text=Gateron+Yellow+Pro', 'is_main' => true],
                    ['sub_image' => 'https://placehold.co/800x600?text=Linear+Switch', 'is_main' => false],
                ],
            ],
        ];

        foreach ($products as $productSeed) {
            $product = Product::updateOrCreate(
                ['slug' => $productSeed['slug']],
                $productSeed['data'] + ['slug' => $productSeed['slug']]
            );

            foreach ($productSeed['variants'] as $variantSeed) {
                Variant::updateOrCreate(
                    ['sku' => $variantSeed['sku']],
                    $variantSeed + ['products_id' => $product->id]
                );
            }

            foreach ($productSeed['images'] as $imageSeed) {
                Image::updateOrCreate(
                    [
                        'products_id' => $product->id,
                        'sub_image' => $imageSeed['sub_image'],
                    ],
                    [
                        'is_main' => $imageSeed['is_main'],
                    ]
                );
            }
        }
    }
}
