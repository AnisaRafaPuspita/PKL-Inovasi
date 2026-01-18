@extends('layouts.admin')
@section('title','Detail Innovation')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">Manage Innovations</h1>
<p style="font-weight:700;color:#061a4d;">Detail</p>

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
  $primary = $innovation->primaryImage ?? null;
  $photos = $innovation->images ?? collect();

  $firstInnovator = $innovation->relationLoaded('innovators')
      ? $innovation->innovators->first()
      : null;

  $categories = $categories ?? config('innovation.categories');
@endphp

<div class="panel mb-4">
  <div class="d-flex justify-content-between align-items-start gap-3">
    <div>
      <h4 class="mb-2" style="font-weight:900;color:#061a4d;">
        {{ $innovation->title }}
      </h4>

      <div class="mb-1"><b>Nama Innovator:</b> {{ $firstInnovator?->name ?? '-' }}</div>
      <div class="mb-1"><b>Fakultas:</b> {{ $firstInnovator?->faculty?->name ?? '-' }}</div>
      <div class="mb-1"><b>Kategori:</b> {{ $innovation->category ?? '-' }}</div>
      <div class="mb-1"><b>Mitra:</b> {{ $innovation->partner ?? '-' }}</div>
      <div class="mb-1"><b>Status HKI:</b> {{ $innovation->hki_status ?? '-' }}</div>
      <div class="mb-1"><b>Video URL:</b> {{ $innovation->video_url ?? '-' }}</div>
      <div class="mb-1"><b>Keberdampakan:</b> {{ $innovation->impact ?? '-' }}</div>
    </div>

    <div style="width:180px;">
      <div style="border:2px solid #061a4d;border-radius:14px;overflow:hidden;background:#fff;">
        @if($primary)
          <img src="{{ asset('storage/'.$primary->image_path) }}" style="width:100%;height:180px;object-fit:cover;">
        @elseif($photos->count())
          <img src="{{ asset('storage/'.$photos->first()->image_path) }}" style="width:100%;height:180px;object-fit:cover;">
        @else
          <div style="height:180px;display:flex;align-items:center;justify-content:center;font-weight:800;">
            No Photo
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="mt-3 d-flex gap-2">
    <a href="{{ route('admin.innovations.index') }}" class="btn btn-outline-secondary">Kembali</a>
    <button id="btnToggleEdit" class="btn btn-navy" type="button">Edit</button>
  </div>
</div>

