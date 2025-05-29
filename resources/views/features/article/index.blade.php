@extends('all.component.app')

@section('content')
<div class="container mt-5 pt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 fw-bold text-primary">Articles</h2>
        @if(auth()->user()->role === 'mentor')
            <a href="{{ route('articles.create') }}" class="btn btn-primary">
                Create New Article
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(auth()->user()->role === 'admin')
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                                <th>Approval</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $article)
                            <tr>
                                <td class="fw-bold text-primary">{{ $article->title }}</td>
                                <td>{{ $article->user->name }}</td>
                                <td>{{ $article->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge 
                                        @if($article->status === 'approved') bg-success
                                        @elseif($article->status === 'rejected') bg-danger
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ Str::limit($article->content, 80) }}</td>
                                <td>
                                    <a href="{{ route('articles.show', $article) }}" class="btn btn-link text-primary p-0">Lebih lanjut</a>
                                </td>
                                <td>
                                    @if($article->status === 'pending')
                                        <form action="{{ route('articles.approve', $article) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm me-2">
                                                Approve
                                            </button>
                                        </form>
                                        <button type="button" onclick="showRejectModal('{{ $article->id }}')" class="btn btn-danger btn-sm">
                                            Reject
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No articles found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="row g-4">
            @forelse($articles as $article)
                <div class="col-12">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <h3 class="h5 fw-bold text-primary mb-0">{{ $article->title }}</h3>
                                <span class="badge 
                                    @if($article->status === 'approved') bg-success
                                    @elseif($article->status === 'rejected') bg-danger
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($article->status) }}
                                </span>
                            </div>
                            <div class="d-flex gap-3 text-muted small mb-2">
                                <span><i class="bi bi-person-fill text-primary"></i> {{ $article->user->name }}</span>
                                <span><i class="bi bi-calendar-event text-primary"></i> {{ $article->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-muted mb-3">{{ Str::limit($article->content, 120) }}</p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="{{ route('articles.show', $article) }}" class="btn btn-link text-primary p-0">Lebih lanjut</a>
                                @if(auth()->user()->role === 'mentor' && $article->user_id === auth()->id())
                                    <a href="{{ route('articles.edit', $article) }}" class="btn btn-link text-warning p-0">Edit</a>
                                    <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">No articles found.</p>
                </div>
            @endforelse
        </div>
    @endif
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