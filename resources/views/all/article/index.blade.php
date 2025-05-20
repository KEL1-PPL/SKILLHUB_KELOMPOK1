@extends('layouts.app')

@section('content')
<h2>Daftar Artikel</h2>
@foreach($articles as $article)
    <div>
        <h3><a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a></h3>
        <p>By {{ $article->mentor->name }}</p>
    </div>
@endforeach

{{ $articles->links() }}
@endsection