@extends('layouts.admin')
@section('title','Manage Innovations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h1 class="m-0" style="font-weight:900;color:#061a4d;">Kelola Inovasi</h1>

    <a href="{{ route('admin.innovations.create') }}" class="btn btn-navy">
        + Tambah Inovasi
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="panel">
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0 innovations-table">
            <thead class="custom-thead">
                <tr>
                    <th class="text-center">Judul Inovasi</th>
                    <th class="text-center">Inovator</th>
                    <th class="text-center" style="min-width:160px;">Status</th>
                    <th class="text-center" style="width:120px;">Actions</th>
                </tr>
            </thead>

            <tbody>
            @forelse($innovations as $row)
                <tr>
                    <td class="td-title">
                        <div class="title-wrap">
                            <div class="title-text">{{ $row->title }}</div>
                        </div>
                    </td>

                    <td class="td-innovator">
                        <div class="innovator-text">
                            {{ optional($row->innovators->first())->name ?? '-' }}
                        </div>
                    </td>

                    <td class="text-center">
                        {{-- Dropdown status yang mulus --}}
                        <form action="{{ route('admin.innovations.status', $row->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')

                            <div class="status-dd {{ $row->status === 'published' ? 'is-published' : 'is-draft' }}">
                                <select name="status"
                                        class="status-select"
                                        aria-label="Ubah status inovasi"
                                        onchange="this.closest('form').submit()">
                                    <option value="published" {{ $row->status === 'published' ? 'selected' : '' }}>
                                        Published
                                    </option>
                                    <option value="draft" {{ $row->status === 'draft' ? 'selected' : '' }}>
                                        Draft
                                    </option>
                                </select>

                                {{-- chevron icon --}}
                                <svg class="chev" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M6.7 9.2a1 1 0 0 1 1.4 0L12 13.1l3.9-3.9a1 1 0 1 1 1.4 1.4l-4.6 4.6a1 1 0 0 1-1.4 0L6.7 10.6a1 1 0 0 1 0-1.4z"/>
                                </svg>
                            </div>
                        </form>
                    </td>

                    <td class="text-center">
                        <a class="action-link" href="{{ route('admin.innovations.show', $row->id) }}">detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        Belum ada data inovasi
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
/* ================= HEADER TABLE ================= */
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

/* ================= BUTTON ================= */
.btn-navy{
    background:#061a4d;
    color:#fff;
    border-radius:12px;
    padding:10px 16px;
    font-weight:800;
    text-decoration:none;
    border: 2px solid rgba(6,26,77,.0);
    transition: transform .08s ease, box-shadow .18s ease;
}
.btn-navy:hover{
    color:#fff;
    box-shadow: 0 10px 22px rgba(6,26,77,.18);
}
.btn-navy:active{ transform: scale(.98); }

/* ================= PANEL ================= */
.panel{
    border:2px solid #061a4d;
    border-radius:18px;
    padding:14px;
    background:#fff;
}

/* ================= TABLE BORDER ================= */
table td, table th{ border-color: rgba(6,26,77,.35)!important; }

/* ================= TABLE POLISH ================= */
.innovations-table td{
    padding: 14px 12px;
    vertical-align: middle;
}
.title-wrap{ display:flex; gap:10px; align-items:flex-start; }
.title-text{
    font-weight:800;
    color:#0b1f5a;
    line-height: 1.25;
    max-width: 520px;
}
.innovator-text{
    font-weight:700;
    color:#0f172a;
    line-height: 1.25;
}

/* link action biar nggak “kecil banget” */
.action-link{
    font-weight:800;
    text-decoration: none;
    color:#0a2b7a;
    padding: 6px 10px;
    border-radius: 10px;
    transition: background .15s ease;
}
.action-link:hover{
    background: rgba(10,43,122,.08);
    text-decoration: none;
}

/* ================= STATUS DROPDOWN (MULUS) ================= */
/* wrapper yang jadi “pill badge” */
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
.status-dd:active{ transform: scale(.99); }

/* select dibuat transparan tapi tetap clickable */
.status-select{
    -webkit-appearance:none;
    -moz-appearance:none;
    appearance:none;

    border: 0;
    outline: none;
    background: transparent;

    padding: 8px 34px 8px 14px; /* ruang chevron */
    border-radius: 999px;

    font-weight: 900;
    font-size: 12px;
    letter-spacing: .2px;
    cursor: pointer;

    /* biar rapi di kolom */
    min-width: 140px;
    text-align: center;
    text-align-last: center;
}

/* chevron */
.status-dd .chev{
    position:absolute;
    right: 10px;
    width: 16px;
    height: 16px;
    opacity: .85;
    pointer-events:none;
    fill: currentColor;
}

/* warna dinamis: published/draft */
.status-dd.is-published{
    background: rgba(22,163,74,.10);
    border-color: rgba(22,163,74,.35);
    color: #0b3b1c;
}
.status-dd.is-draft{
    background: rgba(100,116,139,.10);
    border-color: rgba(100,116,139,.35);
    color: #334155;
}

.status-select:focus-visible{
    box-shadow: 0 0 0 4px rgba(6,26,77,.12);
    border-radius: 999px;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px){
    .title-text{ max-width: 320px; }
    .status-select{ min-width: 130px; }
}
@media (max-width: 576px){
    .innovations-table td{ padding: 12px 10px; }
    .title-text{ max-width: 240px; font-size: 13px; }
    .innovator-text{ font-size: 13px; }
    .status-select{ min-width: 120px; padding-left: 12px; padding-right: 30px; }
}
</style>

@endsection