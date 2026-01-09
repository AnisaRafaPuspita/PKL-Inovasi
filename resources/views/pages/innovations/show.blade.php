@extends('layouts.app')
@section('title', $innovation->title)

@section('content')
<section class="mx-auto max-w-[1512px] px-6 mt-12">
    <h1 class="text-[#001349] text-[28px] md:text-[35px] font-semibold" style="font-family: Inter, sans-serif;">
        {{ $innovation->title }}
    </h1>

    <div class="mt-6 flex items-center gap-4">
        <span class="rounded-full bg-[#1A6ECE]/50 px-6 py-3 text-[20px] text-[#1A6ECE]" style="font-family: Inter, sans-serif;">
            {{ ucfirst($innovation->status) }}
        </span>
        <span class="text-[20px]" style="font-family: Inter, sans-serif;">
            {{ $innovation->category }}
        </span>
    </div>

    <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div class="rounded-[30px] border border-[#8D8585] bg-white p-10">
            <div class="h-[420px] rounded-[20px] bg-gray-100 grid place-content-center">
                <span class="text-[35px] font-light" style="font-family: Inter, sans-serif;">Foto</span>
            </div>
        </div>

        <div class="rounded-[30px] border border-[#8D8585] bg-white p-10">
            <div class="text-[20px] md:text-[30px]" style="font-family: Inter, sans-serif;">
                <p><span class="font-semibold text-[#001349]">Kategori:</span> <span class="font-light">{{ $innovation->category }}</span></p>
                <p class="mt-4"><span class="font-semibold text-[#001349]">Judul:</span> <span class="font-light">{{ $innovation->title }}</span></p>
                <p class="mt-4"><span class="font-semibold text-[#001349]">Mitra:</span> <span class="font-light">{{ $innovation->partner ?? '-' }}</span></p>
                <p class="mt-4"><span class="font-semibold text-[#001349]">Status HKI:</span> <span class="font-light">{{ $innovation->hki_status ?? '-' }}</span></p>

                <p class="mt-4">
                    <span class="font-light">Video URL:</span>
                    @if($innovation->video_url)
                        <a class="underline font-light" href="{{ $innovation->video_url }}" target="_blank" rel="noopener">
                            {{ $innovation->video_url }}
                        </a>
                    @else
                        <span class="font-light">-</span>
                    @endif
                </p>

                <div class="mt-6">
                    <div class="font-semibold text-[#001349]">Inovator:</div>
                    <ul class="mt-2 list-disc pl-6 font-light">
                        @foreach($innovation->innovators as $innovator)
                            <li>{{ $innovator->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-10 rounded-[30px] border border-[#8D8585] bg-white p-10">
        <div class="text-[#001349] text-[26px] md:text-[35px] font-semibold" style="font-family: Inter, sans-serif;">Deskripsi</div>
        <div class="mt-6 text-[22px] md:text-[35px] font-light" style="font-family: Inter, sans-serif;">
            {{ $innovation->description }}
        </div>
    </div>

    <div class="mt-10 rounded-[30px] border border-[#8D8585] bg-white p-10">
        <div class="text-[#001349] text-[26px] md:text-[35px] font-semibold" style="font-family: Inter, sans-serif;">Keunggulan</div>
        <div class="mt-6 text-[22px] md:text-[35px] font-light" style="font-family: Inter, sans-serif;">
            {{ $innovation->advantages ?? '-' }}
        </div>
    </div>

    <div class="mt-10 rounded-[30px] border border-[#8D8585] bg-white p-10">
        <div class="text-[#001349] text-[26px] md:text-[35px] font-semibold" style="font-family: Inter, sans-serif;">Visit Statistics</div>
        <div class="mt-6 text-[22px] md:text-[35px] font-light" style="font-family: Inter, sans-serif;">
            {{ number_format($innovation->views_count ?? 0, 0, ',', '.') }} total views
        </div>
    </div>
</section>
@endsection
