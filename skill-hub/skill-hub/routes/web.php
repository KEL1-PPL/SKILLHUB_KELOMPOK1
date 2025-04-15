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
use App\Http\Controllers\CourseMaterialController; // imam

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
Route::middleware(['auth'])->group(function () {
    Route::prefix('features')->group(function () {
        Route::get('/course', [CourseController::class, 'index'])->name('features.course.index');
        Route::get('/course/create', [CourseController::class, 'create'])->name('features.course.create');
        Route::get('/course/{id}', [CourseController::class, 'show'])->name('features.course.show');
        Route::get('/course/{id}/edit', [CourseController::class, 'edit'])->name('features.course.edit');
        Route::post('/course', [CourseController::class, 'store'])->name('features.course.store');
        Route::put('/course/{id}', [CourseController::class, 'update'])->name('features.course.update');
        Route::delete('/course/{id}', [CourseController::class, 'destroy'])->name('features.course.destroy');
        Route::get('/courses', function () {
            return redirect()->route('features.course.index');
        });
        Route::post('/features/course/{id}/complete', [CourseController::class, 'markCompleted'])->name('features.course.markCompleted');
        //
    });
});

Route::prefix('features/course/{course}/materials')->middleware('auth')->group(function () {
    Route::get('create', [CourseMaterialController::class, 'create'])->name('features.course.materials.create');
    Route::post('/', [CourseMaterialController::class, 'store'])->name('features.course.materials.store');
});

Route::prefix('features/materials')->middleware('auth')->group(function () {
    Route::get('{id}/edit', [CourseMaterialController::class, 'edit'])->name('features.course.materials.edit');
    Route::put('{id}', [CourseMaterialController::class, 'update'])->name('features.course.materials.update');
    Route::delete('{id}', [CourseMaterialController::class, 'destroy'])->name('features.course.materials.destroy');
});

