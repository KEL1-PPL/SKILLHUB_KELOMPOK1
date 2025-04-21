@extends('dashboard.app')

@push('styles')

@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <h2 class="mb-4">📊 Analitik Kemajuan Siswa</h2>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kursus</th>
                        <th>Area Kesulitan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($analytics as $a)
                        <tr>
                            <td>{{ $a->student->name }}</td>
                            <td>{{ $a->course->title }}</td>
                            <td>{{ $a->area_of_struggle }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
