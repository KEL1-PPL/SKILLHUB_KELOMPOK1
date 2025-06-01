<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Material;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $course = Course::all();
        foreach ($course as $courses) {
        for ($i = 1; $i <= 3; $i++){
            Material::create([
                'course_id' => $courses->id,
                'title' => "Materi $i untuk {$courses->title}",
                'content' => "Konten materi $i",
                'order' => $i
            ]);
        }
    }
    }
}
