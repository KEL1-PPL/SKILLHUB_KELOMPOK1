@extends('layouts.app')

@section('content')
    <h1>Edit Rating</h1>

    <form action="{{ route('rating.update', $rating->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="rating">Rating:</label>
        <input type="number" name="rating" id="rating" min="1" max="5" value="{{ $rating->rating }}" required>

        <button type="submit">Update Rating</button>
    </form>
@endsection
