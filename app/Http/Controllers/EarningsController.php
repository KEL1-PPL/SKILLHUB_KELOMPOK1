<?php

namespace App\Http\Controllers;

use App\Models\Earning;
use Illuminate\Http\Request;

class EarningsController extends Controller
{
    public function index()
    {
        $earnings = Earning::all();
        return view('mentor.earnings.index', compact('earnings'));
    }

    public function update(Request $request, $id)
    {
        $earning = Earning::findOrFail($id);
        $earning->update([
            'amount' => $request->amount,
            'note' => $request->note,
        ]);

        return redirect()->back()->with('success', 'Data berhasil dikoreksi.');
    }

    public function destroy($id)
    {
        $earning = Earning::findOrFail($id);
        $earning->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
