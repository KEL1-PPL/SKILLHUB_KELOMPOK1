@extends('all.component.app')

@push('style')
<style>
    @media (min-width: 992px) {
        main {
            margin-left: 260px;
        }
    }

    .table-responsive img {
        margin-right: 10px;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        object-fit: cover;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    }

    .table thead th {
        background: #f8fafc;
        font-weight: 700;
        color: #287094;
        border-bottom: 2px solid #eaeaea;
        vertical-align: middle;
    }

    .table tbody tr {
        transition: background 0.2s;
    }

    .table tbody tr:hover {
        background: #f3f6fa;
    }

    .badge.bg-success {
        background: #27ae60 !important;
    }

    .badge.bg-warning {
        background: #f39c12 !important;
        color: #fff !important;
    }

    .badge.bg-danger {
        background: #e74c3c !important;
    }

    .badge.bg-info {
        background: #287094 !important;
    }

    .btn-group .btn {
        margin-right: 0.25rem;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #eaeaea;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .card {
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(40,112,148,0.07);
    }

    .table td, .table th {
        vertical-align: middle !important;
        padding: 1rem 0.75rem !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ $title ?? 'Course Discounts' }}</h3>
                    <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Discount
                    </a>
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
                                    <th>Course</th>
                                    <th>Discount</th>
                                    <th>Period</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($discounts as $index => $discount)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($discount->course && $discount->course->image)
                                                    <img src="{{ asset('/storage/' . $discount->course->image) }}" 
                                                         alt="{{ $discount->course->title ?? 'Course Image' }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <strong>{{ $discount->course->title ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small>{{ Str::limit($discount->course->description ?? '', 50) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $discount->percentage }}% OFF</span>
                                        </td>
                                        <td>
                                            {{ optional($discount->start_date)->format('d M Y') ?? 'N/A' }} - {{ optional($discount->end_date)->format('d M Y') ?? 'N/A' }}
                                        </td>
                                        <td>
                                            @if(method_exists($discount, 'isActive') && $discount->isActive())
                                                <span class="badge bg-success">Active</span>
                                            @elseif($discount->start_date > now())
                                                <span class="badge bg-warning">Upcoming</span>
                                            @else
                                                <span class="badge bg-danger">Expired</span>
                                            @endif
                                        </td>
                                        <td>{{ $discount->description ?? 'No description' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.discounts.edit', $discount->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.discounts.destroy', $discount->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to delete this discount?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No discounts available</td>
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
