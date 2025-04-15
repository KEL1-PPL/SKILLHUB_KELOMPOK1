<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Http\Request;

class CourseMaterialController extends Controller
{
    public function create($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('features.course.materials.create', compact('course'));
    }

    public function store(Request $request, $courseId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        $course = Course::findOrFail($courseId);
        $course->materials()->create($request->only('title', 'content'));

        return redirect()->route('features.course.show', $courseId)->with('success', 'Materi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $material = CourseMaterial::findOrFail($id);
        return view('features.course.materials.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        $material = CourseMaterial::findOrFail($id);
        $material->update($request->only('title', 'content'));

        return redirect()->route('features.course.show', $material->course_id)->with('success', 'Materi berhasil diupdate.');
    }

    public function destroy($id)
    {
        $material = CourseMaterial::findOrFail($id);
        $courseId = $material->course_id;
        $material->delete();

        return redirect()->route('features.course.show', $courseId)->with('success', 'Materi berhasil dihapus.');
    }
}

