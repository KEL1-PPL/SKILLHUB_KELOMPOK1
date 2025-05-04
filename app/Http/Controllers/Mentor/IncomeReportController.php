<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\MentorIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeReportController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if ($user->role !== 'mentor') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $mentorId = $user->id;
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $incomes = MentorIncome::where('mentorId', $mentorId)
            ->whereBetween('transactionDate', [$startDate, $endDate])
            ->get();

        $totalValid = $incomes->where('status', 'valid')
            ->sum('amount');

        return view('mentor.income-report.index', compact('incomes', 'totalValid', 'startDate', 'endDate'));
    }
}