@extends('all.component.app')

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .form-section {
        background-color: var(--white);
        border-radius: 10px;
        box-shadow: var(--shadow-sm);
        padding: 2rem;
    }
    
    .form-title {
        margin-bottom: 1.5rem;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    .form-control {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    
    .btn-submit {
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
    }
    
    .btn-cancel {
        background-color: var(--light-bg);
        color: var(--text-dark);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
        background-color: var(--border-color);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">{{ $title ?? 'Create Discount' }}</h1>
                <a href="{{ route('admin.discounts.index') }}" class="btn btn-cancel">
                    <i class="bi bi-arrow-left"></i> Back to Discounts
                </a>
            </div>
            
            <div class="form-section">
                <form action="{{ route('admin.discounts.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="course_id" class="form-label">Course</label>
                        <select name="course_id" id="course_id" class="form-control @error('course_id') is-invalid @enderror" required>
                            <option value="">Select a course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="percentage" class="form-label">Discount Percentage (%)</label>
                        <input type="number" name="percentage" id="percentage" 
                               class="form-control @error('percentage') is-invalid @enderror"
                               value="{{ old('percentage') }}" 
                               min="1" max="100" required>
                        @error('percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="text" name="start_date" id="start_date" 
                                   class="form-control datepicker @error('start_date') is-invalid @enderror"
                                   value="{{ old('start_date') }}" 
                                   placeholder="YYYY-MM-DD" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="text" name="end_date" id="end_date" 
                                   class="form-control datepicker @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date') }}" 
                                   placeholder="YYYY-MM-DD" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea name="description" id="description" 
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.discounts.index') }}" class="btn btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-submit">Create Discount</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date pickers
        flatpickr(".datepicker", {
            enableTime: false,
            dateFormat: "Y-m-d",
            minDate: "today"
        });
    });
</script>
@endpush
