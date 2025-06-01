<?php

namespace App\Http\Controllers\All;

use App\Http\Controllers\Controller;
use App\Models\Analytic;
use App\Models\CourseCompletionHistory;
use App\Models\CourseEnrollment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $enrollments = [];
        $completionHistory = [];
        $analytics = [];
        $grouped = [];
        $studentProgresses = [];
        $generatedAnalytics = [];

        if (auth()->user()->role == 'siswa') {
            $enrollments = CourseEnrollment::with(['course.materials.completions', 'progress'])
                ->where('user_id', auth()->user()->id)
                ->get();

            foreach ($enrollments as $enrollment) {
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

                if ($status === 'Selesai') {
                    $existing = CourseCompletionHistory::where('user_id', $enrollment->user_id)
                        ->where('course_id', $enrollment->course_id)
                        ->first();

                    if (!$existing) {
                        CourseCompletionHistory::create([
                            'user_id' => $enrollment->user_id,
                            'course_id' => $enrollment->course_id,
                            'submitted_at' => now(),
                        ]);
                    }
                }
            }

            $completionHistory = CourseCompletionHistory::with('course')
                ->where('user_id', auth()->user()->id)
                ->get();
        }

        if (auth()->user()->role == 'mentor') {
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
            'studentProgresses' => $studentProgresses,
            'generatedAnalytics' => $generatedAnalytics,
        ]);
    }

    /**
     * Generate analytics otomatis berdasarkan data progress
     */
    private function generateAnalytics($studentProgresses)
    {
        $analytics = [];

        foreach ($studentProgresses as $progress) {
            $struggles = [];
            $suggestions = [];

            if ($progress['progress_percentage'] < 20) {
                $struggles[] = "Sangat lambat dalam menyelesaikan kursus";
                $suggestions[] = "Perlu bimbingan intensif dan motivasi tambahan";
            } elseif ($progress['progress_percentage'] < 50) {
                $struggles[] = "Progress lambat";
                $suggestions[] = "Berikan dukungan dan pantau lebih sering";
            }

            if ($progress['days_since_last_access'] > 7) {
                $struggles[] = "Tidak aktif dalam pembelajaran";
                $suggestions[] = "Follow up dan reminder untuk melanjutkan belajar";
            } elseif ($progress['days_since_last_access'] > 3) {
                $struggles[] = "Kurang konsisten belajar";
                $suggestions[] = "Buat jadwal belajar yang lebih teratur";
            }

            $daysSinceEnrolled = now()->diffInDays($progress['enrolled_at']);
            if ($daysSinceEnrolled > 30 && $progress['progress_percentage'] < 30) {
                $struggles[] = "Terjebak di awal pembelajaran";
                $suggestions[] = "Review materi dasar dan berikan contoh tambahan";
            }
            
            if (!empty($struggles)) {
                $analytics[] = [
                    'student_id' => $progress['student_id'],
                    'student_name' => $progress['student_name'],
                    'course_id' => $progress['course_id'],
                    'course_title' => $progress['course_title'],
                    'area_of_struggle' => implode(', ', $struggles),
                    'suggested_action' => implode(', ', $suggestions),
                    'progress_percentage' => $progress['progress_percentage'],
                    'days_since_last_access' => $progress['days_since_last_access'],
                    'days_since_enrolled' => $daysSinceEnrolled
                ];
            }
        }

        return collect($analytics);
    }
}
