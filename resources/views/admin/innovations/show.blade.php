@extends('layouts.admin')
@section('title','Detail Innovation')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">Manage Innovations</h1>
<p style="font-weight:700;color:#061a4d;">Detail</p>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- DETAIL PANEL --}}
<div class="panel mb-4">
  <div class="d-flex justify-content-between align-items-start gap-3">
    <div>
      <h4 class="mb-2" style="font-weight:900;color:#061a4d;">
        {{ $innovation->title }}
      </h4>

      <div class="mb-1"><b>Nama Innovator:</b> {{ $firstInnovator->name ?? '-' }}</div>
      <div class="mb-1"><b>Fakultas:</b> {{ $firstInnovator->faculty->name ?? '-' }}</div>
      <div class="mb-1"><b>Kategori:</b> {{ $innovation->category ?? '-' }}</div>
      <div class="mb-1"><b>Mitra:</b> {{ $innovation->partner ?? '-' }}</div>
      <div class="mb-1"><b>Status HKI:</b> {{ $innovation->hki_status ?? '-' }}</div>
      <div class="mb-1"><b>Video URL:</b> {{ $innovation->video_url ?? '-' }}</div>
    </div>

    <div style="width:180px;">
      <div style="border:2px solid #061a4d;border-radius:14px;overflow:hidden;background:#fff;">
        @php $photo = $firstInnovator->photo ?? null; @endphp
        @if($photo)
          <img src="{{ asset('storage/'.$photo) }}" style="width:100%;height:180px;object-fit:cover;">
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

    {{-- tombol toggle edit --}}
    <button id="btnToggleEdit" class="btn btn-navy" type="button">Edit</button>
  </div>
</div>

{{-- FORM EDIT (HIDDEN DULU) --}}
<div id="editBox" class="panel" style="display:none;">
  <form method="POST"
        action="{{ route('admin.innovations.update', $innovation->id) }}"
        enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
      <div class="col-12 col-lg-4">
        <div style="background:#7c879f;border-radius:36px;padding:26px;">
          <div style="background:#fff;border-radius:18px;height:220px;display:flex;align-items:center;justify-content:center;">
            @if($photo)
              <img src="{{ asset('storage/'.$photo) }}" style="width:100%;height:100%;object-fit:cover;">
            @else
              <strong>Photo</strong>
            @endif
          </div>

          <label class="btn btn-outline-dark w-100 mt-3">
            Edit Photo
            <input type="file" name="photo" hidden>
          </label>
        </div>
      </div>

      <div class="col-12 col-lg-8">
        <label class="fw-bold">Judul Inovasi</label>
        <input class="form-control mb-3" name="title" value="{{ old('title',$innovation->title) }}" required>

        <label class="fw-bold">Nama Innovator</label>
        <input class="form-control mb-3" name="innovator_name"
               value="{{ old('innovator_name', $firstInnovator->name ?? '') }}" required>

        <label class="fw-bold">Fakultas</label>
        <input class="form-control mb-3" name="faculty_name"
               value="{{ old('faculty_name', $firstInnovator->faculty->name ?? '') }}"
               placeholder="Misal: Fakultas Hukum" required>

        <label class="fw-bold">Kategori</label>
        <input class="form-control mb-3" name="category" value="{{ old('category',$innovation->category) }}">

        <label class="fw-bold">Mitra</label>
        <input class="form-control mb-3" name="partner" value="{{ old('partner',$innovation->partner) }}">

        <label class="fw-bold">Status HKI</label>
        <input class="form-control mb-3" name="hki_status" value="{{ old('hki_status',$innovation->hki_status) }}">

        <label class="fw-bold">Video URL</label>
        <input class="form-control mb-3" name="video_url" value="{{ old('video_url',$innovation->video_url) }}">

        <label class="fw-bold">Deskripsi</label>
        <textarea class="form-control mb-3" rows="4" name="description">{{ old('description',$innovation->description) }}</textarea>

        <label class="fw-bold">Keunggulan</label>
        <textarea class="form-control mb-3" rows="3" name="advantages">{{ old('advantages',$innovation->advantages) }}</textarea>

        <label class="fw-bold">Keberdampakan</label>
        <input class="form-control mb-3" name="impact" value="{{ old('impact',$innovation->impact) }}">

        <div class="text-end">
          <button class="btn btn-navy px-4">Simpan Perubahan</button>
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
