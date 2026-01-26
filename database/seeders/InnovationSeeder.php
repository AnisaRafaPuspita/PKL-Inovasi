<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Innovation;
use App\Models\Innovator;



class InnovationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Innovation::truncate(); // opsional, biar bersih saat test (hapus kalau takut data ilang)

        Innovation::create([
            'title'       => 'Smart Agriculture Monitoring System',
            'category'    => 'Pertanian',
            'partner'     => 'Miniplant SV UNDIP, PT. Bhanda Ghara Reksa',
            'hki_status'  => 'Paten Granted',
            'video_url'   => 'https://www.youtube.com/watch?v=dO9h45Mrr2A',
            'description' => 'Sistem IoT untuk monitoring soil moisture, temperatur, dan nutrisi secara realtime.',
            'review'      => 'Ranked #1 in National Innovation Rankings',
            'advantages'  => "Best Innovation Award 2025\nImplemented in 50+ farms",
            'impact'      => 'Meningkatkan efisiensi pemupukan dan produktivitas panen.',
            'is_impact'   => 1,
            'status'      => 'published',
            'views_count' => 0,
            'image_url'   => 'https://placehold.co/450x399',
        ]);

        Innovation::create([
            'title'       => 'Biodiesel dan Bioavtur dari Minyak Goreng Bekas',
            'category'    => 'Energi',
            'partner'     => 'PPSDM Migas, PT. Sinar Energi',
            'hki_status'  => 'Paten Granted',
            'video_url'   => 'https://www.youtube.com/watch?v=dO9h45Mrr2A',
            'description' => 'Produksi biodiesel/bioavtur dari minyak jelantah untuk energi terbarukan.',
            'review'      => 'Layak diimplementasikan untuk skala pilot.',
            'advantages'  => "Bahan baku murah\nRamah lingkungan",
            'impact'      => 'Mengurangi limbah minyak jelantah dan menyediakan alternatif energi bersih.',
            'is_impact'   => 1,
            'status'      => 'published',
            'views_count' => 0,
            'image_url'   => 'https://placehold.co/450x399',
        ]);

        Innovation::create([
            'title'       => 'Aplikasi Monitoring Kesehatan Mahasiswa',
            'category'    => 'Kesehatan',
            'partner'     => 'Klinik UNDIP',
            'hki_status'  => 'Dalam Proses',
            'video_url'   => null,
            'description' => 'Aplikasi untuk memantau kondisi kesehatan dan aktivitas mahasiswa.',
            'review'      => 'Prototype siap uji coba.',
            'advantages'  => "UI simpel\nRealtime monitoring",
            'impact'      => 'Membantu deteksi dini dan monitoring kesehatan.',
            'is_impact'   => 0,
            'status'      => 'published',
            'views_count' => 0,
            'image_url'   => 'https://placehold.co/450x399',
        ]);
    }
}
