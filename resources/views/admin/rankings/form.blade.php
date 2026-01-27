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
               min="1" max="2000">
        @error('rank') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="col-12 col-md-10">
        <label class="fw-bold">Nama Penghargaan</label>
        <input type="text"
               name="achievement"
               class="form-control"
               value="{{ old('achievement', $ranking->achievement) }}">
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
               placeholder="https://...">
        @error('reference_link') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="col-12">
        <label class="fw-bold">Logo</label>
        <input type="file"
               id="logoInput"
               name="logo"
               class="form-control"
               accept="image/*"
               onchange="previewSingleImage(event, 'logoPreview')">
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
        <label class="fw-bold">
          Foto <span class="text-muted fw-normal">(Opsional)</span>
        </label>

        <input type="file"
               id="photosInput"
               name="photos[]"
               class="form-control"
               accept="image/*"
               multiple>

        @error('photos') <small class="text-danger">{{ $message }}</small> @enderror
        @error('photos.*') <small class="text-danger d-block">{{ $message }}</small> @enderror

        <div class="mt-3">
          <div class="fw-bold mb-2" style="color:#061a4d;">Preview Foto</div>

          <div id="photoEmpty"
               style="height:120px;display:flex;align-items:center;justify-content:center;border:2px dashed #cbd5e1;border-radius:12px;">
            <span class="text-muted fw-bold">Belum ada foto dipilih</span>
          </div>

          <div id="photoPreviewGrid"
               class="d-grid"
               style="display:none;grid-template-columns:repeat(4,1fr);gap:10px;"></div>
        </div>

        @if($mode !== 'create' && $ranking->photos->isNotEmpty())
          <div class="mt-3">
            <div class="fw-bold mb-2" style="color:#061a4d;">Foto Tersimpan Saat Ini</div>

            <div class="d-grid" style="grid-template-columns:repeat(4,1fr);gap:10px;">
              @foreach($ranking->photos as $p)
                <div class="saved-photo-card"
                    style="position:relative;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;height:120px;">

                  <img src="{{ asset('storage/'.$p->path) }}"
                      style="width:100%;height:100%;object-fit:cover;display:block;"
                      alt="Foto tersimpan">

                  <input type="checkbox"
                        class="d-none delete-photo-check"
                        name="delete_photo_ids[]"
                        value="{{ $p->id }}">

                  <button type="button"
                          class="remove-saved-photo"
                          style="position:absolute;top:6px;right:6px;width:26px;height:26px;border-radius:999px;border:0;font-weight:900;cursor:pointer;background:rgba(0,0,0,0.7);color:#fff;display:flex;align-items:center;justify-content:center;"
                          aria-label="Hapus foto">
                    ×
                  </button>
                </div>
              @endforeach
            </div>
        @endif



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
function previewSingleImage(event, previewId) {
  const img = document.getElementById(previewId);
  const file = event.target.files && event.target.files[0];

  if (!file) {
    if (img) {
      img.src = '';
      img.classList.add('d-none');
    }
    return;
  }

  img.src = URL.createObjectURL(file);
  img.classList.remove('d-none');
}

document.querySelectorAll('.remove-saved-photo').forEach(btn => {
  btn.addEventListener('click', () => {
    const card = btn.closest('.saved-photo-card');
    if (!card) return;

    const chk = card.querySelector('.delete-photo-check');
    if (chk) chk.checked = true;

    card.style.display = 'none';
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('photosInput');
  const grid = document.getElementById('photoPreviewGrid');
  const empty = document.getElementById('photoEmpty');

  if (!input || !grid || !empty) return;

  let selectedFiles = [];

  function syncInputFiles() {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    input.files = dt.files;
  }

  function render() {
    grid.innerHTML = '';

    if (!selectedFiles.length) {
      grid.style.display = 'none';
      empty.style.display = 'flex';
      return;
    }

    empty.style.display = 'none';
    grid.style.display = 'grid';

    selectedFiles.forEach((file, idx) => {
      const url = URL.createObjectURL(file);

      const wrap = document.createElement('div');
      wrap.style.position = 'relative';
      wrap.style.border = '1px solid #e5e7eb';
      wrap.style.borderRadius = '12px';
      wrap.style.overflow = 'hidden';
      wrap.style.background = '#fff';
      wrap.style.height = '120px';

      const img = document.createElement('img');
      img.src = url;
      img.style.width = '100%';
      img.style.height = '100%';
      img.style.objectFit = 'cover';
      img.style.display = 'block';

      const rm = document.createElement('button');
      rm.type = 'button';
      rm.textContent = '×';
      rm.style.position = 'absolute';
      rm.style.top = '6px';
      rm.style.right = '6px';
      rm.style.width = '26px';
      rm.style.height = '26px';
      rm.style.borderRadius = '999px';
      rm.style.border = '0';
      rm.style.fontWeight = '900';
      rm.style.cursor = 'pointer';
      rm.style.background = 'rgba(0,0,0,0.7)';
      rm.style.color = '#fff';
      rm.style.display = 'flex';
      rm.style.alignItems = 'center';
      rm.style.justifyContent = 'center';
      rm.setAttribute('aria-label', 'Hapus foto');

      rm.addEventListener('click', () => {
        selectedFiles.splice(idx, 1);
        syncInputFiles();
        render();
      });

      wrap.appendChild(img);
      wrap.appendChild(rm);
      grid.appendChild(wrap);

      img.addEventListener('load', () => URL.revokeObjectURL(url));
    });
  }

  input.addEventListener('change', () => {
    const files = Array.from(input.files || []);
    if (!files.length) return;

    files.forEach(f => {
      if (!f.type || !f.type.startsWith('image/')) return;

      const exists = selectedFiles.some(x =>
        x.name === f.name && x.size === f.size && x.lastModified === f.lastModified
      );
      if (!exists) selectedFiles.push(f);
    });

    syncInputFiles();
    render();

  });

  render();
});
</script>
@endpush
