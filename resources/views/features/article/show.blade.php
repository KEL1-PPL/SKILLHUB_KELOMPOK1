@extends('all.component.app')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="bg-white shadow-sm rounded-3 p-4">
                @if($article->image)
                    <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" class="img-fluid rounded-3 mb-4" style="width: 100%; height: 400px; object-fit: cover;">
                @endif
                
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <h1 class="h2 fw-bold text-primary mb-0">{{ $article->title }}</h1>
                    <span class="badge 
                        @if($article->status === 'approved') bg-success
                        @elseif($article->status === 'rejected') bg-danger
                        @else bg-warning
                        @endif">
                        {{ ucfirst($article->status) }}
                    </span>
                </div>

                <div class="d-flex gap-3 text-muted mb-4">
                    <span><i class="bi bi-person-fill"></i> By {{ $article->user->name }}</span>
                    <span><i class="bi bi-calendar-event"></i> {{ $article->created_at->format('M d, Y') }}</span>
                </div>

                @if($article->status === 'rejected' && $article->rejected_note)
                    <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            {{ $article->rejected_note }}
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    {!! nl2br(e($article->content)) !!}
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('articles.index') }}" class="btn btn-link text-primary p-0">
                        <i class="bi bi-arrow-left"></i> Back to Articles
                    </a>

                    @if(auth()->user()->role === 'mentor' && $article->user_id === auth()->id())
                        <div class="d-flex gap-3">
                            <a href="{{ route('articles.edit', $article) }}" class="btn btn-link text-warning p-0">
                                Edit
                            </a>
                            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif

                    @if(auth()->user()->role === 'admin' && $article->status === 'pending')
                        <div class="d-flex gap-3">
                            <form action="{{ route('articles.approve', $article) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    Approve
                                </button>
                            </form>
                            <button type="button" onclick="showRejectModal('{{ $article->id }}')" class="btn btn-danger btn-sm">
                                Reject
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejected_note" class="form-label">Rejection Reason</label>
                        <textarea name="rejected_note" id="rejected_note" rows="3" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal(articleId) {
    const form = document.getElementById('rejectForm');
    form.action = `/articles/${articleId}/reject`;
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>
@endsection 