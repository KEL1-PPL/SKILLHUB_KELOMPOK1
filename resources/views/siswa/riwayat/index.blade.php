@extends('dashboard.app')

@push('styles')

@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <h2 class="mt-5 mb-4">🕘 Riwayat Penyelesaian Kursus</h2>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Kursus</th>
                        <th>Tanggal Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($completionHistory as $history)
                        <tr>
                            <td>{{ $history->course->title }}</td>
                            <td>{{ $history->completed_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
