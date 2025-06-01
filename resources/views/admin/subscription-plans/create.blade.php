@extends('all.component.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            @include('all.component.menu.admin')
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">{{ $title }}</h1>
                <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Plans
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.subscription-plans.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price (Rp)</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="duration_in_days" class="form-label">Duration (Days)</label>
                            <input type="number" class="form-control @error('duration_in_days') is-invalid @enderror" 
                                   id="duration_in_days" name="duration_in_days" value="{{ old('duration_in_days') }}" min="1" required>
                            @error('duration_in_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Features</label>
                            <div id="features-container">
                                <div class="feature-input mb-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="features[]" required>
                                        <button type="button" class="btn btn-danger remove-feature" onclick="removeFeature(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addFeature()">
                                <i class="bi bi-plus"></i> Add Feature
                            </button>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Plan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function addFeature() {
        const container = document.getElementById('features-container');
        const newFeature = document.createElement('div');
        newFeature.className = 'feature-input mb-2';
        newFeature.innerHTML = `
            <div class="input-group">
                <input type="text" class="form-control" name="features[]" required>
                <button type="button" class="btn btn-danger remove-feature" onclick="removeFeature(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newFeature);
    }

    function removeFeature(button) {
        const featuresContainer = document.getElementById('features-container');
        if (featuresContainer.children.length > 1) {
            button.closest('.feature-input').remove();
        }
    }
</script>
@endpush 