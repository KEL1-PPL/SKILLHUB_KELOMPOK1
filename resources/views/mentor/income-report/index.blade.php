@extends('layouts.mentor.app')

@section('content')
<div class="container">
    <h1>Laporan Pendapatan</h1>
    
    <div class="card">
        <div class="card-header">
            <h3>Filter</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('mentor.income-report') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary mt-4">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3>Rincian Pendapatan</h3>
            <h4>Total Pendapatan Valid: Rp {{ number_format($totalValid, 0, ',', '.') }}</h4>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incomes as $income)
                    <tr>
                        <td>{{ $income->transactionDate->format('d/m/Y') }}</td>
                        <td>{{ $income->student->name }}</td>
                        <td>Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
                        <td>{{ $income->status }}</td>
                        <td>{{ $income->note }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection