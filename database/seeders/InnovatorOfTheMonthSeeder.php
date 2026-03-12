<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Innovator;
use Illuminate\Support\Facades\DB;


class InnovatorOfTheMonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $innovator = Innovator::query()->first();

        if (!$innovator) {
            
            $this->command?->warn('Tidak ada data innovators. Jalankan InnovatorSeeder dulu.');
            return;
        }

        $month = (int) now()->format('n');  
        $year  = (int) now()->format('Y');  

     
        DB::table('innovator_of_the_month')->updateOrInsert(
            ['month' => $month, 'year' => $year],
            [
                'innovator_id' => $innovator->id,
                'updated_at'   => now(),
                'created_at'   => now(),
            ]
        );
    }
}
