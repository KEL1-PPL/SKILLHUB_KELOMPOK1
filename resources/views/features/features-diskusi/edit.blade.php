@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 py-6">
    <h1 class="text-xl font-bold mb-4">Edit Diskusi</h1>
    <form action="{{ route('diskusi.update', $diskusi->id) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-gray-700">Judul</label>
            <input type="text" name="title" value="{{ $diskusi->title }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
        </div>
        <div>
            <label class="block text-gray-700">Pertanyaan</label>
            <textarea name="question" rows="5" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">{{ $diskusi->question }}</textarea>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update</button>
    </form>
</div>
@endsection
