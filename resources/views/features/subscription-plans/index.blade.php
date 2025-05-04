@extends('all.component.app')

@push('style')
<style>
    .content-container {
        padding: 2rem;
        padding-top: 120px;
    }

    @media (min-width: 992px) {
        main {
            margin-left: 260px;
        }
    }

    .table-container {
        overflow-x: auto;
    }

    @media (max-width: 576px) {
        .btn-add-package {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="position-relative content-container">

    <!-- Header responsif -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
        <h1 class="h4 fw-bold">Paket Berlangganan</h1>
        <a href="{{ route('admin.subscription-plans.create') }}" class="btn btn-dark btn-add-package d-flex align-items-center">
            <i class="bi bi-plus me-1"></i> Tambah Paket
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <!-- Tabel -->
            <div class="table-container">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Nama Paket</th>
                            <th>Harga</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th>Fitur</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subscriptionPlans as $plan)
                            <tr>
                                <td>
                                    <strong>{{ $plan->name }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($plan->description, 50) }}</small>
                                </td>
                                <td>{{ $plan->formatted_price }}</td>
                                <td>{{ $plan->duration_in_days }} hari</td>
                                <td>
                                    <span class="badge {{ $plan->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $plan->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach (json_decode($plan->features) as $feature)
                                            <li><i class="bi bi-check-circle-fill text-success me-1"></i>{{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('admin.subscription-plans.edit', $plan) }}" class="btn btn-sm btn-outline-secondary me-2">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.subscription-plans.destroy', $plan) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada paket berlangganan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($subscriptionPlans->hasPages())
                <div class="mt-4">
                    {{ $subscriptionPlans->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
