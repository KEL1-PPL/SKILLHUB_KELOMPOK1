@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Daftar Kursus</h1>

        @if(auth()->check() && auth()->user()->role === 'mentor')
        <a href="{{ route('features.course.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            + Tambah Kursus
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($courses as $course)
        <div class="bg-white shadow-md rounded-lg p-5">
            <h2 class="text-xl font-semibold mb-2">{{ $course->title }}</h2>
            <p class="text-gray-600 mb-4">{{ \Illuminate\Support\Str::limit($course->description, 100) }}</p>
            
            <p class="text-sm text-gray-500 mb-2">Mentor: {{ $course->mentor->name ?? '-' }}</p>
            
            <div class="flex justify-between items-center mt-4">
                <a href="{{ route('features.course.show', $course->id) }}" class="text-blue-600 hover:underline">Lihat Detail</a>

                @if(auth()->user()->role === 'mentor')
                <div class="flex gap-2">
                    <a href="{{ route('features.course.edit', $course->id) }}" class="text-yellow-500 hover:underline">Edit</a>
                    <form action="{{ route('features.course.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kursus ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @empty
        <p class="col-span-3 text-gray-500">Belum ada kursus yang tersedia.</p>
        @endforelse
    </div>
</div>
@endsection
