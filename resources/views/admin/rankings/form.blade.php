@extends('layouts.admin')
@section('title','Peringkat Inovasi')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">
  {{ $mode === 'create' ? 'Tambah Peringkat Inovasi' : 'Detail Peringkat Inovasi' }}
</h1>

<form method="POST"
      enctype="multipart/form-data"
      action="{{ $mode === 'create'
          ? route('admin.innovation_rankings.store')
          : route('admin.innovation_rankings.update', $ranking->id) }}">
  @csrf
  @if($mode === 'edit')
    @method('PUT')
  @endif

  <div class="panel">
    <div class="row g-3">

      <div class="col-12 col-md-2">
        <label class="fw-bold">Peringkat</label>
        <input type="number"
               name="rank"
               class="form-control"
               value="{{ old('rank', $ranking->rank) }}"
               min="1" max="100">
        @error('rank') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="col-12 col-md-10">
        <label class="fw-bold">Prestasi</label>
        <input type="text"
               name="achievement"
               class="form-control"
               value="{{ old('achievement', $ranking->achievement) }}"
               placeholder="Contoh: Best Innovation Award 2025">
        @error('achievement') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="col-12">
        <label class="fw-bold">Deskripsi</label>
        <textarea name="description"
                  class="form-control"
                  rows="4"
                  placeholder="Tulis deskripsi singkat...">{{ old('description', $ranking->description) }}</textarea>
        @error('description') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="col-12">
        <label class="fw-bold">Gambar</label>
        <input type="file"
               name="image"
               class="form-control"
               accept="image/*"
               onchange="previewImage(event)">
        @error('image') <small class="text-danger">{{ $message }}</small> @enderror

        <div class="mt-2">
          <img id="imagePreview"
               src="{{ !empty($ranking->image) ? asset('storage/'.$ranking->image) : '' }}"
               class="{{ empty($ranking->image) ? 'd-none' : '' }}"
               style="max-height:120px;border-radius:8px;"
               alt="Preview Gambar">
        </div>
      </div>

    </div>

    <div class="d-flex justify-content-end mt-4 gap-2">
      <a href="{{ route('admin.innovation_rankings.index') }}"
         class="btn btn-outline-secondary">Kembali</a>
      <button type="submit" class="btn btn-navy">Simpan</button>
    </div>
  </div>
</form>
@endsection

@push('scripts')
<script>
function previewImage(event) {
  const img = document.getElementById('imagePreview');
  const file = event.target.files && event.target.files[0];
  if (!file) return;

  img.src = URL.createObjectURL(file);
  img.classList.remove('d-none');
}
</script>
@endpush
