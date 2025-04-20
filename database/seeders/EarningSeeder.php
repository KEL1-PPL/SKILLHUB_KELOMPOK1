use App\Models\Earning;
use Illuminate\Database\Seeder;

class EarningSeeder extends Seeder
{
    public function run()
    {
        Earning::create([
            'mentor_id' => 1, // ID mentor
            'amount' => 500000, // Jumlah pendapatan
            'payment_date' => now(),
            'correction_note' => null, // Tidak ada koreksi untuk data ini
        ]);
    }
}
