@extends('layouts.admin')
@section('title','Innovation Ranking')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">
  {{ $mode === 'create' ? 'Tambah Innovation Ranking' : 'Edit Innovation Ranking' }}
</h1>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

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
        <label class="fw-bold">Rank</label>
        <input type="number" name="rank" class="form-control"
               value="{{ old('rank', $ranking->rank) }}" min="1" max="100" required>
        @error('rank') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="col-12 col-md-6">
        <label class="fw-bold">Pilih Inovasi</label>
        <select class="form-select js-innovation-select" name="innovation_id" required>
          <option value="">-- pilih inovasi --</option>
          @foreach($innovations as $inv)
            <option value="{{ $inv->id }}"
              @selected((int)old('innovation_id', $ranking->innovation_id) === (int)$inv->id)>
              {{ $inv->title }}
            </option>
          @endforeach
        </select>
        @error('innovation_id') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="col-12 col-md-4">
        <label class="fw-bold">Achievement</label>
        <input name="achievement" class="form-control"
               value="{{ old('achievement', $ranking->achievement) }}"
               placeholder="contoh: Best Innovation Award 2025">
        @error('achievement') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="col-12 col-md-4">
        <label class="fw-bold">Status</label>
        @php
          $statusVal = old('status', $ranking->status ?? 'active');
        @endphp
        <select name="status" class="form-select" required>
          <option value="active" {{ $statusVal === 'active' ? 'selected' : '' }}>Active (Tampil)</option>
          <option value="inactive" {{ $statusVal === 'inactive' ? 'selected' : '' }}>Inactive (Sembunyi)</option>
        </select>
        @error('status') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="col-12 col-md-8">
        <label class="fw-bold">Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        @error('image') <small class="text-danger">{{ $message }}</small> @enderror

        @if(!empty($ranking->image))
          <div class="mt-2 d-flex align-items-center gap-3">
            <img src="{{ asset('storage/'.$ranking->image) }}"
                 alt="ranking image"
                 style="height:90px;border-radius:10px;border:1px solid #e5e7eb;">
            <div style="font-size:12px;color:#6b7280;">
              {{ $ranking->image }}
            </div>
          </div>
        @endif
      </div>

    </div>

    <div class="d-flex justify-content-end mt-4 gap-2">
      <a href="{{ route('admin.innovation_rankings.index') }}"
         class="btn btn-outline-secondary">Kembali</a>
      <button class="btn btn-navy">Simpan</button>
    </div>
  </div>
</form>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(function(){
  $('.js-innovation-select').select2({
    width: '100%',
    placeholder: '-- pilih inovasi --',
    allowClear: true
  });
});
</script>
@endpush
