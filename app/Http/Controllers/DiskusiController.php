<?php

namespace App\Http\Controllers;

use App\Models\Diskusi;
use Illuminate\Http\Request;

class DiskusiController extends Controller
{
    public function index() {
        $diskusis = Diskusi::with('user')->latest()->get();
        $title = 'Daftar Diskusi';
        return view('features.features-diskusi.index', compact('diskusis', 'title'));
    }

    public function create() {
        return view('features.features-diskusi.create');
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'question' => 'required',
        ]);

        Diskusi::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'question' => $request->question,
        ]);

        return redirect()->route('diskusi.index')->with('success', 'Diskusi berhasil dibuat.');
    }

    public function show($id) {
        $diskusi = Diskusi::with(['user', 'replies.user'])->findOrFail($id);
        $title = 'Detail Diskusi';
        return view('features.features-diskusi.show', compact('diskusi', 'title'));
    }

    public function edit($id) {
        $diskusi = Diskusi::findOrFail($id);
        $title = 'Edit Diskusi';
        return view('features.features-diskusi.edit', compact('diskusi', 'title'));
    }

    public function update(Request $request, $id) {
        $diskusi = Diskusi::findOrFail($id);
        $diskusi->update([
            'title' => $request->title,
            'question' => $request->question,
        ]);
        return redirect()->route('diskusi.index')->with('success', 'Diskusi berhasil diperbarui.');
    }

    public function destroy($id) {
        $diskusi = Diskusi::findOrFail($id);
        $diskusi->delete();
        return redirect()->route('diskusi.index')->with('success', 'Diskusi berhasil dihapus.');
    }
}
