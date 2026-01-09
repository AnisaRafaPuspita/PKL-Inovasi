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
        // ambil innovator pertama (atau cari by name kalau mau)
        $innovator = Innovator::query()->first();

        if (!$innovator) {
            // kalau belum ada innovator, stop biar gak error
            $this->command?->warn('Tidak ada data innovators. Jalankan InnovatorSeeder dulu.');
            return;
        }

        $month = (int) now()->format('n');  // 1-12
        $year  = (int) now()->format('Y');  // 2026, dst

        // "aman": kalau data bulan+tahun itu sudah ada, di-update. Kalau belum ada, dibuat.
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
