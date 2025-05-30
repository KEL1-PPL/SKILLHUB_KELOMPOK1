@extends('all.component.app')

@push('style')
<style>
    .content-container {
        padding: 2rem;
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
    
    .live-indicator {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>
@endpush

@section('content')
<div class="position-relative content-container">
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h5>{{ $liveClasses->filter(fn($class) => $class->isLive())->count() }}</h5>
                    <small>Sedang Live</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h5>{{ $liveClasses->filter(fn($class) => $class->isUpcoming())->count() }}</h5>
                    <small>Akan Datang</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <h5>{{ $liveClasses->filter(fn($class) => $class->isCompleted())->count() }}</h5>
                    <small>Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h5>{{ $liveClasses->sum('participants_count') }}</h5>
                    <small>Total Peserta</small>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-broadcast me-2"></i>Manajemen Live Class
                    </h5>
                    <a href="{{ route('live-class.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Live Class
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($liveClasses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Platform</th>
                                        <th>Tanggal & Waktu</th>
                                        <th>Status</th>
                                        <th>Peserta</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($liveClasses as $index => $liveClass)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $liveClass->title }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($liveClass->description, 50) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $liveClass->platform }}</span>
                                            </td>
                                            <td>
                                                <small>{{ $liveClass->formatted_datetime }}</small>
                                                @if($liveClass->isUpcoming())
                                                    <br><small class="text-success">{{ $liveClass->time_until_start }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($liveClass->isLive())
                                                    <span class="badge bg-danger live-indicator">🔴 LIVE</span>
                                                @elseif($liveClass->isUpcoming())
                                                    <span class="badge bg-warning ">⏳ Akan Datang</span>
                                                @else
                                                    <span class="badge bg-secondary">✅ Selesai</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $liveClass->participants_count }} orang</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('live-class.show', $liveClass->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    
                                                    <!-- Tombol Edit -->
                                                    @if($liveClass->isUpcoming())
                                                        <a href="{{ route('live-class.edit', $liveClass->id) }}" 
                                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    <!-- Tombol Hapus -->
                                                    @if($liveClass->isUpcoming() || $liveClass->isCompleted())
                                                        <form action="{{ route('live-class.destroy', $liveClass->id) }}" 
                                                              method="POST" style="display: inline;"
                                                              onsubmit="return confirmDelete('{{ $liveClass->title }}', '{{ $liveClass->isCompleted() ? 'selesai' : 'akan datang' }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                    title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <!-- Tombol Live Class yang sedang berlangsung -->
                                                    @if($liveClass->isLive())
                                                        <a href="{{ $liveClass->link }}" 
                                                           target="_blank"
                                                           class="btn btn-sm btn-success" 
                                                           title="Masuk ke Live Class">
                                                            <i class="bi bi-play-circle"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-broadcast" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="text-muted mt-3">Belum ada Live Class</h5>
                            <p class="text-muted">Klik tombol "Tambah Live Class" untuk membuat live class pertama Anda.</p>
                            <a href="{{ route('live-class.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Live Class
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(title, status) {
    const message = status === 'selesai' 
        ? `Apakah Anda yakin ingin menghapus live class "${title}" yang sudah selesai?\n\nData peserta dan riwayat akan ikut terhapus.`
        : `Apakah Anda yakin ingin menghapus live class "${title}" yang akan datang?\n\nSiswa tidak akan bisa bergabung ke live class ini.`;
    
    return confirm(message);
}

document.addEventListener('DOMContentLoaded', function() {
    setInterval(() => {
        const hasLiveClass = document.querySelector('.live-indicator');
        if (hasLiveClass) {
            window.location.reload();
        }
    }, 30000);
});
</script>
@endpush