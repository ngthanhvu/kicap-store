<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Orders_item;
use App\Models\Product;
use App\Models\Category;
use App\Models\Address;
use App\Models\User;
use App\Models\Carts;
use App\Models\Orders;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Favorite;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $title = "Trang chủ";
        $sliders = Setting::getValue('home_slider', $this->defaultHomeSliders());

        $products = Product::with('category', 'mainImage', 'variants', 'images')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $categories = Category::with(['products' => function ($query) {
            $query->with('mainImage', 'variants', 'images');
        }])
            ->has('products')
            ->get();

        $list_category = Category::latest()->take(4)->get();

        $posts = Post::latest()->take(3)->get();

        return view('index', compact('products', 'categories', 'title', 'list_category', 'posts', 'sliders'));
    }

    public function admin(Request $request)
    {
        $title = "Trang quản trị";

        // Lấy tham số lọc từ request
        $filterType = $request->query('filter_type', 'month'); // 'day' hoặc 'month'
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $year = $request->query('year', date('Y'));
        $month = $request->query('month');

        // Tổng doanh thu
        $totalRevenueQuery = Orders::query();
        if ($filterType === 'day' && $startDate && $endDate) {
            $totalRevenueQuery->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($filterType === 'month' && $month && $year) {
            $totalRevenueQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);
        }
        $totalRevenue = $totalRevenueQuery->sum('total_price');

        // Doanh thu theo thời gian
        $monthlyRevenueQuery = Orders::query();
        if ($filterType === 'day' && $startDate && $endDate) {
            $monthlyRevenueQuery->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as revenue')
            )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date');
        } else {
            $monthlyRevenueQuery->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as revenue')
            )
                ->whereYear('created_at', $year)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy('month');
        }
        $monthlyRevenue = $monthlyRevenueQuery->get();

        // Sản phẩm bán chạy
        $topProductsQuery = Orders_item::query();
        if ($filterType === 'day' && $startDate && $endDate) {
            $topProductsQuery->whereBetween('orders.created_at', [$startDate, $endDate]);
        } elseif ($filterType === 'month' && $month && $year) {
            $topProductsQuery->whereYear('orders.created_at', $year)->whereMonth('orders.created_at', $month);
        }
        $topProducts = $topProductsQuery->select(
            'product_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(subtotal) as total_revenue')
        )
            ->with(['product.mainImage'])
            ->join('orders', 'orders_item.order_id', '=', 'orders.id')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        $totalUsers = User::count();
        $totalOrders = Orders::count();
        $totalStock = Product::sum('quantity');

        // Dữ liệu biểu đồ
        $months = range(1, 12);
        $days = $filterType === 'day' && $startDate && $endDate
            ? collect(Carbon::parse($startDate)->toPeriod(Carbon::parse($endDate)))
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray()
            : [];

        $monthlyOrdersQuery = Orders::query();
        if ($filterType === 'day' && $startDate && $endDate) {
            $monthlyOrdersQuery->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as order_count')
            )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(created_at)'));
        } else {
            $monthlyOrdersQuery->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as order_count')
            )
                ->whereYear('created_at', $year)
                ->groupBy(DB::raw('MONTH(created_at)'));
        }
        $monthlyOrders = $monthlyOrdersQuery->get()->pluck('order_count', $filterType === 'day' ? 'date' : 'month')->toArray();

        $monthlyProductsQuery = Orders_item::query()->join('orders', 'orders_item.order_id', '=', 'orders.id');
        if ($filterType === 'day' && $startDate && $endDate) {
            $monthlyProductsQuery->select(
                DB::raw('DATE(orders.created_at) as date'),
                DB::raw('SUM(quantity) as product_count')
            )
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(orders.created_at)'));
        } else {
            $monthlyProductsQuery->select(
                DB::raw('MONTH(orders.created_at) as month'),
                DB::raw('SUM(quantity) as product_count')
            )
                ->whereYear('orders.created_at', $year)
                ->groupBy(DB::raw('MONTH(orders.created_at)'));
        }
        $monthlyProducts = $monthlyProductsQuery->get()->pluck('product_count', $filterType === 'day' ? 'date' : 'month')->toArray();

        $monthlyUsersQuery = User::query();
        if ($filterType === 'day' && $startDate && $endDate) {
            $monthlyUsersQuery->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as user_count')
            )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(created_at)'));
        } else {
            $monthlyUsersQuery->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as user_count')
            )
                ->whereYear('created_at', $year)
                ->groupBy(DB::raw('MONTH(created_at)'));
        }
        $monthlyUsers = $monthlyUsersQuery->get()->pluck('user_count', $filterType === 'day' ? 'date' : 'month')->toArray();

        $actualRevenueQuery = Orders_item::join('products', 'orders_item.product_id', '=', 'products.id')
            ->join('orders', 'orders_item.order_id', '=', 'orders.id');
        if ($filterType === 'day' && $startDate && $endDate) {
            $actualRevenueQuery->whereBetween('orders.created_at', [$startDate, $endDate]);
        } elseif ($filterType === 'month' && $month && $year) {
            $actualRevenueQuery->whereYear('orders.created_at', $year)->whereMonth('orders.created_at', $month);
        }
        $actualRevenue = $actualRevenueQuery->sum(DB::raw('orders_item.quantity * GREATEST(orders_item.price - products.original_price, 0)'));

        $monthlyActualRevenueQuery = Orders_item::join('products', 'orders_item.product_id', '=', 'products.id')
            ->join('orders', 'orders_item.order_id', '=', 'orders.id');
        if ($filterType === 'day' && $startDate && $endDate) {
            $monthlyActualRevenueQuery->select(
                DB::raw('DATE(orders.created_at) as date'),
                DB::raw('SUM(orders_item.quantity * GREATEST(orders_item.price - products.original_price, 0)) as actual_revenue')
            )
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(orders.created_at)'))
                ->orderBy('date');
        } else {
            $monthlyActualRevenueQuery->select(
                DB::raw('MONTH(orders.created_at) as month'),
                DB::raw('SUM(orders_item.quantity * GREATEST(orders_item.price - products.original_price, 0)) as actual_revenue')
            )
                ->whereYear('orders.created_at', $year)
                ->groupBy(DB::raw('MONTH(orders.created_at)'))
                ->orderBy('month');
        }
        $monthlyActualRevenue = $monthlyActualRevenueQuery->get();

        $orderData = $filterType === 'day' && $startDate && $endDate
            ? array_map(fn($day) => $monthlyOrders[$day] ?? 0, $days)
            : array_map(fn($month) => $monthlyOrders[$month] ?? 0, $months);
        $productData = $filterType === 'day' && $startDate && $endDate
            ? array_map(fn($day) => $monthlyProducts[$day] ?? 0, $days)
            : array_map(fn($month) => $monthlyProducts[$month] ?? 0, $months);
        $userData = $filterType === 'day' && $startDate && $endDate
            ? array_map(fn($day) => $monthlyUsers[$day] ?? 0, $days)
            : array_map(fn($month) => $monthlyUsers[$month] ?? 0, $months);

        $labels = $filterType === 'day' && $startDate && $endDate
            ? $days
            : array_map(fn($m) => 'Tháng ' . $m, $months);

        // Kiểm tra yêu cầu AJAX hoặc header X-Requested-With
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'labels' => $labels,
                'monthlyRevenue' => $monthlyRevenue,
                'monthlyActualRevenue' => $monthlyActualRevenue,
                'orderData' => $orderData,
                'productData' => $productData,
                'userData' => $userData,
                'topProducts' => $topProducts->map(function ($product) {
                    return [
                        'product' => [
                            'name' => $product->product->name,
                            'mainImage' => [
                                'image_url' => $product->product->mainImage?->image_url
                            ]
                        ],
                        'total_quantity' => $product->total_quantity,
                        'total_revenue' => $product->total_revenue
                    ];
                }),
                'totalRevenue' => $totalRevenue,
                'actualRevenue' => $actualRevenue
            ]);
        }

        return view('admin.index', compact(
            'title',
            'totalRevenue',
            'monthlyRevenue',
            'topProducts',
            'totalUsers',
            'orderData',
            'productData',
            'userData',
            'totalOrders',
            'totalStock',
            'actualRevenue',
            'monthlyActualRevenue',
            'labels',
            'filterType',
            'startDate',
            'endDate',
            'year',
            'month'
        ));
    }

    public function products(Request $request)
    {
        $title = "Danh sách sản phẩm";

        $categoryId = $request->query('category_id');
        $search = $request->query('search');
        $priceMax = $request->query('price_max', 10000000);
        $sort = $request->query('sort');

        $query = Product::with('category', 'mainImage', 'variants', 'images');

        if ($categoryId) {
            $category = Category::findOrFail($categoryId);
            $childIds = $category->getAllChildIds();
            $categoryIds = array_merge([$categoryId], $childIds);
            $query->whereIn('category_id', $categoryIds);
        }

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $query->where('price', '<=', $priceMax);

        if ($sort) {
            switch ($sort) {
                case 'name-az':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name-za':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price-high-low':
                    $query->orderBy('price', 'desc');
                    break;
                case 'price-low-high':
                    $query->orderBy('price', 'asc');
                    break;
            }
        }

        $products = $query->paginate(12);

        $categories = Category::whereNull('parent_id')->with('allChildren')->get();

        return view('products', compact('products', 'categories', 'title', 'search', 'priceMax', 'sort', 'categoryId'));
    }

    public function profile()
    {
        $title = "Thông tin cá nhân";
        $profile = User::find(Auth::user()->id);
        return view('profile.profile', compact('title', 'profile'));
    }

    public function address()
    {
        $title = "Địa chỉ";
        $addresses = Address::where('user_id', Auth::user()->id)->get();
        return view('profile.address', compact('title', 'addresses'));
    }

    public function history()
    {
        $title = "Lịch sử";
        $userId = Auth::user()->id;
        $status = request()->query('status');

        $query = Orders::where('user_id', $userId)
            ->with(['orderItems.product', 'orderItems.variant', 'user', 'address'])
            ->orderBy('created_at', 'asc');

        if (!empty($status)) {
            $query->where('status', $status);
        }
        $orders = $query->paginate(5);

        return view('profile.history', compact('title', 'orders', 'status'));
    }

    public function checkout()
    {
        $title = "Thanh toán";

        $user_id = Auth::user()->id ?? null;

        $carts = Carts::with(['product.mainImage', 'variant'])->where('user_id', $user_id)->get();

        $addresses = Address::where('user_id', Auth::user()->id)->get();
        return view('checkout', compact('title', 'addresses', 'carts'));
    }

    public function post()
    {
        $title = "Tin tức";

        $posts = Post::all();

        return view('blogs.blog', compact('posts', 'title'));
    }

    public function favorite()
    {
        $title = "Sản phẩm yêu thích";

        $user_id = Auth::user()->id ?? null;

        $favorites = Favorite::with(['product.mainImage'])->where('user_id', $user_id)->get();
        // dd($favorites);

        return view('profile.favorite', compact('title', 'favorites'));
    }

    private function defaultHomeSliders(): array
    {
        return [
            [
                'image' => 'https://placehold.co/1400x520?text=Kicap+Slider+1',
                'title' => 'KEYCAP CHO SETUP DEP',
                'subtitle' => 'Cap nhat bo keycap, switch va gear ban phim co cho goc may cua ban.',
                'button_text' => 'Xem san pham',
                'button_link' => '/san-pham',
            ],
            [
                'image' => 'https://placehold.co/1400x520?text=Kicap+Slider+2',
                'title' => 'BUILD CUSTOM DE DANG',
                'subtitle' => 'Tu starter kit den phu kien build custom, tat ca da san sang.',
                'button_text' => 'Kham pha ngay',
                'button_link' => '/san-pham',
            ],
            [
                'image' => 'https://placehold.co/1400x520?text=Kicap+Slider+3',
                'title' => 'TIN TUC VA REVIEW',
                'subtitle' => 'Xem bai viet moi ve keycap, switch va xu huong mechanical keyboard.',
                'button_text' => 'Doc tin tuc',
                'button_link' => '/tin-tuc',
            ],
        ];
    }
}
