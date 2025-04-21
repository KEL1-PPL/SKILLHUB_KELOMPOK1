<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\CourseCompletionHistory;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        return view('siswa.riwayat.index',[
            'title' => 'riwayat',
            'completionHistory' => CourseCompletionHistory::with('course')
                                    ->where('user_id', auth()->user()->id)->get(),
        ]);
    }
}
