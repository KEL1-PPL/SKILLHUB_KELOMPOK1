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
        $popularCourses = [];
        
        if(auth()->user()->role == 'siswa')
        {
            $enrollments = CourseEnrollment::with(['course', 'progress'])
                            ->where('user_id', auth()->user()->id)->get();

            $completionHistory = CourseCompletionHistory::with('course')
                                    ->where('user_id', auth()->user()->id)->get();

            // Get popular courses based on rating and limit to 6
            $popularCourses = Course::orderBy('rating', 'desc')
                                ->take(6)
                                ->get();
        }

        if(auth()->user()->role == 'mentor')
        {
            $analytics = Analytic::with('student', 'course')->get();
            $grouped = $analytics->groupBy('area_of_struggle')->map->count();

            $studentProgresses = collect(CourseEnrollment::with([
                'user:id,name,email',
                'course:id,title',
                'progress'
            ])
                ->whereHas('user', function ($query) {
                    $query->where('role', 'siswa');
                })
                ->get()
                ->map(function ($enrollment) {
                    $percentage = $enrollment->calculateProgress();
                    $status = $percentage >= 100 ? 'Selesai' : 'Tidak Selesai';

                    $enrollment->progress()->updateOrCreate(
                        ['enrollment_id' => $enrollment->id],
                        [
                            'percentage_completed' => $percentage,
                            'status' => $status,
                            'last_accessed_at' => now(),
                        ]
                    );

                    return [
                        'student_id' => $enrollment->user->id,
                        'student_name' => $enrollment->user->name,
                        'student_email' => $enrollment->user->email,
                        'course_id' => $enrollment->course->id,
                        'course_title' => $enrollment->course->title,
                        'progress_percentage' => $percentage,
                        'status' => $status,
                        'last_accessed' => $enrollment->progress?->last_accessed_at ?? $enrollment->created_at,
                        'enrolled_at' => $enrollment->created_at,
                        'days_since_last_access' => $enrollment->progress?->last_accessed_at ?
                            now()->diffInDays($enrollment->progress->last_accessed_at) :
                            now()->diffInDays($enrollment->created_at)
                    ];
                }));

            $generatedAnalytics = collect($this->generateAnalytics($studentProgresses)['raw_data']);

            // Calculate mentor stats
            $mentorStats = [
                'totalStudents' => $studentProgresses->unique('student_id')->count(),
                'enrolledStudents' => $studentProgresses->count(),
                'avgCoursesPerStudent' => $studentProgresses->count() > 0 
                    ? round($studentProgresses->count() / $studentProgresses->unique('student_id')->count(), 1) 
                    : 0,
                'avgProgress' => $studentProgresses->count() > 0 
                    ? round($studentProgresses->average('progress_percentage'), 1) 
                    : 0,
                'popularCourses' => Course::where('created_by', auth()->id())
                    ->withCount(['enrollments', 'wishlists'])
                    ->orderBy('enrollments_count', 'desc')
                    ->take(5)
                    ->get(),
                'popularArticles' => \App\Models\Article::where('user_id', auth()->id())
                    ->orderBy('views', 'desc')
                    ->take(3)
                    ->get()
            ];
        }

        return view('all.index', [
            'title' => 'Dashboard',
            'enrollments' => $enrollments,
            'completionHistory' => $completionHistory,
            'grouped' => $grouped,
            'analytics' => $analytics,
            'popularCourses' => $popularCourses,
            'mentorStats' => $mentorStats ?? [],
            'studentProgresses' => $studentProgresses ?? collect(),
            'generatedAnalytics' => $generatedAnalytics ?? collect(),
        ]);
    }

    private function generateAnalytics($studentProgresses)
    {
        // Ensure we're working with a collection
        $studentProgresses = collect($studentProgresses);
        
        // Transform the data into analytics collection
        $analytics = $studentProgresses->map(function ($progress) {
            $areaOfStruggle = $this->determineAreaOfStruggle($progress);
            
            return [
                'student_name' => $progress['student_name'],
                'student_id' => $progress['student_id'],
                'course_title' => $progress['course_title'],
                'progress_percentage' => $progress['progress_percentage'],
                'status' => $progress['status'],
                'days_since_last_access' => $progress['days_since_last_access'],
                'area_of_struggle' => $areaOfStruggle['area'],
                'suggested_action' => $areaOfStruggle['action']
            ];
        });

        // Group analytics by course
        $courseGroups = $analytics->groupBy('course_title')->map(function ($group) {
            return [
                'total_students' => $group->count(),
                'average_progress' => round($group->avg('progress_percentage'), 2),
                'completed_count' => $group->where('status', 'Selesai')->count(),
                'active_students' => $group->where('days_since_last_access', '<', 7)->count()
            ];
        });

        return [
            'raw_data' => $analytics->toArray(),
            'course_summary' => $courseGroups->toArray(),
            'total_students' => $analytics->unique('student_name')->count(),
            'average_overall_progress' => $analytics->count() > 0 ? round($analytics->avg('progress_percentage'), 2) : 0,
            'total_courses' => $courseGroups->count(),
            'total_completions' => $analytics->where('status', 'Selesai')->count()
        ];
    }

    private function determineAreaOfStruggle($progress)
    {
        // Low progress and inactive
        if ($progress['progress_percentage'] < 30 && $progress['days_since_last_access'] > 7) {
            return [
                'area' => 'Keterlibatan Rendah',
                'action' => 'Perlu follow-up personal dan motivasi'
            ];
        }
        
        // Stuck at certain progress
        if ($progress['progress_percentage'] >= 30 && $progress['progress_percentage'] < 70 && $progress['days_since_last_access'] > 5) {
            return [
                'area' => 'Kesulitan Materi',
                'action' => 'Tawarkan sesi konsultasi atau bantuan tambahan'
            ];
        }
        
        // High progress but inactive
        if ($progress['progress_percentage'] >= 70 && $progress['days_since_last_access'] > 10) {
            return [
                'area' => 'Kehilangan Momentum',
                'action' => 'Dorong untuk menyelesaikan kursus'
            ];
        }
        
        // Active but slow progress
        if ($progress['days_since_last_access'] <= 7 && $progress['progress_percentage'] < 50) {
            return [
                'area' => 'Kesulitan Pemahaman',
                'action' => 'Sediakan materi tambahan atau penjelasan alternatif'
            ];
        }
        
        // Default case - no significant struggles
        return [
            'area' => 'Progres Normal',
            'action' => 'Pantau dan beri dukungan berkelanjutan'
        ];
    }
}
