<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EarningReport;
use Illuminate\Support\Facades\Auth;

class EarningReportController extends Controller
{
    public function index()
    {
        $reports = EarningReport::where('mentor_id', Auth::id())->orderByDesc('period')->get();
        return view('mentor.earning_reports.index', compact('reports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'period' => 'required|date',
            'amount' => 'required|numeric',
            'note' => 'nullable|string',
        ]);

        EarningReport::create([
            'mentor_id' => Auth::id(),
            'period' => $request->period,
            'amount' => $request->amount,
            'note' => $request->note,
        ]);

        return redirect()->back()->with('success', 'Catatan pendapatan berhasil ditambahkan.');
    }
}
