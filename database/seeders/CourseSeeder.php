<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            ['title' => 'Laravel Basics', 'created_by' => 1],
            ['title' => 'PHP OOP', 'created_by' => 1],
            ['title' => 'Java Spring Boot', 'created_by' => 1],
            ['title' => 'Vue.js for Beginners', 'created_by' => 1],
            ['title' => 'Database Design Fundamentals', 'created_by' => 1],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
