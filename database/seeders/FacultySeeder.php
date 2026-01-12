<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faculty;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculties = [
            'Fakultas Teknik',
            'Fakultas Kedokteran',
            'Fakultas Ekonomika dan Bisnis',
            'Fakultas Hukum',
            'Fakultas Pertanian',
            'Fakultas Sains dan Matematika',
        ];

        foreach ($faculties as $name) {
            Faculty::create([
                'name' => $name,
            ]);
        }
    }
}
