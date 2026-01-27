@extends('layouts.admin')
@section('title','Peringkat Inovasi')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">Peringkat Inovasi</h1>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-end mb-3">
  <a href="{{ route('admin.innovation_rankings.create') }}" class="btn btn-navy">
    + Tambah Peringkat
  </a>
</div>

<div class="panel">
  <table class="table table-bordered align-middle mb-0">
    <thead>
      <tr style="background:#061a4d;color:#fff;">
        <th style="width:90px;">Peringkat</th>
        <th style="width:280px;">Nama Penghargaan</th>
        <th>Deskripsi</th>
        <th style="width:160px;">Sumber</th>
        <th style="width:120px;">Logo</th>
        <th style="width:140px;">Foto</th>
        <th style="width:180px;">Aksi</th>
      </tr>
    </thead>

    <tbody>
      @forelse($rankings as $r)
        @php
          $photoUrl = null;

          if (!empty($r->photo)) {
            $photoUrl = asset('storage/'.$r->photo);
          }

          if (!$photoUrl && !empty($r->photos) && count($r->photos)) {
            $p0 = $r->photos[0];
            $path = $p0->path ?? $p0->image_path ?? $p0->photo ?? null;
            if (!empty($path)) {
              $photoUrl = asset('storage/'.$path);
            }
          }
        @endphp

        <tr>
          <td class="text-center fw-bold">#{{ $r->rank }}</td>

          <td style="white-space:normal;">
            {{ $r->achievement ?? '-' }}
          </td>

          <td style="white-space:normal;">
            @if(!empty($r->description))
              {{ $r->description }}
            @else
              <span class="text-muted">-</span>
            @endif
          </td>

          <td class="text-center">
            @if(!empty($r->reference_link))
              <a href="{{ $r->reference_link }}"
                 target="_blank"
                 rel="noopener noreferrer"
                 class="btn btn-sm btn-outline-primary">
                Lihat
              </a>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>

          <td class="text-center">
            @if(!empty($r->logo))
              <img src="{{ asset('storage/'.$r->logo) }}"
                   style="height:42px;border-radius:8px;cursor:pointer;object-fit:contain;"
                   alt="Logo"
                   data-bs-toggle="modal"
                   data-bs-target="#imagePreviewModal"
                   data-image="{{ asset('storage/'.$r->logo) }}">
            @else
              <span class="text-muted">-</span>
            @endif
          </td>

          <td class="text-center">
            @if(!empty($photoUrl))
              <img src="{{ $photoUrl }}"
                   style="height:54px;border-radius:8px;cursor:pointer;object-fit:cover;"
                   alt="Foto"
                   data-bs-toggle="modal"
                   data-bs-target="#imagePreviewModal"
                   data-image="{{ $photoUrl }}">
            @else
              <span class="text-muted">-</span>
            @endif
          </td>

          <td class="text-center">
            <a class="btn btn-sm btn-outline-dark"
               href="{{ route('admin.innovation_rankings.edit', $r->id) }}">
              Edit
            </a>

            <form class="d-inline" method="POST"
                  action="{{ route('admin.innovation_rankings.destroy', $r->id) }}"
                  onsubmit="return confirm('Hapus peringkat ini?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center text-muted">
            Belum ada peringkat.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Preview Gambar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body text-center">
        <img id="previewImage"
             src=""
             style="max-width:100%;max-height:70vh;border-radius:12px;object-fit:contain;"
             alt="Preview Besar">
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('imagePreviewModal');
  if (!modal) return;

  modal.addEventListener('show.bs.modal', function (event) {
    const trigger = event.relatedTarget;
    const imageUrl = trigger?.getAttribute('data-image');
    const img = modal.querySelector('#previewImage');
    if (img && imageUrl) img.src = imageUrl;
  });

  modal.addEventListener('hidden.bs.modal', function () {
    const img = modal.querySelector('#previewImage');
    if (img) img.src = '';
  });
});
</script>
@endpush
