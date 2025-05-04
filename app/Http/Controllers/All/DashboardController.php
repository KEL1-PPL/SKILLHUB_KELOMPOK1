<?php

namespace App\Http\Controllers\All;

use App\Http\Controllers\Controller;
use App\Models\Analytic;
use App\Models\CourseCompletionHistory;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $enrollments = [];
        $completionHistory = [];
        $analytics = [];
        $grouped = [];
        if(auth()->user()->role == 'siswa')
        {
            $enrollments = CourseEnrollment::with(['course', 'progress'])
                            ->where('user_id', auth()->user()->id)->get();

            $completionHistory = CourseCompletionHistory::with('course')
                                    ->where('user_id', auth()->user()->id)->get();

        }

        if(auth()->user()->role == 'mentor')
        {
            $analytics = Analytic::with('student', 'course')->get();


            $grouped = $analytics->groupBy('area_of_struggle')->map->count();
        }

        return view('all.index',[
            'title' => 'Dashboard',
            'enrollments' => $enrollments,
            'completionHistory' => $completionHistory,
            'grouped' => $grouped,
            'analytics' => $analytics,
        ]);
    }
}
