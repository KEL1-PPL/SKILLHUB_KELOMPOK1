@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Laporan Pendapatan</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('mentor.earning.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="mb-3">
            <label>Periode</label>
            <input type="date" name="period" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Jumlah (Rp)</label>
            <input type="number" name="amount" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="note" class="form-control"></textarea>
        </div>
        <button class="btn btn-primary">Tambah Laporan</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Periode</th>
                <th>Jumlah</th>
                <th>Catatan</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td>{{ \Carbon\Carbon::parse($report->period)->format('F Y') }}</td>
                <td>Rp {{ number_format($report->amount, 0, ',', '.') }}</td>
                <td>{{ $report->note }}</td>
                <td>{{ $report->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
