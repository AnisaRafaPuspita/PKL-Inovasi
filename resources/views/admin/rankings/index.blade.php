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
    <thead class="custom-thead">
      <tr>
        <th style="width:140px;" class="text-center">Peringkat</th>
        <th style="width:120px;" class="text-center">Tahun</th>
        <th style="width:180px;" class="text-center">Nama Penghargaan</th>
        <th style="width:180px;" class="text-center">Deskripsi</th>
        <th style="width:140px;" class="text-center">Sumber</th>
        <th style="width:140px;" class="text-center">Logo</th>
        <th style="width:140px;" class="text-center">Foto</th>
        <th style="width:200px;" class="text-center">Aksi</th>
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

          <td class="text-center fw-bold">
            {{ $r->year ?? '-' }}
          </td>

          <td style="white-space:normal;">
            {{ $r->achievement ?? '-' }}
          </td>

          <td style="white-space:normal;">
            @if(!empty($r->description))
              @php $limit = 150; @endphp

              <span class="desc-short">
                {{ \Illuminate\Support\Str::limit($r->description, $limit) }}
              </span>

              <span class="desc-full d-none">
                {{ $r->description }}
              </span>

              @if(mb_strlen($r->description) > $limit)
                <br>
                <a href="javascript:void(0);" class="toggle-desc text-primary" style="font-size:13px;">
                  Selengkapnya
                </a>
              @endif

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
            <div class="action-stack">
              <div class="action-top">
                <a class="btn btn-sm btn-edit"
                  href="{{ route('admin.innovation_rankings.edit', $r->id) }}">
                  Edit
                </a>

                <form method="POST"
                      action="{{ route('admin.innovation_rankings.destroy', $r->id) }}"
                      onsubmit="return confirm('Hapus peringkat ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-delete">Hapus</button>
                </form>
              </div>

              <form method="POST"
                    action="{{ route('admin.innovation_rankings.status', $r->id) }}">
                @csrf
                @method('PATCH')

                <div class="status-wrapper">
                  <select name="is_active"
                          class="status-dropdown {{ $r->is_active ? 'status-active' : 'status-inactive' }}"
                          onchange="this.form.submit()">
                    <option value="1" {{ $r->is_active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$r->is_active ? 'selected' : '' }}>Nonaktif</option>
                  </select>
                </div>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8" class="text-center text-muted">
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
  if (modal) {
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
  }

  document.querySelectorAll('.toggle-desc').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const td = this.closest('td');
      const shortEl = td.querySelector('.desc-short');
      const fullEl  = td.querySelector('.desc-full');

      if (fullEl.classList.contains('d-none')) {
        shortEl.classList.add('d-none');
        fullEl.classList.remove('d-none');
        this.textContent = 'Sembunyikan';
      } else {
        shortEl.classList.remove('d-none');
        fullEl.classList.add('d-none');
        this.textContent = 'Selengkapnya';
      }
    });
  });
});
</script>

<style>
thead.custom-thead th{
  background:#061a4d !important;
  color:#fff !important;
  font-weight:800;
  text-transform:uppercase;
  font-size:13px;
  letter-spacing:.5px;
  padding:14px 12px;
  border-color: rgba(6,26,77,.4)!important;
}

.action-group{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  flex-wrap:wrap;
}

.action-group form{
  margin:0;
}

.action-group .btn{
  margin:0;
}

.status-wrapper{
  display:inline-block;
  position:relative;
  margin-left:0 !important;
}

.status-dropdown{
  appearance:none;
  -webkit-appearance:none;
  -moz-appearance:none;
  padding:6px 32px 6px 16px;
  border-radius:999px;
  font-size:13px;
  font-weight:500;
  cursor:pointer;
  border:1px solid;
  transition:all .2s ease;
}

.status-wrapper::after{
  content:"▼";
  font-size:10px;
  position:absolute;
  right:12px;
  top:50%;
  transform:translateY(-50%);
  pointer-events:none;
  opacity:.7;
}

.status-active{
  background:#e6f4ea;
  border-color:#b7ebc6;
  color:#1e7e34;
}

.status-inactive{
  background:#fdecea;
  border-color:#f5c2c7;
  color:#b02a37;
}

.action-stack{
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  gap:10px;
}

.action-top{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  flex-wrap:wrap;
}

.action-top form{
  margin:0;
}

.btn-edit{
  background:#061a4d;
  color:#fff;
  border:1px solid #061a4d;
  font-weight:700;
  border-radius:10px;
  padding:6px 14px;
}

.btn-edit:hover{
  color:#fff;
  background:#082766;
  border-color:#082766;
}

.btn-delete{
  background:#fff;
  color:#ef4444;
  border:1px solid #f87171;
  font-weight:700;
  border-radius:10px;
  padding:6px 14px;
}

.btn-delete:hover{
  background:#fff5f5;
  color:#dc2626;
  border-color:#ef4444;
}

.status-wrapper{
  display:inline-block;
  position:relative;
  margin-left:0 !important;
}

.status-dropdown{
  appearance:none;
  -webkit-appearance:none;
  -moz-appearance:none;
  min-width:112px;
  padding:6px 32px 6px 16px;
  border-radius:999px;
  font-size:13px;
  font-weight:600;
  cursor:pointer;
  border:1px solid;
  transition:all .2s ease;
  text-align:center;
  text-align-last:center;
}
</style>
@endpush