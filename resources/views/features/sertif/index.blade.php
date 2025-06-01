@extends('all.component.app')

@section('title', 'Sertifikat Saya')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                @include('all.component.menu.siswa') <!-- Pastikan ada sidebar siswa -->
            </div>

            <!-- Content utama -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h1>Sertifikat Saya</h1>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($certificates->isEmpty())
                            <p>Anda belum memiliki sertifikat.</p>
                        @else
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Sertifikat</th>
                                        <th>Kursus</th>
                                        <th>Tanggal Terbit</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($certificates as $index => $certificate)
                                        <tr>
                                            <td>{{ $certificates->firstItem() + $index }}</td>
                                            <td>{{ $certificate->certificate_number }}</td>
                                            <td>{{ $certificate->course->title }}</td>
                                            <td>{{ \Carbon\Carbon::parse($certificate->issued_at)->format('d M Y') }}</td>
                                            <td>
                                                @if($certificate->certificate_file)
                                                    <a href="{{ asset('storage/' . $certificate->certificate_file) }}" target="_blank" class="btn btn-info btn-sm">Lihat Sertifikat</a>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $certificates->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
