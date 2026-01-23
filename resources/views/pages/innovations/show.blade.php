@extends('layouts.app')
@section('title', $innovation->title)

@section('content')
<section class="mx-auto max-w-[1200px] px-4 md:px-6 mt-10 md:mt-12">

    {{-- Title --}}
    <h1 class="text-[#001349] text-[24px] md:text-[32px] font-semibold leading-tight"
        style="font-family: Inter, sans-serif;">
        {{ $innovation->title }}
    </h1>

    {{-- Meta (status + category) --}}
    <div class="mt-4 flex flex-wrap items-center gap-3">
        <span class="rounded-full bg-[#1A6ECE]/50 px-4 py-2 text-[13px] md:text-[14px] font-semibold text-[#1A6ECE]"
              style="font-family: Inter, sans-serif;">
            {{ ucfirst($innovation->status) }}
        </span>

        <span class="text-[13px] md:text-[14px] text-gray-800"
              style="font-family: Inter, sans-serif;">
            {{ $innovation->category }}
        </span>
    </div>

    {{-- Main grid --}}
    <div class="mt-6 md:mt-8 grid grid-cols-1 lg:grid-cols-2 gap-5 md:gap-6">

        {{-- Image card --}}
        <div class="rounded-[30px] border border-[#8D8585] bg-white p-6 md:p-8">
            {{-- IMAGE CARD SLIDER --}}
            <div class="relative h-[215px] overflow-hidden rounded-[20px] bg-gray-100">

                @php
                    $images = $innovation->images->count()
                        ? $innovation->images
                        : ($innovation->image_url
                            ? collect([(object)['image_path' => $innovation->image_url]])
                            : collect());
                @endphp

                <div
                    id="slider-{{ $innovation->id }}"
                    class="flex h-full transition-transform duration-300 ease-in-out"
                    data-index="0"
                >
                    @foreach ($images as $img)
                        <div class="min-w-full h-full flex items-center justify-center">
                            <img
                                src="{{ asset('storage/' . $img->image_path) }}"
                                class="max-w-full max-h-full object-contain"
                                alt="Foto Inovasi"
                            >
                        </div>
                    @endforeach
                </div>

                @if ($images->count() > 1)
                    <button
                        type="button"
                        class="slide-btn absolute left-2 top-1/2 -translate-y-1/2
                            bg-black/50 text-white w-8 h-8 rounded-full
                            flex items-center justify-center z-10"
                        data-id="{{ $innovation->id }}"
                        data-dir="-1"
                    >
                        &lsaquo;
                    </button>

                    <button
                        type="button"
                        class="slide-btn absolute right-2 top-1/2 -translate-y-1/2
                            bg-black/50 text-white w-8 h-8 rounded-full
                            flex items-center justify-center z-10"
                        data-id="{{ $innovation->id }}"
                        data-dir="1"
                    >
                        &rsaquo;
                    </button>
                @endif
            </div>



        </div>

        {{-- Detail card --}}
        <div class="rounded-[30px] border border-[#8D8585] bg-white p-6 md:p-8">
            <div class="text-[14px] md:text-[16px] leading-relaxed"
                 style="font-family: Inter, sans-serif;">

                <p>
                    <span class="font-semibold text-[#001349]">Kategori:</span>
                    <span class="font-light">{{ $innovation->category }}</span>
                </p>

                <p class="mt-3">
                    <span class="font-semibold text-[#001349]">Judul:</span>
                    <span class="font-light">{{ $innovation->title }}</span>
                </p>

                <p class="mt-3">
                    <span class="font-semibold text-[#001349]">Mitra:</span>
                    <span class="font-light">{{ $innovation->partner ?? '-' }}</span>
                </p>

                <p class="mt-3">
                    <span class="font-semibold text-[#001349]">Status Paten:</span>
                    <span class="font-light">{{ $innovation->hki_status ?? '-' }}</span>
                </p>

                <p class="mt-3">
                    <span class="font-semibold text-[#001349]">Link Inovasi:</span>
                    @if($innovation->video_url)
                        <a class="font-semibold text-[#1A6ECE] hover:underline break-all"
                           href="{{ $innovation->video_url }}" target="_blank" rel="noopener">
                            {{ $innovation->video_url }}
                        </a>
                    @else
                        <span class="font-light">-</span>
                    @endif
                </p>

                <div class="mt-5">
                    <div class="font-semibold text-[#001349]">Inovator:</div>
                    <ul class="mt-2 list-disc pl-6 font-light space-y-1">
                        @foreach($innovation->innovators as $innovator)
                            <li>{{ $innovator->name }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    </div>

    {{-- Description --}}
    <div class="mt-8 rounded-[30px] border border-[#8D8585] bg-white p-6 md:p-8">
        <div class="text-[#001349] text-[18px] md:text-[22px] font-semibold"
             style="font-family: Inter, sans-serif;">
            Deskripsi
        </div>
        <div class="mt-3 text-[14px] md:text-[16px] font-light text-gray-800 leading-relaxed"
             style="font-family: Inter, sans-serif;">
            {{ $innovation->description }}
        </div>
    </div>

    {{-- Advantages --}}
    <div class="mt-6 rounded-[30px] border border-[#8D8585] bg-white p-6 md:p-8">
        <div class="text-[#001349] text-[18px] md:text-[22px] font-semibold"
             style="font-family: Inter, sans-serif;">
            Keunggulan
        </div>
        <div class="mt-3 text-[14px] md:text-[16px] font-light text-gray-800 leading-relaxed"
             style="font-family: Inter, sans-serif;">
            {{ $innovation->advantages ?? '-' }}
        </div>
    </div>

    {{-- KEBERDAMPAKAN (HANYA JIKA ADA) --}}
    @if ($innovation->is_impact)
        <div class="mt-6 rounded-[30px] border border-[#8D8585] bg-white p-6 md:p-8">
            <div class="text-[#001349] text-[18px] md:text-[22px] font-semibold"
            style="font-family: Inter, sans-serif;">
                Keberdampakan
            </div>
            <div class="mt-3 text-[14px] md:text-[16px] font-light text-gray-800 leading-relaxed"
                style="font-family: Inter, sans-serif;">
                {{ $innovation->impact ?? '-' }}
            </div>
        </div>
    @endif

    {{-- Stats --}}
    <div class="mt-6 rounded-[30px] border border-[#8D8585] bg-white p-6 md:p-8">
        <div class="text-[#001349] text-[18px] md:text-[22px] font-semibold"
             style="font-family: Inter, sans-serif;">
            Visit Statistics
        </div>
        <div class="mt-3 text-[14px] md:text-[16px] font-light text-gray-800"
             style="font-family: Inter, sans-serif;">
            {{ number_format($innovation->views_count ?? 0, 0, ',', '.') }} total views
        </div>
    </div>

</section>

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
