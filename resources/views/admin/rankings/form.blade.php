@extends('layouts.admin')
@section('title','Peringkat Inovasi')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">
  {{ $mode === 'create' ? 'Tambah Peringkat Inovasi' : 'Ubah Peringkat Inovasi' }}
</h1>

<form method="POST"
      enctype="multipart/form-data"
      action="{{ $mode === 'create'
          ? route('admin.innovation_rankings.store')
          : route('admin.innovation_rankings.update', $ranking->id) }}">
  @csrf
  @if($mode !== 'create')
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
        <label class="fw-bold">Nama Penghargaan</label>
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
        <label class="fw-bold">Sumber</label>
        <input type="url"
              name="reference_link"
              class="form-control"
              value="{{ old('reference_link', $ranking->reference_link) }}"
              placeholder="https://www.ui-greenmetric.org/...">
        @error('reference_link') <small class="text-danger">{{ $message }}</small> @enderror
      </div>


      <div class="col-12">
        <label class="fw-bold">Logo</label>
        <input type="file"
               name="logo"
               class="form-control"
               accept="image/*"
               onchange="previewImage(event, 'logoPreview')">
        @error('logo') <small class="text-danger">{{ $message }}</small> @enderror

        <div class="mt-2">
          <img id="logoPreview"
               src="{{ !empty($ranking->logo) ? asset('storage/'.$ranking->logo) : '' }}"
               class="{{ empty($ranking->logo) ? 'd-none' : '' }}"
               style="max-height:100px;border-radius:8px;"
               alt="Preview Logo">
        </div>
      </div>

      <div class="col-12">
        <label class="fw-bold">Pamflet<span class="text-muted">(opsional)</span></label>
        <input type="file"
               name="pamphlet"
               class="form-control"
               accept="image/*"
               onchange="previewImage(event, 'pamphletPreview')">
        @error('pamphlet') <small class="text-danger">{{ $message }}</small> @enderror

        <div class="mt-2">
          <img id="pamphletPreview"
               src="{{ !empty($ranking->pamphlet) ? asset('storage/'.$ranking->pamphlet) : '' }}"
               class="{{ empty($ranking->pamphlet) ? 'd-none' : '' }}"
               style="max-height:140px;border-radius:8px;"
               alt="Preview Pamflet">
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
function previewImage(event, previewId) {
  const img = document.getElementById(previewId);
  const file = event.target.files && event.target.files[0];
  if (!file) return;

  img.src = URL.createObjectURL(file);
  img.classList.remove('d-none');
}
</script>
@endpush
