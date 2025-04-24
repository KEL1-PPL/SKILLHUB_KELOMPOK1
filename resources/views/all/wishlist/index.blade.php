@extends('all.component.app')

@section('content')
<div class="container mt-5 pt-5">
    <h2 class="mb-4">💖 Kursus di Wishlist Kamu</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse ($wishlist as $item)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset('image/dashboard_kursus/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title">{{ $item->title }}</h5>
                            <p class="card-text">{{ $item->desc }}</p>
                        </div>
                        <div>
                            <div class="rating mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= $item->rating ? 'filled' : '' }}">⭐</span>
                                @endfor
                            </div>
                            <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm w-100">Hapus dari Wishlist</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Belum ada kursus di wishlist kamu 😢</p>
        @endforelse
    </div>
</div>
@endsection
