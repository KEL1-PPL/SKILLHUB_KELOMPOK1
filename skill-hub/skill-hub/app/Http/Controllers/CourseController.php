<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('mentor')->get(); // kalau ada relasi 'mentor', sekalian eager load

        return view('features.course.index', compact('courses'));
    }

    public function create()
    {
        return view('course.create'); // form tambah kursus
    }

    public function store(Request $request)
    {
        // simpan data kursus baru ke database
    }

    public function show($id)
    {
        // tampilkan detail kursus tertentu
    }

    public function edit($id)
    {
        return view('course.edit'); // form edit kursus
    }

    public function update(Request $request, $id)
    {
        // update data kursus
    }

    public function destroy($id)
    {
        // hapus kursus
    }
}
