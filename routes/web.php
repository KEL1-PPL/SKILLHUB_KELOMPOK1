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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CourseController; // imam
use App\Http\Controllers\MaterialController; // imam

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
    Route::get('dashboard',DashboardController::class)->name('dashboard');
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

    // course - imam
    Route::resource('course', CourseController::class)->names([
        'index' => 'features.course.index',
        'create' => 'features.course.create',
        'store' => 'features.course.store',
        'show' => 'features.course.show',
        'edit' => 'features.course.edit',
        'update' => 'features.course.update',
        'destroy' => 'features.course.destroy'
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

// Admin Routes (Protected with 'auth' and 'can:admin' middleware)
Route::middleware(['auth', 'can:admin'])->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Admin-Only Resource Routes
    Route::resource('about', AboutController::class);
    Route::resource('contact', ContactController::class);
    Route::resource('voucher', VoucherController::class);
});

// Catch-all Fallback Route (for undefined routes)
Route::fallback(function () {
    return view('errors.404');
});

// Auth Routes for the rest of the authentication process
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


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