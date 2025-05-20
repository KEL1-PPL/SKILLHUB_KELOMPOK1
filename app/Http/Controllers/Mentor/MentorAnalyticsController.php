<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\StudentProgress;
use Carbon\Carbon;
use Illuminate\View\View;

class MentorAnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $mentor_id = auth()->id();
        
        // Ambil semua kursus mentor dengan eager loading
        $courses = Course::where('mentor_id', $mentor_id)->with('students')->get();
        
        // Filter berdasarkan kursus jika dipilih
        $query = StudentProgress::query()  // Perbaikan di sini
            ->join('courses', 'student_progress.course_id', '=', 'courses.id')
            ->join('users as students', 'student_progress.student_id', '=', 'students.id')
            ->where('courses.mentor_id', $mentor_id);
            
        if ($request->filled('course_id')) {
            $query->where('student_progress.course_id', $request->course_id);
        }
        
        // Filter berdasarkan periode dengan validasi tanggal
        $endDate = Carbon::now();
        switch($request->get('period', 'month')) {
            case 'week':
                $startDate = $endDate->copy()->subWeek();
                break;
            case 'month':
                $startDate = $endDate->copy()->startOfMonth();
                break;
            case 'year':
                $startDate = $endDate->copy()->startOfYear();
                break;
            default:
                $startDate = $endDate->copy()->startOfMonth();
        }
        
        $query->whereBetween('student_progress.created_at', [$startDate, $endDate]);
        
        // Ambil data progress siswa dengan pagination
        $studentProgress = $query->select(
            'students.name as student_name',
            'courses.title as course_title',
            'student_progress.progress_percentage',
            'student_progress.average_score',
            'student_progress.status'
        )->paginate(10);
        
        // Siapkan data untuk grafik dengan handling data kosong
        $chartData = !$studentProgress->isEmpty() ? (object) [
            'labels' => $studentProgress->pluck('student_name')->toArray(),
            'data' => $studentProgress->pluck('progress_percentage')->toArray()
        ] : null;
        
        return view('mentor.analytics.index', compact('courses', 'studentProgress', 'chartData'));
    }
}