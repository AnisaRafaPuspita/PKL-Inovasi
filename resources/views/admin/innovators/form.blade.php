@extends('layouts.admin')
@section('title', $mode === 'create' ? 'Tambah Highlight Innovator' : 'Edit Highlight Innovator')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
  <h1 class="m-0" style="font-weight:900;color:#061a4d;">
    {{ $mode === 'create' ? 'Tambah Highlight Innovator' : 'Edit Highlight Innovator' }}
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
        : route('admin.innovators.update', $iotm->id) }}"
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
            $photo = old('photo') ? null : ($iotm->photo ?? null);
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
          @php $activeVal = old('is_active', $iotm->is_active ?? 1); @endphp
          <div class="status-dd {{ ((int)$activeVal === 1) ? 'is-on' : 'is-off' }}">
            <select name="is_active" class="status-select" aria-label="Status Highlight Innovator">
              <option value="1" {{ (int)$activeVal === 1 ? 'selected' : '' }}>Aktif</option>
              <option value="0" {{ (int)$activeVal === 0 ? 'selected' : '' }}>Nonaktif</option>
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
          <label class="fw-bold">Pilih Innovator</label>
          <select name="innovator_id" id="innovatorSelect" class="form-select" required>
            <option value="">-- pilih innovator --</option>
            @foreach($innovators as $inv)
              <option value="{{ $inv->id }}" @selected(old('innovator_id', $iotm->innovator_id) == $inv->id)>
                {{ $inv->name }} — {{ $inv->faculty->name ?? '-' }}
              </option>
            @endforeach
          </select>
          @error('innovator_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
          <label class="fw-bold">Inovasi yang di-highlight</label>
          <select name="innovation_id" id="innovationSelect" class="form-select" required>
            <option value="">-- pilih inovasi --</option>

            @php
              $selectedInnovatorId = old('innovator_id', $iotm->innovator_id);
              $preloadInnovations = collect();

              if ($selectedInnovatorId) {
                $found = $innovators->firstWhere('id', (int)$selectedInnovatorId);
                $preloadInnovations = $found ? ($found->innovations ?? collect()) : collect();
              }
            @endphp

            @foreach($preloadInnovations as $inn)
              <option value="{{ $inn->id }}" @selected(old('innovation_id', $iotm->innovation_id) == $inn->id)>
                {{ $inn->title ?? $inn->name ?? ('Inovasi #'.$inn->id) }}
              </option>
            @endforeach
          </select>
          @error('innovation_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
          <label class="fw-bold">Deskripsi</label>
          <textarea class="form-control"
                    rows="5"
                    name="description"
                    placeholder="Tulis deskripsi singkat highlight innovator"
                    required>{{ old('description', $iotm->description) }}</textarea>
          @error('description') <small class="text-danger">{{ $message }}</small> @enderror
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

@php
  $innovatorsJson = $innovators->map(function ($inv) {
    return [
      'id' => $inv->id,
      'innovations' => $inv->innovations->map(function ($inn) {
        return [
          'id' => $inn->id,
          'label' => $inn->title ?? $inn->name ?? ('Inovasi #'.$inn->id),
        ];
      })->values()->all(),
    ];
  })->values()->all();
@endphp

<script id="innovatorsData" type="application/json">
{!! json_encode($innovatorsJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

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

  const innovators = JSON.parse(document.getElementById('innovatorsData').textContent || '[]');

  const innovatorSelect = document.getElementById('innovatorSelect');
  const innovationSelect = document.getElementById('innovationSelect');

  function setInnovationOptions(innovatorId, selectedInnovationId = null) {
    if (!innovationSelect) return;

    innovationSelect.innerHTML = '<option value="">-- pilih inovasi --</option>';

    const inv = innovators.find(x => String(x.id) === String(innovatorId));
    if (!inv) return;

    inv.innovations.forEach(inn => {
      const opt = document.createElement('option');
      opt.value = inn.id;
      opt.textContent = inn.label;
      if (selectedInnovationId && String(selectedInnovationId) === String(inn.id)) {
        opt.selected = true;
      }
      innovationSelect.appendChild(opt);
    });
  }

  if (innovatorSelect) {
    innovatorSelect.addEventListener('change', (e) => {
      setInnovationOptions(e.target.value, null);
    });

    const preSelectedInnovator = innovatorSelect.value;
    const preSelectedInnovation = "{{ old('innovation_id', $iotm->innovation_id) }}";
    if (preSelectedInnovator) {
      setInnovationOptions(preSelectedInnovator, preSelectedInnovation);
    }
  }
</script>
@endsection