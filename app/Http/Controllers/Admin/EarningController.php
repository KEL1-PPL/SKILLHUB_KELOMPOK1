// Menghapus pendapatan mentor
public function destroy(Earning $earning)
{
    $earning->delete();
    return redirect()->route('admin.earnings.index')->with('success', 'Pendapatan berhasil dihapus');
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
    ]);

    return redirect()->route('admin.earnings.index')->with('success', 'Pendapatan berhasil diperbarui');
}
