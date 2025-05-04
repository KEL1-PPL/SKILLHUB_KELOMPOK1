<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Course $course)
    {
        $title = $course->title;
        $materials = $course->materials()->orderBy('order')->get();
        $progress = $this->calculateProgress($course);
        
        return view('features.material.index', compact('course', 'materials', 'title', 'progress'));
    }

    public function create(Course $course)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'mentor') {
            return redirect()->route('features.course.show', $course->slug)
                ->with('error', 'Anda tidak memiliki akses untuk menambah materi');
        }

        $title = 'Tambah Materi';
        return view('features.material.create', compact('course', 'title'));
    }

    public function store(Request $request, Course $course)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'mentor') {
            return redirect()->route('features.course.show', $course->slug)
                ->with('error', 'Anda tidak memiliki akses untuk menambah materi');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer|min:0'
        ]);

        $course->materials()->create($request->all());

        return redirect()->route('features.material.index', $course->id)
            ->with('success', 'Materi berhasil ditambahkan');
    }

    public function show(Course $course, Material $material)
    {
        $title = $material->title;
        $isCompleted = $material->isCompletedByUser(auth()->id());
        $progress = $this->calculateProgress($course);
        
        return view('features.material.show', compact('course', 'material', 'title', 'isCompleted', 'progress'));
    }

    public function edit(Course $course, Material $material)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'mentor') {
            return redirect()->route('features.course.show', $course->slug)
                ->with('error', 'Anda tidak memiliki akses untuk mengedit materi');
        }

        $title = 'Edit Materi';
        return view('features.material.edit', compact('course', 'material', 'title'));
    }

    public function update(Request $request, Course $course, Material $material)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'mentor') {
            return redirect()->route('features.course.show', $course->slug)
                ->with('error', 'Anda tidak memiliki akses untuk mengedit materi');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer|min:0'
        ]);

        $material->update($request->all());

        return redirect()->route('features.material.index', $course->id)
            ->with('success', 'Materi berhasil diperbarui');
    }

    public function destroy(Course $course, Material $material)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'mentor') {
            return redirect()->route('features.course.show', $course->slug)
                ->with('error', 'Anda tidak memiliki akses untuk menghapus materi');
        }

        $material->delete();

        return redirect()->route('features.material.index', $course->id)
            ->with('success', 'Materi berhasil dihapus');
    }

    public function toggleCompletion(Course $course, Material $material)
    {
        if (auth()->user()->role === 'siswa') {
            if ($material->isCompletedByUser(auth()->id())) {
                $material->markAsIncomplete(auth()->id());
            } else {
                $material->markAsCompleted(auth()->id());
            }
        }

        return redirect()->back();
    }

    private function calculateProgress(Course $course)
    {
        if (auth()->user()->role !== 'siswa') {
            return null;
        }

        $totalMaterials = $course->materials()->count();
        if ($totalMaterials === 0) {
            return 0;
        }

        $completedMaterials = $course->materials()
            ->whereHas('completions', function ($query) {
                $query->where('user_id', auth()->id())
                    ->where('is_completed', true);
            })
            ->count();

        return round(($completedMaterials / $totalMaterials) * 100);
    }
}
