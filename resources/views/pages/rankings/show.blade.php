@extends('layouts.app')
@section('title', $ranking->achievement)

@section('content')
<section class="mx-auto max-w-[1200px] px-4 md:px-6 mt-10 md:mt-12">

    {{-- HEADER --}}
    <div class="flex items-start gap-4">

        {{-- LOGO --}}
        <div class="w-[72px] h-[72px] rounded-full border border-gray-300
                    overflow-hidden bg-gray-100 flex items-center justify-center">
            @if($ranking->logo)
                <img src="{{ asset('storage/'.$ranking->logo) }}"
                     class="w-full h-full object-cover"
                     alt="Logo">
            @else
                <span class="text-[11px] text-gray-400 text-center px-2">
                    No Logo
                </span>
            @endif
        </div>

        {{-- TITLE --}}
        <div>
            <h1 class="text-[#001349] text-[24px] md:text-[32px] font-semibold leading-tight"
                style="font-family: Inter, sans-serif;">
                {{ $ranking->achievement }}
            </h1>

            <div class="mt-2 inline-flex items-center gap-2">
                <span class="rounded-full bg-[#1A6ECE]/20
                             px-4 py-1 text-[#1A6ECE]
                             text-[13px] font-semibold">
                    Peringkat #{{ $ranking->rank }}
                </span>
            </div>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="mt-8 flex flex-col lg:flex-row gap-6 items-start">

        {{-- IMAGE SLIDER --}}
        @if($ranking->photos->count())
        <div class="relative w-full lg:w-1/2">

            {{-- SLIDER WRAPPER --}}
            <div class="relative w-full overflow-hidden rounded-[24px]">

                {{-- TRACK --}}
                <div
                    id="slider-{{ $ranking->id }}"
                    class="flex transition-transform duration-300 ease-in-out"
                    data-index="0"
                >
                    @foreach ($ranking->photos as $img)
                        <div class="w-full flex-shrink-0 flex justify-center items-center bg-gray-50">
                            <img
                                src="{{ asset('storage/'.$img->path) }}"
                                alt="Pamflet Ranking"
                                class="max-w-full max-h-[560px] object-contain rounded-[18px] shadow"
                            >
                        </div>
                    @endforeach
                </div>

                {{-- NAVIGATION --}}
                @if ($ranking->photos->count() > 1)
                    <button
                        class="slide-btn absolute left-3 top-1/2 -translate-y-1/2
                            bg-black/40 hover:bg-black/60 text-white
                            w-10 h-10 rounded-full flex items-center justify-center
                            transition"
                        data-id="{{ $ranking->id }}"
                        data-dir="-1"
                    >
                        &#10094;
                    </button>

                    <button
                        class="slide-btn absolute right-3 top-1/2 -translate-y-1/2
                            bg-black/40 hover:bg-black/60 text-white
                            w-10 h-10 rounded-full flex items-center justify-center
                            transition"
                        data-id="{{ $ranking->id }}"
                        data-dir="1"
                    >
                        &#10095;
                    </button>
                @endif
            </div>
        </div>
        @endif





        {{-- DETAIL --}}
        <div class="w-full lg:w-1/2 h-fit">
            <div class="rounded-[30px] border border-[#8D8585]
                    bg-[#F9FAFB] p-6 md:p-8 h-fit shadow-sm">
                <div class="text-[14px] md:text-[18px] leading-relaxed"
                    style="font-family: Inter, sans-serif;">

                    @if($ranking->reference_link)
                        <p class="mb-3">
                            <span class="font-semibold text-[#001349]">Sumber:</span><br>
                            <a href="{{ $ranking->reference_link }}"
                            target="_blank"
                            class="text-[#1A6ECE] font-semibold hover:underline break-all">
                                {{ $ranking->reference_link }}
                            </a>
                        </p>
                    @endif

                    <p class="mt-4">
                        <span class="font-semibold text-[#001349]">Deskripsi:</span>
                    </p>

                    <p class="mt-2 text-gray-800 font-light">
                        {{ $ranking->description ?? '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection

@push('scripts')
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
@endpush


