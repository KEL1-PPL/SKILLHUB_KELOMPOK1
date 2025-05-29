<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\All\DashboardController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RatingReviewController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DiscountController as AdminDiscountController;
use App\Http\Controllers\Admin\MentorIncomeController;
use App\Http\Controllers\Admin\WishlistAnalyticsController as AdminWishlistAnalyticsController;
use App\Http\Controllers\SubscriptionPlanController; //elsa
use App\Http\Controllers\Mentor\MentorDashboardController;
use App\Http\Controllers\Mentor\EarningReportController;
use App\Http\Controllers\Mentor\MentorAnalyticsController;
use App\Http\Controllers\Mentor\MentorCourseController;
use App\Http\Controllers\CourseController; // imam
use App\Http\Controllers\MaterialController; // imam
use App\Http\Controllers\LiveClassController; //elsa
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ArticleController;

// Landing Page
Route::get('/', fn () => view('landing'))->name('landing');

// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

// Authenticated Users
Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, '__invoke'])->name('dashboard');
    Route::resource('profile', ProfileController::class);
    Route::resource('about', AboutController::class);
    Route::resource('contact', ContactController::class);
    Route::resource('cart', CartController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('product', ProductController::class);
    Route::resource('checkout', CheckoutController::class);

    // ccription-plans
    Route::get('/subscription/checkout/{plan}', [SubscriptionPlanController::class, 'checkout'])
    ->name('subscription.checkout');

    Route::get('/subscription/my-subscriptions', [SubscriptionPlanController::class, 'mySubscriptions'])
        ->name('subscription.my-subscriptions');

    // Rating and Review Routes
    Route::resource('ratingreview', RatingReviewController::class);

    // Voucher Routes for Users
    Route::post('voucher/redeem', [VoucherController::class, 'redeem'])->name('voucher.redeem');
    Route::resource('ratingreview', RatingReviewController::class);

    Route::get('/home', fn () => view('home'))->name('home');

    // Course & Material
    Route::resource('course', CourseController::class)->names([
        'index' => 'features.course.index',
        'create' => 'features.course.create',
        'store' => 'features.course.store',
        'show' => 'features.course.show',
        'edit' => 'features.course.edit',
        'update' => 'features.course.update',
        'destroy' => 'features.course.destroy'
    ])->parameters(['course' => 'slug']);

    Route::resource('course/{course}/material', MaterialController::class)->names([
        'index' => 'features.material.index',
        'create' => 'features.material.create',
        'store' => 'features.material.store',
        'show' => 'features.material.show',
        'edit' => 'features.material.edit',
        'update' => 'features.material.update',
        'destroy' => 'features.material.destroy',
    ]);
    Route::post('course/{course}/material/{material}/toggle-completion', [MaterialController::class, 'toggleCompletion'])->name('features.material.toggle-completion');

    // course - imam
    Route::resource('course', CourseController::class);
    Route::resource('course', CourseController::class)->names([
        'index' => 'features.course.index',
        'create' => 'features.course.create',
        'store' => 'features.course.store',
        'show' => 'features.course.show',
        'edit' => 'features.course.edit',
        'update' => 'features.course.update',
        'destroy' => 'features.course.destroy',
    Route::get('/course/{slug}', [CourseController::class, 'show'])->name('course.show')
    ]);
    
    // Material routes
    Route::resource('course/{course}/material', MaterialController::class)
        ->names([
            'index' => 'features.material.index',
            'create' => 'features.material.create',
            'store' => 'features.material.store',
            'show' => 'features.material.show',
            'edit' => 'features.material.edit',
            'update' => 'features.material.update',
            'destroy' => 'features.material.destroy',
        ]);

    Route::post('course/{course}/material/{material}/toggle-completion', [MaterialController::class, 'toggleCompletion'])
        ->name('features.material.toggle-completion');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('about', AboutController::class);
    Route::resource('contact', ContactController::class);
    Route::resource('voucher', VoucherController::class);
    Route::resource('earnings', AdminController::class)->except(['create', 'edit']);
    Route::post('earnings/{earning}/invalidate', [AdminController::class, 'invalidate'])->name('earnings.invalidate');
    Route::get('mentor-incomes', [MentorIncomeController::class, 'index'])->name('mentor-incomes');
    Route::post('mentor-incomes/{id}/correct', [MentorIncomeController::class, 'correct'])->name('mentor-incomes.correct');

    // Subscription Plan Admin
    Route::resource('subscription-plans', SubscriptionPlanController::class)->names([
        'index' => 'subscription-plans.index',
        'create' => 'subscription-plans.create',
        'store' => 'subscription-plans.store',
        'show' => 'subscription-plans.show',
        'edit' => 'subscription-plans.edit',
        'update' => 'subscription-plans.update',
        'destroy' => 'subscription-plans.destroy',
    ]);
});

// Mentor Routes
Route::prefix('mentor')->middleware(['auth', 'role:mentor'])->name('mentor.')->group(function () {
    Route::get('dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');
    Route::get('earning-report', [EarningReportController::class, 'index'])->name('earning-report');
    Route::post('earning-report', [EarningReportController::class, 'store'])->name('earning-report.store');
    Route::get('analytics', [MentorAnalyticsController::class, 'index'])->name('analytics');
    Route::get('course-management', [MentorCourseController::class, 'index'])->name('course-management');
});

