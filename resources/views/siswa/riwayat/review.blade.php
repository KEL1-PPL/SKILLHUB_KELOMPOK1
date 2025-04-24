@extends('dashboard.app')

@section('content')
<div class="container mt-5 pt-5">
    <h2 class="mb-4">{{ $course->title }}</h2>

    <div class="card">
        <div class="card-body">
            @if ($course->materials->isEmpty())
                <p>Tidak ada materi tersedia untuk kursus ini.</p>
            @else
                <ul class="list-group">
                    @foreach ($course->materials as $material)
                        <li class="list-group-item">
                            <strong>{{ $material->title }}</strong><br>
                            <p>{{ $material->content }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <a href="{{ route('siswa.riwayat.index') }}" class="btn btn-secondary mt-4">⬅ Kembali ke Riwayat</a>
</div>
@endsection
