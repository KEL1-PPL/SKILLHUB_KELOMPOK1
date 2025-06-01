@extends('all.component.app')

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <div class="row">
                <div class="col-12 mb-3">
                    <a href="{{ route('diskusi.index') }}" class="btn btn-primary">
                        <i class="ti ti-arrow-left"></i> Kembali ke Daftar Diskusi
                    </a>
                </div>

                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h1 class="card-title">{{ $diskusi->title }}</h1>
                            <p class="text-muted mb-2">Oleh: {{ $diskusi->user->name }}</p>
                            <p class="card-text">{{ $diskusi->question }}</p>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Balasan</h5>
                            @forelse($diskusi->replies as $reply)
                                <div class="card mb-3 border {{ $reply->is_best_answer ? 'border-success' : 'border-secondary' }}">
                                    <div class="card-body">
                                        <p class="card-text">{{ $reply->content }}</p>
                                        <div class="d-flex justify-content-between text-muted small mt-2">
                                            <span>Dijawab oleh: {{ $reply->user->name }}</span>
                                            <div class="d-flex gap-2">
                                                @if(auth()->user()->role === 'mentor')
                                                    <form action="{{ route('replies.best', $reply->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-sm btn-outline-success">Tandai Terbaik</button>
                                                    </form>
                                                @endif
                                                @if(auth()->user()->role === 'admin')
                                                    <form action="{{ route('admin.replies.destroy', $reply->id) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus balasan ini?')">Hapus</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">Belum ada balasan.</p>
                            @endforelse
                        </div>
                    </div>

                    @if(auth()->user()->role === 'mentor')
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Berikan Jawaban</h5>
                                <form action="{{ route('replies.store', $diskusi->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="content" rows="4" class="form-control" placeholder="Tulis jawaban..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Kirim Jawaban</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Diskusi</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>Pembuat:</strong> {{ $diskusi->user->name }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Dibuat:</strong> {{ $diskusi->created_at->format('d M Y') }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Jumlah Balasan:</strong> {{ $diskusi->replies->count() }}
                                </li>
                            </ul>
                            <div class="mt-3">
                                <a href="{{ route('diskusi.index') }}" class="btn btn-primary w-100">Lihat Semua Diskusi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
