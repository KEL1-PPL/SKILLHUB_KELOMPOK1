<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            MentorIncomeSeeder::class,
            SubscriptionPlanSeeder::class,
            CourseSeeder::class,
            CourseEnrollmentSeeder::class,
            CourseProgressSeeder::class,
            MaterialSeeder::class,
            MaterialCompletionsSeeder::class,
            CertificateSeeder::class,
        ]);
    }
}
