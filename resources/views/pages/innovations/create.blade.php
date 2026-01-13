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

        {{-- UPLOAD MULTIPLE FOTO --}}
        <div class="mx-auto w-full max-w-[420px]
                    rounded-[32px]
                    bg-[#1A6ECE]/10
                    border-2 border-dashed border-[#1A6ECE]
                    px-6 py-10 text-center">

            {{-- input trigger (hidden) --}}
            <input
                type="file"
                id="imageInput"
                accept="image/*"
                class="hidden"
            >

            {{-- input FINAL --}}
            <input
                type="file"
                name="images[]"
                id="finalInput"
                multiple
                class="hidden"
            >

            {{-- PREVIEW --}}
            <div id="preview"
                class="flex flex-wrap gap-3 justify-center">
            </div>

            {{-- TOMBOL TAMBAH FOTO --}}
            <div class="mt-6 flex justify-center">
                <button
                    type="button"
                    onclick="addImages()"
                    class="mt-6 inline-flex items-center gap-2
                        rounded-full bg-[#001349]
                        px-4 py-2
                        text-white text-sm font-semibold
                        hover:bg-[#001349]/90 transition">
                    <span>Tambah Foto</span>
                    <img src="{{ asset('images/add_circle.png') }}"
                        class="h-3.5 w-3.5">
                </button>
            </div>
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
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-start gap-4">
                <label class="text-[#001349] text-[18px] font-bold pt-2">
                    Nama Innovator
                </label>

                <div class="space-y-3">
                    {{-- Input ketik nama --}}
                    <input
                        name="new_innovator_name"
                        placeholder="Ketik nama innovator (jika belum ada)"
                        class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 outline-none"
                    >

                    {{-- Dropdown pilih --}}
                    <select
                        name="innovator_id"
                        class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 bg-white outline-none">
                        <option value="">
                            Atau pilih innovator yang sudah ada
                        </option>
                        @foreach ($innovators as $innovator)
                            <option value="{{ $innovator->id }}">
                                {{ $innovator->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Fakultas --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-center gap-4">
                <label class="text-[#001349] text-[18px] font-bold">
                    Fakultas
                </label>

                <select
                    name="faculty_id"
                    class="h-[46px] w-full rounded-[30px]
                        border border-[#001349]
                        px-6 bg-white outline-none">
                    <option value="">Pilih Fakultas</option>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" @selected(old('faculty_id') == $faculty->id)>
                            {{ $faculty->name }}
                        </option>
                    @endforeach
                </select>
            </div>



            {{-- Mitra --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-center gap-4">
                <label class="text-[#001349] text-[18px] font-bold">Mitra</label>
                <input
                    name="partner"
                    value="{{ old('partner') }}"
                    class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 outline-none"
                    placeholder="Contoh: PT ABC, UNDIP, dll"
                >
            </div>

            {{-- Status HKI --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-center gap-4">
                <label class="text-[#001349] text-[18px] font-bold">Status HKI</label>
                <input
                    name="hki_status"
                    value="{{ old('hki_status') }}"
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

            {{-- Kategori --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-center gap-4">
                <label class="text-[#001349] text-[18px] font-bold">
                    Kategori
                </label>

                <select
                    name="category"
                    class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 bg-white outline-none">

                    <option value="">Pilih Kategori</option>

                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected(old('category') == $cat)>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
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
if (window.performance && performance.navigation.type === 2) {
    location.reload();
}
</script>

<script>
let filesBuffer = [];

function addImages() {
    document.getElementById('imageInput').click();
}

document.getElementById('imageInput').addEventListener('change', function (e) {
    const newFiles = Array.from(e.target.files);

    newFiles.forEach(file => {
        filesBuffer.push(file);

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.className = 'h-24 w-24 object-cover rounded-lg';
        document.getElementById('preview').appendChild(img);
    });

    const dataTransfer = new DataTransfer();
    filesBuffer.forEach(file => dataTransfer.items.add(file));
    document.getElementById('finalInput').files = dataTransfer.files;
});
</script>





@endsection
