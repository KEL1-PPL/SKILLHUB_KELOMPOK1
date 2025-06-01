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
use App\Http\Controllers\Admin\MentorIncomeController;
use App\Http\Controllers\Mentor\MentorDashboardController;
use App\Http\Controllers\Mentor\MentorIncomeReportController;
use App\Http\Controllers\Mentor\MentorAnalyticsController;
use App\Http\Controllers\Mentor\MentorCourseController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Quiz\Mentor\QuizController;
use App\Http\Controllers\Quiz\Mentor\QuizQuestionController;
use App\Http\Controllers\Quiz\Student\QuizStudentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page Route
Route::get('/', function () {
    return view('landing');
})->name('landing');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register Routes (Only accessible for guests)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

// Laravel default auth routes
Auth::routes();

/*
|--------------------------------------------------------------------------
| Public Routes (Accessible without authentication)
|--------------------------------------------------------------------------
*/
Route::resource('subscription-plans', SubscriptionPlanController::class)->only(['index', 'show']);

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, '__invoke'])->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Profile Management
    Route::resource('profile', ProfileController::class);

    // General Pages
    Route::resource('about', AboutController::class)->only(['index', 'show']);
    Route::resource('contact', ContactController::class)->only(['index', 'store']);

    // E-commerce Features
    Route::resource('cart', CartController::class);
    Route::resource('category', CategoryController::class)->only(['index', 'show']);
    Route::resource('product', ProductController::class)->only(['index', 'show']);
    Route::resource('checkout', CheckoutController::class);

    // Subscription
    Route::get('/subscription/checkout/{plan}', [SubscriptionPlanController::class, 'checkout'])
        ->name('subscription.checkout');

    // Rating and Review
    Route::resource('ratingreview', RatingReviewController::class);

    // Voucher for Users
    Route::post('voucher/redeem', [VoucherController::class, 'redeem'])->name('voucher.redeem');

    // Course Management
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

    // Course Enrollment
    Route::post('/course/{course}/enroll', [CourseController::class, 'enroll'])->name('course.enroll');

    // Material Management (Nested under Course)
    Route::resource('course/{course}/material', MaterialController::class)->names([
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

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Content Management
    Route::resource('about', AboutController::class)->except(['show']);
    Route::resource('contact', ContactController::class)->except(['show', 'create']);
    Route::resource('voucher', VoucherController::class);

    // Subscription Plans Management
    Route::resource('subscription-plans', SubscriptionPlanController::class)->except(['index', 'show'])->names([
        'create' => 'subscription-plans.create',
        'store' => 'subscription-plans.store',
        'edit' => 'subscription-plans.edit',
        'update' => 'subscription-plans.update',
        'destroy' => 'subscription-plans.destroy',
    ]);

    // Earnings Management
    Route::resource('earnings', AdminController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::post('/earnings/{earning}/invalidate', [AdminController::class, 'invalidate'])->name('earnings.invalidate');

    // Mentor Income Management
    Route::get('/mentor-incomes', [MentorIncomeController::class, 'index'])->name('mentor-incomes.index');
    Route::post('/mentor-incomes/{id}/correct', [MentorIncomeController::class, 'correct'])->name('mentor-incomes.correct');

    // Income Management
    Route::get('/income-management', [AdminController::class, 'incomeManagement'])->name('income-management');
});

/*
|--------------------------------------------------------------------------
| Mentor Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    // Mentor Dashboard & Analytics
    Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [MentorAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/courses', [MentorCourseController::class, 'index'])->name('courses');
    Route::get('/course-management', [MentorCourseController::class, 'index'])->name('course-management');

    // Income Reports
    Route::get('/income-report', [MentorIncomeReportController::class, 'index'])->name('income-report');

    // Quiz Management
    Route::resource('quizzes', QuizController::class);
    Route::get('quizzes/{quiz}/analyze', [QuizController::class, 'analyze'])
        ->name('quizzes.analyze');
    Route::get('courses/{course}/materials', [QuizController::class, 'getMaterials'])
        ->name('courses.materials');

    // Quiz Questions Management (Nested under Quiz)
    Route::prefix('quizzes/{quiz}')->name('quizzes.')->group(function () {
        Route::resource('questions', QuizQuestionController::class)
            ->except(['index', 'show']);
    });
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'siswa'])->prefix('student')->name('student.')->group(function () {
    // Quiz Taking
    Route::get('quizzes', [QuizStudentController::class, 'index'])
        ->name('quizzes.index');
    Route::get('quizzes/{quiz}', [QuizStudentController::class, 'show'])
        ->name('quizzes.show');
    Route::get('quizzes/{quiz}/start', [QuizStudentController::class, 'start'])
        ->name('quizzes.start');
    Route::get('quizzes/{quiz}/attempts/{attempt}', [QuizStudentController::class, 'take'])
        ->name('quizzes.take');
    Route::post('quizzes/{quiz}/attempts/{attempt}/answer', [QuizStudentController::class, 'saveAnswer'])
        ->name('quizzes.save-answer');
    Route::post('quizzes/{quiz}/attempts/{attempt}/submit', [QuizStudentController::class, 'submit'])
        ->name('quizzes.submit');
    Route::get('quizzes/{quiz}/attempts/{attempt}/result', [QuizStudentController::class, 'result'])
        ->name('quizzes.result');
    Route::get('/quiz/{attempt}/certificate', [QuizStudentController::class, 'certificate'])->name('quiz.certificate');
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('errors.404');
});
