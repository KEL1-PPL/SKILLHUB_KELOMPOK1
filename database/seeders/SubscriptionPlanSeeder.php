<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'SkillHub Starter',
                'description' => 'Cocok untuk pemula yang ingin mulai belajar pemrograman dengan akses dasar.',
                'price' => 50000,
                'duration_in_days' => 30,
                'features' => json_encode([
                    'Akses ke semua kursus',
                    'Sertifikat penyelesaian',
                    'Forum diskusi komunitas',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'SkillHub Pro',
                'description' => 'Untuk siswa yang ingin pembelajaran lebih intensif dan proyek nyata.',
                'price' => 100000,
                'duration_in_days' => 30,
                'features' => json_encode([
                    'Akses ke semua kursus',
                    'Live Class dan Proyek Portofolio',
                    'Sertifikat penyelesaian',
                    'Forum komunitas + sesi tanya jawab',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'SkillHub Elite',
                'description' => 'Langganan tahunan eksklusif dengan semua fitur premium dan keuntungan tambahan.',
                'price' => 900000,
                'duration_in_days' => 365,
                'features' => json_encode([
                    'Akses semua kursus & fitur premium',
                    'Proyek portofolio eksklusif',
                    'Webinar bulanan & sesi mentoring',
                    'Sertifikat penyelesaian premium',
                    'Diskon bootcamp & event khusus',
                ]),
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
