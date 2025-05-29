@extends('all.component.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title mb-4">{{ $article->title }}</h1>
                    
                    <div class="text-muted mb-4">
                        <small>
                            By {{ $article->user->name }} | 
                            Published {{ $article->created_at->format('F d, Y') }}
                        </small>
                    </div>

                    <div class="article-content">
                        {!! $article->content !!}
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                            Back to Articles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 