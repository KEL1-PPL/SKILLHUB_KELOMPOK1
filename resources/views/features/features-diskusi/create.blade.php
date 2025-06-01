@extends('all.component.app')

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">Buat Diskusi Baru</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('diskusi.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul</label>
                                    <input type="text" id="title" name="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="question" class="form-label">Pertanyaan</label>
                                    <textarea id="question" name="question" rows="5"
                                              class="form-control @error('question') is-invalid @enderror"
                                              required>{{ old('question') }}</textarea>
                                    @error('question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Kirim</button>
                                    <a href="{{ route('diskusi.index') }}" class="btn btn-secondary">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
