<?php

namespace App\Http\Controllers\All;

use App\Http\Controllers\Controller;
use App\Models\Analytic;
use App\Models\CourseCompletionHistory;
use App\Models\CourseEnrollment;
use App\Models\Course;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $enrollments = [];
        $completionHistory = [];
        $analytics = [];
        $grouped = [];
        $popularCourses = collect();
        if(auth()->user()->role == 'siswa')
        {
            $enrollments = CourseEnrollment::with(['course', 'progress'])
                            ->where('user_id', auth()->user()->id)->get();

            $completionHistory = CourseCompletionHistory::with('course')
                                    ->where('user_id', auth()->user()->id)->get();

            // Ambil 6 kursus populer berdasarkan wishlist
            $popularCourses = Course::withCount('wishlists')
                ->orderByDesc('wishlists_count')
                ->take(6)
                ->get();
            if ($popularCourses->sum('wishlists_count') == 0) {
                $popularCourses = Course::inRandomOrder()->take(6)->get();
            }
        }

        if(auth()->user()->role == 'mentor')
        {
            $analytics = Analytic::with('student', 'course')->get();


            $grouped = $analytics->groupBy('area_of_struggle')->map->count();
        }

        return view('all.index',[
            'title' => 'Dashboard',
            'enrollments' => $enrollments,
            'completionHistory' => $completionHistory,
            'grouped' => $grouped,
            'analytics' => $analytics,
            'popularCourses' => $popularCourses,
        ]);
    }
}
