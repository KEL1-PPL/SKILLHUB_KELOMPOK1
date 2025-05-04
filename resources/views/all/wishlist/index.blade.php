@extends('all.component.app')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #F6F6F6;
            background: linear-gradient(180deg, #287094, #D4D4CE, #F6F6F6, #023246);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            padding-bottom: 80px;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h2 {
            color: #023246;
            font-weight: 700;
            margin-bottom: 2rem;
            font-size: 2.2rem;
        }

        .card {
            background-color: #FFFFFF;
            border: 1px solid #D4D4CE;
            border-radius: 15px;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
            overflow: hidden;
            height: 100%;
            margin-bottom: 30px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .card-title {
            color: #023246;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            line-height: 1.4;
        }

        .card-text {
            color: #555;
            margin-bottom: 2rem;
            line-height: 1.6;
            font-size: 1rem;
        }

        .card-body {
            padding: 2rem;
        }

        .rating {
            margin-bottom: 2rem;
        }

        .rating .star {
            font-size: 18px;
            color: #f39c12;
            margin-right: 5px;
        }

        .btn-primary {
            background-color: #287094;
            border: none;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background-color: #023246;
        }

        .btn-danger {
            background-color: #e74c3c;
            border: none;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .course-card img {
            height: 220px;
            object-fit: cover;
            border-radius: 15px 15px 0 0;
        }
        
        .empty-wishlist {
            text-align: center;
            padding: 100px 0;
            color: #555;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-top: 3rem;
        }
        
        .empty-wishlist i {
            font-size: 5rem;
            color: #D4D4CE;
            margin-bottom: 30px;
            display: block;
        }
        
        .action-buttons {
            display: flex;
            gap: 20px;
        }
        
        .wishlist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4rem;
            background-color: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }
        
        .badge {
            background-color: #287094;
            color: white;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 1rem;
            margin-left: 15px;
        }
        
        .alert {
            border-radius: 12px;
            padding: 1.75rem;
            margin-bottom: 3rem;
            font-size: 1.1rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .container {
            padding-top: 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .row {
            margin-left: -25px;
            margin-right: -25px;
        }

        .col-md-4 {
            padding: 25px;
        }
        
        /* Ensure buttons have adequate spacing */
        .btn-sm {
            padding: 0.6rem 1.2rem;
            font-size: 0.95rem;
        }
        
        /* Add more space between header elements */
        .wishlist-header h2 {
            margin-bottom: 0;
        }
        
        /* Ensure empty state has good spacing */
        .empty-wishlist h3 {
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }
        
        .empty-wishlist p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        
        .empty-wishlist .btn {
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
        }
        
        /* Add more space in the body wrapper */
        .body-wrapper {
            padding: 2rem 0;
        }
        
        /* Ensure consistent spacing in card body sections */
        .card-body > div:first-child {
            margin-bottom: 2.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container">
            <div class="wishlist-header">
                <h2>📚 Wishlist Saya <span class="badge">{{ count($wishlists) }}</span></h2>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Wishlist Grid -->
            <div class="row mt-5" id="wishlist-list">
                @forelse ($wishlists as $wishlist)
                    <div class="col-md-4 mb-5 course-card" id="wishlist-item-{{ $wishlist->course->id }}">
                        <div class="card shadow-sm h-100">
                            <!-- Course Image -->
                            <img src="{{ asset('image/dashboard_kursus/' . $wishlist->course->image) }}" 
                                 class="card-img-top" alt="{{ $wishlist->course->title }}">

                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title">{{ $wishlist->course->title }}</h5>
                                    <p class="card-text">{{ Str::limit($wishlist->course->description, 100) }}</p>
                                </div>
                                <div>
                                    <div class="rating mb-4">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="star {{ $i <= $wishlist->course->rating ? 'filled' : '' }}">⭐</span>
                                        @endfor
                                    </div>
                                    <div class="action-buttons">
                                        <a href="#" class="btn btn-primary btn-sm w-100 mb-3">Lihat Detail</a>
                                        <button class="btn btn-danger btn-sm remove-wishlist" 
                                                data-course-id="{{ $wishlist->course->id }}">
                                                <i class="fas fa-trash mt-5 pt-3"></i> Hapus
                                            </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 empty-wishlist">
                        <i class="far fa-heart"></i>
                        <h3>Wishlist Anda Kosong</h3>
                        <p>Anda belum menambahkan kursus ke wishlist.</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-4">Jelajahi Kursus</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Menangani klik tombol hapus wishlist
            const removeButtons = document.querySelectorAll('.remove-wishlist');
            
            removeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const courseId = this.dataset.courseId;
                    const wishlistItem = document.getElementById('wishlist-item-' + courseId);
                    
                    if (confirm('Apakah Anda yakin ingin menghapus kursus ini dari wishlist?')) {
                        // Kirim permintaan AJAX untuk menghapus kursus dari wishlist
                        fetch('{{ route("wishlist.remove") }}', {
                            method: 'DELETE',
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
                                // Hapus elemen dari DOM
                                wishlistItem.remove();
                                
                                // Periksa apakah wishlist kosong
                                const remainingItems = document.querySelectorAll('.course-card');
                                if (remainingItems.length === 0) {
                                    const emptyWishlist = `
                                        <div class="col-12 empty-wishlist">
                                            <i class="far fa-heart"></i>
                                            <h3>Wishlist Anda Kosong</h3>
                                            <p>Anda belum menambahkan kursus ke wishlist.</p>
                                            <a href="{{ route('dashboard') }}" class="btn btn-primary mt-4">Jelajahi Kursus</a>
                                        </div>
                                    `;
                                    document.getElementById('wishlist-list').innerHTML = emptyWishlist;
                                }
                                
                                // Update badge count
                                const badge = document.querySelector('.badge');
                                if (badge) {
                                    badge.textContent = remainingItems.length;
                                }
                            } else {
                                alert('Terjadi kesalahan saat menghapus dari wishlist.');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush