@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Edit Materi: {{ $material->title }}</h1>

    <form action="{{ route('features.course.materials.update', $material->id) }}" method="POST" class="bg-white shadow rounded p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block font-medium text-gray-700">Judul Materi</label>
            <input type="text" name="title" id="title" value="{{ $material->title }}" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" required>
        </div>

        <div class="mb-4">
            <label for="content" class="block font-medium text-gray-700">Konten</label>
            <textarea name="content" id="content" rows="6" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" required>{{ $material->content }}</textarea>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('features.course.show', $material->course_id) }}" class="mr-4 text-gray-600 hover:underline">Batal</a>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update</button>
        </div>
    </form>
</div>
@endsection
