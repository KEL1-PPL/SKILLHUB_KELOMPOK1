<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Basic Laravel courses
        Course::create([
            'title' => 'Belajar Laravel Dasar',
            'description' => 'Kursus untuk pemula belajar Laravel 11',
            'slug' => Course::generateUniqueSlug('Belajar Laravel Dasar'),
            'rating' => 4,
            'created_by' => User::find(2)->id,
        ]);

        Course::create([
            'title' => 'Belajar Laravel Intermediete',
            'description' => 'Kursus untuk menengah belajar Laravel 11',
            'slug' => Course::generateUniqueSlug('Belajar Laravel Intermediete'),
            'rating' => 5,
            'created_by' => User::find(2)->id,
        ]);

        // Additional courses
        $courses = [
            [
                'title' => 'PHP OOP',
                'description' => 'Fundamental Object-Oriented Programming dengan PHP',
                'created_by' => 1
            ],
            [
                'title' => 'Java Spring Boot',
                'description' => 'Belajar membuat aplikasi dengan Spring Boot',
                'created_by' => 1
            ],
            [
                'title' => 'Vue.js for Beginners',
                'description' => 'Dasar-dasar pengembangan frontend dengan Vue.js',
                'created_by' => 1
            ],
            [
                'title' => 'Database Design Fundamentals',
                'description' => 'Fundamental perancangan basis data',
                'created_by' => 1
            ],
        ];

        foreach ($courses as $course) {
            Course::create([
                'title' => $course['title'],
                'description' => $course['description'],
                'slug' => Course::generateUniqueSlug($course['title']),
                'rating' => rand(3, 5),
                'created_by' => $course['created_by'],
            ]);
        }
    }
}
