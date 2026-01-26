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
        <th style="width:320px;">Prestasi</th>
        <th>Deskripsi</th>
        <th style="width:140px;">Gambar</th>
        <th style="width:180px;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rankings as $r)
        <tr>
          <td class="text-center fw-bold">#{{ $r->rank }}</td>

          <td>{{ $r->achievement ?? '-' }}</td>

          <td>
            @if(!empty($r->description))
              {{ $r->description }}
            @else
              <span class="text-muted">-</span>
            @endif
          </td>

          <td class="text-center">
            @if(!empty($r->image))
              <img src="{{ asset('storage/'.$r->image) }}"
                   style="height:44px;border-radius:8px;cursor:pointer;"
                   alt="Preview"
                   data-bs-toggle="modal"
                   data-bs-target="#imagePreviewModal"
                   data-image="{{ asset('storage/'.$r->image) }}">
            @else
              <span class="text-muted">-</span>
            @endif
          </td>

          <td class="text-center">
            <a class="btn btn-sm btn-outline-dark"
               href="{{ route('admin.innovation_rankings.edit', $r->id) }}">Edit</a>

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
          <td colspan="5" class="text-center text-muted">Belum ada peringkat.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<!-- Modal Preview Gambar -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Preview Gambar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body text-center">
        <img id="previewImage" src=""
             style="max-width:100%;max-height:70vh;border-radius:12px;"
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
