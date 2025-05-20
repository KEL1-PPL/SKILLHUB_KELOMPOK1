@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 shadow rounded-lg">
    <h1 class="text-2xl font-bold mb-6">Daftar Soal untuk Kuis: <span class="text-blue-600">{{ $quiz->title }}</span></h1>

    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('questions.create', $quiz) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
            + Tambah Soal Baru
        </a>
        <a href="{{ route('quizzes.index') }}" class="text-sm text-blue-600 hover:underline">
            ← Kembali ke Daftar Kuis
        </a>
    </div>

    @if ($quiz->questions->isEmpty())
        <p class="text-gray-600">Belum ada soal untuk kuis ini.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="p-3 w-1/2">Pertanyaan</th>
                        <th class="p-3">Pilihan</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quiz->questions as $question)
                        <tr class="border-t">
                            <td class="p-3 align-top">{{ $question->question_text }}</td>
                            <td class="p-3">
                                <ul class="list-disc pl-5">
                                    @foreach ($question->options as $option)
                                        <li>
                                            {{ $option->option_text }}
                                            @if ($option->is_correct)
                                                <span class="text-green-600 font-semibold">(Benar)</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('questions.edit', $question) }}"
                                   class="inline-block text-sm text-blue-600 hover:underline mb-2">Edit</a>

                                <form method="POST" action="{{ route('questions.destroy', $question) }}" onsubmit="return confirm('Yakin ingin menghapus soal ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-sm text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
