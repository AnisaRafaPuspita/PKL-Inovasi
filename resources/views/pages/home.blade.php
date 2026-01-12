@extends('layouts.app')
@section('title', 'UNDIP Innovation')

@section('content')

{{-- HERO --}}
<section class="relative">
    <img src="{{ asset('images/hero.JPG') }}" class="h-[420px] md:h-[560px] w-full object-cover" alt="">
    <div class="absolute inset-0 bg-black/30"></div>

    <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center px-4">
            <h1 class="text-white text-[48px] md:text-[92px] font-bold"
                style="font-family:'Source Serif Pro', serif; text-shadow:0px 4px 4px rgba(0,0,0,.40);">
                UNDIP INNOVATION
            </h1>

            <div class="mt-6 flex flex-wrap items-center justify-center gap-5 md:gap-6">
                {{-- Upload Produk --}}
                <a href="{{ route('innovations.create') }}"
                   class="group inline-flex items-center justify-center gap-2
                          rounded-[10px] border-2 border-white bg-[#001349]/60
                          px-8 py-2.5 text-white font-semibold text-[14px] md:text-[15px]
                          transition duration-200 hover:bg-[#001349]/80 hover:-translate-y-[1px]"
                   style="font-family: Inter, sans-serif;">
                    <span>Upload Produk</span>
                    <img src="{{ asset('images/add_circle.png') }}"
                         alt="Add Icon"
                         class="h-[18px] w-[18px] shrink-0 transition-transform duration-200 group-hover:translate-x-1">
                </a>

                {{-- Selengkapnya --}}
                <a href="{{ route('about') }}"
                   class="group inline-flex items-center justify-center gap-2
                          rounded-[10px] border-2 border-white bg-[#001349]/60
                          px-8 py-2.5 text-white font-semibold text-[14px] md:text-[15px]
                          transition duration-200 hover:bg-[#001349]/80 hover:-translate-y-[1px]"
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
<section class="mx-auto max-w-[1320px] px-3 md:px-4 -mt-10 relative z-10">
    <form action="{{ route('innovations.index') }}" method="GET"
          class="flex items-center gap-4 rounded-[30px] bg-[#D9D9D9]/40 px-5 md:px-6 py-3.5 md:py-4">

        <img src="{{ asset('images/search.png') }}" alt="Search" class="h-5 w-5 md:h-6 md:w-6 shrink-0">

        <input name="q" value="{{ request('q') }}"
               placeholder="Cari"
               class="flex-1 bg-transparent outline-none text-[15px] md:text-[16px] font-semibold
                      placeholder:text-black/60"
               style="font-family: Inter, sans-serif;">

        <button type="button"
                class="inline-flex items-center justify-center h-9 w-9 rounded-full hover:bg-black/10 transition"
                aria-label="Filter">
            <img src="{{ asset('images/Filter.png') }}" alt="Filter" class="h-5 w-5">
        </button>

        <button type="submit" class="sr-only" aria-label="Submit search"></button>
    </form>
</section>

{{-- INOVASI BERDAMPAK --}}
<section class="mx-auto max-w-[1320px] px-3 md:px-4 mt-12 md:mt-14">
    <div class="mx-auto max-w-[800px]
                rounded-[30px] bg-white
                shadow-[0px_4px_8px_rgba(0,0,0,0.25)]
                px-10 py-4
                flex items-center justify-center gap-5">

        <img src="{{ asset('images/Logo Undip Universitas Diponegoro.png') }}"
             class="h-[56px] md:h-[64px]"
             alt="">

        <h2 class="text-[22px] md:text-[26px] font-semibold text-[#001349]"
            style="font-family: Inter, sans-serif;">
            Inovasi Berdampak
        </h2>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
        @foreach($impactInnovations as $inv)
            <div class="rounded-[30px] border-2 border-[#8D8585] bg-white overflow-hidden
                        transition-all duration-200 ease-out hover:-translate-y-1 hover:shadow-lg">
                <div class="h-[215px] md:h-[235px] bg-gray-200">
                    @if ($inv->image_url)
                        <img
                            src="{{ asset('storage/' . $inv->image_url) }}"
                            class="h-full w-full object-cover"
                            alt="Foto Inovasi"
                        >
                    @else
                        <img src="https://placehold.co/450x300" class="h-full w-full object-cover">
                    @endif
                </div>

                <div class="p-5 md:p-6">
                    <div class="text-[18px] md:text-[20px] font-semibold text-[#001349] leading-snug"
                         style="font-family: Inter, sans-serif;">
                        {{ $inv->title }}
                    </div>

                    <div class="mt-1 text-[13px] md:text-[14px] font-semibold text-gray-800"
                         style="font-family: Inter, sans-serif;">
                        {{ $inv->category }}
                    </div>

                    <p class="mt-2 text-[13px] md:text-[14px] text-gray-700 leading-relaxed"
                       style="font-family: Inter, sans-serif;">
                        {{ \Illuminate\Support\Str::limit($inv->description, 100) }}
                    </p>

                    <div class="mt-4 flex items-center justify-between">
                        <span class="rounded-full bg-[#1A6ECE]/50 px-3 py-1.5 text-[12px] font-semibold text-[#1A6ECE]">
                            {{ ucfirst($inv->status) }}
                        </span>

                        <a class="text-[#1A6ECE] font-semibold text-[14px] hover:underline"
                           style="font-family: Inter, sans-serif;"
                           href="{{ route('innovations.show', $inv->id) }}">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- INNOVATOR OF THE MONTH --}}
<section class="mx-auto max-w-[1320px] px-3 md:px-4 mt-14 md:mt-16">
    <div class="inline-flex items-center gap-3 md:gap-4 rounded-[30px] bg-white shadow-[0px_4px_8px_rgba(0,0,0,0.25)] px-6 md:px-10 py-4 md:py-6">
        <img src="{{ asset('images/person.png') }}" alt="Icon" class="h-[38px] md:h-[50px] w-auto">
        <h2 class="text-[#001349] text-[20px] md:text-[24px] font-bold" style="font-family: Inter, sans-serif;">
            Inovator of the Month
        </h2>
    </div>

    <div class="mt-7 rounded-[30px] border-2 border-[#8D8585] bg-white p-6 md:p-8 grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 items-center
                transition-all duration-200 ease-out hover:-translate-y-1 hover:shadow-lg">
        <div class="h-[230px] md:h-[270px] w-full rounded-[30px] border-2 border-[#8D8585] grid place-content-center overflow-hidden">
            @if($innovatorMonth?->innovator?->photo)
                <img src="{{ asset('storage/' . $innovatorMonth->innovator->photo) }}" class="w-full h-full object-cover" alt="">
            @else
                <span class="text-[15px] md:text-[16px] text-gray-500" style="font-family: Inter, sans-serif;">Foto</span>
            @endif
        </div>

        <div class="md:col-span-2 text-[15px] md:text-[16px] font-light text-gray-800 leading-relaxed" style="font-family: Inter, sans-serif;">
            <div class="font-semibold text-[#001349] text-[18px] md:text-[20px]">
                {{ $innovatorMonth?->innovator?->name ?? 'Nama' }}
            </div>
            <div class="mt-2">{{ $innovatorMonth?->innovator?->faculty?->name ?? 'Fakultas' }}</div>
            <div class="mt-2">{{ \Illuminate\Support\Str::limit($innovatorMonth?->innovator?->bio ?? 'Deskripsi', 200) }}</div>

            <div class="mt-4">
                <a href="{{ route('innovator-month.show') }}"
                    class="inline-flex items-center justify-center
                            rounded-[30px] bg-[#001349]
                            px-6 py-2
                            text-white text-[14px] font-semibold
                            transition hover:bg-[#001349]/90">
                        Lihat Detail Inovator
                </a>
            </div>
        </div>
    </div>
</section>

{{-- NATIONAL INNOVATION RANKING --}}
<section class="mx-auto max-w-[1320px] px-3 md:px-4 mt-14 md:mt-16">
    {{-- Section Title --}}
    <div class="inline-flex items-center gap-3 md:gap-4 rounded-[30px] bg-white
                shadow-[0px_4px_8px_rgba(0,0,0,0.25)]
                px-6 md:px-10 py-4 md:py-6">
        <img src="{{ asset('images/Group 28.png') }}" alt="Icon" class="h-[38px] md:h-[50px] w-auto">
        <h2 class="text-[#001349] text-[20px] md:text-[24px] font-bold"
            style="font-family: Inter, sans-serif;">
            National Innovation Ranking
        </h2>
    </div>

    {{-- Ranking Cards --}}
    <div class="mt-7 grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
        @forelse($rankings as $rank)
            <div class="rounded-[30px] border-2 border-[#8D8585] bg-white
                        p-5 md:p-6
                        transition-all duration-200 ease-out
                        hover:-translate-y-1 hover:shadow-lg">

                {{-- Rank + Title --}}
                <div class="flex items-start gap-4">
                    <div class="text-[22px] md:text-[24px] font-bold text-[#001349]">
                        #{{ $rank->rank }}
                    </div>

                    <div>
                        <div class="text-[18px] md:text-[20px] font-semibold text-[#001349] leading-snug">
                            {{ $rank->innovation->title ?? '-' }}
                        </div>

                        <div class="mt-1 text-[13px] md:text-[14px] text-gray-700">
                            {{ $rank->innovation->category ?? 'Kategori' }}
                        </div>
                    </div>
                </div>

                {{-- Achievement --}}
                <div class="mt-3 text-[13px] md:text-[14px] text-gray-700 leading-relaxed">
                    {{ \Illuminate\Support\Str::limit($rank->achievement ?? 'Deskripsi', 100) }}
                </div>

                {{-- Action Button --}}
                <div class="mt-5">
                    <a href="{{ route('innovations.show', $rank->innovation_id ?? 1) }}"
                       class="inline-flex items-center justify-center
                              rounded-[30px] bg-[#001349]
                              px-6 py-2
                              text-white text-[14px] font-semibold
                              transition hover:bg-[#001349]/90">
                        Lihat Detail Inovasi
                    </a>
                </div>
            </div>
        @empty
            <div class="text-gray-600" style="font-family: Inter, sans-serif;">
                Data ranking belum ada.
            </div>
        @endforelse
    </div>
</section>


{{-- INNOVATION PRODUCTS --}}
<section class="mx-auto max-w-[1320px] px-3 md:px-4 mt-14 md:mt-16">
    <div class="inline-flex items-center gap-3 md:gap-4 rounded-[30px] bg-white shadow-[0px_4px_8px_rgba(0,0,0,0.25)] px-6 md:px-10 py-4 md:py-6">
        <img src="{{ asset('images/Box.png') }}" alt="Icon" class="h-[38px] md:h-[50px] w-auto">
        <h2 class="text-[#001349] text-[20px] md:text-[24px] font-bold" style="font-family: Inter, sans-serif;">
            Innovation Products
        </h2>
    </div>

    <div class="mt-7 grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
        @forelse($innovations as $inv)
            <div class="rounded-[30px] border-2 border-[#8D8585] bg-white overflow-hidden
                        transition-all duration-200 ease-out hover:-translate-y-1 hover:shadow-lg">
                <div class="h-[215px] md:h-[235px] bg-gray-200">
                    @if ($inv->image_url)
                        <img
                            src="{{ asset('storage/' . $inv->image_url) }}"
                            class="h-full w-full object-cover"
                            alt="Foto Produk Inovasi"
                        >
                    @else
                        <img src="https://placehold.co/450x300" class="h-full w-full object-cover">
                    @endif
                         
                </div>

                <div class="p-5 md:p-6">
                    <div class="text-[18px] md:text-[20px] font-semibold text-[#001349] leading-snug" style="font-family: Inter, sans-serif;">
                        {{ $inv->title }}
                    </div>

                    <div class="mt-1 text-[13px] md:text-[14px] font-semibold text-gray-800" style="font-family: Inter, sans-serif;">
                        {{ $inv->category }}
                    </div>

                    <p class="mt-2 text-[13px] md:text-[14px] text-gray-700 leading-relaxed" style="font-family: Inter, sans-serif;">
                        {{ \Illuminate\Support\Str::limit($inv->description, 100) }}
                    </p>

                    <div class="mt-4 flex items-center justify-between">
                        <span class="rounded-full bg-[#1A6ECE]/50 px-3 py-1.5 text-[12px] font-semibold text-[#1A6ECE]">
                            {{ ucfirst($inv->status) }}
                        </span>

                        <a class="text-[#1A6ECE] font-semibold text-[14px] hover:underline"
                           style="font-family: Inter, sans-serif;"
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

{{-- MOST VISITED --}}
<section class="mx-auto max-w-[1320px] px-3 md:px-4 mt-14 md:mt-16">
    <div class="inline-flex items-center gap-3 md:gap-4 rounded-[30px] bg-white shadow-[0px_4px_8px_rgba(0,0,0,0.25)] px-6 md:px-10 py-4 md:py-6">
        <img src="{{ asset('images/Arrow up-right.png') }}" alt="Icon" class="h-[40px] md:h-[52px] w-auto">
        <h2 class="text-[#001349] text-[20px] md:text-[24px] font-bold" style="font-family: Inter, sans-serif;">
            Most Visited Innovations
        </h2>
    </div>

    <div class="mt-7 space-y-4 md:space-y-5">
        @foreach($mostVisited as $inv)
            <div class="rounded-[30px] border-2 border-[#8D8585] bg-white p-5 md:p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-5
                        transition-all duration-200 ease-out hover:-translate-y-1 hover:shadow-lg">

                <div class="flex items-start md:items-center gap-5">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/Eye.png') }}" alt="Views" class="h-6 w-6 opacity-70" />
                        <div class="text-[18px] md:text-[20px] font-semibold text-gray-800" style="font-family: Inter, sans-serif;">
                            {{ number_format($inv->views_count ?? 0, 0, ',', '.') }}
                        </div>
                    </div>

                    <div style="font-family: Inter, sans-serif;">
                        <div class="text-[16px] md:text-[18px] font-semibold text-[#001349] leading-snug">
                            {{ $inv->title }}
                        </div>
                        <div class="mt-1 text-[13px] md:text-[14px] text-gray-700 leading-relaxed">
                            {{ $inv->category }} <br>
                            {{ \Illuminate\Support\Str::limit($inv->description, 110) }}
                        </div>
                    </div>
                </div>

                <a href="{{ route('innovations.show', $inv->id) }}"
                    class="inline-flex items-center justify-center
                            rounded-[30px] bg-[#001349]
                            px-6 py-2
                            text-white text-[14px] font-semibold
                            transition hover:bg-[#001349]/90">
                        Lihat Detail Inovasi
                </a>
            </div>
        @endforeach
    </div>
</section>

@endsection
