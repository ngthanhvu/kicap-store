<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@cc.cc')->firstOrFail();

        $posts = [
            [
                'slug' => 'cach-chon-keycap-cho-layout-65',
                'title' => 'Cach chon keycap cho layout 65',
                'content' => 'Huong dan chon keycap theo profile, layout va tone mau de phoi voi ban phim co.',
                'description' => 'Meo chon keycap cho nguoi moi choi gear.',
                'image' => 'https://placehold.co/1200x630?text=Keycap+Guide',
                'status' => 'published',
            ],
            [
                'slug' => 'switch-linear-va-tactile-khac-nhau-gi',
                'title' => 'Switch linear va tactile khac nhau gi',
                'content' => 'So sanh cam giac go, am thanh va tinh huong su dung cua linear va tactile.',
                'description' => 'Tong hop nhanh cho nguoi dang build custom keyboard.',
                'image' => 'https://placehold.co/1200x630?text=Switch+Guide',
                'status' => 'published',
            ],
            [
                'slug' => 'gmk67-co-phai-kit-quoc-dan',
                'title' => 'GMK67 co phai kit quoc dan',
                'content' => 'Danh gia nhanh uu diem, nhuoc diem va cac bai mod co ban cho kit GMK67.',
                'description' => 'Goc nhin cho nguoi moi bat dau choi custom keyboard.',
                'image' => 'https://placehold.co/1200x630?text=GMK67+Review',
                'status' => 'published',
            ],
            [
                'slug' => '5-mau-keycap-de-phoi-voi-case-trang',
                'title' => '5 mau keycap de phoi voi case trang',
                'content' => 'Tong hop 5 tong mau keycap de len setup sach se, de phoi va ton desk setup.',
                'description' => 'Goi y phoi mau cho ban phim vo trang.',
                'image' => 'https://placehold.co/1200x630?text=Keycap+Color+Match',
                'status' => 'published',
            ],
            [
                'slug' => 'co-nen-mua-artisan-khi-moi-choi-gear',
                'title' => 'Co nen mua artisan khi moi choi gear',
                'content' => 'Phan tich khi nao nen mua artisan, cach dat dung diem nhan va tranh roi setup.',
                'description' => 'Bai viet ngan cho nguoi moi bat dau suu tam.',
                'image' => 'https://placehold.co/1200x630?text=Artisan+Tips',
                'status' => 'published',
            ],
            [
                'slug' => 'huong-dan-build-ban-phim-em-cho-van-phong',
                'title' => 'Huong dan build ban phim em cho van phong',
                'content' => 'Chon switch, foam, stab va keycap de build mot bo phim go em va de dung hang ngay.',
                'description' => 'Checklist nhanh cho setup lam viec.',
                'image' => 'https://placehold.co/1200x630?text=Office+Keyboard+Build',
                'status' => 'published',
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(
                ['slug' => $post['slug']],
                $post + ['user_id' => $admin->id]
            );
        }
    }
}
