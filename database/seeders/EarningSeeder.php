<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Earning;

class EarningSeeder extends Seeder
{
    public function run(): void
    {
        Earning::create([
            'amount' => 750000,
            'note' => 'Bonus awal bulan',
        ]);
    }
}
