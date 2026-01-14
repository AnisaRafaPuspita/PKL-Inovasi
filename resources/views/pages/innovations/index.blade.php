@extends('layouts.app')
@section('title', 'Pencarian Inovasi')

@section('content')
<section class="mx-auto max-w-[1320px] px-4 mt-10">

    {{-- SEARCH BAR --}}
    <form id="searchForm"
          action="{{ route('innovations.index') }}"
          method="GET"
          class="mx-auto max-w-[1000px]
                 flex items-center gap-3
                 rounded-full bg-[#F2F2F2]
                 px-4 py-3">

        {{-- Input search --}}
        <input
            name="q"
            value="{{ request('q') }}"
            placeholder="Cari inovasi..."
            class="flex-1 bg-transparent outline-none px-3 text-[15px]"
        >

        {{-- Tombol filter --}}
        <button type="button"
            onclick="toggleFilter()"
            class="p-2 rounded-full hover:bg-gray-200">
            {{-- Icon filter --}}
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5 text-gray-600"
                 fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 13.414V19a1 1 0 01-.447.832l-4 2.667A1 1 0 018 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
            </svg>
        </button>

        {{-- Tombol cari --}}
        <button type="submit"
            class="rounded-full bg-[#001349]
                   px-6 py-2
                   text-white font-semibold">
            Cari
        </button>
    </form>

    {{-- ADVANCED FILTER (HIDDEN) --}}
    <div id="advancedFilter"
         class="mx-auto max-w-[1000px]
                mt-4 hidden
                rounded-[20px]
                bg-[#F9F9F9]
                px-6 py-4">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Kategori --}}
            <select name="category" form="searchForm"
                class="rounded-full px-5 py-2 outline-none">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}"
                        @selected(request('category') == $cat)>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>

            {{-- Fakultas --}}
            <select name="faculty_id" form="searchForm"
                class="rounded-full px-5 py-2 outline-none">
                <option value="">Semua Fakultas</option>
                @foreach ($faculties as $faculty)
                    <option value="{{ $faculty->id }}"
                        @selected(request('faculty_id') == $faculty->id)>
                        {{ $faculty->name }}
                    </option>
                @endforeach
            </select>

        </div>
    </div>

    {{-- HASIL SEARCH --}}
    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse ($innovations as $inv)
            <div class="rounded-[24px] border bg-white overflow-hidden
                        transition hover:-translate-y-1 hover:shadow-lg">

                {{-- Foto --}}
                {{-- IMAGE CARD SLIDER --}}
                <div class="relative h-[215px] overflow-hidden rounded-t-[30px]">

                    @php
                        // kumpulin gambar: prioritas images(), fallback image_url
                        $images = $inv->images->count()
                            ? $inv->images
                            : ($inv->image_url
                                ? collect([(object)['image_path' => $inv->image_url]])
                                : collect());
                    @endphp

                    {{-- SLIDER --}}
                    <div
                        id="slider-{{ $inv->id }}"
                        class="flex h-full transition-transform duration-300 ease-in-out"
                        data-index="0"
                    >
                        @foreach ($images as $img)
                            <img
                                src="{{ asset('storage/' . $img->image_path) }}"
                                class="min-w-full h-full object-cover"
                                alt="Foto Inovasi"
                            >
                        @endforeach
                    </div>

                    {{-- PANAH (MUNCUL HANYA JIKA FOTO > 1) --}}
                    @if ($images->count() > 1)
                        {{-- kiri --}}
                        <button
                            type="button"
                            class="slide-btn absolute left-2 top-1/2 -translate-y-1/2
                                bg-black/50 text-white w-8 h-8 rounded-full
                                flex items-center justify-center
                                z-10 pointer-events-auto"
                            data-id="{{ $inv->id }}"
                            data-dir="-1"
                        >
                            &lsaquo;
                        </button>

                        {{-- kanan --}}
                        <button
                            type="button"
                            class="slide-btn absolute right-2 top-1/2 -translate-y-1/2
                                bg-black/50 text-white w-8 h-8 rounded-full
                                flex items-center justify-center
                                z-10 pointer-events-auto"
                            data-id="{{ $inv->id }}"
                            data-dir="1"
                        >
                            &rsaquo;
                        </button>
                    @endif
                </div>


                {{-- Konten --}}
                <div class="p-5">
                    <div class="text-[17px] font-semibold text-[#001349]">
                        {{ $inv->title }}
                    </div>

                    <div class="mt-1 text-[13px] font-semibold text-gray-700">
                        {{ $inv->category }}
                    </div>

                    <p class="mt-2 text-[13px] text-gray-600">
                        {{ \Illuminate\Support\Str::limit($inv->description, 100) }}
                    </p>

                    <a href="{{ route('innovations.show', $inv->id) }}"
                       class="inline-block mt-3 text-[#1A6ECE]
                              font-semibold text-[14px] hover:underline">
                        Lihat Detail →
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500">
                Tidak ada inovasi yang ditemukan.
            </p>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="mt-10">
        {{ $innovations->links() }}
    </div>

</section>

{{-- JS --}}
<script>
function toggleFilter() {
    document.getElementById('advancedFilter')
        .classList.toggle('hidden');
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
