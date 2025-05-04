<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\StudentProgress;
use Carbon\Carbon;

class MentorAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $mentor_id = auth()->id();
        
        // Ambil semua kursus mentor
        $courses = Course::where('mentor_id', $mentor_id)->get();
        
        // Filter berdasarkan kursus jika dipilih
        $query = App\Http\Controllers\Mentor\StudentProgress::query()
            ->join('courses', 'student_progress.course_id', '=', 'courses.id')
            ->join('users as students', 'student_progress.student_id', '=', 'students.id')
            ->where('courses.mentor_id', $mentor_id);
            
        if ($request->course_id) {
            $query->where('student_progress.course_id', $request->course_id); // Perbaikan baris 15: menggunakan student_progress.course_id
        }
        
        // Filter berdasarkan periode
        switch($request->period) {
            case 'week':
                $query->where('student_progress.created_at', '>=', Carbon::now()->subWeek());
                break;
            case 'month':
                $query->where('student_progress.created_at', '>=', Carbon::now()->subMonth());
                break;
            case 'year':
                $query->where('student_progress.created_at', '>=', Carbon::now()->subYear());
                break;
            default:
                $query->where('student_progress.created_at', '>=', Carbon::now()->startOfMonth()); // Perbaikan baris 21: menggunakan startOfMonth()
        }
        
        // Ambil data progress siswa
        $studentProgress = $query->select(
            'students.name as student_name',
            'courses.title as course_title',
            'student_progress.progress_percentage',
            'student_progress.average_score',
            'student_progress.status'
        )->get();
        
        // Siapkan data untuk grafik
        $chartData = new \stdClass();
        $chartData->labels = $studentProgress->pluck('student_name')->toArray();
        $chartData->data = $studentProgress->pluck('progress_percentage')->toArray();
        
        return view('mentor.analytics.index', compact('courses', 'studentProgress', 'chartData'));
    }
}