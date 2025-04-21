@extends('dashboard.app')

@push('styles')

@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <h2 class="mb-4">📚 Kursus yang Diikuti & Progres</h2>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Kursus</th>
                        <th>Progres</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($enrollments as $item)
                        <tr>
                            <td>{{ $item->course->title }}</td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $item->progress->percentage_completed }}%;"
                                        aria-valuenow="{{ $item->progress->percentage_completed }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                        {{ $item->progress->percentage_completed }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($item->progress->status === 'Selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
