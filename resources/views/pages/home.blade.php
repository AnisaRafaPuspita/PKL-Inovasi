@extends('layouts.app')
@section('title', 'UNDIP Innovation')

@section('content')
{{-- HERO --}}
<section class="relative">
    <img src="{{ asset('images/hero.JPG') }}" class="h-[546px] w-full object-cover" alt="">
    <div class="absolute inset-0 bg-black/30"></div>

    <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center px-6">
            <h1 class="text-white text-[56px] md:text-[96px] font-bold"
                style="font-family:'Source Serif Pro', serif; text-shadow:0px 4px 4px rgba(0,0,0,.40);">
                UNDIP INNOVATION
            </h1>

            <div class="mt-6 flex flex-wrap items-center justify-center gap-6">
                {{-- Upload Produk --}}
                <a href="{{ route('innovations.create') }}"
                class="group inline-flex items-center justify-center gap-2
                        rounded-[10px] border-2 border-white bg-[#001349]/50
                        px-8 py-2 text-white font-semibold text-[15px]
                        whitespace-nowrap
                        transition duration-200
                        hover:bg-[#001349]/70 hover:-translate-y-[1px]"
                style="font-family: Inter, sans-serif;">
                    <span>Upload Produk</span>
                    <img src="{{ asset('images/add_circle.png') }}"
                        alt="Add Icon"
                        class="h-[18px] w-[18px] shrink-0 transition-transform duration-200 group-hover:translate-x-1">
                </a>

                {{-- Selengkapnya --}}
                <a href="{{ route('about') }}"
                class="group inline-flex items-center justify-center gap-2
                        rounded-[10px] border-2 border-white bg-[#001349]/50
                        px-8 py-2 text-white font-semibold text-[15px]
                        whitespace-nowrap
                        transition duration-200
                        hover:bg-[#001349]/70 hover:-translate-y-[1px]"
                style="font-family: Inter, sans-serif;">
                    <span>Selengkapnya</span>
                    <img src="{{ asset('images/Arrow 4.png') }}"
                        alt="Arrow Icon"
                        class="h-[14px] w-[14px] shrink-0 transition-transform duration-200 group-hover:translate-x-1">
                </a>
            </div>
        </div>
    </div>
</section>

{{-- SEARCH --}}
<section class="mx-auto max-w-[1512px] px-6 -mt2 relative z-10">
    <form action="{{ route('innovations.index') }}" method="GET"
          class="flex items-center gap-4 rounded-[30px] bg-[#D9D9D9]/40 px-6 py-4">

        {{-- ICON SEARCH --}}
        <img src="{{ asset('images/search.png') }}"
             alt="Search"
             class="h-6 w-6 shrink-0">

        {{-- INPUT --}}
        <input name="q" value="{{ request('q') }}"
               placeholder="Cari"
               class="flex-1 bg-transparent outline-none text-[18px] font-semibold
                      placeholder:text-black/60"
               style="font-family: Inter, sans-serif;">

        {{-- FILTER BUTTON --}}
        <button type="button"
                class="inline-flex items-center justify-center
                       h-9 w-9 rounded-full
                       hover:bg-black/10 transition"
                aria-label="Filter">
            <img src="{{ asset('images/Filter.png') }}"
                 alt="Filter"
                 class="h-5 w-5">
        </button>

        {{-- SUBMIT (OPTIONAL, kalau mau klik search icon juga submit) --}}
        <button type="submit"
                class="sr-only"
                aria-label="Submit search">
        </button>

    </form>
</section>


{{-- IMPACT SECTION TITLE CARD --}}
<section class="mx-auto max-w-[1512px] px-6 mt-16">
    <div class="mx-auto max-w-[540px] rounded-[30px] bg-white shadow-[0px_4px_4px_rgba(0,0,0,0.90)] p-8 text-center">
        <img src="{{ asset('images/Logo Undip Universitas Diponegoro.png') }}" class="mx-auto h-[107px] w-[107px]" alt="">
        <h2 class="mt-4 text-[35px] font-semibold" style="font-family: Inter, sans-serif;">
            Inovasi Berdampak
        </h2>
    </div>

    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-10">
        @foreach($impactInnovations as $inv)
            <div class="rounded-[30px] border border-[#8D8585] bg-white overflow-hidden">
                <div class="h-[292px] bg-gray-200">
                    <img src="{{ $inv->image_url ?? 'https://placehold.co/450x399' }}" class="h-full w-full object-cover" alt="">
                </div>

                <div class="p-6">
                    <div class="text-[32px] md:text-[40px] font-bold" style="font-family: Inter, sans-serif;">
                        {{ $inv->title }}
                    </div>
                    <div class="mt-1 text-[22px] md:text-[30px] font-semibold" style="font-family: Inter, sans-serif;">
                        {{ $inv->category }}
                    </div>
                    <p class="mt-2 text-[18px] md:text-[25px]" style="font-family: Inter, sans-serif;">
                        {{ \Illuminate\Support\Str::limit($inv->description, 90) }}
                    </p>

                    <div class="mt-6 flex items-center justify-between">
                        <span class="rounded-full bg-[#1A6ECE]/50 px-4 py-1 text-[15px] text-[#1A6ECE]">
                            {{ ucfirst($inv->status) }}
                        </span>

                        <a class="text-[18px] md:text-[20px]" href="{{ route('innovations.show', $inv->id) }}">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- INNOVATOR OF THE MONTH (summary card) --}}
<section class="mx-auto max-w-[1512px] px-6 mt-24">
    <div class="inline-flex items-center gap-4 rounded-[30px] bg-white shadow-[0px_4px_4px_rgba(0,0,0,0.90)] px-10 py-6">
        <img src="{{ asset('images/person.png') }}" alt="Login Icon" class="h-[59px] w-auto"> 
        <h2 class="text-[#001349] text-[36px] md:text-[50px] font-bold" style="font-family: Inter, sans-serif;">
        Inovator of the Month
        </h2>
    </div>

    <div class="mt-10 rounded-[30px] border border-[#8D8585] bg-white p-10 grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
        <div class="h-[322px] w-[322px] rounded-[30px] border border-[#8D8585] grid place-content-center">
            <span class="text-[35px]" style="font-family: Inter, sans-serif;">Foto</span>
        </div>

        <div class="md:col-span-2 text-[22px] md:text-[35px] font-light" style="font-family: Inter, sans-serif;">
            <div><b>{{ $innovatorMonth?->innovator?->name ?? 'Nama' }}</b></div>
            <div class="mt-3">{{ $innovatorMonth?->innovator?->faculty?->name ?? 'Fakultas' }}</div>
            <div class="mt-3">{{ \Illuminate\Support\Str::limit($innovatorMonth?->innovator?->bio ?? 'Deskripsi', 180) }}</div>
        </div>

        <div class="md:col-span-3">
            <a href="{{ route('innovator-month.show') }}"
               class="inline-flex rounded-[30px] bg-[#001349] px-10 py-3 text-white text-[20px] font-medium"
               style="font-family: Inter, sans-serif;">
                Lihat Detail Inovator
            </a>
        </div>
    </div>
</section>

{{-- NATIONAL INNOVATION RANKING --}}
<section class="mx-auto max-w-[1512px] px-6 mt-24">
    <div class="inline-flex items-center gap-4 rounded-[30px] bg-white shadow-[0px_4px_4px_rgba(0,0,0,0.90)] px-10 py-6">
        <img src="{{ asset('images/Group 28.png') }}" alt="Login Icon" class="h-[59px] w-auto">
        <h2 class="text-[#001349] text-[36px] md:text-[50px] font-bold" style="font-family: Inter, sans-serif;">
            National Innovation Ranking
        </h2>
    </div>

    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-10">
        @forelse($rankings as $rank)
            <div class="rounded-[30px] border border-[#8D8585] bg-white p-8">
                <div class="flex items-center gap-4">
                    {{-- icon ranking --}}
                    <img src="{{ $rank->image_url ?? 'https://placehold.co/65x65' }}"
                         class="h-[65px] w-[65px] object-cover"
                         alt="">

                    <div class="text-[35px] font-semibold" style="font-family: Inter, sans-serif;">
                        #{{ $rank->rank }}
                    </div>
                </div>

                <div class="mt-6 text-[28px] md:text-[35px] font-semibold" style="font-family: Inter, sans-serif;">
                    {{ $rank->innovation_name }}
                </div>

                <div class="mt-4 text-[22px] md:text-[35px] font-light" style="font-family: Inter, sans-serif;">
                    {{ $rank->category ?? 'Kategori' }}
                    <br><br>
                    {{ \Illuminate\Support\Str::limit($rank->achievement ?? 'Deskripsi', 80) }}
                </div>

                <a href="{{ route('innovations.show', $rank->innovation_id ?? 1) }}"
                   class="mt-8 inline-flex w-full justify-center rounded-[30px] bg-[#001349] px-10 py-3 text-white text-[20px] font-medium"
                   style="font-family: Inter, sans-serif;">
                    Lihat Detail Inovasi
                </a>
            </div>
        @empty
            <div class="text-gray-600" style="font-family: Inter, sans-serif;">
                Data ranking belum ada.
            </div>
        @endforelse
    </div>
</section>


{{-- INNOVATION PRODUCTS --}}
<section class="mx-auto max-w-[1512px] px-6 mt-24">
    <div class="inline-flex items-center gap-4 rounded-[30px] bg-white shadow-[0px_4px_4px_rgba(0,0,0,0.90)] px-10 py-6">
        <img src="{{ asset('images/Box.png') }}" alt="Login Icon" class="h-[59px] w-auto">
        <h2 class="text-[#001349] text-[36px] md:text-[50px] font-bold" style="font-family: Inter, sans-serif;">
            Innovation Products
        </h2>
    </div>

    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-10">
        @forelse($innovations as $inv)
            <div class="rounded-[30px] border border-[#8D8585] bg-white overflow-hidden">
                <div class="h-[292px] bg-gray-200">
                    <img src="{{ $inv->image_url ?? 'https://placehold.co/450x399' }}"
                         class="h-full w-full object-cover" alt="">
                </div>

                <div class="p-6">
                    <div class="text-[32px] md:text-[40px] font-bold" style="font-family: Inter, sans-serif;">
                        {{ $inv->title }}
                    </div>

                    <div class="mt-1 text-[22px] md:text-[30px] font-semibold" style="font-family: Inter, sans-serif;">
                        {{ $inv->category }}
                    </div>

                    <p class="mt-2 text-[18px] md:text-[25px]" style="font-family: Inter, sans-serif;">
                        {{ \Illuminate\Support\Str::limit($inv->description, 90) }}
                    </p>

                    <div class="mt-6 flex items-center justify-between">
                        <span class="rounded-full bg-[#1A6ECE]/50 px-4 py-1 text-[15px] text-[#1A6ECE]">
                            {{ ucfirst($inv->status) }}
                        </span>

                        <a class="text-[18px] md:text-[20px]"
                           href="{{ route('innovations.show', $inv->id) }}">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-gray-600" style="font-family: Inter, sans-serif;">
                Belum ada produk inovasi.
            </div>
        @endforelse
    </div>
</section>



{{-- MOST VISITED (list) --}}
<section class="mx-auto max-w-[1512px] px-6 mt-24">
    <div class="inline-flex items-center gap-4 rounded-[30px] bg-white shadow-[0px_4px_4px_rgba(0,0,0,0.90)] px-10 py-6">
        <img src="{{ asset('images/Arrow up-right.png') }}" alt="Login Icon" class="h-[64px] w-auto"> 
        <h2 class="text-[#001349] text-[36px] md:text-[50px] font-bold" style="font-family: Inter, sans-serif;">
            Most Visited Innovations
        </h2>
    </div>

    <div class="mt-10 space-y-6">
        @foreach($mostVisited as $inv)
            <div class="rounded-[30px] border border-[#8D8585] bg-white p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="h-10 w-10 border-2 border-black/70 rounded"></div>
                    <div class="text-[28px] md:text-[35px] font-light" style="font-family: Inter, sans-serif;">
                        {{ number_format($inv->views_count ?? 0, 0, ',', '.') }}
                    </div>
                    <div style="font-family: Inter, sans-serif;">
                        <div class="text-[24px] md:text-[35px] font-light">{{ $inv->title }}</div>
                        <div class="text-[18px] md:text-[25px] font-light">
                            {{ $inv->category }} <br>
                            {{ \Illuminate\Support\Str::limit($inv->description, 80) }}
                        </div>
                    </div>
                </div>

                <a href="{{ route('innovations.show', $inv->id) }}"
                   class="inline-flex rounded-[30px] bg-[#001349] px-10 py-3 text-white text-[20px] font-medium"
                   style="font-family: Inter, sans-serif;">
                    Lihat Detail Inovasi
                </a>
            </div>
        @endforeach
    </div>
</section>
@endsection
