@extends('layouts.admin')
@section('title','Data Innovator')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
  <h1 class="m-0" style="font-weight:900;color:#061a4d;">Data Innovator</h1>

  <a href="{{ route('admin.innovators.create') }}" class="btn btn-navy">
    Tambah Innovator
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

@if(session('error'))
  <div class="alert alert-danger mt-3">{{ session('error') }}</div>
@endif

<div class="panel mt-3">
  <div class="table-responsive">
    <table class="table align-middle mb-0 innovator-table">
      <thead class="custom-thead">
        <tr>
          <th style="width:110px;" class="text-center">Foto</th>
          <th style="min-width:200px;" class="text-center">Nama</th>
          <th style="min-width:180px;" class="text-center">Fakultas</th>
          <th style="min-width:280px;" class="text-center">Deskripsi</th>
          <th style="min-width:260px;" class="text-center">Inovasi</th>
          <th style="min-width:280px;" class="text-center">Aksi</th>
        </tr>
      </thead>

      <tbody>
        @forelse($items as $row)
          <tr>
            <td class="text-center">
              @if($row->photo)
                @php
                  $src = str_starts_with($row->photo, 'http')
                    ? $row->photo
                    : Storage::disk('s3')->url($row->photo);
                @endphp
                <img src="{{ $src }}" alt="Foto" class="photo-thumb">
              @else
                <div class="photo-empty">N/A</div>
              @endif
            </td>

            <td class="text-center">
              <div class="cell-strong">{{ $row->innovator?->name ?? '-' }}</div>
            </td>

            <td class="text-center">
              <div class="cell-muted">{{ $row->innovator?->faculty?->name ?? '-' }}</div>
            </td>

            <td>
              <div class="desc-text">
                {{ \Illuminate\Support\Str::limit($row->description ?? '-', 110) }}
              </div>
            </td>

            <td>
              <div class="cell-strong">
                {{ $row->innovation?->title ?? '-' }}
              </div>
            </td>

            <td class="text-center">
              <div class="aksi-wrap">
                <a class="btn btn-navy btn-sm" href="{{ route('admin.innovators.edit', $row->id) }}">
                  Edit
                </a>

                <form method="POST"
                      action="{{ route('admin.innovators.destroy', $row->id) }}"
                      onsubmit="return confirm('Yakin mau hapus data ini?')"
                      class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-outline-danger btn-sm">Hapus</button>
                </form>

                <form action="{{ route('admin.innovators.status', $row->id) }}"
                      method="POST"
                      class="d-inline js-status-form">
                  @csrf
                  @method('PATCH')

                  @php $active = (int) ($row->is_active ?? 1); @endphp

                  <div class="status-dd {{ $active ? 'is-on' : 'is-off' }}">
                    <select name="is_active"
                            class="status-select js-status-select"
                            aria-label="Ubah status aktif">
                      <option value="1" {{ $active ? 'selected' : '' }}>Aktif</option>
                      <option value="0" {{ !$active ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <svg class="chev" viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M6.7 9.2a1 1 0 0 1 1.4 0L12 13.1l3.9-3.9a1 1 0 1 1 1.4 1.4l-4.6 4.6a1 1 0 0 1-1.4 0L6.7 10.6a1 1 0 0 1 0-1.4z"/>
                    </svg>
                  </div>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">Belum ada data.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">
    {{ $items->links() }}
  </div>
</div>

<style>
.custom-thead th{
  background:#061a4d !important;
  color:#ffffff !important;
  font-weight:800;
  text-transform:uppercase;
  font-size:13px;
  letter-spacing:.5px;
  padding:14px 12px;
  border-color: rgba(6,26,77,.4)!important;
}

table td, table th{
  border-color: rgba(6,26,77,.35)!important;
}

.table tbody tr:hover{
  background:#f1f5ff;
  transition:.2s ease-in-out;
}

.panel{
  border:2px solid #061a4d;
  border-radius:18px;
  padding:14px;
  background:#fff;
}

.btn-navy{
  background:#061a4d;
  color:#fff;
  border-radius:12px;
  padding:10px 16px;
  font-weight:800;
  text-decoration:none;
  border: 2px solid rgba(6,26,77,0);
  transition: transform .08s ease, box-shadow .18s ease;
}
.btn-navy:hover{ color:#fff; box-shadow: 0 10px 22px rgba(6,26,77,.18); }
.btn-navy:active{ transform: scale(.98); }

.photo-thumb{
  width:60px;
  height:60px;
  border-radius:14px;
  object-fit:cover;
  border: 2px solid rgba(6,26,77,.18);
}
.photo-empty{
  width:60px;
  height:60px;
  border-radius:14px;
  background:#eef2ff;
  display:flex;
  align-items:center;
  justify-content:center;
  color:#6b7280;
  font-weight:800;
  margin: 0 auto;
  border: 2px dashed rgba(6,26,77,.18);
}

.cell-strong{
  font-weight:500;
  color:#061a4d;
  line-height: 1.25;
}
.cell-muted{
  font-weight:500;
  color:#0f172a;
  opacity:.85;
  line-height: 1.25;
}
.desc-text{
  font-weight:500;
  color:#111827;
  line-height: 1.35;
}

.aksi-wrap{
  display:flex;
  gap:10px;
  justify-content:center;
  align-items:center;
  flex-wrap: wrap;
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
  padding: 8px 34px 8px 14px;
  border-radius: 999px;
  font-weight: 500;
  font-size: 12px;
  letter-spacing: .2px;
  cursor: pointer;
  min-width: 120px;
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

@media (max-width: 576px){
  .status-select{ min-width: 112px; padding-left: 12px; padding-right: 30px; }
  .photo-thumb, .photo-empty{ width:54px; height:54px; border-radius:12px; }
}
</style>

@push('scripts')
<script>
(function () {
  document.addEventListener('submit', function(e){
    const f = e.target.closest('.js-status-form');
    if(!f) return;
    e.preventDefault();
  }, true);

  document.addEventListener('change', async function (e) {
    const select = e.target.closest('.js-status-select');
    if (!select) return;

    const form = select.closest('.js-status-form');
    const action = form.getAttribute('action');
    const token = form.querySelector('input[name="_token"]')?.value || '';

    const wrap = form.querySelector('.status-dd');
    const prev = select.dataset.prev ?? select.value;

    const fd = new FormData();
    fd.append('is_active', select.value);
    fd.append('_method', 'PATCH');

    select.disabled = true;

    try {
      const res = await fetch(action, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: fd
      });

      const data = await res.json().catch(() => ({}));
      if (!res.ok || data.ok === false) throw new Error(data.message || 'Gagal update status');

      select.dataset.prev = select.value;

      if (select.value === '1') {
        wrap.classList.add('is-on');
        wrap.classList.remove('is-off');
      } else {
        wrap.classList.add('is-off');
        wrap.classList.remove('is-on');
      }
    } catch (err) {
      select.value = prev;
      alert(err.message || 'Terjadi error');
    } finally {
      select.disabled = false;
    }
  });
})();
</script>
@endpush
@endsection