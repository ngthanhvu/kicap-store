# Kicap Store - E-commerce Platform

Một nền tảng thương mại điện tử được xây dựng bằng Laravel, chuyên cung cấp các sản phẩm về mechanical keyboard, keycaps, và phụ kiện.

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.2-7952B3?style=flat-square&logo=bootstrap)

---

## 📑 Mục lục

- [Tính năng](#-tính-năng)
- [Yêu cầu hệ thống](#-yêu-cầu-hệ-thống)
- [Cài đặt](#-cài-đặt)
- [Cấu hình](#-cấu-hình)
- [Chạy ứng dụng](#-chạy-ứng-dụng)
- [Các lệnh hữu ích](#-các-lệnh-hữu-ích)
- [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
- [Cấu trúc dự án](#-cấu-trúc-dự-án)
- [Đóng góp](#-đóng-góp)
- [License](#-license)

---

## ✨ Tính năng

### Người dùng
- ✅ Đăng ký, đăng nhập, quên mật khẩu (OTP qua email)
- ✅ Đăng nhập xã hội (Google, Facebook)
- ✅ Xem danh sách sản phẩm với bộ lọc (danh mục, khoảng giá, sắp xếp)
- ✅ Tìm kiếm sản phẩm
- ✅ Chi tiết sản phẩm với hình ảnh, biến thể, đánh giá
- ✅ Giỏ hàng (thêm, sửa, xóa)
- ✅ Yêu thích sản phẩm
- ✅ Đặt hàng và thanh toán
- ✅ Theo dõi lịch sử đơn hàng
- ✅ Hủy đơn, mua lại đơn cũ
- ✅ Đánh giá và thích sản phẩm

### Thanh toán
- 💳 VNPay
- 💳 MoMo
- 💳 ZaloPay
- 💳 PayPal

### Quản trị (Admin)
- 📊 Dashboard thống kê
- 📦 Quản lý sản phẩm (CRUD, import Excel)
- 🏷️ Quản lý danh mục
- 👥 Quản lý người dùng
- 📝 Quản lý đơn hàng
- 🎫 Quản lý mã giảm giá (coupon)
- ⭐ Quản lý đánh giá, bình luận
- 📰 Quản lý bài viết/blog
- ⚙️ Cài đặt slider trang chủ

---

## 🛠️ Yêu cầu hệ thống

- **PHP**: >= 8.1
- **Composer**: >= 2.x
- **Database**: MySQL / PostgreSQL / SQLite
- **Node.js**: >= 16.x (cho Vite)
- **npm** hoặc **yarn**

---

## 📦 Cài đặt

### 1. Clone repository

```bash
git clone <repository-url>
cd kicap-store
```

### 2. Cài đặt dependencies

```bash
composer install
npm install
```

### 3. Cấu hình môi trường

```bash
copy .env.example .env
```

Sửa file `.env` với thông tin database của bạn:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kicap_store
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Tạo database và chạy migrations

```bash
php artisan migrate
```

### 5. Tạo application key

```bash
php artisan key:generate
```

### 6. Link storage

```bash
php artisan storage:link
```

### 7. Build assets (nếu cần)

```bash
npm run build
```

Hoặc chạy development mode:

```bash
npm run dev
```

---

## ⚙️ Cấu hình

### Social Login

Thêm thông tin vào `.env`:

```env
# Google
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT=http://localhost:8000/login/google/callback

# Facebook
FACEBOOK_CLIENT_ID=your-facebook-client-id
FACEBOOK_CLIENT_SECRET=your-facebook-client-secret
FACEBOOK_REDIRECT=http://localhost:8000/login/facebook/callback

# PayPal
PAYPAL_CLIENT_ID=your-paypal-client-id
PAYPAL_SECRET=your-paypal-secret
PAYPAL_MODE=sandbox

# MoMo
MOMO_PARTNER_CODE=your-partner-code
MOMO_ACCESS_KEY=your-access-key
MOMO_SECRET_KEY=your-secret-key

# VNPay
VNPAY_TMN_CODE=your-tmncode
VNPAY_HASH_SECRET=your-secret
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_RETURN_URL=http://localhost:8000/vnpay/callback

# ZaloPay
ZALOPAY_APP_ID=your-app-id
ZALOPAY_KEY1=your-key1
ZALOPAY_KEY2=your-key2
```

### Email (cho OTP và thông báo)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@kicap.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🚀 Chạy ứng dụng

### Cách 1: Chạy trực tiếp (Development)

```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

### Cách 2: Chạy bằng Docker (Khuyến nghị)

Dự án đã được cấu hình sẵn Docker với 2 services: **app** (Laravel) và **db** (MySQL).

#### Bước 1: Chuẩn bị môi trường

```bash
# Copy file cấu hình
copy .env.example .env

# Chỉnh sửa .env nếu cần (tuỳ chọn)
# Các biến môi trường cho Docker:
# - DB_DATABASE=php3_final
# - DB_USERNAME=laravel
# - DB_PASSWORD=secret
# - DB_ROOT_PASSWORD=root
```

#### Bước 2: Build và chạy containers

```bash
# Build và start tất cả services
docker-compose up -d --build

# Xem logs để theo dõi
docker-compose logs -f
```

#### Bước 3: Chạy migrations (nếu cần)

```bash
# Option 1: Set biến môi trường để tự động migrate
# Thêm vào .env: RUN_MIGRATIONS=true
# Sau đó restart container
docker-compose restart app

# Option 2: Chạy migration thủ công
docker-compose exec app php artisan migrate
```

#### Bước 4: Truy cập ứng dụng

- **Ứng dụng**: http://localhost:8000
- **Database**: localhost:3309 (MySQL 8.0)

#### Một số lệnh Docker hữu ích

```bash
# Xem danh sách containers đang chạy
docker-compose ps

# Xem logs
docker-compose logs -f app
docker-compose logs -f db

# Chạy lệnh trong container
docker-compose exec app php artisan list
docker-compose exec app composer install
docker-compose exec app npm install
docker-compose exec app npm run build

# Dừng containers
docker-compose down

# Dừng và xóa volumes (lưu ý: sẽ mất dữ liệu database)
docker-compose down -v

# Restart services
docker-compose restart

# Rebuild container
docker-compose up -d --build --force-recreate
```

#### Tạo admin user trong Docker

```bash
docker-compose exec app php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@kicap.com',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
```

---

### Troubleshooting Docker

**Lỗi permission storage:**
```bash
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chmod -R ug+rwx /var/www/storage
```

**Lỗi database connection:**
- Kiểm tra DB trong `.env` đã đúng với `docker-compose.yml`
- Đảm bảo db container đã healthy: `docker-compose ps`
- Xem logs db: `docker-compose logs db`

**Lỗi composer install trong container:**
```bash
docker-compose exec app composer install --no-interaction
```

**Reset hoàn toàn và build lại:**
```bash
docker-compose down -v
docker-compose up -d --build
docker-compose exec app php artisan migrate:fresh --seed
```

### Tài khoản admin mặc định

Sau khi chạy migrations, tạo admin:

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@kicap.com',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
```

---

## 🔧 Các lệnh hữu ích

```bash
# Chạy tests
php artisan test

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize cho production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Tạo model, migration, controller
php artisan make:model Product -mcr
php artisan make:controller Admin/ProductController --resource
php artisan make:migration create_products_table

# Seeder
php artisan db:seed
php artisan db:seed --class=ProductSeeder
```

---

## 🧰 Công nghệ sử dụng

### Backend
- **Laravel 10.x** - PHP Framework
- **Laravel Socialite** - Social authentication
- **Guzzle HTTP Client** - HTTP requests
- **Barryvdh DomPDF** - PDF generation (hóa đơn)
- **Simple QRCode** - QR code generation
- **Maatwebsite Excel** - Excel import/export

### Frontend
- **Bootstrap 5.2** - CSS Framework
- **Tailwind CSS** - Utility-first CSS
- **Vanilla JavaScript** - Interactivity
- **Swiper.js** - Carousel/Slider
- **SweetAlert2** - Alert dialogs
- **iziToast** - Toast notifications
- **FontAwesome 6** - Icons
- **Bootstrap Icons** - Icons

### Database
- **MySQL** - Primary database

### Payment Gateways
- **VNPay**
- **MoMo**
- **ZaloPay**
- **PayPal**

---

## 📁 Cấu trúc dự án

```
kicap-store/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Controllers
│   │   └── Middleware/       # Middleware
│   ├── Models/               # Eloquent Models
│   ├── Mail/                 # Mailable classes
│   └── Services/             # Service classes
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── public/
│   ├── css/                  # CSS files
│   ├── js/                   # JavaScript files
│   └── storage/              # Uploaded files (symlink)
├── resources/
│   ├── views/                # Blade templates
│   │   ├── admin/            # Admin views
│   │   ├── auth/             # Authentication views
│   │   ├── profile/          # User profile views
│   │   ├── includes/         # Partial views
│   │   └── ...
│   └── js/
├── routes/
│   └── web.php               # Web routes
├── storage/
│   └── app/
│       └── public/           # Public storage
├── .env                      # Environment configuration
├── .env.example              # Example environment
├── composer.json             # PHP dependencies
├── package.json              # Node dependencies
└── vite.config.js            # Vite configuration
```

---

## 🤝 Đóng góp

Mọi đóng góp đều được chào đón! Vui lòng:

1. Fork project
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Mở Pull Request

---

## 📄 License

Dự án này được cấp phép theo [MIT License](LICENSE).

---

## 📞 Liên hệ

- **Email**: support@kicap.com
- **Website**: https://kicap-store.com

---

<div align="center">

**Made with ❤️ by Kicap Store Team**

</div>
