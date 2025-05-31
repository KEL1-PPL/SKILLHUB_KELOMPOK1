@extends('all.component.app')

@push('styles')
    <!-- Google Fonts: Figtree -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #F6F6F6;
            background: linear-gradient(180deg, #287094, #D4D4CE, #F6F6F6, #023246);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h2, h1 {
            color: #023246;
            font-weight: 700;
        }

        .body-wrapper {
            padding: 2rem 0;
            margin-top: 100px; /* supaya gak ketutupan sidebar */
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .card {
            background-color: #FFFFFF;
            border: 1px solid #D4D4CE;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .card-text {
            color: #666;
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .btn-primary {
            background-color: #287094;
            border: none;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background-color: #023246;
        }

        .flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .btn-create {
            background-color: #287094;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn-create:hover {
            background-color: #023246;
        }
    </style>
@endpush

@section('content')
<div class="body-wrapper">
    <div class="container">
        <div class="card">
            <h2 class="card-title">{{ $diskusi->title }}</h2>
            <p class="text-sm text-gray-600 mb-3">oleh {{ $diskusi->user->name }}</p>
            <p class="card-text">{{ $diskusi->question }}</p>
        </div>

        <div class="card bg-gray-100 p-4 rounded shadow-inner">
            <h3 class="card-title">Balasan</h3>
            @forelse($diskusi->replies as $reply)
                <div class="card border {{ $reply->is_best_answer ? 'border-green-500' : 'border-gray-300' }}">
                    <p class="card-text">{{ $reply->content }}</p>
                    <div class="text-sm text-gray-500 flex justify-between mt-2">
                        <span>Dijawab oleh: {{ $reply->user->name }}</span>
                        <div class="space-x-2">
                            @if(auth()->user()->role === 'mentor')
                                <form action="{{ route('replies.best', $reply->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-green-600 hover:underline text-sm">Tandai Terbaik</button>
                                </form>
                            @endif
                            @if(auth()->user()->role === 'admin')
                                <form action="{{ route('admin.replies.destroy', $reply->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:underline text-sm">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Belum ada balasan.</p>
            @endforelse
        </div>

        @if(auth()->user()->role === 'mentor')
        <div class="card">
            <h4 class="font-semibold text-gray-700 mb-2">Berikan Jawaban</h4>
            <form action="{{ route('replies.store', $diskusi->id) }}" method="POST" class="space-y-3">
                @csrf
                <textarea name="content" rows="4" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300" placeholder="Tulis jawaban..."></textarea>
                <button type="submit" class="btn-primary">Kirim Jawaban</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
