<?php

namespace Database\Seeders;

use App\Models\MentorIncome;
use Illuminate\Database\Seeder;

class MentorIncomeSeeder extends Seeder
{
    public function run()
    {
        MentorIncome::create([
            'mentorId' => 'mentor-1',
            'studentId' => 'student-1',
            'amount' => 150000,
            'transactionDate' => now(),
            'status' => 'valid',
            'courseId' => 'course-1'
        ]);

        MentorIncome::create([
            'mentorId' => 'mentor-1',
            'studentId' => 'student-2',
            'amount' => 100000,
            'transactionDate' => now(),
            'status' => 'valid',
            'note' => 'Pembayaran langganan basic',
            'subscriptionId' => 'sub-1'
        ]);
    }
}
