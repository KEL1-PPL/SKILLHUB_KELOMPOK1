@extends('all.component.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title ?? 'Data Wishlist' }}</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>User</th>
                                    <th>Course</th>
                                    <th>Added Date</th>
                                    <th>Course Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wishlists as $index => $wishlist)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $wishlist->user->name ?? 'N/A' }}
                                            <br>
                                            <small>{{ $wishlist->user->email ?? '' }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($wishlist->course && $wishlist->course->image)
                                                    <img src="{{ asset('image/dashboard_kursus/' . $wishlist->course->image) }}" 
                                                         alt="{{ $wishlist->course->title ?? 'Course Image' }}" 
                                                         class="img-thumbnail mr-2" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <strong>{{ $wishlist->course->title ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small>{{ Str::limit($wishlist->course->description ?? '', 50) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $wishlist->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            @if($wishlist->course && $wishlist->course->price)
                                                Rp {{ number_format($wishlist->course->price, 0, ',', '.') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('features.course.show', $wishlist->course_id ?? 0) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> View Course
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data wishlist</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
