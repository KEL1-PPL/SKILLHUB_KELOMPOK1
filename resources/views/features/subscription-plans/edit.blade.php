@extends('all.component.app')

@section('content')
<div class="container-fluid" style="padding-top: 120px;">
    {{-- Judul halaman --}}
    <div class="row mb-3">
        <div class="col-lg-10 offset-lg-2 col-md-12 px-4">
            <h1 class="h4 fw-bold">Edit Paket Berlangganan</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 offset-lg-2 col-md-12 px-4">
            <div class="card shadow">
                <div class="card-body">
                    <form method="POST"
                          action="{{ route('admin.subscription-plans.update', $subscriptionPlan) }}">
                        @csrf
                        @method('PUT')

                        <div class="row gy-3">
                            {{-- Kiri: Nama, Deskripsi, Harga, Durasi --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        Nama Paket <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $subscriptionPlan->name) }}"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        Deskripsi <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control"
                                              id="description"
                                              name="description"
                                              rows="3"
                                              required>{{ old('description', $subscriptionPlan->description) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">
                                        Harga <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number"
                                               step="0.01"
                                               class="form-control"
                                               id="price"
                                               name="price"
                                               value="{{ old('price', $subscriptionPlan->price) }}"
                                               required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="duration_in_days" class="form-label">
                                        Durasi (hari) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"
                                           class="form-control"
                                           id="duration_in_days"
                                           name="duration_in_days"
                                           value="{{ old('duration_in_days', $subscriptionPlan->duration_in_days) }}"
                                           required>
                                </div>
                            </div>

                            {{-- Kanan: Fitur + tombol tambah --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        Fitur <span class="text-danger">*</span>
                                    </label>
                                    <div id="features-container">
                                        @foreach (old('features', json_decode($subscriptionPlan->features)) as $feature)
                                            <div class="input-group mb-2">
                                                <input type="text"
                                                       name="features[]"
                                                       class="form-control"
                                                       value="{{ $feature }}"
                                                       required>
                                                <button class="btn btn-outline-danger"
                                                        type="button"
                                                        onclick="removeFeature(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button"
                                            class="btn btn-outline-primary btn-sm mt-2"
                                            onclick="addFeature()">
                                        <i class="bi bi-plus"></i> Tambah Fitur
                                    </button>
                                </div>

                                <div class="form-check form-switch mb-3 ms-4">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', $subscriptionPlan->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Status Aktif
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.subscription-plans.index') }}"
                               class="btn btn-light">Batal</a>
                            <button type="submit" class="btn btn-dark">
                                Simpan Perubahan
                            </button>
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
        const wrapper = document.createElement('div');
        wrapper.className = 'input-group mb-2';
        wrapper.innerHTML = `
            <input type="text" name="features[]" class="form-control" required>
            <button class="btn btn-outline-danger" type="button" onclick="removeFeature(this)">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(wrapper);
    }

    function removeFeature(button) {
        button.closest('.input-group').remove();
    }

    let wasMobile = window.innerWidth < 768;
    window.addEventListener('resize', () => {
        const isMobile = window.innerWidth < 768;
        if (wasMobile && !isMobile) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        wasMobile = isMobile;
    });
</script>
@endpush
@endsection
