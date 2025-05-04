<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WishlistAnalyticsController extends Controller
{
    public function index()
    {
        // Mendapatkan semua data wishlist
        $wishlists = Wishlist::with('course', 'user')->get();

        return view('all.admin.wishlist.index', [
            'title' => 'Data Wishlist',
            'wishlists' => $wishlists,
        ]);
    }
    
    public function dashboard()
    {
        // 1. Most wishlisted courses (top 10)
        $topCourses = Wishlist::select('course_id', DB::raw('count(*) as total'))
            ->with('course')
            ->groupBy('course_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
            
        // 2. Wishlist trends over time (last 30 days)
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $dailyWishlists = Wishlist::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // 3. Monthly wishlist trends (last 12 months)
        $twelveMonthsAgo = Carbon::now()->subMonths(12);
        $monthlyWishlists = Wishlist::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', $twelveMonthsAgo)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::createFromDate($item->year, $item->month, 1)->format('M Y'),
                    'total' => $item->total
                ];
            });
            
        // 4. Most active users in wishlisting
        $activeUsers = Wishlist::select('user_id', DB::raw('count(*) as total'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
            
        // 5. Total counts for summary cards
        $totalWishlists = Wishlist::count();
        $totalCourses = Course::count();
        $totalUsers = User::count();
        $coursesWithWishlists = Wishlist::distinct('course_id')->count('course_id');
        
        // 6. Recent wishlist activities
        $recentActivities = Wishlist::with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return view('all.admin.wishlist.dashboard', [
            'title' => 'Wishlist Analytics',
            'topCourses' => $topCourses,
            'dailyWishlists' => $dailyWishlists,
            'monthlyWishlists' => $monthlyWishlists,
            'activeUsers' => $activeUsers,
            'totalWishlists' => $totalWishlists,
            'totalCourses' => $totalCourses,
            'totalUsers' => $totalUsers,
            'coursesWithWishlists' => $coursesWithWishlists,
            'recentActivities' => $recentActivities
        ]);
    }
}
