@extends('all.component.app')

@section('title', 'Buat Sertifikat Baru')  <!-- Title halaman -->

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (Jika ada) -->
            <div class="col-md-3">
                @include('all.component.menu.admin') <!-- Menampilkan sidebar admin -->
            </div>

            <!-- Content utama -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h1>Buat Sertifikat Baru</h1>
                    </div>
                    <div class="card-body">
                        <!-- Menampilkan error jika ada -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.certificates.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Pilih Kursus -->
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Pilih Kursus:</label>
                                <select name="course_id" id="course_id" class="form-select" required>
                                    <option value="">-- Pilih Kursus --</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tanggal Terbit -->
                            <div class="mb-3">
                                <label for="issued_at" class="form-label">Tanggal Terbit:</label>
                                <input type="date" id="issued_at" name="issued_at" class="form-control" value="{{ old('issued_at') }}" required>
                            </div>

                            <!-- Upload File Sertifikat -->
                            <div class="mb-3">
                                <label for="certificate_file" class="form-label">Upload File Sertifikat (PDF):</label>
                                <input type="file" id="certificate_file" name="certificate_file" class="form-control" accept="application/pdf">
                            </div>

                            <!-- Tombol Kirim -->
                            <button type="submit" class="btn btn-primary">Buat Sertifikat</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
