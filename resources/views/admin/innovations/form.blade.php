@extends('layouts.admin')
@section('title','Manage Innovations')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">
    Manage Innovations
</h1>
<p style="font-weight:700;color:#061a4d;">
    {{ $mode === 'create' ? 'Tambah Inovasi' : 'Edit Inovasi' }}
</p>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

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
    $firstInnovator = $innovation->relationLoaded('innovators')
        ? $innovation->innovators->first()
        : null;

    $existingPhotos = $innovation->relationLoaded('photos')
        ? $innovation->photos
        : collect();
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

        {{-- LEFT: FOTO --}}
        <div class="col-12 col-lg-4">
            <div style="background:#7c879f;border-radius:24px;padding:20px;">

                <div style="background:#fff;border-radius:16px;min-height:200px;padding:12px;">
                    <div class="fw-bold mb-2">Foto Inovasi</div>

                    @if($mode === 'edit' && $existingPhotos->count())
                        <div class="d-grid" style="grid-template-columns:repeat(2,1fr);gap:10px;">
                            @foreach($existingPhotos as $p)
                                <div style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;height:110px;">
                                    <img
                                        src="{{ asset('storage/'.$p->path) }}"
                                        alt="photo"
                                        style="width:100%;height:100%;object-fit:cover;"
                                    >
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="height:160px;display:flex;align-items:center;justify-content:center;border:2px dashed #cbd5e1;border-radius:12px;">
                            <span class="text-muted fw-bold">Belum ada foto</span>
                        </div>
                    @endif
                </div>

                <label class="btn btn-outline-dark w-100 mt-3">
                    Upload Foto
                    <input type="file" name="photos[]" hidden multiple accept="image/*">
                </label>
            </div>
        </div>

        {{-- RIGHT: FORM --}}
        <div class="col-12 col-lg-8">
            <label class="fw-bold">Judul Inovasi</label>
            <input type="text" class="form-control mb-3" name="title" required
                   value="{{ old('title', $innovation->title) }}">

            <label class="fw-bold">Nama Innovator</label>
            <input type="text" class="form-control mb-3" name="innovator_name" required
                   value="{{ old('innovator_name', $firstInnovator?->name) }}">

            <label class="fw-bold">Fakultas</label>
            <select name="faculty_id" class="form-select mb-3" required>
                <option value="">-- Pilih Fakultas --</option>
                @foreach($faculties as $f)
                    <option value="{{ $f->id }}"
                        @selected(old('faculty_id', $firstInnovator?->faculty_id) == $f->id)>
                        {{ $f->name }}
                    </option>
                @endforeach
            </select>

            <label class="fw-bold">Kategori</label>
            <input type="text" class="form-control mb-3" name="category"
                   value="{{ old('category', $innovation->category) }}">

            <label class="fw-bold">Mitra</label>
            <input type="text" class="form-control mb-3" name="partner"
                   value="{{ old('partner', $innovation->partner) }}">

            <label class="fw-bold">Status HKI</label>
            <input type="text" class="form-control mb-3" name="hki_status"
                   value="{{ old('hki_status', $innovation->hki_status) }}">

            <label class="fw-bold">Video URL</label>
            <input type="text" class="form-control mb-3" name="video_url"
                   value="{{ old('video_url', $innovation->video_url) }}">

            <label class="fw-bold">Deskripsi</label>
            <textarea class="form-control mb-3" rows="4"
                      name="description">{{ old('description', $innovation->description) }}</textarea>

            <label class="fw-bold">Keunggulan</label>
            <textarea class="form-control mb-3" rows="3"
                      name="advantages">{{ old('advantages', $innovation->advantages) }}</textarea>

            <label class="fw-bold">Keberdampakan</label>
            <input type="text" class="form-control mb-4" name="impact"
                   value="{{ old('impact', $innovation->impact) }}">

            <div class="text-end">
                <button type="submit" class="btn btn-navy px-4">
                    {{ $mode === 'create' ? 'Simpan Inovasi' : 'Simpan Perubahan' }}
                </button>
            </div>
        </div>

    </div>
</form>
</div>

<style>
.panel{ background:#fff; border:2px solid #061a4d; border-radius:18px; padding:20px; }
.btn-navy{ background:#061a4d; color:#fff; font-weight:700; border-radius:10px; border:1px solid #061a4d; }
.btn-navy:hover{ background:#04133a; border-color:#04133a; color:#fff; }
</style>
@endsection
