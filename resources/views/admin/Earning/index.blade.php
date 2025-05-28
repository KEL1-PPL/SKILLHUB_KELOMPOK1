@php
    $totalEarnings = \App\Models\Earning::valid()->sum('amount');
@endphp

<p>Total Valid Earnings: {{ $totalEarnings }}</p>

@foreach($earnings as $earning)
    <div>
        <p>
            {{ $earning->mentor->name }} - {{ $earning->amount }} - {{ $earning->payment_date }}
            @if(!$earning->is_valid)
                <span style="color: red;">(Invalid)</span>
                <p>Correction Note: {{ $earning->correction_note }}</p>
            @endif
        </p>
        
        <form action="{{ route('admin.earnings.destroy', $earning) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
            @csrf
            @method('DELETE')
            <button type="submit">Hapus</button>
        </form>
        
        <form action="{{ route('admin.earnings.update', $earning) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="text" name="amount" value="{{ $earning->amount }}" required>
            <input type="text" name="correction_note" placeholder="Catatan Koreksi" required>
            <button type="submit">Koreksi</button>
        </form>

        <form action="{{ route('admin.earnings.invalidate', $earning) }}" method="POST">
            @csrf
            <input type="text" name="correction_note" placeholder="Catatan Koreksi" required>
            <button type="submit">Tandai Tidak Valid</button>
        </form>
    </div>
@endforeach
