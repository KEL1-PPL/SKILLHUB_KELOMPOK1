@php
    $totalEarnings = \App\Models\Earning::valid()->sum('amount');
@endphp

<p>Total Valid Earnings: {{ number_format($totalEarnings, 0, ',', '.') }}</p>

@foreach($earnings as $earning)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:15px;">
        <p>
            <strong>{{ $earning->mentor->name }}</strong> -
            Rp{{ number_format($earning->amount, 0, ',', '.') }} -
            {{ \Carbon\Carbon::parse($earning->payment_date)->format('d M Y') }}
            @if(!$earning->is_valid)
                <span style="color: red;">(Tidak Valid)</span><br>
                <small><strong>Catatan Koreksi:</strong> {{ $earning->correction_note }}</small>
            @endif
        </p>

        {{-- Tombol Hapus --}}
        <form action="{{ route('admin.earnings.destroy', $earning) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
            @csrf
            @method('DELETE')
            <button type="submit">Hapus</button>
        </form>

        {{-- Koreksi Jumlah Pendapatan --}}
        <form action="{{ route('admin.earnings.update', $earning) }}" method="POST" style="margin-top: 10px;">
            @csrf
            @method('PUT')
            <input type="number" name="amount" value="{{ $earning->amount }}" required>
            <input type="text" name="correction_note" placeholder="Catatan Koreksi" required value="{{ $earning->correction_note }}">
            <button type="submit">Simpan Koreksi</button>
        </form>

        {{-- Tandai Tidak Valid --}}
        <form action="{{ route('admin.earnings.invalidate', $earning) }}" method="POST" style="margin-top: 10px;">
            @csrf
            <input type="text" name="correction_note" placeholder="Catatan Koreksi" required>
            <button type="submit" style="color: red;">Tandai Tidak Valid</button>
        </form>
    </div>
@endforeach
