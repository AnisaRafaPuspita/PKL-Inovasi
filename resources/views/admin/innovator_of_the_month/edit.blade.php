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
        <label class="fw-bold">Nama Innovator</label>
        <input class="form-control" name="innovator_name" value="{{ old('innovator_name', $iotm?->innovator_name) }}">
        @error('innovator_name') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="fw-bold">Asal Fakultas</label>
        <input class="form-control" name="faculty" value="{{ old('faculty', $iotm?->faculty) }}">
        @error('faculty') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="fw-bold">Deskripsi</label>
        <textarea class="form-control" rows="3" name="description">{{ old('description', $iotm?->description) }}</textarea>
        @error('description') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="mb-3">
        <label class="fw-bold">Pilih Inovasi</label>
        <select class="form-select js-innovation-select" name="innovation_id">
          <option value="">-- pilih inovasi --</option>
          @foreach($innovations as $inv)
            <option value="{{ $inv->id }}"
              @selected((int)old('innovation_id', $iotm?->innovation_id) === (int)$inv->id)>
              {{ $inv->title }}
            </option>
          @endforeach
        </select>
        @error('innovation_id') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="d-flex justify-content-end">
        <button class="btn btn-navy">Simpan Perubahan</button>
      </div>
    </div>

  </div>
</div>

</form>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
  /* bikin select2 nyatu sama style input bootstrap */
  .select2-container--default .select2-selection--single{
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: .375rem;
    display:flex;
    align-items:center;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered{
    padding-left: .75rem;
    padding-right: 2.25rem;
    line-height: 38px;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow{
    height: 38px;
    right: .75rem;
  }
</style>

<script>
$(function(){
  $('.js-innovation-select').select2({
    width: '100%',
    placeholder: '-- pilih inovasi --',
    allowClear: true
  });

  // preview foto saat dipilih
  const input = document.getElementById('photoInput');
  const preview = document.getElementById('photoPreview');
  const placeholder = document.getElementById('photoPlaceholder');
  const fileName = document.getElementById('photoName');

  if(input){
    input.addEventListener('change', function(){
      const file = this.files && this.files[0];
      if(!file) return;

      fileName.textContent = file.name;

      const reader = new FileReader();
      reader.onload = (e) => {
        preview.src = e.target.result;
        preview.style.display = 'block';
        if(placeholder) placeholder.style.display = 'none';
      };
      reader.readAsDataURL(file);
    });
  }
});
</script>
@endpush
