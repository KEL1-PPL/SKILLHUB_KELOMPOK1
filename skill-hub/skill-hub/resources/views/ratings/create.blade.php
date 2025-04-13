@extends('layouts.app')

@section('content')
    <h1>Tambah Rating</h1>

    <form action="{{ route('rating.store') }}" method="POST">
        @csrf
        <label for="user_id">User:</label>
        <select name="user_id" id="user_id">
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>

        <label for="materi_id">Materi:</label>
        <select name="materi_id" id="materi_id">
            @foreach($materis as $materi)
                <option value="{{ $materi->id }}">{{ $materi->title }}</option>
            @endforeach
        </select>

        <label for="rating">Rating:</label>
        <input type="number" name="rating" id="rating" min="1" max="5" required>

        <button type="submit">Simpan Rating</button>
    </form>
@endsection
