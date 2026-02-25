@extends('layouts.admin')
@section('title','Detail Permission')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">Persetujuan Inovasi</h1>
<p style="font-weight:700;color:#061a4d;">Detail</p>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@php
  $permStatus = optional($innovation->permission)->status;
  $photos = $innovation->images ?? collect();
  $innovatorList = $innovation->innovators ?? collect();

  $categoryDisplay = $innovation->category ?? '-';
  if (($innovation->category ?? '') === 'other') {
    $categoryDisplay = 'Inovasi Lainnya';
  }

  $kiType = $innovation->ki_type ?? null;
  $kiStatus = $innovation->ki_status ?? null;
  $kiNumber = trim((string)($innovation->ki_number ?? '')) ?: null;

  if (!$kiType) $kiType = $innovation->hki_type ?? null;
  if (!$kiStatus) $kiStatus = $innovation->hki_status ?? null;

  if (!$kiNumber) {
    if (in_array(($kiStatus ?? ''), ['terdaftar','on_process'], true)) {
      $kiNumber = trim((string)($innovation->hki_registration_number ?? '')) ?: null;
    } elseif (($kiStatus ?? '') === 'granted') {
      $kiNumber = trim((string)($innovation->hki_patent_number ?? '')) ?: null;
    }
  }

  $kiTypeLabelMap = [
    'paten' => 'Paten',
    'hak_cipta' => 'Hak Cipta',
    'desain_industri' => 'Desain Industri',
    'merek' => 'Merek',
  ];
  $kiTypeLabel = $kiType ? ($kiTypeLabelMap[$kiType] ?? $kiType) : '-';

  $kiNumberLabel = 'Nomor KI';
  if ($kiType && $kiStatus) {
    if ($kiType === 'hak_cipta') {
      if ($kiStatus === 'terdaftar' || $kiStatus === 'on_process') $kiNumberLabel = 'Nomor Pendaftaran';
      if ($kiStatus === 'granted') $kiNumberLabel = 'Nomor Surat Pencatatan Hak Cipta';
    } else {
      if ($kiStatus === 'terdaftar' || $kiStatus === 'on_process') $kiNumberLabel = 'Nomor Pendaftaran';
      if ($kiStatus === 'granted') $kiNumberLabel = 'Nomor Sertifikat';
    }
  }
@endphp

