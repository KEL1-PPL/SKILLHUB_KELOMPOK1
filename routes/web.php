<?php

use App\Http\Controllers\All\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
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
use App\Http\Controllers\Admin\EarningsController;
use App\Http\Controllers\Mentor\IncomeReportController;
use App\Http\Controllers\Admin\MentorIncomeController;
use App\Http\Controllers\Admin\WishlistAnalyticsController as AdminWishlistAnalyticsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Mentor\MentorDashboardController;
use App\Http\Controllers\Mentor\MentorIncomeReportController; 
use App\Http\Controllers\Mentor\MentorAnalyticsController;
use App\Http\Controllers\Mentor\MentorCourseController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\CourseController; // imam
use App\Http\Controllers\MaterialController; // imam
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\WishlistAnalyticsController;

// Landing Page Route
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register Routes (Only accessible for guests)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

// User Routes (Protected by 'auth' middleware)
Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, '__invoke'])->name('dashboard');
    
    // Profile Routes
    Route::resource('profile', ProfileController::class);

    // General Pages
    Route::resource('about', AboutController::class);
    Route::resource('contact', ContactController::class);

    // Cart Routes
    Route::resource('cart', CartController::class);

    // Category and Product Routes
    Route::resource('category', CategoryController::class);
    Route::resource('product', ProductController::class);

    // Checkout Routes
    Route::resource('checkout', CheckoutController::class);

    // Subscription-plans -elsa
    Route::get('/subscription/checkout/{plan}', [SubscriptionPlanController::class, 'checkout'])
    ->name('subscription.checkout');

    // Rating and Review Routes
    Route::resource('ratingreview', RatingReviewController::class);

    // Voucher Routes for Users
    Route::post('voucher/redeem', [VoucherController::class, 'redeem'])->name('voucher.redeem');

    // Home Route
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // course - imam
    Route::resource('course', CourseController::class)->names([
        'index' => 'features.course.index',
        'create' => 'features.course.create',
        'store' => 'features.course.store',
        'show' => 'features.course.show',
        'edit' => 'features.course.edit',
        'update' => 'features.course.update',
        'destroy' => 'features.course.destroy'
    ])->parameters([
        'course' => 'slug'
    ]);

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
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Admin-Only Resource Routes
    Route::resource('about', AboutController::class);
    Route::resource('contact', ContactController::class);
    Route::resource('voucher', VoucherController::class);

    // Earnings Routes for Admin
    Route::resource('earnings', AdminController::class)->except(['create', 'edit']);
    Route::post('/earnings/{earning}/invalidate', [AdminController::class, 'invalidate'])->name('admin.earnings.invalidate')
        ->name('admin.earnings.invalidate');  

    // Mentor Income Routes
    Route::get('/mentor-incomes', [MentorIncomeController::class, 'index'])->name('admin.mentor-incomes');
    Route::post('/mentor-incomes/{id}/correct', [MentorIncomeController::class, 'correct'])
        ->name('admin.mentor-incomes.correct'); 
});

// Mentor Routes
Route::middleware(['auth', 'mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [MentorAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/courses', [MentorCourseController::class, 'index'])->name('courses');
    Route::get('/income-report', [MentorIncomeReportController::class, 'index'])->name('income-report');
});

// Route untuk admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/income-management', [AdminController::class, 'incomeManagement']);
    // Route admin lainnya
});

// Route untuk mentor
Route::middleware(['auth', 'role:mentor'])->prefix('mentor')->group(function () {
    Route::get('/income-report', [IncomeReportController::class, 'index'])->name('mentor.income-report');
    // Route mentor lainnya
});

Route::middleware(['auth', 'mentor'])->group(function () {
    Route::get('/income-report', [App\Http\Controllers\Mentor\IncomeReportController::class, 'index'])->name('mentor.income-report');
});

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
    Route::get('/income-report', [MentorIncomeReportController::class, 'index'])->name('mentor.income-report');
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

// Admin Routes (Protected with 'auth' and 'can:admin' middleware)
Route::middleware(['auth', 'can:admin'])->prefix('admin')->group(function () {
    // Admin-Only Resource Routes
    Route::resource('about', AboutController::class);
    Route::resource('contact', ContactController::class);
    Route::resource('voucher', VoucherController::class);
    
    // Add this route for all wishlists
    Route::get('all-wishlists', [WishlistController::class, 'showWishlistAll'])->name('wishlist.all');
});
