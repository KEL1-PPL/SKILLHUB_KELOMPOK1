@extends('all.component.app')

@section('title', 'Manajemen Sertifikat')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                @include('all.component.menu.admin')
            </div>

            <!-- Content -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h1>Daftar Sertifikat</h1>
                        <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary">Tambah Sertifikat</a>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Sertifikat</th>
                                    <th>Kursus</th>
                                    <th>Tanggal Terbit</th>
                                    <th>File Sertifikat</th> <!-- Kolom baru untuk file sertifikat -->
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($certificates as $index => $certificate)
                                    <tr>
                                        <td>{{ $certificates->firstItem() + $index }}</td>
                                        <td>{{ $certificate->certificate_number }}</td>
                                        <td>{{ $certificate->course->title }}</td>
                                        <td>
                                            <!-- Pastikan issued_at diformat dengan Carbon jika valid -->
                                            @if($certificate->issued_at)
                                                {{ \Carbon\Carbon::parse($certificate->issued_at)->format('d M Y') }}
                                            @else
                                                - <!-- Jika issued_at kosong -->
                                            @endif
                                        </td>
                                        <td>
                                            <!-- Tampilkan link untuk file sertifikat, jika ada -->
                                            @if($certificate->certificate_file)
                                                <a href="{{ asset('storage/' . $certificate->certificate_file) }}" target="_blank" class="btn btn-info btn-sm">Lihat Sertifikat</a>
                                            @else
                                                - <!-- Jika tidak ada file sertifikat -->
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.certificates.edit', $certificate->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('admin.certificates.destroy', $certificate->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin hapus sertifikat ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        {{ $certificates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
