<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            ['title' => 'Web Development Dasar', 'description' => 'Belajar HTML, CSS, dan JavaScript dari nol.', 'rating' => 4, 'image' => 'WebDev.png', 'created_by' => 1, 'price' => 100.00],
            ['title' => 'Data Science Pemula', 'description' => 'Analisis data menggunakan Python dan Pandas.', 'rating' => 5, 'image' => 'DataSci.png', 'created_by' => 1, 'price' => 150.00],
            ['title' => 'Desain Grafis Modern', 'description' => 'Kuasai Adobe Photoshop dan Illustrator.', 'rating' => 3, 'image' => 'GraphicDes.png', 'created_by' => 1, 'price' => 120.00],
            ['title' => 'Mobile App Development', 'description' => 'Bangun aplikasi mobile dengan Flutter.', 'rating' => 4, 'image' => 'MobileApp.png', 'created_by' => 1, 'price' => 200.00],
            ['title' => 'UI/UX Design', 'description' => 'Pelajari dasar desain aplikasi yang menarik.', 'rating' => 5, 'image' => 'UIX.png', 'created_by' => 1, 'price' => 130.00],
            ['title' => 'Machine Learning Dasar', 'description' => 'Memahami supervised & unsupervised learning.', 'rating' => 5, 'image' => 'MachineLearning.png', 'created_by' => 1, 'price' => 180.00],
            ['title' => 'Cybersecurity 101', 'description' => 'Lindungi data dan sistem dengan baik.', 'rating' => 4, 'image' => 'CyberSecurity.png', 'created_by' => 1, 'price' => 140.00],
            ['title' => 'DevOps & CI/CD', 'description' => 'Belajar automation dan deployment modern.', 'rating' => 4, 'image' => 'DevopsEngineer.png', 'created_by' => 1, 'price' => 170.00],
            ['title' => 'Cloud Computing', 'description' => 'Pahami AWS dan layanan cloud lainnya.', 'rating' => 4, 'image' => 'CloudComputing.png', 'created_by' => 1, 'price' => 160.00],
            ['title' => 'Game Development', 'description' => 'Ciptakan game seru dengan Unity.', 'rating' => 5, 'image' => 'GameDev.png', 'created_by' => 1, 'price' => 220.00],
            ['title' => 'Digital Marketing', 'description' => 'Optimalkan visibilitas brand digitalmu.', 'rating' => 3, 'image' => 'DigiMarketing.png', 'created_by' => 1, 'price' => 110.00],
            ['title' => 'Artificial Intelligence', 'description' => 'Terjun ke dunia AI dan aplikasi nyatanya.', 'rating' => 5, 'image' => 'AI.png', 'created_by' => 1, 'price' => 250.00],
        ];

        // Menyisipkan data kursus ke tabel 'courses'
        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
