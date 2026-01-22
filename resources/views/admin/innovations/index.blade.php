@extends('layouts.admin')
@section('title','Manage Innovations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 style="font-weight:900;color:#061a4d;">Manage Innovations</h1>

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
    <table class="table table-bordered align-middle mb-0">
        <thead style="background:#061a4d;color:#fff;">
            <tr>
                <th>Innovation Title</th>
                <th>Innovator</th>
                <th style="width:140px;" class="text-center">Status</th>
                <th style="width:120px;" class="text-center">Actions</th>
            </tr>
        </thead>

        <tbody>
        @forelse($innovations as $row)
            <tr>
                <td>{{ $row->title }}</td>
                <td>{{ optional($row->innovators->first())->name ?? '-' }}</td>

                <td class="text-center">
                    @if($row->status === 'published')
                        <span class="badge badge-published">Published</span>
                    @elseif($row->status === 'draft')
                        <span class="badge badge-draft">Draft</span>
                    @elseif($row->status === 'pending')
                        <span class="badge badge-pending">Pending</span>
                    @else
                        <span class="badge badge-unknown">{{ $row->status ?? '-' }}</span>
                    @endif
                </td>

                <td class="text-center">
                    <a href="{{ route('admin.innovations.show', $row->id) }}">detail</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">
                    Belum ada data inovasi
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<style>
.btn-navy{
    background:#061a4d;
    color:#fff;
    border-radius:10px;
    padding:10px 16px;
    font-weight:700;
    text-decoration:none;
}
.panel{
    border:2px solid #061a4d;
    border-radius:18px;
    padding:14px;
    background:#fff;
}
table td, table th{
    border-color: rgba(6,26,77,.4)!important;
}

.badge{
    display:inline-block;
    padding:7px 12px;
    border-radius:999px;
    font-weight:800;
    font-size:12px;
    letter-spacing:.2px;
}
.badge-published{
    background:#16a34a;
    color:#fff;
}
.badge-draft{
    background:#64748b;
    color:#fff;
}
.badge-pending{
    background:#f59e0b;
    color:#111827;
}
.badge-unknown{
    background:#e5e7eb;
    color:#111827;
}
</style>
@endsection
