<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LiveClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LiveClassController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'siswa') {
            $liveClasses = LiveClass::where('status', 'published')
                ->live()
                ->orderBy('datetime', 'asc')
                ->get();

            $liveClasses = $liveClasses->filter(function($class) {
                return $class->isLive();
            });

            $upcomingClasses = LiveClass::where('status', 'published')
                ->upcoming()
                ->orderBy('datetime', 'asc')
                ->get();

            $upcomingClasses = $upcomingClasses->filter(function($class) {
                return $class->isUpcoming();
            });

            return view('features.live-class-student.index', [
                'title' => 'live',
                'liveClasses' => $liveClasses,
                'upcomingClasses' => $upcomingClasses
            ]);
        }

        $liveClasses = LiveClass::orderBy('datetime', 'desc')->get();
        return view('features.live-class.index', [
            'title' => 'live',
            'liveClasses' => $liveClasses
        ]);
    }

    public function create()
    {
        if (auth()->user()->role == 'siswa') {
            return redirect()->route('live-class-student.index')
                ->with('error', 'Anda tidak memiliki akses untuk membuat live class.');
        }
        
        return view('features.live-class.create', [
            'title' => 'live'
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->role == 'siswa') {
            return redirect()->route('live-class-student.index')
                ->with('error', 'Anda tidak memiliki akses untuk membuat live class.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'datetime' => 'required|date|after:now',
            'platform' => 'required|string|max:50',
            'link' => 'required|url|max:500',
        ]);

        try {
            $datetimeWIB = Carbon::parse($request->datetime, 'Asia/Jakarta');

            LiveClass::create([
                'title' => $request->title,
                'description' => $request->description,
                'datetime' => $datetimeWIB,
                'platform' => $request->platform,
                'link' => $request->link,
                'user_id' => auth()->id(),
                'status' => 'published'
            ]);

            return redirect()->route('live-class.index')
                ->with('success', 'Live class berhasil dibuat!');
        } catch (\Exception $e) {
            Log::error('Error creating live class: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat live class. Silakan coba lagi.');
        }
    }

    public function show($id)
    {
        try {
            $liveClass = LiveClass::findOrFail($id);
            
            Log::info('LiveClass show method called', [
                'id' => $id,
                'user_role' => auth()->user()->role,
                'class_status' => $liveClass->status,
                'debug_status' => $liveClass->debug_status
            ]);
            
            if (auth()->user()->role == 'siswa' && $liveClass->status !== 'published') {
                return redirect()->route('live-class-student.index')
                    ->with('error', 'Live class tidak tersedia.');
            }

            if (auth()->user()->role == 'siswa') {
                return view('features.live-class-student.show', [
                    'title' => 'live',
                    'liveClass' => $liveClass
                ]);
            }

            return view('features.live-class.show', [
                'title' => 'live',
                'liveClass' => $liveClass
            ]);
        } catch (\Exception $e) {
            Log::error('Error in show method: ' . $e->getMessage());
            return redirect()->route('live-class-student.index')
                ->with('error', 'Live class tidak ditemukan.');
        }
    }

    public function edit($id)
    {
        if (auth()->user()->role == 'siswa') {
            return redirect()->route('live-class-student.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit live class.');
        }
        
        $liveClass = LiveClass::findOrFail($id);

        return view('features.live-class.edit', [
            'title' => 'live',
            'liveClass' => $liveClass
        ]);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role == 'siswa') {
            return redirect()->route('live-class-student.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengubah live class.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'datetime' => 'required|date|after:now',
            'platform' => 'required|string|max:50',
            'link' => 'required|url|max:500',
        ]);

        try {
            $liveClass = LiveClass::findOrFail($id);
            $datetimeWIB = Carbon::parse($request->datetime, 'Asia/Jakarta');

            $liveClass->update([
                'title' => $request->title,
                'description' => $request->description,
                'datetime' => $datetimeWIB,
                'platform' => $request->platform,
                'link' => $request->link,
            ]);

            return redirect()->route('live-class.index')
                ->with('success', 'Live class berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating live class: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui live class. Silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        if (auth()->user()->role == 'siswa') {
            return redirect()->route('live-class-student.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus live class.');
        }
        
        try {
            $liveClass = LiveClass::findOrFail($id);
            
            Log::info('Live class deletion attempt', [
                'id' => $id,
                'title' => $liveClass->title,
                'status' => $liveClass->status,
                'is_live' => $liveClass->isLive(),
                'is_upcoming' => $liveClass->isUpcoming(),
                'is_completed' => $liveClass->isCompleted(),
                'deleted_by' => auth()->id()
            ]);
            
            if ($liveClass->isLive()) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus live class yang sedang berlangsung.');
            }
            
            $title = $liveClass->title;
            $participantsCount = $liveClass->participants_count;
            $isCompleted = $liveClass->isCompleted();
            
            $liveClass->delete();
            
            $message = $isCompleted 
                ? "Live class '$title' yang sudah selesai berhasil dihapus! (Total peserta: $participantsCount)"
                : "Live class '$title' yang akan datang berhasil dihapus!";
            return redirect()->route('live-class.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error('Error deleting live class: ' . $e->getMessage(), [
                'id' => $id,
                'user_id' => auth()->id()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus live class. Silakan coba lagi.');
        }
    }

    public function join($id)
    {
        try {
            $liveClass = LiveClass::findOrFail($id);
            
            Log::info('Join method called', [
                'id' => $id,
                'user_id' => auth()->id(),
                'class_title' => $liveClass->title,
                'class_status' => $liveClass->status,
                'debug_status' => $liveClass->debug_status
            ]);
            
            if ($liveClass->status !== 'published') {
                return redirect()->route('live-class-student.index')
                    ->with('error', 'Live class tidak tersedia.');
            }
            
            if (!$liveClass->isLive()) {
                if ($liveClass->isUpcoming()) {
                    return redirect()->route('live-class-student.show', $id)
                        ->with('error', 'Live class belum dimulai. Silakan tunggu hingga waktu yang dijadwalkan.');
                } else {
                    return redirect()->route('live-class-student.show', $id)
                        ->with('error', 'Live class sudah selesai.');
                }
            }

            $liveClass->increment('participants_count');
            
            Log::info('User joined live class', [
                'user_id' => auth()->id(),
                'class_id' => $id,
                'participants_count' => $liveClass->participants_count + 1
            ]);
            
            return redirect()->away($liveClass->link);
            
        } catch (\Exception $e) {
            Log::error('Error in join method: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'class_id' => $id
            ]);
            return redirect()->route('live-class-student.index')
                ->with('error', 'Terjadi kesalahan saat bergabung ke live class.');
        }
    }
}