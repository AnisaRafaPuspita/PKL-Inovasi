@extends('layouts.admin')
@section('title','Detail Permission')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">Persetujuan Inovasi</h1>
<p style="font-weight:700;color:#061a4d;">Detail</p>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@php
    $first = $innovation->innovators->first();
    $permStatus = optional($innovation->permission)->status;
    $photos = $innovation->images ?? collect();
    $innovatorList = $innovation->innovators ?? collect();

    $showRegNumber = in_array(($innovation->hki_status ?? ''), ['terdaftar', 'on_process'], true)
        && trim((string)($innovation->hki_registration_number ?? '')) !== '';

    $showPatentNumber = (($innovation->hki_status ?? '') === 'granted')
        && trim((string)($innovation->hki_patent_number ?? '')) !== '';
@endphp

<div class="panel">
    <div class="d-flex justify-content-between align-items-start gap-4">
        <div style="flex:1;">
            <h3 class="perm-title">
                {{ $innovation->title }}
            </h3>

            <div class="perm-row">
                <b>Nama Innovator:</b>
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
                    <span class="val">-</span>
                @endif
            </div>

            <div class="perm-row"><b>Fakultas:</b> <span class="val">{{ $first?->faculty?->name ?? '-' }}</span></div>
            <div class="perm-row"><b>Kategori:</b> <span class="val">{{ $innovation->category ?? '-' }}</span></div>
            <div class="perm-row"><b>Mitra:</b> <span class="val">{{ $innovation->partner ?? '-' }}</span></div>
            <div class="perm-row"><b>Status Paten:</b> <span class="val">{{ $innovation->hki_status ?? '-' }}</span></div>

            @if($showRegNumber)
                <div class="perm-row"><b>Nomor Pendaftaran HKI:</b> <span class="val">{{ $innovation->hki_registration_number }}</span></div>
            @endif

            @if($showPatentNumber)
                <div class="perm-row"><b>Nomor Paten:</b> <span class="val">{{ $innovation->hki_patent_number }}</span></div>
            @endif

            <div class="perm-row">
                <b>Link Inovasi:</b>
                <span class="val">
                    @if($innovation->video_url)
                        <a href="{{ $innovation->video_url }}" target="_blank" rel="noopener noreferrer">
                            {{ $innovation->video_url }}
                        </a>
                    @else
                        -
                    @endif
                </span>
            </div>

            <div class="mt-3">
                <div class="perm-section-title">Deskripsi</div>
                <div class="val">{{ $innovation->description ?? '-' }}</div>
            </div>

            <div class="mt-3">
                <div class="perm-section-title">Keunggulan</div>
                <div class="val">{{ $innovation->advantages ?? '-' }}</div>
            </div>

            <div class="mt-3">
                <div class="perm-section-title">Keberdampakan</div>
                <div class="val">{{ $innovation->impact ?? '-' }}</div>
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
                        <span class="badge bg-secondary">Belum di review</span>
                    @endif
                </div>
            </div>
        </div>

        <div style="width:360px;">
            @if($photos->count())
                <div style="
                    display:grid;
                    grid-template-columns:repeat(2, 1fr);
                    gap:10px;
                ">
                    @foreach($photos as $p)
                        <a href="{{ asset('storage/'.$p->image_path) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           style="
                             border:2px solid #061a4d;
                             border-radius:14px;
                             overflow:hidden;
                             background:#fff;
                           ">
                            <img src="{{ asset('storage/'.$p->image_path) }}"
                                 style="width:100%;height:150px;object-fit:cover;display:block;">
                        </a>
                    @endforeach
                </div>
            @else
                <div style="
                    height:220px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-weight:800;
                    border:2px solid #061a4d;
                    border-radius:18px;
                ">
                    No Photo
                </div>
            @endif
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
}


.perm-row{
  margin-bottom:4px;
  font-size:15px;
  color:#0f172a;
}

.perm-row b{
  font-weight:900;
  color:#061a4d;
}

.perm-row .val{
  font-weight:500;
  color:#0f172a;
}

.perm-ol{
  margin:6px 0 0 18px;
  padding-left:18px;
}

.perm-ol li{
  margin-bottom:3px;
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
</style>
@endsection
