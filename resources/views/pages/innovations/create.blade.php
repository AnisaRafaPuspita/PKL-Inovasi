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

            {{-- Innovators --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-4">
                <label class="text-[#001349] text-[18px] font-bold pt-2">
                    Innovator
                </label>

                <div id="innovators-wrapper" class="space-y-3">

                    {{-- SATU ITEM INNOVATOR --}}
                    <div class="innovator-item grid grid-cols-1 md:grid-cols-2 gap-3">

                        <!-- ROW 1: INNOVATOR BARU -->
                        <input
                            type="text"
                            name="innovators[0][name]"
                            placeholder="Ketik nama innovator (jika baru)"
                            class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 outline-none"
                        >

                        <select
                            name="innovators[0][faculty_new]"
                            class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 bg-white outline-none"
                        >
                            <option value="">Pilih Fakultas</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>

                        <!-- ROW 2: INNOVATOR EXISTING -->
                        <select
                            name="innovators[0][innovator_id]"
                            
                            class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 bg-white outline-none"
                        >
                            <option value="">Atau pilih innovator yang sudah ada</option>
                            @foreach ($innovators as $innovator)
                                <option
                                    value="{{ $innovator->id }}"
                                    data-faculty-id="{{ $innovator->faculty_id }}"
                                >
                                    {{ $innovator->name }}
                                </option>
                            @endforeach
                        </select>

                        <select
                            name="innovators[0][faculty_id]"
                            class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 bg-white outline-none"
                        >
                            <option value="">Pilih Fakultas</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>

                    </div>


                </div>

                {{-- TOMBOL TAMBAH INNOVATOR --}}
                <div class="md:col-start-2">
                    <button
                        type="button"
                        onclick="addInnovator()"
                        class="mt-1 inline-flex items-center gap-2
                            rounded-full bg-[#001349]
                            px-4 py-2
                            text-white text-sm font-semibold
                            hover:bg-[#001349]/90 transition"
                    >
                        <span>Tambah Innovator</span>
                        <img
                            src="{{ asset('images/add_circle.png') }}"
                            class="h-3.5 w-3.5"
                            alt="+"
                        >
                    </button>
                </div>
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

            {{-- Status Paten --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-start gap-4">
                <label class="text-[#001349] text-[18px] font-bold pt-2">
                    Status Paten
                </label>

                <div class="space-y-2">
                    <select
                        name="hki_status"
                        id="hki_status"
                        class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 bg-white outline-none"
                        onchange="handleHkiStatus(this.value)"
                    >
                        <option value="">Pilih Status</option>
                        <option value="terdaftar">Terdaftar</option>
                        <option value="on_process">On Process</option>
                        <option value="granted">Granted</option>
                    </select>

                    {{-- Nomor Pendaftaran --}}
                    <input
                        type="text"
                        name="hki_registration_number"
                        id="hki_registration"
                        placeholder="Nomor Pendaftaran HKI"
                        class="hidden h-[46px] w-full rounded-[30px] border border-[#001349] px-6 outline-none"
                    >

                    {{-- Nomor Paten --}}
                    <input
                        type="text"
                        name="hki_patent_number"
                        id="hki_patent"
                        placeholder="Nomor Paten"
                        class="hidden h-[46px] w-full rounded-[30px] border border-[#001349] px-6 outline-none"
                    >
                </div>
            </div>


            {{-- Link URL --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-center gap-4">
                <label class="text-[#001349] text-[18px] font-bold">Link Inovasi</label>
                <input
                    name="video_url"
                    value="{{ old('video_url') }}"
                    class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 outline-none"
                    placeholder="https://..."
                >
            </div>

            {{-- Kategori --}}
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] items-start gap-4">
                <label class="text-[#001349] text-[18px] font-bold pt-2">
                    Kategori
                </label>

                {{-- KOLOM KANAN --}}
                <div class="space-y-2">
                    <select
                        name="category"
                        id="category"
                        onchange="handleCategory(this.value)"
                        class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 bg-white outline-none"
                    >
                        <option value="">Pilih Kategori</option>

                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach

                        <option value="other">Inovasi Lainnya</option>
                    </select>

                    <input
                        type="text"
                        name="category_other"
                        id="category_other"
                        placeholder="Ketik kategori inovasi"
                        class="hidden h-[46px] w-full rounded-[30px] border border-[#001349] px-6 outline-none"
                    />
                </div>
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
                    placeholder="Masukkan Keberdampakan apabila produk sudah memiliki kebermanfaatan baik itu untuk institusi, perusahaan, maupun masyarakat"
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

<script>
let innovatorIndex = 1;

function addInnovator() {
    const wrapper = document.getElementById('innovators-wrapper');

    const html = `
    <div class="innovator-item grid grid-cols-1 md:grid-cols-2 gap-3">

        <!-- ROW 1: INNOVATOR BARU -->
        <input
            type="text"
            name="innovators[${innovatorIndex}][name]"
            placeholder="Ketik nama innovator (jika baru)"
            class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 outline-none"
        >

        <select
            name="innovators[${innovatorIndex}][faculty_new]"
            class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 bg-white outline-none"
        >
            <option value="">Pilih Fakultas</option>
            @foreach($faculties as $faculty)
                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
            @endforeach
        </select>

        <!-- ROW 2: INNOVATOR EXISTING -->
        <select
            name="innovators[${innovatorIndex}][innovator_id]"
            class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 bg-white outline-none"
        >
            <option value="">Atau pilih innovator yang sudah ada</option>
            @foreach ($innovators as $innovator)
                <option
                    value="{{ $innovator->id }}"
                    data-faculty-id="{{ $innovator->faculty_id }}"
                >
                    {{ $innovator->name }}
                </option>
            @endforeach
        </select>

        <select
            name="innovators[${innovatorIndex}][faculty_id]"
            class="h-[46px] w-full rounded-[30px] border border-[#001349] px-6 bg-white outline-none"
        >
            <option value="">Pilih Fakultas</option>
            @foreach($faculties as $faculty)
                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
            @endforeach
        </select>

    </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);
    innovatorIndex++;
}

</script>





<script>
document.addEventListener('change', function (e) {
    if (!e.target.matches('select[name$="[innovator_id]"]')) return;

    const select = e.target;
    const row = select.closest('.innovator-item');
    const facultySelect = row.querySelector(
        'select[name$="[faculty_id]"]'
    );

    const option = select.options[select.selectedIndex];

    if (option && option.dataset.facultyId) {
        facultySelect.value = option.dataset.facultyId;
    } else {
        facultySelect.value = '';
    }
});
</script>









<script>
function handleHkiStatus(value) {
    const reg = document.getElementById('hki_registration');
    const patent = document.getElementById('hki_patent');

    reg.classList.add('hidden');
    patent.classList.add('hidden');

    if (value === 'terdaftar') {
        reg.classList.remove('hidden');
    }

    if (value === 'granted') {
        patent.classList.remove('hidden');
    }
}
</script>

<script>
function handleCategory(value) {
    const other = document.getElementById('category_other');

    if (value === 'other') {
        other.classList.remove('hidden');
    } else {
        other.classList.add('hidden');
        other.value = '';
    }
}
</script>








@endsection
