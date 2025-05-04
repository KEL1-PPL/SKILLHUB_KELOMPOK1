@extends('dashboard.app')

@push('styles')
    <style>
        .no-history {
            text-align: center;
            padding: 2rem;
            font-style: italic;
            color: gray;
        }
    </style>
@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <h2 class="mt-5 mb-4">🕘 Riwayat Penyelesaian Kursus</h2>

            @if($completionHistory->isEmpty())
                <div class="no-history">
                    Belum ada kursus yang diselesaikan. Silakan selesaikan kursus terlebih dahulu.
                </div>
            @else
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Kursus</th>
                            <th>Tanggal Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($completionHistory as $history)
                            <tr>
                                <td>{{ $history->course->title }}</td>
                                <td>{{ $history->completed_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('courses.review', $history->course->id) }}" class="btn btn-sm btn-primary">
                                        Tinjau Materi
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
@endpush