// Fallback untuk route yang tidak ditemukan
Route::fallback(fn () => view('errors.404'));

// Subscription-plans -elsa
Route::resource('subscription-plans', SubscriptionPlanController::class)
    ->names([
        'index' => 'admin.subscription-plans.index',
        'create' => 'admin.subscription-plans.create',
        'store' => 'admin.subscription-plans.store',
        'show' => 'admin.subscription-plans.show',
        'edit' => 'admin.subscription-plans.edit',
        'update' => 'admin.subscription-plans.update',
        'destroy' => 'admin.subscription-plans.destroy',
    ]);

Route::get('/api/subscription-plans', [SubscriptionPlanController::class, 'getActivePlans'])
    ->name('api.subscription-plans');

// Catch-all Fallback Route (for undefined routes)
Route::fallback(function () {
    return view('errors.404');
});

// Auth Routes
Auth::routes();
;

// Routes untuk Mentor
Route::prefix('mentor')->middleware(['auth', 'role:mentor'])->group(function () {
    Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('mentor.dashboard');
    Route::get('/income-report', [EarningReportController::class, 'index'])->name('mentor.income-report');
    Route::get('/analytics', [MentorAnalyticsController::class, 'index'])->name('mentor.analytics');
    Route::get('/course-management', [MentorCourseController::class, 'index'])->name('mentor.course-management');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// User Routes (Protected by 'auth' middleware)
Route::middleware('auth')->group(function () {    
    // Wishlist Routes
    Route::post('/wishlist', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('/wishlist', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
    
    // Make admin dashboard and wishlist analytics accessible to all users
    Route::get('admin/wishlist', [AdminWishlistAnalyticsController::class, 'index'])->name('admin.wishlist.index');
    Route::get('admin/wishlist/dashboard', [AdminWishlistAnalyticsController::class, 'dashboard'])->name('admin.wishlist.dashboard');

    // Discount Management - Make accessible to all authenticated users
    Route::get('admin/discounts', [AdminDiscountController::class, 'index'])->name('admin.discounts.index');
    Route::get('admin/discounts/create', [AdminDiscountController::class, 'create'])->name('admin.discounts.create');
    Route::post('admin/discounts', [AdminDiscountController::class, 'store'])->name('admin.discounts.store');
    Route::get('admin/discounts/{discount}/edit', [AdminDiscountController::class, 'edit'])->name('admin.discounts.edit');
    Route::put('admin/discounts/{discount}', [AdminDiscountController::class, 'update'])->name('admin.discounts.update');
    Route::delete('admin/discounts/{discount}', [AdminDiscountController::class, 'destroy'])->name('admin.discounts.destroy');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

// course - imam
Route::resource('course', CourseController::class);
Route::resource('course', CourseController::class)->names([
    'index' => 'features.course.index',
    'create' => 'features.course.create',
    'store' => 'features.course.store',
    'show' => 'features.course.show',
    'edit' => 'features.course.edit',
    'update' => 'features.course.update',
    'destroy' => 'features.course.destroy',
Route::get('/course/{slug}', [CourseController::class, 'show'])->name('course.show')
]);

// Live Class Management - elsa
Route::middleware(['auth'])->group(function () {
    // Live Class Routes
    Route::get('/mentor/live-class', [LiveClassController::class, 'index'])->name('live-class.index');
    Route::post('/mentor/live-class', [LiveClassController::class, 'store'])->name('live-class.store');
    Route::get('/mentor/live-class/{id}', [LiveClassController::class, 'show'])->name('live-class.show');
    Route::get('/mentor/live-class/{id}/edit', [LiveClassController::class, 'edit'])->name('live-class.edit');
    Route::put('/mentor/live-class/{id}', [LiveClassController::class, 'update'])->name('live-class.update');
    Route::delete('/mentor/live-class/{id}', [LiveClassController::class, 'destroy'])->name('live-class.destroy');
});

// Admin Routes (Protected with 'auth' and 'can:admin' middleware)
Route::middleware(['auth', 'can:admin'])->prefix('admin')->group(function () {
    // Admin-Only Resource Routes
    Route::resource('about', AboutController::class);
    Route::resource('contact', ContactController::class);
    Route::resource('voucher', VoucherController::class);
    
    // Add this route for all wishlists
    Route::get('all-wishlists', [WishlistController::class, 'showWishlistAll'])->name('wishlist.all');
});

// ratings - raffanda
Route::middleware('auth')->group(function () {
    Route::get('/ratings', [RatingController::class, 'index'])->name('ratings.index');
    Route::get('/ratings/create', [RatingController::class, 'create'])->name('ratings.create');
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
    Route::get('/ratings/{id}/edit', [RatingController::class, 'edit'])->name('ratings.edit');
    Route::put('/ratings/{id}', [RatingController::class, 'update'])->name('ratings.update');
    Route::delete('/ratings/{id}', [RatingController::class, 'destroy'])->name('ratings.destroy');
});

// articles - reynal
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