<div class="panel">
  <div class="row g-4 align-items-start">
    <div class="col-12 col-lg-8">
      <h3 class="perm-title">{{ $innovation->title }}</h3>

      <div class="perm-grid">
        <div class="label">Nama Inovator</div>
        <div class="value">
          @if($innovatorList->count())
            <ol class="perm-ol">
              @foreach($innovatorList as $inv)
                <li>
                  {{ $inv->name ?? '-' }}
                  <span class="faculty">- {{ $inv->faculty?->name ?? '-' }}</span>
                </li>
              @endforeach
            </ol>
          @else
            <span class="text-muted">-</span>
          @endif
        </div>

        <div class="label">Kategori</div>
        <div class="value">{{ $categoryDisplay }}</div>

        <div class="label">Mitra</div>
        <div class="value">{{ $innovation->partner ?? '-' }}</div>

        <div class="label">Status KI</div>
        <div class="value">
          <div>{{ $kiTypeLabel }}</div>
          <div>{{ $kiStatus ?? '-' }}</div>
        </div>

        <div class="label">{{ $kiNumberLabel }}</div>
        <div class="value">{{ $kiNumber ?? '-' }}</div>

        <div class="label">Link Inovasi</div>
        <div class="value">
          @if(!empty($innovation->video_url))
            <a href="{{ $innovation->video_url }}" target="_blank" rel="noopener noreferrer">
              {{ $innovation->video_url }}
            </a>
          @else
            <span class="text-muted">-</span>
          @endif
        </div>
      </div>

      <div class="mt-3">
        <div class="perm-section-title">Deskripsi</div>
        <div class="text-block">{{ $innovation->description ?? '-' }}</div>
      </div>

      <div class="mt-3">
        <div class="perm-section-title">Keunggulan</div>
        <div class="text-block">{{ $innovation->advantages ?? '-' }}</div>
      </div>

      <div class="mt-3">
        <div class="perm-section-title">Keberdampakan</div>
        <div class="text-block">{{ $innovation->impact ?? '-' }}</div>
      </div>

      <div class="mt-4 d-flex gap-2 align-items-center flex-wrap">
        <a class="btn btn-outline-secondary" href="{{ route('admin.permissions.index') }}">Kembali</a>

        <form method="POST" action="{{ route('admin.permissions.accept', $innovation->id) }}">
          @csrf
          <button type="submit" class="btn btn-success">Terima</button>
        </form>

        <form method="POST" action="{{ route('admin.permissions.decline', $innovation->id) }}">
          @csrf
          <button type="submit" class="btn btn-danger">Tolak</button>
        </form>

        <div class="ms-auto">
          @if($permStatus === 'accepted')
            <span class="badge bg-success">Diterima</span>
          @elseif($permStatus === 'declined')
            <span class="badge bg-danger">Ditolak</span>
          @elseif($permStatus === 'pending')
            <span class="badge bg-warning text-dark">Pending</span>
          @else
            <span class="badge bg-secondary">Belum direview</span>
          @endif
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="photo-card">
        <div class="photo-title">Foto</div>

        @if($photos->count())
          <div class="photo-grid">
            @foreach($photos as $p)
              <a href="{{ asset('storage/'.$p->image_path) }}"
                 target="_blank"
                 rel="noopener noreferrer"
                 class="photo-item">
                <img src="{{ asset('storage/'.$p->image_path) }}" alt="Foto inovasi">
              </a>
            @endforeach
          </div>
        @else
          <div class="photo-empty">Belum ada foto</div>
        @endif
      </div>
    </div>
  </div>
</div>

<style>
.panel{
  background:#fff;
  border:2px solid #061a4d;
  border-radius:18px;
  padding:18px;
}
.perm-title{
  font-weight:900;
  color:#061a4d;
  margin-bottom:12px;
  font-size:24px;
  line-height:1.2;
}
.perm-grid{
  display:grid;
  grid-template-columns: 180px 1fr;
  gap:10px 12px;
  font-size:15px;
  color:#0f172a;
}
.perm-grid .label{
  font-weight:900;
  color:#061a4d;
}
.perm-grid .value{
  font-weight:500;
  color:#0f172a;
}
.perm-ol{
  margin:6px 0 0 18px;
  padding-left:18px;
}
.perm-ol li{
  margin-bottom:6px;
  font-size:15px;
  font-weight:500;
  color:#0f172a;
}
.perm-ol .faculty{
  color:#061a4d;
  opacity:.8;
  font-weight:600;
}
.perm-section-title{
  font-weight:900;
  color:#061a4d;
  margin-top:14px;
  margin-bottom:6px;
  font-size:16px;
}
.text-block{
  white-space:pre-wrap;
  line-height:1.6;
  color:#0f172a;
}
.photo-card{
  border:2px solid #061a4d;
  border-radius:18px;
  padding:14px;
  background:#fff;
}
.photo-title{
  font-weight:900;
  color:#061a4d;
  margin-bottom:10px;
}
.photo-grid{
  display:grid;
  grid-template-columns:repeat(2, 1fr);
  gap:10px;
  max-height:540px;
  overflow:auto;
  padding-right:4px;
}
.photo-item{
  border:1px solid rgba(6,26,77,.25);
  border-radius:14px;
  overflow:hidden;
  background:#fff;
  display:block;
}
.photo-item img{
  width:100%;
  height:150px;
  object-fit:cover;
  display:block;
}
.photo-empty{
  height:220px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:800;
  border:2px dashed #cbd5e1;
  border-radius:18px;
  color:#64748b;
}
@media (max-width: 992px){
  .perm-grid{
    grid-template-columns: 1fr;
  }
}
</style>
@endsection
