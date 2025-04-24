<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
{
    $courses = Course::all();
    $title = 'Kursus'; // atau 'Dashboard', atau sesuai nama sidebar

    return view('features.course.index', compact('courses', 'title'));
}


    public function create()
    {
        return view('features.course.create'); // form tambah kursus
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
        return view('features.course.edit'); // form edit kursus
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
