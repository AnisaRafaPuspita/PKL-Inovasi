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
                        px-8 py-2.5
                        text-white font-bold
                        text-[16px] md:text-[17px]
                        transition duration-200
                        hover:bg-[#001349]/80 hover:-translate-y-[1px]"
                style="font-family: Inter, sans-serif;">
                    <span>Upload Produk</span>
                    <img src="{{ asset('images/add_circle.png') }}"
                        alt="Add Icon"
                        class="h-[18px] w-[18px] shrink-0
                                transition-transform duration-200
                                group-hover:translate-x-1">
                </a>

                {{-- Selengkapnya --}}
                <a href="{{ route('about') }}"
                class="group inline-flex items-center justify-center gap-2
                        rounded-[10px] border-2 border-white bg-[#001349]/60
                        px-8 py-2.5
                        text-white font-bold
                        text-[16px] md:text-[17px]
                        transition duration-200
                        hover:bg-[#001349]/80 hover:-translate-y-[1px]"
                style="font-family: Inter, sans-serif;">
                    <span>Tentang Inovasi</span>
                    <img src="{{ asset('images/Arrow 4.png') }}"
                        alt="Arrow Icon"
                        class="h-[14px] w-[14px] shrink-0
                                transition-transform duration-200
                                group-hover:translate-x-1">
                </a>

            </div>
        </div>
    </div>
</section>

{{-- SEARCH HOME --}}
<section class="mx-auto max-w-[1320px] px-3 md:px-4 -mt-10 relative z-10">

    <form id="homeSearchForm"
          action="{{ route('innovations.index') }}"
          method="GET"
          autocomplete="off"
          class="rounded-[30px] bg-[#D9D9D9]/40 px-5 md:px-6 py-3.5 md:py-4">

        {{-- BAR SEARCH --}}
        <div class="flex items-center gap-4">

            {{-- icon search --}}
            <img src="{{ asset('images/search.png') }}"
                 alt="Search"
                 class="h-5 w-5 md:h-6 md:w-6 shrink-0">

            {{-- input --}}
            <input
                type="search"
                name="q"
                value="{{ request('q') }}"
                autocomplete="off"
                placeholder="Cari inovasi..."
                class="flex-1 bg-transparent outline-none
                       text-[15px] md:text-[16px] font-semibold
                       placeholder:text-black/60"
                style="font-family: Inter, sans-serif;"
            >

            {{-- tombol filter --}}
            <button type="button"
                    id="homeFilterToggle"
                    class="inline-flex items-center justify-center
                           h-9 w-9 rounded-full
                           hover:bg-black/10 transition"
                    aria-label="Filter">
                <img src="{{ asset('images/Filter.png') }}"
                     alt="Filter"
                     class="h-5 w-5">
            </button>

            {{-- submit (hidden) --}}
            <button type="submit" class="sr-only">Cari</button>
        </div>

        {{-- FILTER ADVANCED --}}
        <div id="homeFilter"
             class="mt-4 hidden
                    rounded-[20px] bg-white
                    px-4 md:px-6 py-4
                    shadow">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- kategori --}}
                <select name="category"
                        autocomplete="off"
                        class="h-[42px] rounded-full px-5
                               border border-[#001349]/30
                               outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach (config('innovation.categories') as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>

                {{-- fakultas --}}
                <select name="faculty_id"
                        autocomplete="off"
                        class="h-[42px] rounded-full px-5
                               border border-[#001349]/30
                               outline-none">
                    <option value="">Semua Fakultas</option>
                    @foreach ($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                    @endforeach
                </select>

                {{-- innovator --}}
                <select name="innovator_id"
                        autocomplete="off"
                        class="h-[42px] rounded-full px-5
                            border border-[#001349]/30
                            outline-none">
                    <option value="">Semua Innovator</option>
                    @foreach ($innovators as $innovator)
                        <option value="{{ $innovator->id }}"
                            {{ request('innovator_id') == $innovator->id ? 'selected' : '' }}>
                            {{ $innovator->name }}
                        </option>
                    @endforeach
                </select>


            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                <button type="submit"
                        class="w-full rounded-full
                               bg-[#001349] py-2.5
                               text-white font-semibold">
                    Cari
                </button>

                <a href="{{ route('innovations.index') }}"
                   class="w-full rounded-full
                          bg-gray-200 py-2.5
                          text-gray-800 font-semibold text-center">
                    Reset
                </a>
            </div>
        </div>
    </form>
</section>

{{-- JS FINAL --}}
<script>
(function () {
    const filter = document.getElementById('homeFilter');
    const toggle = document.getElementById('homeFilterToggle');

    // toggle filter
    toggle.addEventListener('click', function () {
        filter.classList.toggle('hidden');
    });

    // auto-open filter jika ada filter aktif
    const hasActiveFilter =
        new URLSearchParams(window.location.search).get('category') ||
        new URLSearchParams(window.location.search).get('faculty_id')||
        new URLSearchParams(window.location.search).get('innovator_id');

    if (hasActiveFilter && filter) {
        filter.classList.remove('hidden');
    }
})();
</script>

@php
    // helper untuk path gambar: bisa dari relasi images atau legacy image_url
    $resolveImageSrc = function ($imgPathOrUrl) {
        if (!$imgPathOrUrl) return null;

        // kalau sudah URL lengkap
        if (preg_match('/^https?:\/\//i', $imgPathOrUrl)) return $imgPathOrUrl;

        // kalau sudah diawali storage/
        if (str_starts_with($imgPathOrUrl, 'storage/')) return asset($imgPathOrUrl);

        // normal case: simpan di storage disk public
        return asset('storage/' . ltrim($imgPathOrUrl, '/'));
    };
@endphp

{{-- INOVASI BERDAMPAK --}}
<section class="mx-auto max-w-[1320px] px-3 md:px-4 mt-12 md:mt-14">
    <div class="mx-auto max-w-[800px]
                rounded-[30px] bg-white
                shadow-[0px_4px_8px_rgba(0,0,0,0.25)]
                px-10 py-4
                flex items-center justify-center gap-5">

        <img src="{{ asset('images/Logo Undip Universitas Diponegoro.png') }}"
             class="h-[56px] md:h-[64px]" alt="">

        <h2 class="text-[22px] md:text-[26px] font-semibold text-[#001349]">
            Inovasi Berdampak
        </h2>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
        @forelse ($impactInnovations as $index => $inv)
            <div class="impact-item {{ $index >= 6 ? 'hidden' : '' }}">
                <div class="rounded-[30px] border-2 border-[#8D8585] bg-white overflow-hidden
                            transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">

                    {{-- IMAGE CARD SLIDER --}}
                    <div class="relative h-[215px] overflow-hidden rounded-t-[30px]">

                        @php
                            $images = $inv->images->count()
                                ? $inv->images->map(fn($i) => (object)['image_path' => $i->image_path])
                                : ($inv->image_url
                                    ? collect([(object)['image_path' => $inv->image_url]])
                                    : collect());

                            $hasImages = $images->count() > 0;
                        @endphp

                        @if($hasImages)
                            {{-- SLIDER --}}
                            <div
                                id="slider-{{ $inv->id }}"
                                class="flex h-full transition-transform duration-300 ease-in-out"
                                data-index="0"
                            >
                                @foreach ($images as $img)
                                    @php $src = $resolveImageSrc($img->image_path); @endphp
                                    <img
                                        src="{{ $src }}"
                                        class="min-w-full h-full object-cover"
                                        alt="Foto Inovasi"
                                    >
                                @endforeach
                            </div>

                            {{-- PANAH (MUNCUL HANYA JIKA FOTO > 1) --}}
                            @if ($images->count() > 1)
                                <button type="button"
                                    class="slide-btn absolute left-2 top-1/2 -translate-y-1/2
                                        bg-black/50 text-white w-8 h-8 rounded-full
                                        flex items-center justify-center"
                                    data-id="{{ $inv->id }}"
                                    data-dir="-1">
                                    &lsaquo;
                                </button>

                                <button type="button"
                                    class="slide-btn absolute right-2 top-1/2 -translate-y-1/2
                                        bg-black/50 text-white w-8 h-8 rounded-full
                                        flex items-center justify-center"
                                    data-id="{{ $inv->id }}"
                                    data-dir="1">
                                    &rsaquo;
                                </button>
                            @endif
                        @else
                            {{-- Placeholder kalau tidak ada gambar --}}
                            <div class="h-full w-full bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                                No Image
                            </div>
                        @endif
                    </div>

                    <div class="p-5 md:p-6">
                        <div class="text-[18px] md:text-[20px] font-semibold text-[#001349]">
                            {{ $inv->title }}
                        </div>

                        <div class="mt-1 text-[13px] font-semibold text-gray-800">
                            {{ $inv->category }}
                        </div>

                        <p class="mt-2 text-[13px] text-gray-700">
                            {{ \Illuminate\Support\Str::limit($inv->description, 100) }}
                        </p>

                        <div class="mt-4 flex items-center justify-between">
                            <span class="rounded-full bg-[#1A6ECE]/50 px-3 py-1.5 text-[12px] font-semibold text-[#1A6ECE]">
                                {{ ucfirst($inv->status) }}
                            </span>

                            <a href="{{ route('innovations.show', $inv->id) }}"
                               class="text-[#1A6ECE] font-semibold text-[14px] hover:underline">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-gray-600 col-span-1 md:col-span-3">
                Belum ada inovasi berdampak.
            </div>
        @endforelse
    </div>

    @if (isset($impactInnovations) && $impactInnovations->count() > 6)
        <div class="mt-8 text-center">
            <button type="button" onclick="showMoreImpact()"
                class="rounded-full bg-[#001349] px-8 py-2 text-white font-semibold">
                Selengkapnya
            </button>
        </div>
    @endif
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
        <div class="h-[230px] md:h-[270px] w-full rounded-[30px] border-2 border-[#8D8585] flex items-center justify-center overflow-hidden">
            @if($innovatorMonth?->photo)
                <img
                src="{{ asset('storage/'.$innovatorMonth->photo) }}"
                alt="Innovator of the Month"
                class="max-w-full max-h-full object-contain"
                >
            @else
                <div class="text-gray-400">No photo</div>
            @endif
        </div>



        <div class="md:col-span-2 text-[15px] md:text-[16px] font-light text-gray-800 leading-relaxed" style="font-family: Inter, sans-serif;">
            <div class="font-semibold text-[#001349] text-[18px] md:text-[20px]">
                {{ $innovatorMonth?->innovator?->name ?? 'Nama' }}
            </div>
            <div class="mt-2">{{ $innovatorMonth?->innovator?->faculty?->name ?? 'Fakultas' }}</div>
            <div class="mt-2">
            {{ \Illuminate\Support\Str::limit($innovatorMonth?->description ?? 'Deskripsi', 200) }}
            </div>

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

    {{-- HEADER --}}
    <div class="inline-flex items-center gap-3 md:gap-4 rounded-[30px] bg-white
                shadow-[0px_4px_8px_rgba(0,0,0,0.25)]
                px-6 md:px-10 py-4 md:py-6">
        <img src="{{ asset('images/Group 28.png') }}"
             class="h-[38px] md:h-[50px] w-auto"
             alt="Icon">
        <h2 class="text-[#001349] text-[20px] md:text-[24px] font-bold"
            style="font-family: Inter, sans-serif;">
            National Innovation Ranking
        </h2>
    </div>

    {{-- CARDS --}}
    <div class="mt-7 grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">

        @forelse($rankings as $rank)
            <div class="rounded-[30px] border-2 border-[#8D8585] bg-white
                        p-5 md:p-6
                        transition-all duration-200
                        hover:-translate-y-1 hover:shadow-lg">

                <div class="flex items-start justify-between gap-4">

                    {{-- LEFT --}}
                    <div class="flex items-start gap-4">
                        <div class="text-[22px] md:text-[24px] font-bold text-[#001349]">
                            #{{ $rank->rank }}
                        </div>

                        <div>
                            <div class="text-[18px] md:text-[20px] font-semibold text-[#001349] leading-snug">
                                {{ $rank->achievement }}
                            </div>

                            <div class="mt-2 text-[13px] md:text-[14px] text-gray-700 leading-relaxed">
                                {{ \Illuminate\Support\Str::limit($rank->description, 80) }}
                            </div>
                        </div>
                    </div>

                    {{-- LOGO --}}
                    <div class="w-[64px] h-[64px] md:w-[72px] md:h-[72px]
                                rounded-full border border-gray-300
                                overflow-hidden bg-gray-100 flex items-center justify-center
                                flex-shrink-0">
                        @if($rank->logo)
                            <img src="{{ asset('storage/'.$rank->logo) }}"
                                 class="w-full h-full object-cover"
                                 alt="Logo">
                        @else
                            <span class="text-[11px] text-gray-400 text-center px-2">
                                No Logo
                            </span>
                        @endif
                    </div>
                </div>

                {{-- BUTTON --}}
                <div class="mt-5">
                    <a href="{{ route('rankings.show', $rank->id) }}"
                       class="inline-flex items-center justify-center
                              rounded-[30px] bg-[#001349]
                              px-6 py-2
                              text-white text-[14px] font-semibold
                              transition hover:bg-[#001349]/90">
                        Lihat Detail Ranking
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 py-10">
                Data ranking belum tersedia.
            </div>
        @endforelse

    </div>
</section>



{{-- INNOVATION PRODUCTS --}}
<section class="mx-auto max-w-[1320px] px-3 md:px-4 mt-14 md:mt-16">
    <div class="inline-flex items-center gap-3 rounded-[30px] bg-white
                shadow-[0px_4px_8px_rgba(0,0,0,0.25)]
                px-6 md:px-10 py-4 md:py-6">

        <img src="{{ asset('images/Box.png') }}" class="h-[38px] md:h-[50px]" alt="">
        <h2 class="text-[#001349] text-[20px] md:text-[24px] font-bold">
            Innovation Products
        </h2>
    </div>

    <div class="mt-7 grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
        @forelse ($innovations as $index => $inv)
            <div class="product-item {{ $index >= 6 ? 'hidden' : '' }}">
                <div class="rounded-[30px] border-2 border-[#8D8585] bg-white overflow-hidden
                            transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">

                    <div class="relative h-[215px] overflow-hidden rounded-t-[30px]">

                        @php
                            $images = $inv->images->count()
                                ? $inv->images->map(fn($i) => (object)['image_path' => $i->image_path])
                                : ($inv->image_url
                                    ? collect([(object)['image_path' => $inv->image_url]])
                                    : collect());

                            $hasImages = $images->count() > 0;
                        @endphp

                        @if($hasImages)
                            <div
                                id="slider-{{ $inv->id }}"
                                class="flex h-full transition-transform duration-300 ease-in-out"
                                data-index="0"
                            >
                                @foreach ($images as $img)
                                    @php $src = $resolveImageSrc($img->image_path); @endphp
                                    <img
                                        src="{{ $src }}"
                                        class="min-w-full h-full object-cover"
                                        alt="Foto Inovasi"
                                    >
                                @endforeach
                            </div>

                            @if ($images->count() > 1)
                                <button type="button"
                                    class="slide-btn absolute left-2 top-1/2 -translate-y-1/2
                                        bg-black/50 text-white w-8 h-8 rounded-full
                                        flex items-center justify-center"
                                    data-id="{{ $inv->id }}"
                                    data-dir="-1">
                                    &lsaquo;
                                </button>

                                <button type="button"
                                    class="slide-btn absolute right-2 top-1/2 -translate-y-1/2
                                        bg-black/50 text-white w-8 h-8 rounded-full
                                        flex items-center justify-center"
                                    data-id="{{ $inv->id }}"
                                    data-dir="1">
                                    &rsaquo;
                                </button>
                            @endif
                        @else
                            <div class="h-full w-full bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                                No Image
                            </div>
                        @endif
                    </div>

                    <div class="p-5 md:p-6">
                        <div class="text-[18px] md:text-[20px] font-semibold text-[#001349]">
                            {{ $inv->title }}
                        </div>

                        <div class="mt-1 text-[13px] font-semibold text-gray-800">
                            {{ $inv->category }}
                        </div>

                        <p class="mt-2 text-[13px] text-gray-700">
                            {{ \Illuminate\Support\Str::limit($inv->description, 100) }}
                        </p>

                        <div class="mt-4 flex items-center justify-between">
                            <span class="rounded-full bg-[#1A6ECE]/50 px-3 py-1.5 text-[12px] font-semibold text-[#1A6ECE]">
                                {{ ucfirst($inv->status) }}
                            </span>

                            <a href="{{ route('innovations.show', $inv->id) }}"
                               class="text-[#1A6ECE] font-semibold text-[14px] hover:underline">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-gray-600 col-span-1 md:col-span-3">
                Belum ada innovation products.
            </div>
        @endforelse
    </div>

    @if (method_exists($innovations, 'count') && $innovations->count() > 6)
        <div class="mt-8 text-center">
            <button type="button" onclick="showMoreProduct()"
                class="rounded-full bg-[#001349] px-8 py-2 text-white font-semibold">
                Selengkapnya
            </button>
        </div>
    @endif

    {{-- kalau kamu mau pagination beneran, tinggal buka ini:
    <div class="mt-8">
        {{ $innovations->links() }}
    </div>
    --}}
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
        @forelse($mostVisited as $inv)
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
        @empty
            <div class="text-gray-600">Belum ada data most visited.</div>
        @endforelse
    </div>
</section>

<script>
function showMoreImpact() {
    document.querySelectorAll('.impact-item.hidden')
        .forEach(el => el.classList.remove('hidden'));
}

function showMoreProduct() {
    document.querySelectorAll('.product-item.hidden')
        .forEach(el => el.classList.remove('hidden'));
}
</script>

<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.slide-btn');
    if (!btn) return;

    const id = btn.dataset.id;
    const direction = parseInt(btn.dataset.dir);

    slideCard(id, direction);
});

function slideCard(id, direction) {
    const slider = document.getElementById('slider-' + id);
    if (!slider) return;

    const total = slider.children.length;
    let index = parseInt(slider.dataset.index || 0);

    index = (index + direction + total) % total;
    slider.dataset.index = index;

    slider.style.transform = `translateX(-${index * 100}%)`;
}
</script>

@endsection
