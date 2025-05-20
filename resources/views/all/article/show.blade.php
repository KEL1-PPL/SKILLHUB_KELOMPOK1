@extends('layouts.app')

@section('content')
<h2>{{ $article->title }}</h2>
<p>By {{ $article->mentor->name }}</p>
<div>{{ $article->content }}</div>
<a href="{{ route('articles.index') }}">← Kembali ke daftar</a>
@endsection