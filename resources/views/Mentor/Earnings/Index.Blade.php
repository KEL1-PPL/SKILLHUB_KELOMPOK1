@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard Pendapatan Mentor</h2>

    @if(session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif

    <table border="1" cellpadding="10" style="width: 100%">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jumlah</th>
            <th>Catatan</th>
            <th>Aksi</th>
        </tr>
        @foreach($earnings as $earning)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $earning->created_at->format('Y-m-d') }}</td>
            <td>{{ $earning->amount }}</td>
            <td>{{ $earning->note }}</td>
            <td>
                <form action="{{ url('/mentor/earnings/' . $earning->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                </form>

                <form action="{{ url('/mentor/earnings/' . $earning->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('PUT')
                    <input type="text" name="amount" value="{{ $earning->amount }}" required>
                    <input type="text" name="note" value="{{ $earning->note }}">
                    <button>Koreksi</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
