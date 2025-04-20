@foreach($earnings as $earning)
    <div>
        <p>{{ $earning->mentor->name }} - {{ $earning->amount }} - {{ $earning->payment_date }}</p>
        
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
    </div>
@endforeach
