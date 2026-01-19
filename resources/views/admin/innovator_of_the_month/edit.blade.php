@extends('layouts.admin')
@section('title','Innovator of The Month')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">Innovator The Month</h1>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.innovator_of_month.update') }}" enctype="multipart/form-data">
@csrf

<div class="panel">
  <div class="d-flex justify-content-between align-items-start gap-4 flex-wrap">

    {{-- LEFT PHOTO --}}
    <div style="width:260px;">
      <div style="border:2px solid #061a4d;border-radius:22px;height:320px;display:flex;align-items:center;justify-content:center;overflow:hidden;background:#f3f5ff;">
        @if(!empty($iotm?->photo))
          <img id="photoPreview" src="{{ asset('storage/'.$iotm->photo) }}" alt="photo" style="width:100%;height:100%;object-fit:cover;">
        @else
          <img id="photoPreview" src="" alt="photo" style="width:100%;height:100%;object-fit:cover;display:none;">
          <div id="photoPlaceholder" style="color:#6b7280;font-weight:700;">No Photo</div>
        @endif
      </div>

      <div class="mt-3">
        <input id="photoInput" type="file" name="photo" class="form-control" accept="image/*">
        <small id="photoName" class="text-muted d-block mt-1"></small>
        @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
      </div>
    </div>

    {{-- RIGHT FORM --}}
    <div style="flex:1; min-width: 340px;">

      <div class="mb-3">
        <label class="fw-bold">Pilih Innovator</label>
        <select name="innovator_id" id="innovator-select" class="form-select" required>
          <option value="">-- pilih innovator --</option>
          @foreach($innovators as $innovator)
            <option value="{{ $innovator->id }}"
              @selected(old('innovator_id', $iotm?->innovator_id) == $innovator->id)>
              {{ $innovator->name }} — {{ $innovator->faculty->name ?? '-' }}
            </option>
          @endforeach
        </select>
        @error('innovator_id') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      {{-- PILIH INOVASI (SATU SAJA) --}}
      <div class="mb-3">
        <label class="fw-bold">Pilih Inovasi Unggulan</label>
        <select name="innovation_id" id="innovation-select" class="form-select">
            <option value="">-- pilih inovasi unggulan --</option>
        </select>
        <small class="text-muted">
          * Satu inovasi yang ditampilkan sebagai highlight
        </small>
      </div>

      {{-- DESKRIPSI --}}
      <div class="mb-3">
        <label class="fw-bold">Deskripsi</label>
        <textarea
          class="form-control"
          rows="3"
          name="description"
        >{{ old('description', $iotm?->description) }}</textarea>
        @error('description') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="fw-bold">Bulan</label>
              <select class="form-select" name="month" required>
                @for($m=1;$m<=12;$m++)
                  <option value="{{ $m }}"
                    @selected(old('month', $iotm?->month) == $m)>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                  </option>
                @endfor
              </select>
            </div>

        <div class="col-md-6 mb-3">
          <label class="fw-bold">Tahun</label>
          <input type="number" class="form-control" name="year"
            value="{{ old('year', $iotm?->year ?? now()->year) }}" required>
        </div>
      </div>



      <div class="d-flex justify-content-end">
        <button class="btn btn-navy">Simpan Perubahan</button>
      </div>

    </div>


  </div>
</div>

</form>

<div
    id="innovator-data"
    data-innovations='@json(
        $innovators->mapWithKeys(function ($i) {
            return [
                $i->id => $i->innovations->map(function ($inv) {
                    return [
                        "id" => $inv->id,
                        "title" => $inv->title
                    ];
                })
            ];
        })
    )'
    data-selected="{{ old('innovation_id', $iotm?->innovation_id) }}"
></div>

<script>
    const el = document.getElementById('innovator-data');
    window.innovatorInnovations = JSON.parse(el.dataset.innovations);
    window.selectedInnovationId = el.dataset.selected;
</script>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    $('.js-innovation-select').select2({
        width: '100%',
        placeholder: '-- pilih inovasi --',
        allowClear: true
    });
});
</script>

<script>
    const innovatorSelect  = document.getElementById('innovator-select');
    const innovationSelect = document.getElementById('innovation-select');

    function updateInnovationOptions(innovatorId) {
        innovationSelect.innerHTML = '<option value="">-- pilih inovasi unggulan --</option>';

        if (!innovatorId || !innovatorInnovations[innovatorId]) return;

        innovatorInnovations[innovatorId].forEach(inv => {
            const opt = document.createElement('option');
            opt.value = inv.id;
            opt.textContent = inv.title;

            if (inv.id == selectedInnovationId) {
                opt.selected = true;
            }

            innovationSelect.appendChild(opt);
        });
    }

    // saat ganti innovator
    innovatorSelect.addEventListener('change', e => {
        updateInnovationOptions(e.target.value);
    });

    // saat page load (EDIT / reload error)
    document.addEventListener('DOMContentLoaded', () => {
        updateInnovationOptions(innovatorSelect.value);
    });
</script>
@endpush
