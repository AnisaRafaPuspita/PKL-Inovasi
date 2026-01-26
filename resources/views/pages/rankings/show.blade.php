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

        {{-- PAMFLET --}}
        @if($ranking->pamphlet)
            <div class="w-full lg:w-1/2">
                <div class="rounded-[30px] border border-[#8D8585]
                    bg-[#F9FAFB] p-6 md:p-8 h-fit shadow-sm">
                    <img src="{{ asset('storage/'.$ranking->pamphlet) }}"
                        class="w-full h-auto rounded-[20px]"
                        alt="Pamflet Ranking">
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
