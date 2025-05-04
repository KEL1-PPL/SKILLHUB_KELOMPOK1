@extends('all.component.app')

@push('style')
<style>
    /* Course Index Page Specific Styles */
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }
    
    .page-description {
        color: var(--text-light);
        font-size: 1rem;
    }
    
    .search-container {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .search-input {
        padding: 0.75rem 1rem 0.75rem 3rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-color);
        width: 100%;
        font-size: 0.95rem;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    
    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        font-size: 1.25rem;
    }
    
    .course-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .course-card {
        border-radius: 0.5rem;
        overflow: hidden;
        background-color: var(--white);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        transition: transform 0.2s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow);
    }
    
    .course-image {
        height: 180px;
        width: 100%;
        object-fit: cover;
    }
    
    .course-content {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .course-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }
    
    .course-description {
        color: var(--text-light);
        font-size: 0.875rem;
        margin-bottom: 1rem;
        flex-grow: 1;
    }
    
    .course-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    
    .course-rating {
        display: flex;
        align-items: center;
    }
    
    .rating-stars {
        color: var(--accent-color);
        font-size: 0.875rem;
    }
    
    .course-price {
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .course-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .btn-wishlist {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger-color);
        border: none;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .btn-wishlist:hover {
        background-color: rgba(239, 68, 68, 0.2);
    }
    
    .btn-wishlist.added {
        background-color: var(--danger-color);
        color: var(--white);
    }
    
    .btn-detail {
        background-color: var(--primary-color);
        color: var(--white);
        border: none;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .btn-detail:hover {
        background-color: var(--primary-dark);
    }
    
    .category-badge {
        display: inline-block;
        background-color: rgba(37, 99, 235, 0.1);
        color: var(--primary-color);
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        margin-bottom: 0.75rem;
    }
    
    .instructor-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .instructor-avatar {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        background-color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .instructor-name {
        font-size: 0.875rem;
        color: var(--text-light);
    }
    
    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 3rem;
        background-color: var(--white);
        border-radius: 0.5rem;
        box-shadow: var(--shadow-sm);
    }
    
    .empty-icon {
        font-size: 3rem;
        color: var(--text-light);
        margin-bottom: 1rem;
    }
    
    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }
    
    .empty-description {
        color: var(--text-light);
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">📚 Jelajahi Kursus Populer</h1>
    <p class="page-description">Temukan kursus terbaik untuk meningkatkan keterampilan Anda</p>
</div>

<!-- Search Bar -->
<div class="search-container">
    <i class="bi bi-search search-icon"></i>
    <input type="text" id="search-course" class="search-input" placeholder="Cari kursus berdasarkan judul, kategori, atau instruktur...">
</div>

<!-- Course Grid -->
<div class="course-grid" id="course-list">
    @forelse ($courses as $course)
    <div class="course-card" data-title="{{ strtolower($course->title) }}">
        <img src="{{ asset('image/dashboard_kursus/' . $course->image) }}" class="course-image" alt="{{ $course->title }}">
        
        <div class="course-content">
            <span class="category-badge">{{ $course->category ?? 'Umum' }}</span>
            
            <h3 class="course-title">{{ $course->title }}</h3>
            
          
            
            <p class="course-description">{{ Str::limit($course->description, 100) }}</p>
            
            <div class="course-meta">
                <div class="course-rating">
                    <div class="rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $course->rating ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                    <span class="ms-1">({{ $course->rating }})</span>
                </div>
                
                <div class="course-price">
                    {{ $course->price ? 'Rp ' . number_format($course->price, 3, ',', '.') : 'Gratis' }}
                </div>
            </div>
            
            <div class="course-actions">
                <button type="button" class="btn-wishlist" data-course-id="{{ $course->id }}">
                    <i class="bi bi-heart"></i> Tambah ke Wishlist
                </button>
                
                <a href="#" class="btn-detail">
                    <i class="bi bi-eye"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="bi bi-journal-x empty-icon"></i>
        <h3 class="empty-title">Belum ada kursus tersedia</h3>
        <p class="empty-description">Kursus yang Anda cari belum tersedia saat ini.</p>
    </div>
    @endforelse
</div>

<!-- Toast Container for Notifications -->
<div class="toast-container"></div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Pencarian kursus
        const searchInput = document.getElementById('search-course');
        const courseCards = document.querySelectorAll('.course-card');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let foundCourses = false;
            
            courseCards.forEach(card => {
                const title = card.dataset.title;
                if (title.includes(searchTerm)) {
                    card.style.display = 'block';
                    foundCourses = true;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show empty state if no courses found
            const emptyState = document.querySelector('.empty-state');
            if (emptyState) {
                if (!foundCourses) {
                    emptyState.style.display = 'block';
                } else {
                    emptyState.style.display = 'none';
                }
            }
        });
        
        // Menangani klik tombol wishlist
        const wishlistButtons = document.querySelectorAll('.btn-wishlist');

        wishlistButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const courseId = this.dataset.courseId;
                const button = this;

                // Kirim permintaan AJAX untuk menambahkan kursus ke wishlist
                fetch('{{ route("wishlist.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        course_id: courseId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.classList.add('added');
                        button.innerHTML = '<i class="bi bi-heart-fill"></i> Ditambahkan ke Wishlist';
                        showToast('Kursus berhasil ditambahkan ke wishlist!', 'success');
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    showToast('Terjadi kesalahan saat menambahkan ke wishlist.', 'error');
                });
            });
        });
    });
</script>
@endpush