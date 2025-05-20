@extends('layouts.mentor.app')

@section('title', 'Laporan Pendapatan')

@section('content')
<div class="container">
    <h1>Laporan Pendapatan</h1>

    <!-- Filter Section -->
    <div class="card mt-4">
        <div class="card-body">
            <form method="GET" action="{{ route('mentor.earnings') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Periode</label>
                    <select name="period" class="form-select">
                        <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status Pembayaran</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="paid">Sudah Dibayar</option>
                        <option value="pending">Menunggu Pembayaran</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan Pendapatan -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Ringkasan Pendapatan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Total Pendapatan</h6>
                            <h3>Rp {{ number_format($totalEarnings ?? 0, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning-dark text-white">
                        <div class="card-body">
                            <h6>Pendapatan Bulan Ini</h6>
                            <h3>Rp {{ number_format($monthlyEarnings ?? 0, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info-dark text-white">
                        <div class="card-body">
                            <h6>Jumlah Transaksi</h6>
                            <h3>{{ $totalTransactions ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Transaksi</h5>
            <button class="btn btn-success" onclick="exportToExcel()">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kursus</th>
                            <th>Siswa</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions ?? [] as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                                <td>{{ $transaction->course_title }}</td>
                                <td>{{ $transaction->student_name }}</td>
                                <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->status == 'paid' ? 'success' : 'warning' }}">
                                        {{ $transaction->status == 'paid' ? 'Sudah Dibayar' : 'Menunggu' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="note-text">{{ $transaction->note ?? '-' }}</span>
                                    <button class="btn btn-sm btn-link" onclick="editNote({{ $transaction->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="viewDetail({{ $transaction->id }})">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Edit Catatan -->
    <div class="modal fade" id="noteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Catatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="noteForm">
                        <input type="hidden" id="transactionId">
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" id="noteText" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveNote()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editNote(transactionId) {
    // Implementasi edit catatan
    $('#transactionId').val(transactionId);
    $('#noteModal').modal('show');
}

function saveNote() {
    const transactionId = $('#transactionId').val();
    const note = $('#noteText').val();
    
    // Implementasi AJAX untuk menyimpan catatan
    $.ajax({
        url: `/mentor/transactions/${transactionId}/note`,
        method: 'POST',
        data: { note: note },
        success: function(response) {
            $('#noteModal').modal('hide');
            // Refresh halaman atau update tampilan
            location.reload();
        },
        error: function(error) {
            alert('Gagal menyimpan catatan');
        }
    });
}

function exportToExcel() {
    // Implementasi export ke Excel
    window.location.href = '{{ route("mentor.earnings.export") }}' + window.location.search;
}

function viewDetail(transactionId) {
    // Implementasi lihat detail transaksi
    window.location.href = `/mentor/transactions/${transactionId}`;
}
</script>
@endsection