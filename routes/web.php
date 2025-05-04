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
use App\Http\Controllers\Admin\WishlistAnalyticsController;
use App\Http\Controllers\Admin\DiscountController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CourseController; // imam
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NotificationController;



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
    // Dashboard Route
    Route::get('dashboard', DashboardController::class)->name('dashboard');

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

    // Rating and Review Routes
    Route::resource('ratingreview', RatingReviewController::class);

    // Voucher Routes for Users
    Route::post('voucher/redeem', [VoucherController::class, 'redeem'])->name('voucher.redeem');

    // Home Route
    Route::get('/home', function () {
        return view('home'); // Ganti dengan halaman home Anda
    })->name('home');
    
    // Wishlist Routes
    Route::post('/wishlist', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('/wishlist', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
    
    // Make admin dashboard and wishlist analytics accessible to all users
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/wishlist', [WishlistAnalyticsController::class, 'index'])->name('admin.wishlist.index');
    Route::get('admin/wishlist/dashboard', [WishlistAnalyticsController::class, 'dashboard'])->name('admin.wishlist.dashboard');

    // Discount Management - Make accessible to all authenticated users
    Route::get('admin/discounts', [DiscountController::class, 'index'])->name('admin.discounts.index');
    Route::get('admin/discounts/create', [DiscountController::class, 'create'])->name('admin.discounts.create');
    Route::post('admin/discounts', [DiscountController::class, 'store'])->name('admin.discounts.store');
    Route::get('admin/discounts/{discount}/edit', [DiscountController::class, 'edit'])->name('admin.discounts.edit');
    Route::put('admin/discounts/{discount}', [DiscountController::class, 'update'])->name('admin.discounts.update');
    Route::delete('admin/discounts/{discount}', [DiscountController::class, 'destroy'])->name('admin.discounts.destroy');
    
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

// Catch-all Fallback Route (for undefined routes)
Route::fallback(function () {
    return view('errors.404');
});

// Auth Routes for the rest of the authentication process
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Course Routes
Route::resource('course', CourseController::class)->names([
    'index' => 'features.course.index',
    'create' => 'features.course.create',
    'store' => 'features.course.store',
    'show' => 'features.course.show',
    'edit' => 'features.course.edit',
    'update' => 'features.course.update',
    'destroy' => 'features.course.destroy',
]);
