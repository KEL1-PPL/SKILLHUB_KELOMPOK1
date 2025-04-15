@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Tambah Kursus Baru</h1>

    @if ($errors->any())
    <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('features.course.store') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label for="title" class="block font-medium text-gray-700">Judul Kursus</label>
            <input type="text" name="title" id="title" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
        </div>

        <div>
            <label for="description" class="block font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" id="description" rows="5" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required></textarea>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('features.course.index') }}" class="mr-4 text-gray-600 hover:underline">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Simpan</button>
        </div>
    </form>
</div>
@endsection
