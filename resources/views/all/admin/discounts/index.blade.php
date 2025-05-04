@extends('all.component.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
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
                                                    <img src="{{ asset('image/dashboard_kursus/' . $discount->course->image) }}" 
                                                         alt="{{ $discount->course->title ?? 'Course Image' }}" 
                                                         class="img-thumbnail mr-2" 
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
                                            {{ $discount->start_date->format('d M Y') }} - {{ $discount->end_date->format('d M Y') }}
                                        </td>
                                        <td>
                                            @if($discount->isActive())
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
