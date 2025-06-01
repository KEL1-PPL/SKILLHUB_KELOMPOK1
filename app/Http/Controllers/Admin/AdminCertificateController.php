<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminCertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with('user', 'course')->orderBy('created_at', 'desc')->paginate(10);
        return view('all.admin.sertifikat.index', [
            'title' => 'Manajement Sertifikat',
            'certificates' => $certificates
        ]);
    }

    public function create()
    {
        $courses = Course::all();
        return view('all.admin.sertifikat.create', [
            'title' => 'Manajement Sertifikat',
            'courses' => $courses
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'issued_at' => 'required|date',
            'certificate_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $issuedAt = Carbon::parse($validated['issued_at']);
        $certificateNumberPrefix = 'CERT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

        $filePath = null;
        if ($request->hasFile('certificate_file')) {
            $filePath = $request->file('certificate_file')->store('certificates', 'public');
        }

        $course = Course::findOrFail($validated['course_id']);
        $students = $course->enrollments()->with('user')->get()->pluck('user')->filter();

        if ($students->isEmpty()) {
            return redirect()->route('admin.certificates.index')
                ->with('error', 'Tidak ada siswa yang terdaftar di kursus ini.');
        }

        foreach ($students as $index => $student) {
            $uniqueNumber = $certificateNumberPrefix . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            Certificate::create([
                'user_id' => $student->id,
                'course_id' => $validated['course_id'],
                'certificate_number' => $uniqueNumber,
                'issued_at' => $issuedAt,
                'certificate_file' => $filePath,
            ]);
        }

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil dibuat.');
    }

    public function edit(Certificate $certificate)
    {
        $certificate->issued_at = Carbon::parse($certificate->issued_at);
        $courses = Course::all();
        
        return view('all.admin.sertifikat.edit', [
            'title' => 'Manajement Sertifikat',
            'certificate' => $certificate,
            'courses' => $courses
        ]);
    }

    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'issued_at' => 'required|date',
            'certificate_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $issuedAt = Carbon::parse($validated['issued_at']);

        if ($request->hasFile('certificate_file')) {
            if ($certificate->certificate_file) {
                Storage::disk('public')->delete($certificate->certificate_file);
            }
            $filePath = $request->file('certificate_file')->store('certificates', 'public');
            $certificate->certificate_file = $filePath;
        }

        $certificate->course_id = $validated['course_id'];
        $certificate->issued_at = $issuedAt;
        $certificate->save();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil diperbarui.');
    }

    public function destroy(Certificate $certificate)
    {
        if ($certificate->certificate_file) {
            Storage::disk('public')->delete($certificate->certificate_file);
        }

        $certificate->delete();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }
} 