<div id="editBox" class="panel" style="display:none;">
  <form method="POST"
        action="{{ route('admin.innovations.update', $innovation->id) }}"
        enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
      <div class="col-12 col-lg-4">
        <div style="background:#7c879f;border-radius:24px;padding:20px;">

          <div style="background:#fff;border-radius:16px;min-height:200px;padding:12px;">
            <div class="fw-bold mb-2">Foto Inovasi</div>

            <div id="photoEmpty" style="height:160px;display:flex;align-items:center;justify-content:center;border:2px dashed #cbd5e1;border-radius:12px;">
              <span class="text-muted fw-bold">Belum ada foto</span>
            </div>

            <div id="photoPreview" class="d-grid mt-2" style="display:none;grid-template-columns:repeat(2,1fr);gap:10px;"></div>
          </div>

          <div class="d-flex gap-2 mt-3">
            <label class="btn btn-outline-dark w-100 mb-0">
              Tambah Foto
              <input id="photosInput" type="file" name="images[]" accept="image/*" multiple hidden>
            </label>

            <button type="button" id="clearPhotosBtn" class="btn btn-outline-secondary">Clear</button>
          </div>
        </div>

        @if($photos->count())
          <div class="mt-3">
            <div class="fw-bold mb-2" style="color:#061a4d;">Hapus Foto Lama</div>
            <div class="d-grid" style="grid-template-columns:repeat(2,1fr);gap:10px;">
              @foreach($photos as $img)
                <label style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;cursor:pointer;">
                  <img src="{{ asset('storage/'.$img->image_path) }}" style="width:100%;height:110px;object-fit:cover;">
                  <div style="padding:8px;display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="delete_image_ids[]" value="{{ $img->id }}">
                    <span style="font-weight:700;color:#061a4d;">Hapus</span>
                  </div>
                </label>
              @endforeach
            </div>
          </div>
        @endif
      </div>

      <div class="col-12 col-lg-8">
        <label class="fw-bold">Judul Inovasi</label>
        <input type="text" class="form-control mb-3" name="title" required
               value="{{ old('title', $innovation->title) }}">

        <label class="fw-bold">Nama Innovator (jika belum ada)</label>
        <input type="text" class="form-control mb-3" name="new_innovator_name"
               placeholder="Ketik nama innovator (jika belum ada)"
               value="{{ old('new_innovator_name', '') }}">

        <label class="fw-bold">Atau pilih innovator yang sudah ada</label>
        <select name="innovator_id" class="form-select mb-3">
          <option value="">-- Pilih Innovator --</option>
          @foreach($innovators as $inv)
            <option value="{{ $inv->id }}"
              @selected(old('innovator_id', $firstInnovator?->id) == $inv->id)>
              {{ $inv->name }} ({{ $inv->faculty?->name ?? '-' }})
            </option>
          @endforeach
        </select>

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
        <select name="category" class="form-select mb-3">
          <option value="">-- Pilih Kategori --</option>
          @foreach($categories as $cat)
            <option value="{{ $cat }}" @selected(old('category', $innovation->category) == $cat)>
              {{ $cat }}
            </option>
          @endforeach
        </select>

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
               value="{{ old('impact', $innovation->impact) }}"
               placeholder="Isi agar masuk Inovasi Berdampak">

        <div class="text-end">
          <button class="btn btn-navy px-4" type="submit">Simpan Perubahan</button>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
  const btn = document.getElementById('btnToggleEdit');
  const box = document.getElementById('editBox');

  btn.addEventListener('click', () => {
    const isOpen = box.style.display === 'block';
    box.style.display = isOpen ? 'none' : 'block';
    btn.textContent = isOpen ? 'Edit' : 'Tutup Edit';
    if(!isOpen) box.scrollIntoView({behavior:'smooth', block:'start'});
  });

  document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('photosInput');
    const preview = document.getElementById('photoPreview');
    const empty = document.getElementById('photoEmpty');
    const clearBtn = document.getElementById('clearPhotosBtn');

    if (!input || !preview || !empty || !clearBtn) return;

    let selectedFiles = [];

    function syncInputFiles() {
      const dt = new DataTransfer();
      selectedFiles.forEach(f => dt.items.add(f));
      input.files = dt.files;
    }

    function render() {
      preview.innerHTML = '';

      if (!selectedFiles.length) {
        preview.style.display = 'none';
        empty.style.display = 'flex';
        return;
      }

      empty.style.display = 'none';
      preview.style.display = 'grid';

      selectedFiles.forEach((file, idx) => {
        const url = URL.createObjectURL(file);

        const wrap = document.createElement('div');
        wrap.style.position = 'relative';
        wrap.style.border = '1px solid #e5e7eb';
        wrap.style.borderRadius = '12px';
        wrap.style.overflow = 'hidden';
        wrap.style.height = '110px';

        const img = document.createElement('img');
        img.src = url;
        img.style.width = '100%';
        img.style.height = '100%';
        img.style.objectFit = 'cover';

        const b = document.createElement('button');
        b.type = 'button';
        b.textContent = '×';
        b.style.position = 'absolute';
        b.style.top = '6px';
        b.style.right = '6px';
        b.style.width = '26px';
        b.style.height = '26px';
        b.style.borderRadius = '999px';
        b.style.border = '0';
        b.style.fontWeight = '900';
        b.style.cursor = 'pointer';
        b.style.background = 'rgba(0,0,0,0.6)';
        b.style.color = '#fff';
        b.addEventListener('click', () => {
          selectedFiles.splice(idx, 1);
          syncInputFiles();
          render();
        });

        wrap.appendChild(img);
        wrap.appendChild(b);
        preview.appendChild(wrap);

        img.addEventListener('load', () => URL.revokeObjectURL(url));
      });
    }

    input.addEventListener('change', () => {
      const files = Array.from(input.files || []);
      if (!files.length) return;

      files.forEach(f => {
        if (!f.type || !f.type.startsWith('image/')) return;
        selectedFiles.push(f);
      });

      syncInputFiles();
      render();
      input.value = '';
    });

    clearBtn.addEventListener('click', () => {
      selectedFiles = [];
      syncInputFiles();
      render();
      input.value = '';
    });

    render();
  });
</script>

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
}
</style>
@endsection
