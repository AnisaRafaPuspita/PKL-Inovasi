@extends('layouts.admin')
@section('title','Manage Innovations')

@section('content')
<div class="page-head">
    <h1 class="page-title">Kelola Inovasi</h1>

    <a href="{{ route('admin.innovations.create') }}" class="btn btn-navy btn-add-innovation">
        + Tambah Inovasi
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="panel mb-3">
    <form method="GET" action="{{ route('admin.innovations.index') }}">
        <div class="filter-bar">
            <div class="filter-item filter-search">
                <label class="filter-label">Search</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="filter-input"
                    placeholder="Cari judul inovasi atau nama innovator"
                >
            </div>

            <div class="filter-item">
                <label class="filter-label">Status</label>
                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>
                        Published
                    </option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>
                </select>
            </div>

            <div class="filter-item">
                <label class="filter-label">Tahun</label>
                <select name="year" class="filter-select">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $itemYear)
                        <option value="{{ $itemYear }}" {{ (string) request('year') === (string) $itemYear ? 'selected' : '' }}>
                            {{ $itemYear }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-navy">Filter</button>
                <a href="{{ route('admin.innovations.index') }}" class="btn btn-reset">Reset</a>
            </div>
        </div>
    </form>
</div>

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

    <div class="table-footer">
        <div class="table-footer-left">
            @if($innovations->count())
                <div class="table-footer-info">
                    Menampilkan {{ $innovations->firstItem() }} - {{ $innovations->lastItem() }} dari {{ $innovations->total() }} data
                </div>
            @endif
        </div>

        <div class="table-footer-right">
            <form method="GET" action="{{ route('admin.innovations.index') }}" class="per-page-form">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="year" value="{{ request('year') }}">

                <select name="per_page" class="per-page-select" onchange="this.form.submit()">
                    <option value="20" {{ (string) request('per_page', 20) === '20' ? 'selected' : '' }}>20</option>
                    <option value="50" {{ (string) request('per_page') === '50' ? 'selected' : '' }}>50</option>
                    <option value="100" {{ (string) request('per_page') === '100' ? 'selected' : '' }}>100</option>
                </select>
            </form>

            @if($innovations->hasPages())
                <div class="pagination-wrap">
                    {{ $innovations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.page-head{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    margin-bottom:16px;
    gap:12px;
    flex-wrap:wrap;
}

.page-title{
    font-weight:900;
    color:#061a4d;
    margin:0;
}

.btn-add-innovation{
    margin-top:16px;
}
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
.btn-navy:active{
    transform: scale(.98);
}

.btn-reset{
    background:#fff;
    color:#061a4d;
    border:2px solid rgba(6,26,77,.18);
    border-radius:12px;
    padding:10px 16px;
    font-weight:800;
    text-decoration:none;
    transition:all .18s ease;
}
.btn-reset:hover{
    color:#061a4d;
    background:rgba(6,26,77,.04);
}

.panel{
    border:2px solid #061a4d;
    border-radius:18px;
    padding:14px;
    background:#fff;
}

.filter-bar{
    display:grid;
    grid-template-columns: 2fr 1fr 1fr auto;
    gap:14px;
    align-items:end;
}

.filter-item{
    display:flex;
    flex-direction:column;
    gap:6px;
}

.filter-search{
    min-width:260px;
}

.filter-label{
    font-size:13px;
    font-weight:800;
    color:#061a4d;
    margin:0;
}

.filter-input,
.filter-select{
    width:100%;
    min-height:44px;
    border:1.5px solid rgba(6,26,77,.18);
    border-radius:12px;
    padding:10px 14px;
    font-weight:600;
    color:#0f172a;
    background:#fff;
    outline:none;
    transition:border-color .18s ease, box-shadow .18s ease;
}

.filter-input:focus,
.filter-select:focus,
.per-page-select:focus{
    border-color:#061a4d;
    box-shadow:0 0 0 4px rgba(6,26,77,.08);
}

.filter-actions{
    display:flex;
    gap:10px;
    align-items:end;
    flex-wrap:wrap;
}

table td, table th{
    border-color: rgba(6,26,77,.35)!important;
}

.innovations-table td{
    padding: 14px 12px;
    vertical-align: middle;
}

.title-wrap{
    display:flex;
    gap:10px;
    align-items:flex-start;
}

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
.status-dd:active{
    transform: scale(.99);
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
    font-weight: 900;
    font-size: 12px;
    letter-spacing: .2px;
    cursor: pointer;
    min-width: 140px;
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

.table-footer{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
    flex-wrap:wrap;
    margin-top:18px;
    padding-top:14px;
    border-top:1px solid rgba(6,26,77,.12);
}

.table-footer-left{
    display:flex;
    align-items:center;
}

.table-footer-right{
    display:flex;
    align-items:center;
    gap:12px;
    flex-wrap:wrap;
}

.table-footer-info{
    font-size:14px;
    font-weight:700;
    color:#334155;
}

.per-page-form{
    margin:0;
}

.per-page-select{
    min-width:82px;
    height:40px;
    border:1px solid rgba(6,26,77,.12);
    border-radius:10px;
    padding:0 12px;
    font-weight:700;
    color:#061a4d;
    background:#fff;
    outline:none;
}

.pagination-wrap{
    margin:0;
}

.pagination-wrap .pagination{
    margin:0;
    gap:6px;
}

.pagination-wrap nav{
    display:flex;
    align-items:center;
    gap:20px;
}

.pagination-wrap .small.text-muted{
    margin:0 14px 0 0;
    white-space:nowrap;
}

.pagination-wrap .page-link{
    border-radius:10px !important;
    color:#061a4d;
    font-weight:700;
    border:1px solid rgba(6,26,77,.12);
    padding:.55rem .85rem;
}

.pagination-wrap .page-link:hover{
    color:#061a4d;
    background:rgba(6,26,77,.06);
    border-color:rgba(6,26,77,.2);
}

.pagination-wrap .page-item.active .page-link{
    background:#061a4d;
    border-color:#061a4d;
    color:#fff;
}

.pagination-wrap .page-item.disabled .page-link{
    color:#94a3b8;
    background:#f8fafc;
    border-color:#e5e7eb;
}

@media (max-width: 1200px){
    .filter-bar{
        grid-template-columns: 1fr 1fr 1fr;
    }
}

@media (max-width: 768px){
    .title-text{
        max-width: 320px;
    }

    .status-select{
        min-width: 130px;
    }

    .table-footer{
        flex-direction:column;
        align-items:flex-start;
    }

    .table-footer-right{
        width:100%;
        justify-content:space-between;
    }
}

@media (max-width: 576px){
    .filter-bar{
        grid-template-columns: 1fr;
    }

    .filter-actions{
        width:100%;
    }

    .filter-actions .btn,
    .filter-actions .btn-reset{
        width:100%;
        text-align:center;
        justify-content:center;
    }

    .innovations-table td{
        padding: 12px 10px;
    }

    .title-text{
        max-width: 240px;
        font-size: 13px;
    }

    .innovator-text{
        font-size: 13px;
    }

    .status-select{
        min-width: 120px;
        padding-left: 12px;
        padding-right: 30px;
    }

    .table-footer-right{
        display:flex;
        align-items:center;
        gap:28px;
        flex-wrap:wrap;
    }

    .per-page-select{
        width:100%;
    }

    .per-page-form{
        margin:0;
        margin-right:8px;
    }

    .pagination-wrap{
        margin-left:8px;
    }

    .table-footer-info{
        font-size:14px;
        font-weight:700;
        color:#334155;
        margin-right:12px;
    }
}
</style>

@endsection