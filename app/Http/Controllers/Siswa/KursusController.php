<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;

class KursusController extends Controller
{
    public function index()
    {
        return view('siswa.kursus.index',[
            'title' => 'kursus',
            'enrollments' => CourseEnrollment::with(['course', 'progresS'])
                            ->where('user_id', auth()->user()->id)->get(),
        ]);
    }
}
