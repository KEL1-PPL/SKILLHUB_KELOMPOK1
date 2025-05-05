<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CourseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $courses = Course::all();
        $title = 'Kursus';

        return view('features.course.index', compact('courses', 'title'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'mentor') {
            return redirect()->route('features.course.index')->with('error', 'Anda tidak memiliki akses untuk menambah kursus');
        }
        $title = 'Tambah Kursus';
        return view('features.course.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:courses,title',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.unique' => 'Judul kursus sudah ada, data tidak boleh sama.',
        ]);

        // Generate slug unik menggunakan fungsi generateUniqueSlug
        $slug = Course::generateUniqueSlug($request->title);

        // Simpan data jika validasi lolos
        $imagePath = $request->file('image')->store('courses', 'public');

        Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'slug' => $slug,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('features.course.index')->with('success', 'Kursus berhasil disimpan.');
    }

    public function show($slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        $title = $course->title;
        return view('features.course.show', compact('course', 'title'));
    }

    public function edit($slug)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'mentor') {
            return redirect()->route('features.course.index')->with('error', 'Anda tidak memiliki akses untuk mengedit kursus');
        }

        $course = Course::where('slug', $slug)->firstOrFail();
        $title = 'Edit Kursus';
        return view('features.course.edit', compact('course', 'title'));
    }

    public function update(Request $request, $slug)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'mentor') {
            return redirect()->route('features.course.index')->with('error', 'Anda tidak memiliki akses untuk mengedit kursus');
        }

        $course = Course::where('slug', $slug)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            Storage::disk('public')->delete($course->image);
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($data);

        return redirect()->route('features.course.index')->with('success', 'Kursus berhasil diperbarui');
    }

    public function destroy($slug)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'mentor') {
            return redirect()->route('features.course.index')->with('error', 'Anda tidak memiliki akses untuk menghapus kursus');
        }

        $course = Course::where('slug', $slug)->firstOrFail();
        Storage::disk('public')->delete($course->image);
        $course->delete();

        return redirect()->route('features.course.index')->with('success', 'Kursus berhasil dihapus');
    }

    public static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = Course::where('slug', 'LIKE', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
