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
use App\Http\Controllers\Mentor\MentorDashboardController;
use App\Http\Controllers\Mentor\EarningReportController;
use App\Http\Controllers\Mentor\MentorAnalyticsController;
use App\Http\Controllers\Mentor\MentorCourseController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\MentorIncomeController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\All\ArticleExploreController;

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
    Route::post('voucher/redeem', [VoucherController::class, 'redeem'])->name('voucher.redeem');
    Route::resource('ratingreview', RatingReviewController::class);
    Route::get('articles', [ArticleExploreController::class, 'index'])->name('articles.index');
    Route::get('articles/{article}', [ArticleExploreController::class, 'show'])->name('articles.show');

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

    Route::get('/subscription/checkout/{plan}', [SubscriptionPlanController::class, 'checkout'])->name('subscription.checkout');
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

// Auth scaffold default (jika pakai Laravel UI)
Auth::routes();
