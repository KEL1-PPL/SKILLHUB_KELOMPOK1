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
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\AdminCertificateController;
use App\Http\Controllers\Admin\AdminSubscriptionPlanController;
use App\Http\Controllers\Admin\EarningsController;
use App\Http\Controllers\Admin\MentorIncomeController;
use App\Http\Controllers\Admin\WishlistAnalyticsController as AdminWishlistAnalyticsController;
use App\Http\Controllers\Mentor\MentorDashboardController;
use App\Http\Controllers\Mentor\MentorIncomeReportController;
use App\Http\Controllers\Mentor\MentorAnalyticsController;
use App\Http\Controllers\Mentor\MentorCourseController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Quiz\Mentor\QuizController;
use App\Http\Controllers\Quiz\Mentor\QuizQuestionController;
use App\Http\Controllers\Quiz\Student\QuizStudentController;
use App\Http\Controllers\LiveClassController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DiskusiController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\CertificateController;
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
    // Dashboard & Home
    Route::get('dashboard', [DashboardController::class, '__invoke'])->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Profile Routes
    Route::resource('profile', ProfileController::class);

    // General Pages
    Route::resource('about', AboutController::class)->only(['index', 'show']);
    Route::resource('contact', ContactController::class)->only(['index', 'store']);

    // E-commerce Features
    Route::resource('cart', CartController::class);
    Route::resource('category', CategoryController::class)->only(['index', 'show']);
    Route::resource('product', ProductController::class)->only(['index', 'show']);
    Route::resource('checkout', CheckoutController::class);

    // Subscription Routes
    Route::get('/subscription/checkout/{plan}', [SubscriptionPlanController::class, 'checkout'])
        ->name('subscription.checkout');
    Route::get('/subscription/my-subscriptions', [SubscriptionPlanController::class, 'mySubscriptions'])
        ->name('subscription.my-subscriptions');

    // Rating and Review Routes
    Route::resource('ratingreview', RatingReviewController::class);
    Route::resource('ratings', RatingController::class);

    // Voucher Routes
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

    // Material Management
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

    // Wishlist Routes
    Route::post('/wishlist', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('/wishlist', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');

    // Live Class Routes
    Route::resource('live-class', LiveClassController::class);
    Route::prefix('live-class-student')->name('live-class-student.')->group(function () {
        Route::get('/', [LiveClassController::class, 'index'])->name('index');
        Route::get('/{id}', [LiveClassController::class, 'show'])->name('show');
        Route::get('/{id}/join', [LiveClassController::class, 'join'])->name('join');
    });

    // Article Routes
    Route::resource('articles', ArticleController::class);
    Route::post('/articles/{article}/approve', [ArticleController::class, 'approve'])->name('articles.approve');
    Route::post('/articles/{article}/reject', [ArticleController::class, 'reject'])->name('articles.reject');

    // Discussion Routes
    Route::resource('diskusi', DiskusiController::class);
    Route::post('/diskusi/{id}/reply', [ReplyController::class, 'store'])->name('replies.store');
    Route::post('/reply/{id}/best', [ReplyController::class, 'markAsBest'])->name('replies.best');

    // Certificate Routes
    Route::get('/my-certificates', [CertificateController::class, 'myCertificates'])->name('my.certificates');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', AdminController::class);
    
    // Course Management
    Route::resource('courses', CourseController::class);
    
    // Article Management
    Route::resource('articles', ArticleController::class);
    Route::post('articles/{article}/approve', [ArticleController::class, 'approve'])->name('articles.approve');
    Route::post('articles/{article}/reject', [ArticleController::class, 'reject'])->name('articles.reject');
    
    // Subscription Plans
    Route::resource('subscription-plans', AdminSubscriptionPlanController::class);
    
    // Discounts
    Route::resource('discounts', DiscountController::class);
    
    // Certificates
    Route::resource('certificates', AdminCertificateController::class);
    
    // Wishlist Analytics
    Route::get('wishlist', [AdminWishlistAnalyticsController::class, 'index'])->name('wishlist.index');
    Route::get('wishlist/dashboard', [AdminWishlistAnalyticsController::class, 'dashboard'])->name('wishlist.dashboard');
    
    // Analytics
    Route::get('analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('analytics/export', [AdminController::class, 'exportAnalytics'])->name('analytics.export');
});

/*
|--------------------------------------------------------------------------
| Mentor Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    // Dashboard & Analytics
    Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [MentorAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/courses', [MentorCourseController::class, 'index'])->name('courses');
    Route::get('/course-management', [MentorCourseController::class, 'index'])->name('course-management');

    // Income Reports
    Route::get('/income-report', [MentorIncomeReportController::class, 'index'])->name('income-report');

    // Quiz Management
    Route::resource('quizzes', QuizController::class);
    Route::get('quizzes/{quiz}/analyze', [QuizController::class, 'analyze'])->name('quizzes.analyze');
    Route::get('courses/{course}/materials', [QuizController::class, 'getMaterials'])->name('courses.materials');

    // Quiz Questions Management
    Route::prefix('quizzes/{quiz}')->name('quizzes.')->group(function () {
        Route::resource('questions', QuizQuestionController::class)->except(['index', 'show']);
    });
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'siswa'])->prefix('student')->name('student.')->group(function () {
    // Quiz Taking
    Route::get('quizzes', [QuizStudentController::class, 'index'])->name('quizzes.index');
    Route::get('quizzes/{quiz}', [QuizStudentController::class, 'show'])->name('quizzes.show');
    Route::get('quizzes/{quiz}/start', [QuizStudentController::class, 'start'])->name('quizzes.start');
    Route::get('quizzes/{quiz}/attempts/{attempt}', [QuizStudentController::class, 'take'])->name('quizzes.take');
    Route::post('quizzes/{quiz}/attempts/{attempt}/answer', [QuizStudentController::class, 'saveAnswer'])->name('quizzes.save-answer');
    Route::post('quizzes/{quiz}/attempts/{attempt}/submit', [QuizStudentController::class, 'submit'])->name('quizzes.submit');
    Route::get('quizzes/{quiz}/attempts/{attempt}/result', [QuizStudentController::class, 'result'])->name('quizzes.result');
    Route::get('/quiz/{attempt}/certificate', [QuizStudentController::class, 'certificate'])->name('quiz.certificate');
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::get('/api/subscription-plans', [SubscriptionPlanController::class, 'getActivePlans'])->name('api.subscription-plans');

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('errors.404');
});
