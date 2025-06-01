<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialCompletion;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialCompletionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MaterialCompletion::create([
            'user_id' => User::find(3)->id,
            'material_id' => Material::find(1)->id,
            'is_completed' => rand(0, 1),
        ]);
    }
}
