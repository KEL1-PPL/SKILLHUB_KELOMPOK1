<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LiveClass;

class LiveClassController extends Controller
{
    public function index()
    {
        // Get all live classes ordered by datetime
        $liveClasses = LiveClass::orderBy('datetime', 'desc')->get();
        
        return view('features.live-class.index', [
            'title' => 'live',
            'liveClasses' => $liveClasses
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'datetime' => 'required|date|after:now',
            'platform' => 'required|string|max:50',
            'link' => 'required|url|max:500',
        ], [
            'title.required' => 'Judul live class harus diisi.',
            'title.max' => 'Judul live class maksimal 255 karakter.',
            'description.required' => 'Deskripsi harus diisi.',
            'datetime.required' => 'Tanggal dan waktu harus diisi.',
            'datetime.after' => 'Tanggal dan waktu harus di masa depan.',
            'platform.required' => 'Platform harus dipilih.',
            'platform.max' => 'Platform maksimal 50 karakter.',
            'link.required' => 'Link akses harus diisi.',
            'link.url' => 'Link akses harus berupa URL yang valid.',
            'link.max' => 'Link akses maksimal 500 karakter.',
        ]);

        try {
            LiveClass::create([
                'title' => $request->title,
                'description' => $request->description,
                'datetime' => $request->datetime,
                'platform' => $request->platform,
                'link' => $request->link,
                // You can add user_id if you want to track who created the live class
                // 'user_id' => auth()->id(),
            ]);

            return redirect()->route('live-class.index')->with('success', 'Live class berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat live class. Silakan coba lagi.');
        }
    }

    public function show($id)
    {
        $liveClass = LiveClass::findOrFail($id);
        
        return view('features.live-class.show', [
            'title' => 'live',
            'liveClass' => $liveClass
        ]);
    }

    public function edit($id)
    {
        $liveClass = LiveClass::findOrFail($id);
        
        return view('features.live-class.edit', [
            'title' => 'live',
            'liveClass' => $liveClass
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'datetime' => 'required|date',
            'platform' => 'required|string|max:50',
            'link' => 'required|url|max:500',
        ], [
            'title.required' => 'Judul live class harus diisi.',
            'title.max' => 'Judul live class maksimal 255 karakter.',
            'description.required' => 'Deskripsi harus diisi.',
            'datetime.required' => 'Tanggal dan waktu harus diisi.',
            'platform.required' => 'Platform harus dipilih.',
            'platform.max' => 'Platform maksimal 50 karakter.',
            'link.required' => 'Link akses harus diisi.',
            'link.url' => 'Link akses harus berupa URL yang valid.',
            'link.max' => 'Link akses maksimal 500 karakter.',
        ]);

        try {
            $liveClass = LiveClass::findOrFail($id);
            $liveClass->update($request->all());

            return redirect()->route('live-class.index')->with('success', 'Live class berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui live class. Silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        try {
            $liveClass = LiveClass::findOrFail($id);
            $liveClass->delete();

            return redirect()->route('live-class.index')->with('success', 'Live class berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus live class. Silakan coba lagi.');
        }
    }
}