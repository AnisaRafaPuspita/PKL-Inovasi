@extends('layouts.admin')
@section('title', $mode === 'create' ? 'Tambah Innovator' : 'Edit Innovator')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
  <h1 class="m-0" style="font-weight:900;color:#061a4d;">
    {{ $mode === 'create' ? 'Tambah Innovator' : 'Edit Innovator' }}
  </h1>
</div>

@if(session('success'))
  <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

@if($errors->any())
  <div class="alert alert-danger mt-3">
    <div style="font-weight:900;">Gagal menyimpan:</div>
    <ul class="mb-0">
      @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form method="POST"
      action="{{ $mode === 'create'
        ? route('admin.innovators.store')
        : route('admin.innovators.update', $innovator->id) }}"
      enctype="multipart/form-data"
      class="mt-3">
  @csrf
  @if($mode === 'edit')
    @method('PUT')
  @endif

  <div class="panel">
    <div class="d-flex justify-content-between align-items-start gap-4 flex-wrap">

      <div style="width:260px;">
        <div class="photo-box">
          @php
            $photo = old('photo') ? null : ($innovator->photo ?? null);
            $photoUrl = $photo
              ? (str_starts_with($photo, 'http') ? $photo : asset('storage/'.$photo))
              : null;
          @endphp

          @if($photoUrl)
            <img id="photoPreview" src="{{ $photoUrl }}" alt="photo" class="photo-img">
            <div id="photoPlaceholder" class="photo-placeholder" style="display:none;">No Photo</div>
          @else
            <img id="photoPreview" src="" alt="photo" class="photo-img" style="display:none;">
            <div id="photoPlaceholder" class="photo-placeholder">No Photo</div>
          @endif
        </div>

        <div class="mt-3">
          <label class="fw-bold mb-1 d-block">Foto</label>
          <input id="photoInput" type="file" name="photo" class="form-control" accept="image/*">
          @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
          @if($mode === 'edit')
            <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengganti foto</small>
          @endif
        </div>

        <div class="mt-3">
          <label class="fw-bold mb-1 d-block">Status</label>
          <div class="status-dd {{ (old('is_active', $innovator->is_active ?? 1) == 1) ? 'is-on' : 'is-off' }}">
            <select name="is_active" class="status-select" aria-label="Status Innovator">
              <option value="1" {{ old('is_active', $innovator->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
              <option value="0" {{ old('is_active', $innovator->is_active ?? 1) == 0 ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <svg class="chev" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M6.7 9.2a1 1 0 0 1 1.4 0L12 13.1l3.9-3.9a1 1 0 1 1 1.4 1.4l-4.6 4.6a1 1 0 0 1-1.4 0L6.7 10.6a1 1 0 0 1 0-1.4z"/>
            </svg>
          </div>
          @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
      </div>

      <div style="flex:1; min-width: 340px;">

        <div class="mb-3">
          <label class="fw-bold">Nama Innovator</label>
          <input type="text"
                 name="name"
                 class="form-control"
                 value="{{ old('name', $innovator->name) }}"
                 placeholder="Nama lengkap innovator"
                 required>
          @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
          <label class="fw-bold">Fakultas</label>
          <select name="faculty_id" class="form-select" required>
            <option value="">-- pilih fakultas --</option>
            @foreach($faculties as $f)
              <option value="{{ $f->id }}" @selected(old('faculty_id', $innovator->faculty_id) == $f->id)>
                {{ $f->name }}
              </option>
            @endforeach
          </select>
          @error('faculty_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
          <label class="fw-bold">Bio / Deskripsi</label>
          <textarea class="form-control" rows="5" name="bio" placeholder="Tulis deskripsi singkat innovator">{{ old('bio', $innovator->bio) }}</textarea>
          @error('bio') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="d-flex justify-content-end gap-2">
          <a href="{{ route('admin.innovators.index') }}" class="btn btn-outline-secondary" style="border-radius:12px;font-weight:800;">
            Batal
          </a>
          <button class="btn btn-navy">
            {{ $mode === 'create' ? 'Simpan' : 'Update' }}
          </button>
        </div>

      </div>
    </div>
  </div>
</form>

<style>
.panel{
  border:2px solid #061a4d;
  border-radius:18px;
  padding:16px;
  background:#fff;
}

.btn-navy{
  background:#061a4d;
  color:#fff;
  border-radius:12px;
  padding:10px 16px;
  font-weight:900;
  text-decoration:none;
  border: 2px solid rgba(6,26,77,0);
  transition: transform .08s ease, box-shadow .18s ease;
}
.btn-navy:hover{ color:#fff; box-shadow: 0 10px 22px rgba(6,26,77,.18); }
.btn-navy:active{ transform: scale(.98); }

.photo-box{
  border:2px solid #061a4d;
  border-radius:22px;
  height:320px;
  display:flex;
  align-items:center;
  justify-content:center;
  overflow:hidden;
  background:#f3f5ff;
}
.photo-img{
  width:100%;
  height:100%;
  object-fit:cover;
}
.photo-placeholder{
  color:#6b7280;
  font-weight:900;
}

.status-dd{
  position: relative;
  display: inline-flex;
  align-items: center;
  border-radius: 999px;
  padding: 2px;
  border: 2px solid rgba(6,26,77,.18);
  background: #f8fafc;
  transition: transform .08s ease, box-shadow .18s ease, border-color .18s ease;
}
.status-dd:hover{
  box-shadow: 0 10px 18px rgba(0,0,0,.06);
  border-color: rgba(6,26,77,.28);
}
.status-select{
  -webkit-appearance:none;
  -moz-appearance:none;
  appearance:none;
  border: 0;
  outline: none;
  background: transparent;
  padding: 10px 36px 10px 14px;
  border-radius: 999px;
  font-weight: 900;
  font-size: 12px;
  letter-spacing: .2px;
  cursor: pointer;
  min-width: 140px;
  text-align: center;
  text-align-last: center;
}
.status-dd .chev{
  position:absolute;
  right: 10px;
  width: 16px;
  height: 16px;
  opacity: .85;
  pointer-events:none;
  fill: currentColor;
}
.status-dd.is-on{
  background: rgba(22,163,74,.10);
  border-color: rgba(22,163,74,.35);
  color: #0b3b1c;
}
.status-dd.is-off{
  background: rgba(239,68,68,.10);
  border-color: rgba(239,68,68,.35);
  color: #7f1d1d;
}
</style>

<script>
  const photoInput = document.getElementById('photoInput');
  const photoPreview = document.getElementById('photoPreview');
  const photoPlaceholder = document.getElementById('photoPlaceholder');

  if (photoInput) {
    photoInput.addEventListener('change', (e) => {
      const file = e.target.files && e.target.files[0] ? e.target.files[0] : null;
      if (!file) return;
      const url = URL.createObjectURL(file);
      photoPreview.src = url;
      photoPreview.style.display = 'block';
      if (photoPlaceholder) photoPlaceholder.style.display = 'none';
    });
  }
</script>
@endsection