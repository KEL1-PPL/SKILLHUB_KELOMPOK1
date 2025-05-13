<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EarningReport;
use App\Models\User;

class EarningReportSeeder extends Seeder
{
    public function run(): void
    {
        $mentor = User::where('role', 'mentor')->first(); // ambil 1 mentor

        if ($mentor) {
            EarningReport::factory()->count(10)->create([
                'mentor_id' => $mentor->id,
                'period' => now()->subMonths(rand(1, 12)),
                'amount' => rand(50000, 300000),
                'note' => 'Pendapatan dari kursus A/B'
            ]);
        }
    }
}
