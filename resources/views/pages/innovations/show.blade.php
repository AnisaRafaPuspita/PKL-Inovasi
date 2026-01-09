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
            <div class="h-[240px] md:h-[320px] rounded-[20px] bg-gray-100 grid place-content-center overflow-hidden">
                {{-- Kalau nanti ada image_url / foto, tinggal tampilkan di sini --}}
                @if(!empty($innovation->image_url))
                    <img src="{{ $innovation->image_url }}" class="w-full h-full object-cover" alt="Foto Inovasi">
                @else
                    <span class="text-[18px] md:text-[20px] font-light text-gray-500"
                          style="font-family: Inter, sans-serif;">Foto</span>
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
                    <span class="font-semibold text-[#001349]">Status HKI:</span>
                    <span class="font-light">{{ $innovation->hki_status ?? '-' }}</span>
                </p>

                <p class="mt-3">
                    <span class="font-semibold text-[#001349]">Video URL:</span>
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
@endsection
