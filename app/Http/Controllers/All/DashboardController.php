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
        $mentorStats = [];

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
            $mentorId = auth()->user()->id;
            
            // Ambil user_id unik yang pernah menyelesaikan material apapun
            $studentIdsWithMaterial = \App\Models\MaterialCompletion::where('is_completed', true)
                ->distinct('user_id')
                ->pluck('user_id');

            // 1. Siswa terdaftar kursus (hanya yang pernah menyelesaikan material)
            $enrolledStudents = $studentIdsWithMaterial->count();

            // 2. Rata-rata kursus per siswa (hanya siswa yang pernah menyelesaikan material)
            $courseCountPerStudent = \App\Models\MaterialCompletion::where('is_completed', true)
                ->select('user_id', 'material_id')
                ->distinct()
                ->get()
                ->groupBy('user_id')
                ->map(function($items) {
                    // Hitung jumlah kursus unik yang diikuti siswa (dari relasi material->course)
                    return $items->map(function($item) {
                        return $item->material->course_id ?? null;
                    })->unique()->count();
                });
            $avgCoursesPerStudent = $enrolledStudents > 0 ? round($courseCountPerStudent->sum() / $enrolledStudents, 1) : 0;
            
            // 3. Total students
            $totalStudents = \App\Models\User::where('role', 'siswa')->count();
            
            // 4. Total courses
            $totalCourses = \App\Models\Course::where('created_by', $mentorId)->count();
            
            // 5. Average student progress (berdasarkan material_completions)
            $progressPerStudent = [];
            $studentsWithMaterial = \App\Models\MaterialCompletion::where('is_completed', true)
                ->select('user_id', 'material_id')
                ->distinct()
                ->get()
                ->groupBy('user_id');

            foreach ($studentsWithMaterial as $userId => $completions) {
                // Ambil semua course yang diikuti siswa ini
                $courseIds = $completions->map(function($item) {
                    return $item->material->course_id ?? null;
                })->unique();
                foreach ($courseIds as $courseId) {
                    if (!$courseId) continue;
                    // Total material di course ini
                    $totalMaterial = \App\Models\Material::where('course_id', $courseId)->count();
                    if ($totalMaterial == 0) continue;
                    // Material yang sudah selesai oleh siswa ini di course ini
                    $completedMaterial = $completions->filter(function($item) use ($courseId) {
                        return $item->material->course_id == $courseId;
                    })->count();
                    $progress = ($completedMaterial / $totalMaterial) * 100;
                    $progressPerStudent[] = $progress;
                }
            }
            $avgProgress = count($progressPerStudent) > 0 ? round(array_sum($progressPerStudent) / count($progressPerStudent), 1) : 0;
            
            // 6. Total articles
            $totalArticles = \App\Models\Article::where('user_id', $mentorId)->count();
            
            // 7. Popular articles (based on views/clicks)
            $popularArticles = \App\Models\Article::where('user_id', $mentorId)
                ->where('status', 'approved')
                ->orderBy('views', 'desc')
                ->take(5)
                ->get();

            // 8. Popular courses (by wishlist)
            $popularCoursesMentor = \App\Models\Course::withCount('wishlists')
                ->where('created_by', $mentorId)
                ->orderByDesc('wishlists_count')
                ->take(5)
                ->get();

            $mentorStats = [
                'totalStudents' => $totalStudents,
                'enrolledStudents' => $enrolledStudents,
                'avgCoursesPerStudent' => $avgCoursesPerStudent,
                'totalCourses' => $totalCourses,
                'avgProgress' => $avgProgress,
                'totalArticles' => $totalArticles,
                'popularArticles' => $popularArticles,
                'popularCourses' => $popularCoursesMentor
            ];

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
            'mentorStats' => $mentorStats ?? null,
        ]);
    }
}
