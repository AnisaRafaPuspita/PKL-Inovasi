@extends('layouts.app')
@section('title', 'Produk Inovasi')

@section('content')
<section class="relative">
    <img src="{{ asset('images/hero.JPG') }}" class="h-[546px] w-full object-cover" alt="">
    <div class="absolute inset-0 bg-black/30"></div>

    <div class="absolute inset-0 flex items-center justify-center">
        <h1 class="text-white text-[56px] md:text-[96px] font-bold text-center px-6"
            style="font-family:'Source Serif Pro', serif; text-shadow:0px 4px 4px rgba(0,0,0,.40);">
            UNDIP INNOVATION
        </h1>
    </div>
</section>

<section class="mx-auto max-w-[1512px] px-6 py-16">
    <h2 class="text-black text-[40px] font-bold text-center" style="font-family:'Source Serif Pro', serif;">
        PRODUK INOVASI
    </h2>

    <div class="mt-8 max-w-5xl mx-auto text-[20px] font-normal text-justify" style="font-family:'Source Serif Pro', serif;">
        Universitas Diponegoro sebagai salah satu Perguruan Tinggi Negeri Berbadan Hukum di Indonesia, memiliki Visi besar, yaitu “Universitas Diponegoro Menjadi Universitas Riset yang Unggul”. Dalam mencapai visi tersebut, telah ditetapkan dalam misi-misinya, yang diantaranya adalah dengan Menyelenggarakan penelitian yang menghasilkan publikasi, Hak Atas Kekayaan Intelektual (HAKI), buku ajar, kebijakan dan teknologi yang berhasil guna dan berdaya guna dengan mengedepankan budaya dan sumber daya lokal; dan juga dengan Menyelenggarakan pengabdian kepada masyarakat yang menghasilkan publikasi, Hak Atas Kekayaan Intelektual (HAKI), buku ajar, kebijakan dan teknologi yang berhasil guna dan berdaya guna dengan mengedepankan budaya dan sumber daya lokal. Untuk mencapai tujuan mulia tersebut, Undip akan terus mendukung penelitian yang dapat bermanfaat bagi masyarakat, dan juga mendorong untuk dapat terimplementasinya hasil penelitian yang telah dilaksanakan untuk masyarakat yang luas.
        <br><br>
        Dalam implementasinya, Undip telah memberikan berbagai pengaplikasian hasil penelitian terhadap masyarakat, seperti halnya dalam menciptakan berbagai Inovasi yang dapat membantu kehidupan manusia. Berikut adalah rincian hasil inovasi dari para peneliti Undip, secara spesifik adalah output dari Matching Fund Kedaireka 2022 dan juga 2023
    </div>
</section>
@endsection

