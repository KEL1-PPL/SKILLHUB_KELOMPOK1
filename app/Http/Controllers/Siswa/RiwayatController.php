<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCompletionHistory;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        return view('siswa.riwayat.index', [
            'title' => 'riwayat',
            'completionHistory' => CourseCompletionHistory::with('course')
                                        ->where('user_id', auth()->user()->id)->get(),
        ]);
    }

    public function review($id)
    {
        // Pastikan kursus benar-benar telah diselesaikan oleh siswa
        $history = CourseCompletionHistory::where('user_id', auth()->id())
                    ->where('course_id', $id)
                    ->firstOrFail();

        $course = Course::with('materials')->findOrFail($id);

        return view('siswa.riwayat.review', [
            'title' => 'Tinjau Materi: ' . $course->title,
            'course' => $course,
        ]);
    }
}
