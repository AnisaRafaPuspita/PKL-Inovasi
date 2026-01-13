@extends('layouts.admin')
@section('title','Manage Innovations')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">Manage Innovations</h1>
<p style="font-weight:700;color:#061a4d;">
    {{ $mode === 'create' ? 'Tambah' : 'Edit' }}
</p>

{{-- ERROR VALIDATION --}}
@if($errors->any())
    <div class="alert alert-danger">
        <div class="fw-bold mb-1">Gagal menyimpan:</div>
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    // Aman untuk create/edit
    $firstInnovator = null;

    // kalau $firstInnovator dikirim dari controller, pakai itu
    if (isset($firstInnovator) && $firstInnovator) {
        // already set
    } else {
        // ambil dari relasi kalau ada
        $firstInnovator = ($innovation && $innovation->relationLoaded('innovators'))
            ? $innovation->innovators->first()
            : null;
    }

    $photo = $firstInnovator?->photo;

    // bikin url foto yang aman
    // jika di DB nyimpan "innovators/xxx.jpg" -> asset('storage/'.$photo)
    // jika nyimpan sudah berupa url -> pakai apa adanya
    $photoUrl = null;
    if ($photo) {
        $photoUrl = str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://')
            ? $photo
            : asset('storage/'.$photo);
    }

    // default koleksi fakultas biar ga error kalau lupa ngirim dari controller
    $faculties = $faculties ?? collect();

    // selected faculty id
    $selectedFacultyId = old('faculty_id', $firstInnovator?->faculty_id ?? '');
@endphp

<div class="panel">
    <form
        method="POST"
        action="{{ $mode === 'create'
            ? route('admin.innovations.store')
            : route('admin.innovations.update', $innovation->id) }}"
        enctype="multipart/form-data"
    >
        @csrf
        @if($mode === 'edit')
            @method('PUT')
        @endif

        <div class="row g-4">
            {{-- FOTO INNOVATOR --}}
            <div class="col-12 col-lg-4">
                <div style="background:#7c879f;border-radius:36px;padding:26px;">
                    <div
                        id="photoBox"
                        style="background:#fff;border-radius:18px;height:220px;
                               display:flex;align-items:center;justify-content:center;overflow:hidden;"
                    >
                        @if($photoUrl)
                            <img
                                id="photoPreview"
                                src="{{ $photoUrl }}"
                                alt="Photo"
                                style="width:100%;height:100%;object-fit:cover;"
                            >
                        @else
                            <strong id="photoPlaceholder">Photo</strong>
                            <img
                                id="photoPreview"
                                src=""
                                alt="Photo"
                                style="display:none;width:100%;height:100%;object-fit:cover;"
                            >
                        @endif
                    </div>

                    <label class="btn btn-outline-dark w-100 mt-3">
                        Edit Photo
                        <input
                            id="photoInput"
                            type="file"
                            name="photo"
                            accept="image/*"
                            hidden
                        >
                    </label>

                   
                </div>
            </div>

            {{-- FORM --}}
            <div class="col-12 col-lg-8">
                <label class="fw-bold">Judul Inovasi</label>
                <input
                    class="form-control mb-3"
                    name="title"
                    required
                    value="{{ old('title', $innovation->title ?? '') }}"
                >

                <label class="fw-bold">Nama Innovator</label>
                <input
                    type="text"
                    class="form-control mb-3"
                    name="innovator_name"
                    required
                    value="{{ old('innovator_name', $firstInnovator?->name ?? '') }}"
                >

                {{-- FAKULTAS --}}
                <label class="fw-bold">Fakultas</label>
                <select name="faculty_id" class="form-select mb-3" required>
                    <option value="">-- Pilih Fakultas --</option>

                    @foreach($faculties as $f)
                        <option value="{{ $f->id }}" @selected((string)$selectedFacultyId === (string)$f->id)>
                            {{ $f->name }}
                        </option>
                    @endforeach
                </select>

                <label class="fw-bold">Kategori</label>
                <input
                    class="form-control mb-3"
                    name="category"
                    value="{{ old('category', $innovation->category ?? '') }}"
                >

                <label class="fw-bold">Mitra</label>
                <input
                    class="form-control mb-3"
                    name="partner"
                    value="{{ old('partner', $innovation->partner ?? '') }}"
                >

                <label class="fw-bold">Status HKI</label>
                <input
                    class="form-control mb-3"
                    name="hki_status"
                    value="{{ old('hki_status', $innovation->hki_status ?? '') }}"
                >

                <label class="fw-bold">Video URL</label>
                <input
                    class="form-control mb-3"
                    name="video_url"
                    value="{{ old('video_url', $innovation->video_url ?? '') }}"
                >

                <label class="fw-bold">Deskripsi</label>
                <textarea class="form-control mb-3" rows="4" name="description">{{ old('description', $innovation->description ?? '') }}</textarea>

                <label class="fw-bold">Keunggulan</label>
                <textarea class="form-control mb-3" rows="3" name="advantages">{{ old('advantages', $innovation->advantages ?? '') }}</textarea>

                <label class="fw-bold">Keberdampakan</label>
                <input
                    class="form-control mb-3"
                    name="impact"
                    value="{{ old('impact', $innovation->impact ?? '') }}"
                >

                <div class="text-end">
                    <button type="submit" class="btn btn-navy px-4">
                        {{ $mode === 'create' ? 'Simpan' : 'Simpan Perubahan' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.panel{
    border:2px solid #061a4d;
    border-radius:18px;
    padding:20px;
    background:#fff;
}
.btn-navy{
    background:#061a4d;
    color:#fff;
    font-weight:700;
    border-radius:10px;
    border:1px solid #061a4d;
}
.btn-navy:hover{
    background:#04133a;
    border-color:#04133a;
    color:#fff;
}
</style>

<script>
(function(){
  const input = document.getElementById('photoInput');
  const img = document.getElementById('photoPreview');
  const placeholder = document.getElementById('photoPlaceholder');

  if(!input || !img) return;

  input.addEventListener('change', function(){
    const file = this.files && this.files[0];
    if(!file) return;

    const url = URL.createObjectURL(file);
    img.src = url;
    img.style.display = 'block';
    if(placeholder) placeholder.style.display = 'none';
  });
})();
</script>
@endsection
