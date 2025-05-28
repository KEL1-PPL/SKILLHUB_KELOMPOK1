@extends('all.component.app')

@push('styles')
<style>
    /* Live Class Specific Styles */
    .live-class-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    /* Card Styles */
    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        margin-bottom: 2rem;
    }

    .card-header {
        background: linear-gradient(135deg, #6084fc, #ff6b35);
        color: white;
        border-radius: 20px 20px 0 0 !important;
        padding: 1.5rem 2rem;
        border: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h4 {
        margin: 0;
        font-weight: 600;
    }

    .card-body {
        padding: 2rem;
    }

    /* Header Section */
    .page-header {
        text-align: center;
        margin-bottom: 3rem;
        color: #1a202c;
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .page-header p {
        font-size: 1.2rem;
        opacity: 0.8;
    }

    .header-icon {
        color: #6084fc;
        margin-right: 1rem;
    }

    /* Form Styles */
    .form-label {
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background-color: #ffffff;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6084fc;
        box-shadow: 0 0 0 3px rgba(96, 132, 252, 0.3);
        background-color: #ffffff;
    }

    /* Button Styles */
    .btn-primary {
        background: linear-gradient(135deg, #6084fc, #4f46e5);
        border: none;
        border-radius: 12px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        background: linear-gradient(135deg, #4f46e5, #6084fc);
        box-shadow: 0 5px 15px rgba(96, 132, 252, 0.4);
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-1px);
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: white;
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        color: white;
    }

    .btn-warning:hover {
        transform: translateY(-1px);
        background: linear-gradient(135deg, #d97706, #f59e0b);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        color: white;
    }

    .btn-danger:hover {
        transform: translateY(-1px);
        background: linear-gradient(135deg, #dc2626, #ef4444);
        color: white;
    }

    .btn-outline-primary {
        border: 2px solid #6084fc;
        color: #6084fc;
        background: transparent;
        border-radius: 12px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background: #6084fc;
        color: white;
        transform: translateY(-2px);
    }

    /* Alert Styles */
    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid #22c55e;
        color: #15803d;
        border-radius: 12px;
        padding: 1rem 1.5rem;
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid #ef4444;
        color: #dc2626;
        border-radius: 12px;
        padding: 1rem 1.5rem;
    }

    /* Live Class Item Styles */
    .live-class-item {
        background: #ffffff;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border-left: 4px solid #6084fc;
        transition: all 0.3s ease;
        position: relative;
    }

    .live-class-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .class-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.5rem;
        padding-right: 120px;
    }

    .class-description {
        color: #6b7280;
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .class-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .meta-item i {
        color: #6084fc;
    }

    .platform-badge {
        background: linear-gradient(135deg, #ff6b35, #f59e0b);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-upcoming {
        background: rgba(34, 197, 94, 0.1);
        color: #15803d;
        border: 1px solid #22c55e;
    }

    .status-live {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: 1px solid #ef4444;
    }

    .status-completed {
        background: rgba(107, 114, 128, 0.1);
        color: #4b5563;
        border: 1px solid #9ca3af;
    }

    .join-link {
        color: #6084fc;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .join-link:hover {
        color: #ff6b35;
    }

    .action-buttons {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        display: flex;
        gap: 0.5rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6b7280;
        border-radius: 15px;
        background: linear-gradient(135deg, rgba(96, 132, 252, 0.1), rgba(255, 107, 53, 0.1));
        border: 2px dashed #6084fc;
        margin: 2rem 0;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.6;
        color: #6084fc;
    }

    .empty-state h5 {
        color: #1a202c;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #6084fc, #ff6b35);
        color: white;
        border-radius: 20px 20px 0 0;
        border: none;
        padding: 1.5rem 2rem;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border: none;
        padding: 1rem 2rem 2rem;
    }

    /* Animation */
    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: translateY(20px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 2rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .class-meta {
            flex-direction: column;
            gap: 0.5rem;
        }

        .live-class-container {
            padding: 0 1rem;
        }

        .class-title {
            padding-right: 0;
            margin-bottom: 2.5rem;
        }

        .action-buttons {
            position: static;
            margin-top: 1rem;
            justify-content: flex-end;
        }

        .card-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="live-class-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>
                    <i class="bi bi-broadcast-tower header-icon"></i>
                    Manajemen Live Class
                </h1>
                <p>Buat dan kelola sesi live class untuk siswa Anda</p>
            </div>

            <!-- Success Alert -->
            @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <!-- Error Alert -->
            @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <!-- Main Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="bi bi-list me-2"></i>Daftar Live Class</h4>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addLiveClassModal">
                                <i class="bi bi-plus me-2"></i>Tambah Live Class
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="liveClassesList">
                                {{-- Check if there are live classes --}}
                                @if(isset($liveClasses) && $liveClasses->count() > 0)
                                    {{-- Loop through live classes from database --}}
                                    @foreach($liveClasses as $class)
                                    <div class="live-class-item">
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-warning btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editLiveClassModal"
                                                    onclick="editLiveClass({{ $class->id }}, '{{ $class->title }}', '{{ $class->description }}', '{{ $class->datetime->format('Y-m-d\TH:i') }}', '{{ $class->platform }}', '{{ $class->link }}')">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="deleteLiveClass({{ $class->id }}, '{{ $class->title }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="class-title">{{ $class->title }}</div>
                                        <div class="class-description">{{ $class->description }}</div>
                                        <div class="class-meta">
                                            <div class="meta-item">
                                                <i class="bi bi-calendar"></i>
                                                <span>{{ \Carbon\Carbon::parse($class->datetime)->format('d F Y, H:i') }}</span>
                                            </div>
                                            <div class="meta-item">
                                                <i class="bi bi-people"></i>
                                                <span>{{ $class->participants_count ?? 0 }} peserta</span>
                                            </div>
                                            <div class="meta-item">
                                                <span class="platform-badge">{{ $class->platform }}</span>
                                            </div>
                                            <div class="meta-item">
                                                @php
                                                    $now = now();
                                                    $start = \Carbon\Carbon::parse($class->datetime);
                                                    $end = $start->copy()->addHours(2);
                                                    
                                                    if ($now >= $start && $now <= $end) {
                                                        $status = 'live';
                                                        $statusText = 'LIVE';
                                                    } elseif ($start > $now) {
                                                        $status = 'upcoming';
                                                        $statusText = 'Akan Datang';
                                                    } else {
                                                        $status = 'completed';
                                                        $statusText = 'Selesai';
                                                    }
                                                @endphp
                                                <span class="status-badge status-{{ $status }}">{{ $statusText }}</span>
                                            </div>
                                            <div class="meta-item">
                                                <i class="bi bi-link"></i>
                                                <a href="{{ $class->link }}" target="_blank" class="join-link">Join Link</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    {{-- Empty state when no live classes --}}
                                    <div class="empty-state" id="emptyState">
                                        <i class="bi bi-camera-video-off"></i>
                                        <h5>Belum Ada Live Class</h5>
                                        <p>Buat live class pertama Anda untuk mulai mengajar secara virtual!</p>
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLiveClassModal">
                                                <i class="bi bi-plus me-2"></i>Buat Live Class Pertama
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Live Class Modal -->
<div class="modal fade" id="addLiveClassModal" tabindex="-1" aria-labelledby="addLiveClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLiveClassModalLabel">
                    <i class="bi bi-plus me-2"></i>Buat Live Class Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('live-class.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Judul -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Live Class</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}"
                               placeholder="Contoh: Belajar React untuk Pemula" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" required
                                  placeholder="Jelaskan apa yang akan dipelajari dalam live class ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal dan Waktu -->
                    <div class="mb-3">
                        <label for="datetime" class="form-label">Tanggal & Waktu</label>
                        <input type="datetime-local" class="form-control @error('datetime') is-invalid @enderror" 
                               id="datetime" name="datetime" value="{{ old('datetime') }}" required>
                        @error('datetime')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Platform -->
                    <div class="mb-3">
                        <label for="platform" class="form-label">Platform</label>
                        <select class="form-select @error('platform') is-invalid @enderror" 
                                id="platform" name="platform" required>
                            <option value="">-- Pilih Platform --</option>
                            <option value="Zoom" {{ old('platform') == 'Zoom' ? 'selected' : '' }}>Zoom</option>
                            <option value="Google Meet" {{ old('platform') == 'Google Meet' ? 'selected' : '' }}>Google Meet</option>
                            <option value="Microsoft Teams" {{ old('platform') == 'Microsoft Teams' ? 'selected' : '' }}>Microsoft Teams</option>
                            <option value="Discord" {{ old('platform') == 'Discord' ? 'selected' : '' }}>Discord</option>
                            <option value="YouTube Live" {{ old('platform') == 'YouTube Live' ? 'selected' : '' }}>YouTube Live</option>
                        </select>
                        @error('platform')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Link Akses -->
                    <div class="mb-3">
                        <label for="link" class="form-label">Link Akses</label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror" 
                               id="link" name="link" value="{{ old('link') }}"
                               placeholder="https://zoom.us/j/123456789" required>
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-rocket me-2"></i>Buat Live Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Live Class Modal -->
<div class="modal fade" id="editLiveClassModal" tabindex="-1" aria-labelledby="editLiveClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLiveClassModalLabel">
                    <i class="bi bi-pencil me-2"></i>Edit Live Class
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editLiveClassForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Judul -->
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Judul Live Class</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                    </div>

                    <!-- Tanggal dan Waktu -->
                    <div class="mb-3">
                        <label for="edit_datetime" class="form-label">Tanggal & Waktu</label>
                        <input type="datetime-local" class="form-control" id="edit_datetime" name="datetime" required>
                    </div>

                    <!-- Platform -->
                    <div class="mb-3">
                        <label for="edit_platform" class="form-label">Platform</label>
                        <select class="form-select" id="edit_platform" name="platform" required>
                            <option value="">-- Pilih Platform --</option>
                            <option value="Zoom">Zoom</option>
                            <option value="Google Meet">Google Meet</option>
                            <option value="Microsoft Teams">Microsoft Teams</option>
                            <option value="Discord">Discord</option>
                            <option value="YouTube Live">YouTube Live</option>
                        </select>
                    </div>

                    <!-- Link Akses -->
                    <div class="mb-3">
                        <label for="edit_link" class="form-label">Link Akses</label>
                        <input type="url" class="form-control" id="edit_link" name="link" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-2"></i>Update Live Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteLiveClassModal" tabindex="-1" aria-labelledby="deleteLiveClassModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLiveClassModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus live class <strong id="deleteClassName"></strong>?</p>
                <p class="text-muted">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteLiveClassForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Set minimum datetime to current time
    document.addEventListener('DOMContentLoaded', function() {
        const datetimeInputs = document.querySelectorAll('input[type="datetime-local"]');
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        const minDateTime = now.toISOString().slice(0, 16);
        
        datetimeInputs.forEach(input => {
            input.min = minDateTime;
        });
    });

    // Edit Live Class Function
    function editLiveClass(id, title, description, datetime, platform, link) {
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_datetime').value = datetime;
        document.getElementById('edit_platform').value = platform;
        document.getElementById('edit_link').value = link;
        
        const form = document.getElementById('editLiveClassForm');
        form.action = `/mentor/live-class/${id}`;
    }

    // Delete Live Class Function
    function deleteLiveClass(id, title) {
        document.getElementById('deleteClassName').textContent = title;
        
        const form = document.getElementById('deleteLiveClassForm');
        form.action = `/mentor/live-class/${id}`;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteLiveClassModal'));
        modal.show();
    }

    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        });
    });

    // Reopen modal if there are validation errors
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('addLiveClassModal'));
            modal.show();
        });
    @endif
</script>
@endpush