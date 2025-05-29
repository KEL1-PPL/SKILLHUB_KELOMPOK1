@extends('all.component.app')

@section('content')
<div class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="mb-4">Articles</h1>
                        
                        <div class="row g-4">
                            @forelse ($articles as $article)
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $article->title }}</h5>
                                            <p class="card-text text-muted">
                                                {{ Str::limit(strip_tags($article->content), 150) }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <small class="text-muted">
                                                    By {{ $article->user->name }}
                                                </small>
                                                <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-primary btn-sm">
                                                    Read More
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        No articles found.
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $articles->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
