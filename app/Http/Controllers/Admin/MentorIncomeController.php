<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MentorIncome;
use Illuminate\Http\Request;

class MentorIncomeController extends Controller
{
    public function index()
    {
        $incomes = MentorIncome::all();
        return view('admin.mentor-income.index', compact('incomes'));
    }

    public function correct($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:corrected,deleted',
            'correctionNote' => 'required|string'
        ]);

        $income = MentorIncome::findOrFail($id);
        $income->update([
            'status' => $request->status,
            'correctionNote' => $request->correctionNote
        ]);

        return redirect()->back();
    }
}