<?php

namespace App\Http\Controllers\All;

use App\Http\Controllers\Controller;
use App\Models\Analytic;
use App\Models\CourseCompletionHistory;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $enrollments = [];
        $completionHistory = [];
        $analytics = [];
        $grouped = [];
        if(auth()->user()->role == 'siswa')
        {
            $enrollments = CourseEnrollment::with(['course', 'progress'])
                            ->where('user_id', auth()->user()->id)->get();

            $completionHistory = CourseCompletionHistory::with('course')
                                    ->where('user_id', auth()->user()->id)->get();

        }

        if(auth()->user()->role == 'mentor')
        {
            $analytics = Analytic::with('student', 'course')->get();
            $grouped = $analytics->groupBy('area_of_struggle')->map->count();

            $studentProgresses = CourseEnrollment::with([
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
                })
                ->sortBy('student_name')
                ->values();

            $generatedAnalytics = $this->generateAnalytics($studentProgresses);
        }

        return view('all.index', [
            'title' => 'Dashboard',
            'enrollments' => $enrollments,
            'completionHistory' => $completionHistory,
            'grouped' => $grouped,
            'analytics' => $analytics,
        ]);
    }
}
