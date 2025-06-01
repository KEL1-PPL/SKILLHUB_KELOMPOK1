<?php

namespace Database\Seeders;

use App\Models\CourseEnrollment;
use App\Models\CourseProgres;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

            CourseProgres::create([
                'enrollment_id' => CourseEnrollment::find(1)->id,
                'percentage_completed' => rand(0, 100),
                'status' => rand(0, 1) ? 'Selesai' : 'Tidak Selesai',
                'last_accessed_at' => Carbon::now()->subDays(rand(0, 10)),
            ]);
    }
}
