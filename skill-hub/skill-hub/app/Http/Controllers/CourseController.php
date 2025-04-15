<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseEnrollment;
use App\Models\CourseCompletionHistory;
use App\Models\CourseProgres; // Ensure this model exists in the specified namespace

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $courses = Course::with(['enrollments', 'progress'])
                    ->whereHas('enrollments', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->get();

        return view('features.course.index', compact('courses'));
    }

    public function create()
    {
        return view('course.create'); // form tambah kursus
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('features.course.index')->with('success', 'Kursus berhasil ditambahkan!');
    }

    public function show($id)
    {
        $course = Course::with('mentor')->findOrFail($id);

    $progress = null;
    $completed = false;

    if (Auth::user()->role === 'student') {
        $enrollment = CourseEnrollment::where('user_id', Auth::id())
            ->where('course_id', $id)->first();

        if ($enrollment) {
            $progress = CourseProgres::where('enrollment_id', $enrollment->id)->first();
            $completed = CourseCompletionHistory::where('user_id', Auth::user()->id)
                ->where('course_id', $id)->exists();
        }
    }

    return view('features.course.show', compact('course', 'progress', 'completed'));
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('features.course.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $course = Course::findOrFail($id);
        $course->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('features.course.index')->with('success', 'Kursus berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('features.course.index')->with('success', 'Kursus berhasil dihapus!');
    }

    public function markCompleted($id)
{
    $user = Auth::user();

    // Cek apakah user sudah enroll
    $enrollment = CourseEnrollment::firstOrCreate([
        'user_id' => $user->id,
        'course_id' => $id,
    ], [
        'enrolled_at' => now(),
    ]);

    // Tandai progres 100%
    CourseProgres::updateOrCreate(
        ['enrollment_id' => $enrollment->id],
        [
            'percentage_completed' => 100,
            'status' => 'completed',
            'last_accessed_at' => now(),
        ]
    );

    // Tambah ke riwayat penyelesaian kalau belum ada
    CourseCompletionHistory::firstOrCreate([
        'user_id' => $user->id,
        'course_id' => $id,
    ], [
        'completed_at' => now(),
    ]);

    return redirect()->route('features.course.show', $id)->with('success', 'Kursus telah ditandai sebagai selesai.');
}

}
