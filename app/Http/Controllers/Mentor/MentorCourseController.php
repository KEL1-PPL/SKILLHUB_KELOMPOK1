<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MentorCourseController extends Controller
{
    public function index()
    {
        return view('mentor.course-management.index');
    }
}