<?php

namespace Database\Seeders;

use App\Models\Certificate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh dummy: 5 data sertifikat
        for ($i = 1; $i <= 5; $i++) {
            Certificate::create([
                'user_id' => 1, // Pastikan ada user_id di tabel users
                'course_id' => 1, // Pastikan ada course_id di tabel courses
                'certificate_number' => Str::upper(Str::random(10)),
                'certificate_file' => null, // Kalau mau ada file, bisa diisi
                'issued_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
