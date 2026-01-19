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

    $categories = $categories ?? config('innovation.categories');
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

<script>
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

      const btn = document.createElement('button');
      btn.type = 'button';
      btn.textContent = '×';
      btn.style.position = 'absolute';
      btn.style.top = '6px';
      btn.style.right = '6px';
      btn.style.width = '26px';
      btn.style.height = '26px';
      btn.style.borderRadius = '999px';
      btn.style.border = '0';
      btn.style.fontWeight = '900';
      btn.style.cursor = 'pointer';
      btn.style.background = 'rgba(0,0,0,0.6)';
      btn.style.color = '#fff';
      btn.addEventListener('click', () => {
        selectedFiles.splice(idx, 1);
        syncInputFiles();
        render();
      });

      wrap.appendChild(img);
      wrap.appendChild(btn);
      preview.appendChild(wrap);

      img.addEventListener('load', () => URL.revokeObjectURL(url));
    });
  }

  // IMPORTANT: JANGAN set input.value = '' karena itu bisa mengosongkan input.files sebelum submit
  input.addEventListener('change', () => {
    const files = Array.from(input.files || []);
    if (!files.length) return;

    // (Optional) Hindari duplikasi: kalau nama+size sama, skip
    files.forEach(f => {
      if (!f.type || !f.type.startsWith('image/')) return;
      const exists = selectedFiles.some(x => x.name === f.name && x.size === f.size && x.lastModified === f.lastModified);
      if (!exists) selectedFiles.push(f);
    });

    syncInputFiles();
    render();
  });

  clearBtn.addEventListener('click', () => {
    selectedFiles = [];
    syncInputFiles();
    render();
  });

  render();
});
</script>

@endsection
