@extends('layouts.app')

@push('styles')
<style>
    /* Live Class Specific Styles */
    .live-class-container {
        max-width: 1400px;
        margin: 0 auto;
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
        background: linear-gradient(135deg, var(--deep-pastel-blue), var(--deep-pastel-orange));
        color: white;
        border-radius: 20px 20px 0 0 !important;
        padding: 1.5rem 2rem;
        border: none;
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
        color: var(--text-dark);
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
        color: var(--deep-pastel-blue);
        margin-right: 1rem;
    }

    /* Form Styles */
    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background-color: var(--pure-white);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--deep-pastel-blue);
        box-shadow: 0 0 0 3px rgba(184, 226, 242, 0.3);
        background-color: var(--pure-white);
    }

    /* Button Styles */
    .btn-primary {
        background: linear-gradient(135deg, var(--deep-pastel-blue), var(--pastel-blue));
        border: none;
        border-radius: 12px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: var(--text-dark);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        background: linear-gradient(135deg, var(--pastel-blue), var(--deep-pastel-blue));
        box-shadow: 0 5px 15px rgba(184, 226, 242, 0.4);
        color: var(--text-dark);
    }

    /* Alert Styles */
    .alert-success {
        background: rgba(184, 226, 242, 0.2);
        border: 1px solid var(--pastel-blue);
        color: var(--text-dark);
        border-radius: 12px;
        padding: 1rem 1.5rem;
    }

    /* Live Class Item Styles */
    .live-class-item {
        background: var(--pure-white);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border-left: 4px solid var(--deep-pastel-blue);
        transition: all 0.3s ease;
    }

    .live-class-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .class-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .class-description {
        color: var(--text-gray);
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .class-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.9rem;
        color: var(--text-gray);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .meta-item i {
        color: var(--deep-pastel-blue);
    }

    .platform-badge {
        background: linear-gradient(135deg, var(--deep-pastel-orange), var(--pastel-orange));
        color: var(--text-dark);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .join-link {
        color: var(--deep-pastel-blue);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .join-link:hover {
        color: var(--deep-pastel-orange);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-gray);
        border-radius: 15px;
        background: linear-gradient(135deg, var(--light-pastel-blue), var(--light-pastel-orange));
        border: 2px dashed var(--pastel-blue);
        margin: 2rem 0;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.6;
        color: var(--deep-pastel-blue);
    }

    .empty-state h5 {
        color: var(--text-dark);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .empty-state p {
        color: var(--text-gray);
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .empty-state small {
        background: rgba(255, 255, 255, 0.8);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        display: inline-block;
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
    }
</style>
@endpush

@section('content')
<div class="live-class-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-broadcast-tower header-icon"></i>
            Manajemen Live Class
        </h1>
        <p>Buat dan kelola sesi live class untuk siswa Anda</p>
    </div>

    <!-- Success Alert -->
    <div class="alert alert-success d-none" id="successAlert">
        <i class="fas fa-check-circle me-2"></i>
        <span>Live class berhasil dibuat!</span>
    </div>

    <div class="row">
        <!-- Form Create Live Class -->
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-plus me-2"></i>Buat Live Class Baru</h4>
                </div>
                <div class="card-body">
                    <form id="liveClassForm">
                        <!-- Judul -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Live Class</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   placeholder="Contoh: Belajar React untuk Pemula" required>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="Jelaskan apa yang akan dipelajari dalam live class ini..." required></textarea>
                        </div>

                        <!-- Tanggal dan Waktu -->
                        <div class="mb-3">
                            <label for="datetime" class="form-label">Tanggal & Waktu</label>
                            <input type="datetime-local" class="form-control" id="datetime" name="datetime" required>
                        </div>

                        <!-- Platform -->
                        <div class="mb-3">
                            <label for="platform" class="form-label">Platform</label>
                            <select class="form-select" id="platform" name="platform" required>
                                <option value="">-- Pilih Platform --</option>
                                <option value="Zoom">Zoom</option>
                                <option value="Google Meet">Google Meet</option>
                                <option value="Microsoft Teams">Microsoft Teams</option>
                                <option value="Discord">Discord</option>
                                <option value="YouTube Live">YouTube Live</option>
                            </select>
                        </div>

                        <!-- Link Akses -->
                        <div class="mb-4">
                            <label for="link" class="form-label">Link Akses</label>
                            <input type="url" class="form-control" id="link" name="link" 
                                   placeholder="https://zoom.us/j/123456789" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-rocket me-2"></i>Buat Live Class
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- List Live Classes -->
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-list me-2"></i>Daftar Live Class</h4>
                </div>
                <div class="card-body">
                    <div id="liveClassesList">
                        {{-- Check if there are live classes --}}
                        @if(isset($liveClasses) && $liveClasses->count() > 0)
                            {{-- Loop through live classes from database --}}
                            @foreach($liveClasses as $class)
                            <div class="live-class-item">
                                <div class="class-title">{{ $class->title }}</div>
                                <div class="class-description">{{ $class->description }}</div>
                                <div class="class-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ \Carbon\Carbon::parse($class->datetime)->format('d F Y, H:i') }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span>{{ $class->participants_count ?? 0 }} peserta</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="platform-badge">{{ $class->platform }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-link"></i>
                                        <a href="{{ $class->join_link }}" target="_blank" class="join-link">Join Link</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            {{-- Empty state when no live classes --}}
                            <div class="empty-state" id="emptyState">
                                <i class="fas fa-video-slash"></i>
                                <h5>Belum Ada Live Class</h5>
                                <p>Buat live class pertama Anda untuk mulai mengajar secara virtual!</p>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Tips: Mulai dengan membuat live class yang menarik dan interaktif
                                    </small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Set minimum datetime to current time
    document.addEventListener('DOMContentLoaded', function() {
        const datetimeInput = document.getElementById('datetime');
        const now = new Date();
        // Set timezone offset
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        datetimeInput.min = now.toISOString().slice(0, 16);
    });

    // Handle form submission
    document.getElementById('liveClassForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        const datetime = document.getElementById('datetime').value;
        const platform = document.getElementById('platform').value;
        const link = document.getElementById('link').value;
        
        // Hide empty state if it exists
        const emptyState = document.getElementById('emptyState');
        if (emptyState) {
            emptyState.style.display = 'none';
        }
        
        // Create new live class item
        const newLiveClass = createLiveClassItem(title, description, datetime, platform, link);
        
        // Add to list
        const liveClassesList = document.getElementById('liveClassesList');
        liveClassesList.insertAdjacentHTML('afterbegin', newLiveClass);
        
        // Show success alert
        const successAlert = document.getElementById('successAlert');
        successAlert.classList.remove('d-none');
        
        // Reset form
        this.reset();
        
        // Hide alert after 5 seconds
        setTimeout(function() {
            successAlert.classList.add('d-none');
        }, 5000);
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Function to create live class item HTML
    function createLiveClassItem(title, description, datetime, platform, link) {
        // Format datetime
        const date = new Date(datetime);
        const formattedDate = date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        const formattedTime = date.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
        
        return `
            <div class="live-class-item fade-in">
                <div class="class-title">${title}</div>
                <div class="class-description">${description}</div>
                <div class="class-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>${formattedDate}, ${formattedTime}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span>0 peserta</span>
                    </div>
                    <div class="meta-item">
                        <span class="platform-badge">${platform}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-link"></i>
                        <a href="${link}" target="_blank" class="join-link">Join Link</a>
                    </div>
                </div>
            </div>
        `;
    }
</script>
@endpush