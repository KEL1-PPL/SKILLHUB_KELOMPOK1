@extends('layouts.app')

@section('content')
    <h1>Daftar Rating</h1>

    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <a href="{{ route('rating.create') }}">Tambah Rating</a>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Materi</th>
                <th>Rating</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ratings as $rating)
                <tr>
                    <td>{{ $rating->user->name }}</td>
                    <td>{{ $rating->materi->title }}</td>
                    <td>{{ $rating->rating }}</td>
                    <td>
                        <a href="{{ route('rating.edit', $rating->id) }}">Edit</a>
                        <form action="{{ route('rating.destroy', $rating->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
