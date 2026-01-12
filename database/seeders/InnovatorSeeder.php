<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Innovator;
use App\Models\Faculty;

class InnovatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculty = Faculty::first();

        Innovator::create([
            'name'        => 'Dr. Andi Saputra',
            'faculty_id'  => $faculty->id,
            'bio'         => 'Peneliti bidang teknologi pertanian dan IoT.',
            'photo'       => 'https://placehold.co/300x300',
        ]);
    
    }
}
