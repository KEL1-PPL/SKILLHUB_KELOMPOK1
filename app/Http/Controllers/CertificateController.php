<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;  // Pastikan menggunakan Carbon untuk parsing dan format tanggal

class CertificateController extends Controller
{
    /**
     * Menampilkan daftar sertifikat (index).
     */
    public function index()
    {
        $certificates = Certificate::with('user', 'course')->orderBy('created_at', 'desc')->paginate(10);
        $title = 'Daftar Sertifikat';

        return view('all.admin.sertifikat.index', compact('certificates', 'title'));
    }

    /**
     * Menampilkan form buat sertifikat baru.
     */
    public function create()
    {
        $courses = Course::all(); // Pastikan sudah ada model Course
        $title = 'Buat Sertifikat Baru';

        return view('all.admin.sertifikat.create', compact('courses', 'title'));
    }

    /**
     * Simpan sertifikat baru.
     */
    public function store(Request $request)
    {
        // Validasi untuk kursus dan tanggal
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',  // Mengambil kursus
            'issued_at' => 'required|date',  // Tanggal terbit sertifikat
            'certificate_file' => 'nullable|file|mimes:pdf|max:2048',  // File sertifikat
        ]);

        // Menggunakan Carbon untuk mengonversi issued_at menjadi objek DateTime
        $issuedAt = Carbon::parse($validated['issued_at']);  // Menggunakan Carbon untuk parsing string menjadi DateTime

        // Membuat nomor sertifikat
        $certificateNumberPrefix = 'CERT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

        // Menyimpan file sertifikat jika ada
        $filePath = null;
        if ($request->hasFile('certificate_file')) {
            $filePath = $request->file('certificate_file')->store('certificates', 'public');
        }

        // Ambil kursus berdasarkan id yang dipilih
        $course = Course::findOrFail($validated['course_id']);

        // Mengambil semua siswa yang terdaftar di kursus ini menggunakan relasi enrollments
        $students = $course->enrollments()->with('user')->get()->pluck('user')->filter(); // Hindari null user

        // Periksa jika tidak ada siswa yang terdaftar
        if ($students->isEmpty()) {
            return redirect()->route('admin.certificates.index')
                ->with('error', 'Tidak ada siswa yang terdaftar di kursus ini.');
        }

        // Menambahkan sertifikat untuk setiap siswa yang terdaftar di kursus tersebut
        foreach ($students as $index => $student) {
            $uniqueNumber = $certificateNumberPrefix . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);  // Sertifikat nomor unik
            Certificate::create([
                'user_id' => $student->id,  // Menyimpan user_id (siswa)
                'course_id' => $validated['course_id'],
                'certificate_number' => $uniqueNumber,
                'issued_at' => $issuedAt,  // Pastikan kita menyimpan objek DateTime
                'certificate_file' => $filePath,
            ]);
        }

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil dibuat.');
    }

    /**
     * Menampilkan form edit sertifikat.
     */
   public function edit(Certificate $certificate)
{
    // Pastikan issued_at menjadi objek Carbon
    $certificate->issued_at = Carbon::parse($certificate->issued_at);

    $courses = \App\Models\Course::all(); // Mengambil semua kursus
    $title = 'Edit Sertifikat';  // Mendefinisikan title

    return view('all.admin.sertifikat.edit', compact('certificate', 'courses', 'title'));
}

    /**
     * Update sertifikat yang sudah ada.
     */
    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',  // Mengambil kursus
            'issued_at' => 'required|date',  // Tanggal terbit sertifikat
            'certificate_file' => 'nullable|file|mimes:pdf|max:2048',  // File sertifikat
        ]);

        // Menggunakan Carbon untuk mengonversi issued_at menjadi objek DateTime
        $issuedAt = Carbon::parse($validated['issued_at']);  // Menggunakan Carbon untuk parsing string menjadi DateTime

        // Jika upload file baru, hapus yang lama
        if ($request->hasFile('certificate_file')) {
            if ($certificate->certificate_file) {
                Storage::disk('public')->delete($certificate->certificate_file);
            }
            $filePath = $request->file('certificate_file')->store('certificates', 'public');
            $certificate->certificate_file = $filePath;
        }

        $certificate->course_id = $validated['course_id'];
        $certificate->issued_at = $issuedAt;  // Menggunakan objek DateTime untuk issued_at
        $certificate->save();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil diperbarui.');
    }

    /**
     * Hapus sertifikat yang sudah tidak valid.
     */
    public function destroy(Certificate $certificate)
    {
        if ($certificate->certificate_file) {
            Storage::disk('public')->delete($certificate->certificate_file);
        }

        $certificate->delete();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }

    public function myCertificates()
{
    $user = auth()->user(); // Ambil user yang sedang login

    // Ambil semua sertifikat yang terkait dengan user yang sedang login
    $certificates = $user->certificates()->with('course')->paginate(10); 
    $title = 'Daftar Sertifikat';

    // Kirim sertifikat ke view 'features.sertif.index'
    return view('features.sertif.index', compact('certificates', 'title'));
}


    /**
     * Siswa mengunduh sertifikat setelah menyelesaikan kursus.
     */
    public function download(Certificate $certificate)
    {
        $user = auth()->user();
        if ($user->id !== $certificate->user_id && !$user->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        if (!$certificate->certificate_file || !Storage::disk('public')->exists($certificate->certificate_file)) {
            abort(404, 'File sertifikat tidak ditemukan.');
        }

        return Storage::disk('public')->download($certificate->certificate_file, "Certificate-{$certificate->certificate_number}.pdf");
    }
}
