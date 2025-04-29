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
use App\Http\Controllers\Admin\EarningsController;
use App\Http\Controllers\Mentor\IncomeReportController;
use App\Http\Controllers\Admin\MentorIncomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Mentor\MentorDashboardController;
use App\Http\Controllers\Mentor\MentorIncomeReportController; 
use App\Http\Controllers\Mentor\MentorAnalyticsController;
use App\Http\Controllers\Mentor\MentorCourseController;

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

    // Rating and Review Routes
    Route::resource('ratingreview', RatingReviewController::class);

    // Voucher Routes for Users
    Route::post('voucher/redeem', [VoucherController::class, 'redeem'])->name('voucher.redeem');

    // Home Route
    Route::get('/home', function () {
        return view('home');
    })->name('home');
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

// Catch-all Fallback Route
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
