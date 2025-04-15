@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold">{{ $course->title }}</h1>
        <p class="text-gray-600 mt-2">{{ $course->description }}</p>
        <p class="text-sm text-gray-500 mt-1">Mentor: {{ $course->mentor->name ?? '-' }}</p>
    </div>

    @if(auth()->user()->role === 'student')
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded p-4">
        <p class="font-semibold">Progress Belajar:</p>
        <p class="text-blue-700 mt-1">
            {{ $progress ? $progress->percentage_completed . '%' : 'Belum memulai' }}
        </p>

        @if(!$completed)
        <form action="{{ route('features.course.markCompleted', $course->id) }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Tandai Selesai
            </button>
        </form>
        @else
        <p class="mt-2 text-green-700 font-semibold">✔ Kamu telah menyelesaikan kursus ini.</p>
        @endif
    </div>
    @endif

    <!-- Placeholder untuk daftar materi -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-3">Materi Kursus</h2>
        <p class="text-gray-500">Fitur materi akan ditambahkan di tahap selanjutnya...</p>
    </div>

    <div class="mt-8">
        <a href="{{ route('features.course.index') }}" class="text-blue-600 hover:underline">&larr; Kembali ke Daftar Kursus</a>
    </div>
</div>
@endsection
