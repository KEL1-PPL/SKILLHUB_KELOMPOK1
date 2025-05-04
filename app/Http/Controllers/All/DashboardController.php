<?php

namespace App\Http\Controllers\All;

use App\Http\Controllers\Controller;
use App\Models\Analytic;
use App\Models\Course;
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
        $courses = []; // Inisialisasi array untuk kursus

        // Mengambil data kursus untuk siswa
        if(auth()->user()->role == 'siswa')
        {
            // Mengambil kursus yang sedang diikuti oleh siswa
            $enrollments = CourseEnrollment::with(['course', 'progress'])
                            ->where('user_id', auth()->user()->id)
                            ->get();

            $completionHistory = CourseCompletionHistory::with('course')
                                    ->where('user_id', auth()->user()->id)
                                    ->get();

            // Mengambil kursus berdasarkan yang diikuti oleh siswa
            $courses = Course::whereIn('id', $enrollments->pluck('course_id'))->get();
        }

        // Mengambil data analitik untuk mentor
        if(auth()->user()->role == 'mentor')
        {
            // Mengambil analitik untuk mentor
            $analytics = Analytic::with('student', 'course')->get();
            $grouped = $analytics->groupBy('area_of_struggle')->map->count();

            // Mengambil semua kursus untuk mentor
            $courses = Course::all();
        }

        // Debugging untuk memeriksa apakah data kursus sudah ada
        $courses = Course::get(); // Gunakan get() alih-alih all()
        // dd($courses); // Debugging untuk memastikan data kursus diambil
        
        // Kirim data ke view
        return view('all.index', [
            'title' => 'Dashboard',
            'courses' => $courses, // Mengirim data kursus ke view
            'enrollments' => $enrollments,
            'completionHistory' => $completionHistory,
            'grouped' => $grouped,
            'analytics' => $analytics,
        ]);
    }
}
