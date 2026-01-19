@extends('layouts.app')
@section('title', 'Innovator of the Month')

@section('content')
<section class="mx-auto max-w-[1200px] px-4 md:px-6 mt-8 md:mt-12">

  {{-- CARD 1: Innovator of the Month --}}
  <div class="rounded-[30px] border border-[#8D8585] bg-white p-6 md:p-10">
    <div class="flex items-center gap-3">
      <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#001349] text-white">
        {{-- icon simple --}}
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z"/></svg>
      </span>
      <h1 class="text-[#001349] text-[28px] md:text-[40px] font-bold" style="font-family: Inter, sans-serif;">
        Innovator of the Month
      </h1>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">
      {{-- FOTO --}}
      <div class="rounded-[20px] border border-[#8D8585] p-6">
        <div class="h-[220px] md:h-[260px] rounded-[16px] bg-gray-100 overflow-hidden flex items-center justify-center">
          @if($iom->photo)
            <img
              src="{{ asset('storage/' . $iom->photo) }}"
              alt="Foto Innovator of the Month"
              class="max-w-full max-h-full object-contain"
            >
          @else
            <span class="text-gray-500">Foto</span>
          @endif
        </div>
      </div>


      {{-- INFO --}}
      <div class="rounded-[20px] border border-[#8D8585] p-6">
        <div class="space-y-5" style="font-family: Inter, sans-serif;">
          <div>
            <div class="text-gray-700">Nama</div>
            <div class="mt-1 text-[18px] md:text-[20px] font-semibold text-[#001349]">
              {{ $iom->innovator?->name ?? '-' }}
            </div>
          </div>

          <div>
            <div class="text-gray-700">Fakultas</div>
            <div class="mt-1 text-[18px] md:text-[20px] font-semibold text-[#001349]">
              {{ $iom->innovator?->faculty?->name ?? '-' }}
            </div>
          </div>

          <div>
            <div class="text-gray-700">Deskripsi</div>
            <div class="mt-1 text-[18px] md:text-[20px] font-semibold text-[#001349]">
              {{ $iom->description ?? '-'  }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- CARD 2: Featured Innovation --}}
  <div class="mt-8 rounded-[30px] border border-[#8D8585] bg-white p-6 md:p-10">
    <h2 class="text-[22px] md:text-[26px] font-medium" style="font-family: Inter, sans-serif;">
      Featured Innovation
    </h2>

    @if($featuredInnovation)
      <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">
        {{-- KIRI: info --}}
        <div>
          <div class="text-[20px] md:text-[26px] font-light" style="font-family: Inter, sans-serif;">
            {{ $featuredInnovation->title }}
          </div>

          <div class="mt-3 flex items-center gap-3">
            <span class="rounded-full bg-[#1A6ECE]/50 px-4 py-1 text-[14px] text-[#1A6ECE]">
              {{ ucfirst($featuredInnovation->status) }}
            </span>
            <span class="text-[14px] text-gray-700">
              {{ $featuredInnovation->category }}
            </span>
          </div>

          <div class="mt-6 text-[14px] md:text-[16px] text-gray-700 leading-relaxed" style="font-family: Inter, sans-serif;">
            <div class="font-medium text-gray-900">Description</div>
            <div class="mt-1">{{ $featuredInnovation?->description ?? '-' }}</div>

            <div class="mt-4 font-medium text-gray-900">Achievements</div>
            <div class="mt-1">{{ $featuredInnovation->advantages ?? '-' }}</div>
          </div>

          {{-- Keberdampakan --}}
          <div class="mt-6 rounded-[20px] bg-[#1A6ECE]/10 border border-[#8D8585] p-6">
            <div class="text-[16px] font-medium text-gray-900">Keberdampakan</div>
            <div class="mt-2 text-gray-700">
              {{ $featuredInnovation->impact ?? '-' }}
            </div>
          </div>

          {{-- Tombol --}}
          <div class="mt-6">
            <a href="{{ route('innovations.show', $featuredInnovation->id) }}"
               class="inline-flex rounded-[30px] bg-[#001349] px-8 py-3 text-white text-[16px] font-medium">
              Lihat Detail Inovasi
            </a>
          </div>
        </div>

        {{-- KANAN: foto innovation --}}
        <div class="rounded-[20px] border border-[#8D8585] p-6">
          <div class="relative h-[220px] md:h-[260px] overflow-hidden rounded-[16px]">

            {{-- FEATURED INNOVATION IMAGE SLIDER --}}
            @if($featuredInnovation)
              @php
                  $images = $featuredInnovation->images->count()
                      ? $featuredInnovation->images
                      : ($featuredInnovation->image_url
                          ? collect([(object)['image_path' => $featuredInnovation->image_url]])
                          : collect());
              @endphp

              <div class="relative h-[300px] overflow-hidden rounded-[20px] border">

                  @if($images->count())
                      <div
                          id="slider-featured-{{ $featuredInnovation->id }}"
                          class="flex h-full transition-transform duration-300 ease-in-out"
                          data-index="0"
                      >
                          @foreach ($images as $img)
                              <img
                                  src="{{ asset('storage/'.$img->image_path) }}"
                                  class="min-w-full h-full object-cover"
                                  alt="Featured Innovation"
                              >
                          @endforeach
                      </div>

                      @if ($images->count() > 1)
                          <button
                              type="button"
                              class="slide-btn absolute left-3 top-1/2 -translate-y-1/2
                                    bg-black/50 text-white w-8 h-8 rounded-full z-10"
                              data-id="featured-{{ $featuredInnovation->id }}"
                              data-dir="-1"
                          >
                              &lsaquo;
                          </button>

                          <button
                              type="button"
                              class="slide-btn absolute right-3 top-1/2 -translate-y-1/2
                                    bg-black/50 text-white w-8 h-8 rounded-full z-10"
                              data-id="featured-{{ $featuredInnovation->id }}"
                              data-dir="1"
                          >
                              &rsaquo;
                          </button>
                      @endif
                  @else
                      <div class="h-full flex items-center justify-center text-gray-400">
                          Gambar inovasi belum tersedia
                      </div>
                  @endif

              </div>
          @else
              <div class="h-[300px] flex items-center justify-center text-gray-400">
                  Inovasi unggulan belum dipilih
              </div>
          @endif


                
          </div>
        </div>
      </div>
    @else
      <p class="mt-3 text-gray-500" style="font-family: Inter, sans-serif;">
        Belum ada inovasi unggulan untuk inovator ini.
      </p>
    @endif
  </div>

</section>
@endsection
