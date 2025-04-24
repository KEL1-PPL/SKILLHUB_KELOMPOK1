<?php

namespace App\Http\Controllers\Admin;

use App\Models\Earning;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EarningController extends Controller
{
    // Menghapus pendapatan mentor
    public function destroy(Earning $earning)
    {
        if (!$earning->is_valid) {
            return redirect()->route('admin.earnings.index')->with('error', 'Pendapatan tidak valid tidak dapat dihapus.');
        }

        $earning->delete();
        return redirect()->route('admin.earnings.index')->with('success', 'Pendapatan berhasil dihapus.');
    }

    // Memperbarui pendapatan dengan catatan koreksi
    public function update(Request $request, Earning $earning)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'correction_note' => 'required|string',
        ]);

        $earning->update([
            'amount' => $request->amount,
            'correction_note' => $request->correction_note,
            'is_valid' => true, // Ensure the record is marked as valid after correction
        ]);

        return redirect()->route('admin.earnings.index')->with('success', 'Pendapatan berhasil diperbarui.');
    }

    // Menandai pendapatan sebagai tidak valid dengan catatan koreksi
    public function invalidate(Request $request, Earning $earning)
    {
        $request->validate([
            'correction_note' => 'required|string',
        ]);

        $earning->update([
            'is_valid' => false,
            'correction_note' => $request->correction_note,
        ]);

        return redirect()->route('admin.earnings.index')->with('success', 'Pendapatan berhasil ditandai sebagai tidak valid.');
    }
}
