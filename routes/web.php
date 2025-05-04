<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Siswa\KursusController;
use App\Http\Controllers\Siswa\RiwayatController;
use App\Http\Controllers\Mentor\ProgressController;
use App\Http\Controllers\Admin\EarningsController;
use App\Http\Controllers\Mentor\IncomeReportController;
use App\Http\Controllers\Admin\MentorIncomeController;
use Illuminate\Support\Facades\Route;

// Landing Page Route
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


//Register
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

// User yang sudah login, baik itu role admin, mentor dan siswa
Route::middleware('auth')->group(function () {
    Route::get('dashboard',DashboardController::class)->name('dashboard');

    //Route untuk siswa
    Route::prefix('siswa')->group(function(){
        Route::get('kursus', [KursusController::class, 'index'])->name('siswa.kursus.index');
        Route::get('riwayat', [RiwayatController::class, 'index'])->name('siswa.riwayat.index');
        Route::get('riwayat/{id}/review', [RiwayatController::class, 'review'])->name('courses.review');
    });

    //Route untuk Mentor
    Route::prefix('mentor')->group(function(){
        Route::get('progress',[ProgressController::class,'index'])->name('mentor.progress.index');
    });
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

    // Subscription-plans
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

// Catch-all Fallback Route
// Subscription-plans
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

// Catch-all Fallback Route (for undefined routes)
Route::fallback(function () {
    return view('errors.404');
});


// Auth Routes
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
