@extends('layouts.admin')
@section('title', $mode === 'create' ? 'Tambah Innovator' : 'Edit Innovator')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">
  {{ $mode === 'create' ? 'Tambah Innovator' : 'Edit Innovator' }}
</h1>

@if(session('success'))
  <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<form method="POST"
      action="{{ $mode === 'create'
        ? route('admin.innovator_of_month.store')
        : route('admin.innovator_of_month.update', $iotm->id) }}"
      enctype="multipart/form-data"
      class="mt-3">
  @csrf
  @if($mode === 'edit')
    @method('PUT')
  @endif

  <div class="panel">
    <div class="d-flex justify-content-between align-items-start gap-4 flex-wrap">

      <div style="width:260px;">
        <div style="border:2px solid #061a4d;border-radius:22px;height:320px;display:flex;align-items:center;justify-content:center;overflow:hidden;background:#f3f5ff;">
          @if(!empty($iotm->photo))
            <img id="photoPreview" src="{{ asset('storage/'.$iotm->photo) }}" alt="photo" style="width:100%;height:100%;object-fit:cover;">
          @else
            <img id="photoPreview" src="" alt="photo" style="width:100%;height:100%;object-fit:cover;display:none;">
            <div id="photoPlaceholder" style="color:#6b7280;font-weight:700;">No Photo</div>
          @endif
        </div>

        <div class="mt-3">
          <input id="photoInput" type="file" name="photo" class="form-control" accept="image/*">
          @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
          @if($mode === 'edit')
            <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengganti foto</small>
          @endif
        </div>
      </div>

      <div style="flex:1; min-width: 340px;">

        <div class="mb-3">
          <label class="fw-bold">Pilih Innovator</label>
          <select name="innovator_id" id="innovator-select" class="form-select" required>
            <option value="">-- pilih innovator --</option>
            @foreach($innovators as $innovator)
              <option value="{{ $innovator->id }}"
                @selected(old('innovator_id', $iotm->innovator_id) == $innovator->id)>
                {{ $innovator->name }} — {{ $innovator->faculty->name ?? '-' }}
              </option>
            @endforeach
          </select>
          @error('innovator_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
          <label class="fw-bold">Pilih Inovasi Unggulan</label>
          <select name="innovation_id" id="innovation-select" class="form-select" required>
            <option value="">-- pilih inovasi unggulan --</option>
          </select>
          @error('innovation_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
          <label class="fw-bold">Deskripsi</label>
          <textarea class="form-control" rows="3" name="description">{{ old('description', $iotm->description) }}</textarea>
          @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="d-flex justify-content-end">
          <button class="btn btn-navy">
            {{ $mode === 'create' ? 'Simpan' : 'Update' }}
          </button>
        </div>

      </div>
    </div>
  </div>
</form>

<div
  id="innovator-data"
  data-innovations='@json(
    $innovators->mapWithKeys(fn ($i) => [
      $i->id => $i->innovations->map(fn ($inv) => ["id" => $inv->id, "title" => $inv->title])
    ])
  )'
  data-selected="{{ old('innovation_id', $iotm->innovation_id) }}"
></div>

<script>
  const el = document.getElementById('innovator-data');
  const innovatorInnovations = JSON.parse(el.dataset.innovations || '{}');
  const selectedInnovationId = el.dataset.selected;

  const innovatorSelect  = document.getElementById('innovator-select');
  const innovationSelect = document.getElementById('innovation-select');

  function updateInnovationOptions(innovatorId) {
    innovationSelect.innerHTML = '<option value="">-- pilih inovasi unggulan --</option>';
    if (!innovatorId || !innovatorInnovations[innovatorId]) return;

    innovatorInnovations[innovatorId].forEach(inv => {
      const opt = document.createElement('option');
      opt.value = inv.id;
      opt.textContent = inv.title;
      if (String(inv.id) === String(selectedInnovationId)) opt.selected = true;
      innovationSelect.appendChild(opt);
    });
  }

  innovatorSelect.addEventListener('change', e => updateInnovationOptions(e.target.value));
  document.addEventListener('DOMContentLoaded', () => updateInnovationOptions(innovatorSelect.value));

  const photoInput = document.getElementById('photoInput');
  const photoPreview = document.getElementById('photoPreview');
  const photoPlaceholder = document.getElementById('photoPlaceholder');

  if (photoInput) {
    photoInput.addEventListener('change', (e) => {
      const file = e.target.files?.[0];
      if (!file) return;
      const url = URL.createObjectURL(file);
      photoPreview.src = url;
      photoPreview.style.display = 'block';
      if (photoPlaceholder) photoPlaceholder.style.display = 'none';
    });
  }
</script>
@endsection
