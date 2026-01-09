@extends('layouts.app')
@section('title', 'Upload Produk')

@section('content')

<section class="mx-auto max-w-[1512px] px-6 mt-10" style="font-family: Inter, sans-serif;">
    <h1 class="text-[#001349] text-[28px] md:text-[40px] font-extrabold">
        Upload Produk
    </h1>

    @if ($errors->any())
        <div class="mt-6 rounded-[20px] border border-red-300 bg-red-50 p-4 text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('innovations.store') }}" method="POST" enctype="multipart/form-data" class="mt-8">
        @csrf

        {{-- FOTO UPLOAD CARD --}}
        <div class="mx-auto w-full max-w-[420px]
            rounded-[32px]
            bg-[#1A6ECE]/10
            border-2 border-dashed border-[#1A6ECE]
            p-6 text-center">

            <label for="image" class="cursor-pointer block">
                <div id="image-preview"
                    class="mx-auto h-[240px] w-[260px]
                            rounded-[24px]
                            bg-white
                            overflow-hidden
                            grid place-items-center">

                    <span id="image-placeholder"
                        class="text-[#5B5B5B] text-[16px] font-medium">
                        Tambah Foto
                    </span>

                    <img id="preview-img"
                        class="hidden h-full w-full object-cover"
                        alt="Preview">
                </div>

                <p class="mt-3 text-[#5B5B5B] text-[15px] font-medium">
                    Klik untuk pilih gambar
                </p>
            </label>

            <input id="image"
                name="image"
                type="file"
                accept="image/*"
                class="hidden">
        </div>


        {{-- FORM FIELDS --}}
        <div class="mt-10 space-y-5">

            {{-- Judul Inovasi --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-center gap-4">
                <label class="text-[#001349] text-[18px] font-bold">Judul Inovasi</label>
                <input
                    name="title"
                    value="{{ old('title') }}"
                    class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 outline-none"
                    placeholder="Masukkan judul inovasi"
                    required
                >
            </div>

            {{-- Nama Innovator --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-center gap-4">
                <label class="text-[#001349] text-[18px] font-bold">Nama Innovator</label>
                <select
                    name="innovator_id"
                    class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 outline-none bg-white"
                    required
                >
                    <option value="">Pilih innovator</option>
                    @foreach($innovators as $innovator)
                        <option value="{{ $innovator->id }}" @selected(old('innovator_id') == $innovator->id)>
                            {{ $innovator->name }}{{ $innovator->faculty?->name ? ' - ' . $innovator->faculty->name : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Mitra --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-center gap-4">
                <label class="text-[#001349] text-[18px] font-bold">Mitra</label>
                <input
                    name="partners"
                    value="{{ old('partners') }}"
                    class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 outline-none"
                    placeholder="Contoh: PT ABC, UNDIP, dll"
                >
            </div>

            {{-- Status HKI --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-center gap-4">
                <label class="text-[#001349] text-[18px] font-bold">Status HKI</label>
                <input
                    name="ip_status"
                    value="{{ old('ip_status') }}"
                    class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 outline-none"
                    placeholder="Contoh: Paten Granted"
                >
            </div>

            {{-- Nama Video URL --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-center gap-4">
                <label class="text-[#001349] text-[18px] font-bold">Nama Video URL</label>
                <input
                    name="video_url"
                    value="{{ old('video_url') }}"
                    class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 outline-none"
                    placeholder="https://..."
                >
            </div>

            {{-- (Opsional) Kategori --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-center gap-4">
                <label class="text-[#001349] text-[18px] font-bold">Kategori</label>
                <input
                    name="category"
                    value="{{ old('category') }}"
                    class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 outline-none"
                    placeholder="Contoh: Energi, Pertanian, IoT"
                >
            </div>

            {{-- Deskripsi --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-start gap-4">
                <label class="text-[#001349] text-[18px] font-bold pt-2">Deskripsi</label>
                <textarea
                    name="description"
                    class="h-[171px] w-full rounded-[30px] border border-[#001349] px-6 py-4 outline-none resize-none"
                    placeholder="Masukkan deskripsi inovasi"
                >{{ old('description') }}</textarea>
            </div>

            {{-- Keunggulan --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-start gap-4">
                <label class="text-[#001349] text-[18px] font-bold pt-2">Keunggulan</label>
                <textarea
                    name="advantages"
                    class="h-[114px] w-full rounded-[30px] border border-[#001349] px-6 py-4 outline-none resize-none"
                    placeholder="Masukkan keunggulan"
                >{{ old('advantages') }}</textarea>
            </div>

            {{-- Keberdampakan --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-start gap-4">
                <label class="text-[#001349] text-[18px] font-bold pt-2">Keberdampakan</label>
                <textarea
                    name="impact"
                    class="h-[94px] w-full rounded-[30px] border border-[#001349] px-6 py-4 outline-none resize-none"
                    placeholder="Masukkan keberdampakan"
                >{{ old('impact') }}</textarea>
            </div>

            {{-- ACTIONS --}}
            <div class="pt-4 flex flex-wrap gap-4">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-[30px] bg-[#001349] px-10 py-3 text-white text-[16px] font-semibold"
                >
                    Simpan Produk
                </button>

                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center justify-center rounded-[30px] border border-[#001349] px-10 py-3 text-[#001349] text-[16px] font-semibold"
                >
                    Batal
                </a>
            </div>

        </div>
    </form>
</section>

<script>
document.getElementById('image').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const previewImg = document.getElementById('preview-img');
    const placeholder = document.getElementById('image-placeholder');

    const reader = new FileReader();
    reader.onload = function (e) {
        previewImg.src = e.target.result;
        previewImg.classList.remove('hidden');
        placeholder.classList.add('hidden');
    };
    reader.readAsDataURL(file);
});
</script>

@endsection
