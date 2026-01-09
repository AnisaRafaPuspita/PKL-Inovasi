<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Innovation;
use App\Models\InnovationRanking;

class InnovationRankingSeeder extends Seeder
{
    public function run(): void
    {
        $innovations = Innovation::orderBy('id')->take(3)->get();

        if ($innovations->count() < 3) {
            $this->command?->warn('Minimal butuh 3 data di tabel innovations untuk seed ranking.');
            return;
        }

        InnovationRanking::insert([
            [
                'rank'          => 1,
                'innovation_id' => $innovations[0]->id,
                'achievement'   => 'Best Innovation Award 2025',
                'status'        => 'active',
                'image'         => 'rank-1.png',
            ],
            [
                'rank'          => 2,
                'innovation_id' => $innovations[1]->id,
                'achievement'   => 'Top 10 National Innovation',
                'status'        => 'active',
                'image'         => 'rank-2.png',
            ],
            [
                'rank'          => 3,
                'innovation_id' => $innovations[2]->id,
                'achievement'   => 'Finalist Innovation Expo',
                'status'        => 'active',
                'image'         => 'rank-3.png',
            ],
        ]);
    }
}
