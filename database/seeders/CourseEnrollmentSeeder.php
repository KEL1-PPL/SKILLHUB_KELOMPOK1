<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseEnrollment::create([
            'user_id' => 3,
            'course_id' => Course::find(1)->id,
            'enrolled_at' => now(),
        ]);
    }
}
