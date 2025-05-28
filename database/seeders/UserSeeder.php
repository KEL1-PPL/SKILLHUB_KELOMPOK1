<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@skillhub.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'learning_path' => 'web-development',
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Mentor User',
            'email' => 'mentor@skillhub.com',
            'password' => Hash::make('password'),
            'role' => 'mentor',
            'learning_path' => 'web-development',
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Student User',
            'email' => 'student@skillhub.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'learning_path' => 'web-development',
            'email_verified_at' => now()
        ]);
    }
}
