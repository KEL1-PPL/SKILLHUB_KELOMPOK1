<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MentorIncomeReportController extends Controller
{
    public function index()
    {
        return view('mentor.income-report.index');
    }
}
