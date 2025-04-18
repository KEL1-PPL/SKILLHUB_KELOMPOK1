<?php

// ======= app/Http/Controllers/VoucherController.php =======
namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    // Menampilkan daftar voucher untuk pengguna
    public function index()
    {
        $vouchers = Voucher::where('expiration_date', '>=', now())->get(); // Menampilkan voucher yang masih berlaku
        return view('user.voucher.index', compact('vouchers'));
    }

    // Menebus voucher
    public function redeem(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:vouchers,code',
        ]);

        $voucher = Voucher::where('code', $request->code)->first();

        // Mengecek apakah voucher masih berlaku
        if ($voucher->expiration_date < now()) {
            return redirect()->route('voucher.index')->with('error', 'Voucher sudah kedaluwarsa.');
        }

        // Terapkan diskon atau beri pesan sukses
        return redirect()->route('checkout.index')->with('success', 'Voucher berhasil digunakan.');
    }
}
