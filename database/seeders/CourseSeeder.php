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
            'slug' => Course::generateUniqueSlug('Belajar Laravel Dasar'),
            'rating' => 5,
            'created_by' => User::find(2)->id,
        ]);
    }
}
