<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Analytic;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index()
    {
        $analytics = Analytic::with('student', 'course')->get();
        return view('mentor.progress.index',[
            'title' => 'progress',
            'analytics' => $analytics,
            'grouped' => $analytics->groupBy('area_of_struggle')->map->count(),
        ]);
    }
}
