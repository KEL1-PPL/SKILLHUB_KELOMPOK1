@extends('all.component.app')

@section('content')
<!-- Styling tambahan -->
<style>
    table td, table th {
        white-space: nowrap;
        vertical-align: middle;
    }
    .table-responsive {
        overflow-x: auto;
        margin-left: 270px; /* Menambahkan margin kiri lebih besar untuk menghindari sidebar */
    }

    /* Menambahkan margin-top yang lebih besar untuk judul */
    .container h2 {
        margin-top: 80px; /* Menambah jarak lebih banyak dari atas */
        font-size: 24px;
        font-weight: 600;
    }

    /* Mengurangi ukuran tabel dan menambah padding */
    .table-responsive {
        max-width: 90%; /* Lebar tabel lebih kecil */
        margin-right: 10px; /* Mengatur tabel agar rata tengah */
    }

    table {
        width: 100%; /* Tabel mengisi 100% dari kontainer */
    }

    /* Tombol untuk menambahkan rating lebih ke kanan */
    .mb-3.d-flex {
        justify-content: flex-end; /* Tombol ke kanan */
        margin-right: 20px; /* Menambahkan jarak kanan agar tidak terlalu rapat */
    }

    .container {
        padding: 20px;
        margin-left: 50px; /* Menambahkan jarak kiri pada container untuk seluruh konten */
    }
</style>

<div class="container">
    <h2 class="mb-4 text-center">Daftar Rating</h2>

    <!-- Alert Flash Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> 
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tombol untuk Menambahkan Rating (pindah ke kanan) -->
    <div class="mb-3 d-flex justify-content-end">
        <a href="{{ route('ratings.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Tambah Rating
        </a>
    </div>

    @if($ratings->count())
    <!-- Tabel Daftar Rating -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover" style="max-width: 100%;"> <!-- Ukuran tabel lebih kecil -->
            <thead class="thead-light">
                <tr>
                    <th>Nama Kursus</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ratings as $rating)
                <tr>
                    <td>{{ $rating->course->title }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rating->value)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                            <span class="ms-2">{{ $rating->value }} / 5</span>
                        </div>
                    </td>
                    <td>{{ Str::limit($rating->comment, 50) }}</td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('ratings.edit', $rating->id) }}" class="btn btn-warning btn-sm me-2" 
                               data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Rating">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            <!-- Tombol untuk memicu modal -->
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $rating->id }}" 
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Rating">
                                <i class="bi bi-trash"></i> Hapus
                            </button>

                            <!-- Modal Konfirmasi -->
                            <div class="modal fade" id="deleteModal{{ $rating->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $rating->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $rating->id }}">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin menghapus rating untuk <strong>{{ $rating->course->title }}</strong>? Tindakan ini tidak dapat dibatalkan.
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('ratings.destroy', $rating->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="alert alert-info text-center">Belum ada rating.</p>
    @endif
</div>

<!-- Tooltip & Auto-dismiss Alert -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tooltip init
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auto-dismiss success alert
        let alert = document.querySelector('.alert-success');
        if (alert) {
            setTimeout(() => {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }, 3000);
        }
    });
</script>
@endsection
