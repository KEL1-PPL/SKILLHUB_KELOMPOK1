@extends('all.component.app')

@section('content')
<div class="container-fluid" style="padding-top: 120px;">
    {{-- Judul halaman --}}
    <div class="row mb-3">
        <div class="col-lg-10 offset-lg-2 col-md-12 px-4">
            <h1 class="h4 fw-bold">Tambah Paket Berlangganan</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 offset-lg-2 col-md-12 px-4">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('admin.subscription-plans.store') }}" method="POST">
                        @csrf

                        <div class="row gy-3">
                            {{-- Kiri: Nama, Deskripsi, Harga, Durasi --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Paket <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Masukkan nama paket">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" rows="3" required
                                        class="form-control @error('description') is-invalid @enderror"
                                        placeholder="Masukkan deskripsi paket">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="price" id="price" value="{{ old('price') }}" required
                                            class="form-control @error('price') is-invalid @enderror"
                                            placeholder="0">
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="duration_in_days" class="form-label">Durasi (hari) <span class="text-danger">*</span></label>
                                    <input type="number" name="duration_in_days" id="duration_in_days" value="{{ old('duration_in_days') }}" required
                                        class="form-control @error('duration_in_days') is-invalid @enderror"
                                        placeholder="30">
                                    @error('duration_in_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Kanan: Fitur + tombol tambah + Status --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fitur <span class="text-danger">*</span></label>
                                    <div id="features-container">
                                        @if(old('features'))
                                            @foreach(old('features') as $i => $feat)
                                                <div class="input-group mb-2">
                                                    <input type="text" name="features[]" value="{{ $feat }}" required
                                                        class="form-control @error('features.'.$i) is-invalid @enderror"
                                                        placeholder="Masukkan fitur">
                                                    <button type="button" onclick="removeFeature(this)" class="btn btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="input-group mb-2">
                                                <input type="text" name="features[]" required class="form-control"
                                                    placeholder="Masukkan fitur">
                                                <button type="button" onclick="removeFeature(this)" class="btn btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" onclick="addFeature()" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-plus"></i> Tambah Fitur
                                    </button>
                                    @error('features')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check form-switch mb-3 ms-4">
                                    <input type="checkbox" name="is_active" id="is_active" value="1"
                                        {{ old('is_active', true) ? 'checked' : '' }}
                                        class="form-check-input">
                                    <label for="is_active" class="form-check-label">Status Aktif</label>
                                    @error('is_active')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-light">Batal</a>
                            <button type="submit" class="btn btn-dark">Simpan Paket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function addFeature() {
        const container = document.getElementById('features-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" name="features[]" required class="form-control" placeholder="Masukkan fitur">
            <button type="button" onclick="removeFeature(this)" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
    }

    function removeFeature(btn) {
        const c = document.getElementById('features-container');
        if (c.children.length > 1) {
            btn.parentElement.remove();
        }
    }
</script>
@endpush
@endsection
