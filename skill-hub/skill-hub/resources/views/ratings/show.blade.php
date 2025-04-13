@extends('layouts.app')

@section('content')
    <h1>Rating Detail</h1>
    <p>User: {{ $rating->user->name }}</p>
    <p>Materi: {{ $rating->materi->title }}</p>
    <p>Rating: {{ $rating->rating }}</p>
@endsection
