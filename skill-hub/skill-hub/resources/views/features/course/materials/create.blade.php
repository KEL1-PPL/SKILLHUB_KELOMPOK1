@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Tambah Materi untuk Kursus: {{ $course->title }}</h1>

    <form action="{{ route('features.course.materials.store', $course->id) }}" method="POST" class="bg-white shadow rounded p-6">
        @csrf

        <div class="mb-4">
            <label for="title" class="block font-medium text-gray-700">Judul Materi</label>
            <input type="text" name="title" id="title" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" required>
        </div>

        <div class="mb-4">
            <label for="content" class="block font-medium text-gray-700">Konten</label>
            <textarea name="content" id="content" rows="6" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" required></textarea>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('features.course.show', $course->id) }}" class="mr-4 text-gray-600 hover:underline">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection
