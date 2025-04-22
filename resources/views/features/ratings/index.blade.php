@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4" style="font-size: 24px; font-weight: 600;">Daftar Rating</h2>

    <!-- Tombol untuk Menambahkan Rating -->
    <a href="{{ route('ratings.create') }}" class="btn btn-success mb-3">Tambah Rating</a>

    @if($ratings->count())
    <!-- Tabel Daftar Rating -->
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
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
                <td>{{ $rating->course_name }}</td>
                <td>{{ $rating->rating }} / 5</td>
                <td>{{ Str::limit($rating->review, 50) }}</td>
                <td>
                    <!-- Tombol Edit -->
                    <a href="{{ route('ratings.edit', $rating->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <!-- Tombol Hapus -->
                    <form action="{{ route('ratings.destroy', $rating->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="alert alert-info">Belum ada rating.</p>
    @endif
</div>
@endsection
