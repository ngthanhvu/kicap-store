<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartsController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SettingController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dang-nhap', function () {
    $title = 'Đăng nhập';
    return view('auth.login', compact('title'));
})->name('login');
Route::get('/dang-ky', function () {
    $title = 'Đăng ký';
    return view('auth.register', compact('title'));
});
Route::get('/san-pham', [HomeController::class, 'products'])->name('products');
//carts
Route::get('/gio-hang', [CartsController::class, 'index'])->name('carts.index');
Route::post('/gio-hang/create', [CartsController::class, 'store'])->name('carts.store');
Route::put('/gio-hang/{id}', [CartsController::class, 'update'])->name('carts.update');
Route::delete('/gio-hang/{id}', [CartsController::class, 'delete'])->name('carts.delete');

Route::get('/chi-tiet/{slug}', [ProductController::class, 'show'])->name('products.show');

//profile
Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
Route::get('/profile/history', [HomeController::class, 'history'])->name('history');
Route::get('/profile/address', [HomeController::class, 'address'])->name('address');
Route::post('/address/create', [AddressController::class, 'store'])->name('address.store');
Route::delete('/address/{id}', [AddressController::class, 'destroy'])->name('address.destroy');
Route::put('/profile/{user}', [UserController::class, 'update'])->name('profile.update');
Route::post('/profile/password', [UserController::class, 'changePassword'])->name('profile.password');
Route::post('/profile/cancel-order/{id}', [OrdersController::class, 'cancelOrder'])->name('profile.cancel-order');
Route::post('/profile/reorder/{id}', [OrdersController::class, 'reorder'])->name('profile.reorder');
Route::get('/profile/favorite', [HomeController::class, 'favorite'])->name('profile.favorite');
//auth
Route::post('dang-ky', [UserController::class, 'register']);
Route::post('dang-nhap', [UserController::class, 'login'])->name('dang-nhap');
Route::post('dang-xuat', [UserController::class, 'logout']);
Route::get('/login/google', [UserController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [UserController::class, 'handleGoogleCallback']);
Route::get('/login/facebook', [UserController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('/login/facebook/callback', [UserController::class, 'handleFacebookCallback']);
Route::post('/send-otp', [UserController::class, 'sendOtp']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);
Route::get('/quen-mat-khau', function () {
    $title = "Quên mật khẩu";
    return view('auth.forgot', compact('title'));
});
Route::get('/doi-mat-khau', function () {
    $title = "Xác nhận mật khẩu";
    return view('auth.reset', compact('title'));
})->name('reset.password');

//admin
Route::middleware('check.role:admin')->group(function () {
    Route::get('/admin', [HomeController::class, 'admin'])->name('admin.index');
    //products
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/admin/products/create', [ProductController::class, 'create']);
    Route::post('/admin/products', [ProductController::class, 'store']);
    Route::get('/admin/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy']);
    Route::post('/admin/products/import', [ProductController::class, 'import'])->name('admin.products.import');
    //categories
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'edit']);
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy']);
    //users
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    //orders
    Route::get('/admin/orders', [OrdersController::class, 'index'])->name('admin.orders.index');
    Route::delete('/admin/orders/delete/{id}', [OrdersController::class, 'destroy'])->name('admin.orders.destroy');
    Route::put('/admin/orders/{id}', [OrdersController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    //coupon
    Route::get('/admin/coupons', [CouponController::class, 'index'])->name('admin.coupons.index');
    Route::get('/admin/coupons/create', [CouponController::class, 'create'])->name('admin.coupons.create');
    Route::post('/admin/coupons', [CouponController::class, 'store'])->name('admin.coupons.store');
    Route::get('/admin/coupons/{id}/edit', [CouponController::class, 'edit'])->name('admin.coupons.edit');
    Route::put('/admin/coupons/{id}', [CouponController::class, 'update'])->name('admin.coupons.update');
    Route::delete('/admin/coupons/{id}/delete', [CouponController::class, 'destroy'])->name('admin.coupons.destroy');
    //comment and rating
    Route::get('/admin/comments', [RatingController::class, 'index'])->name('admin.comments.index');
    Route::put('/admin/ratings/{rating}/reply', [RatingController::class, 'reply'])->name('ratings.reply');
    //post
    Route::get('/admin/posts', [PostController::class, 'index'])->name('admin.posts.index');
    Route::get('/admin/posts/create', [PostController::class, 'create'])->name('admin.posts.create');
    Route::post('/admin/posts', [PostController::class, 'store'])->name('admin.posts.store');
    Route::delete('/admin/posts/{post}', [PostController::class, 'destroy'])->name('admin.posts.destroy');
    Route::get('/admin/posts/{post}/edit', [PostController::class, 'edit'])->name('admin.posts.edit');
    Route::put('/admin/posts/{post}', [PostController::class, 'update'])->name('admin.posts.update');
    //settings
    Route::get('/admin/settings/home-slider', [SettingController::class, 'editHomeSlider'])->name('admin.settings.home-slider.edit');
    Route::put('/admin/settings/home-slider', [SettingController::class, 'updateHomeSlider'])->name('admin.settings.home-slider.update');
});

//checkout
Route::middleware('is_login')->group(function () {
    Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/create', [OrdersController::class, 'store'])->name('orders.store');
    //add favorite
    Route::post('/favorite', [FavoriteController::class, 'store'])->name('favorite.store');
    Route::delete('/favorite/{id}', [FavoriteController::class, 'destroy'])->name('favorite.destroy');
});
//alert
Route::get('/success', function () {
    $title = "Thành công!";
    return view('alert.success', compact('title'));
})->name('alert.success');
Route::get('/fail', function () {
    $title = "Thất bại!";
    return view('alert.fail', compact('title'));
})->name('alert.fail');
//payment
Route::get('/vnpay/callback', [PaymentController::class, 'vnpayCallback'])->name('vnpay.callback');
Route::get('/momo/callback', [PaymentController::class, 'momoCallback'])->name('momo.callback');
Route::post('/momo/ipn', [PaymentController::class, 'momoIpn'])->name('momo.ipn');
Route::get('/zalopay/callback', [PaymentController::class, 'zalopayCallback'])->name('zalopay.callback');
Route::get('/paypal/callback', [PaymentController::class, 'paypalCallback'])->name('paypal.callback');
Route::get('/paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('paypal.cancel');
// coupon
Route::post('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('coupon.apply');
//rating 
Route::post('/products/{product}/ratings', [RatingController::class, 'store'])->name('ratings.store');
Route::post('/ratings/{rating}/like', [RatingController::class, 'like'])->name('ratings.like');
Route::delete('/ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');
// print order
Route::get('/order/{id}/print-invoice', [OrdersController::class, 'printInvoice'])->name('orders.printInvoice');
// blog
Route::get('/tin-tuc', [HomeController::class, 'post'])->name('blog.index');
Route::get('/tin-tuc/{slug}', [PostController::class, 'show'])->name('blog.show');
//chat bot
Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');
