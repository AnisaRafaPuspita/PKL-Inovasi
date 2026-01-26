@extends('layouts.admin')
@section('title','Permission Innovations')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">Persetujuan Inovasi</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="panel">
    <table class="table table-bordered align-middle mb-0">
        <thead>
        <tr style="background:#061a4d;color:#fff;">
            <th>Judul Inovasi</th>
            <th style="width:160px;">Detail</th>
            <th style="width:220px;">Status</th>
        </tr>
        </thead>
        <tbody>
        @forelse($innovations as $inv)
            @php
                $permStatus = optional($inv->permission)->status;
            @endphp

            <tr>
                <td>{{ $inv->title }}</td>

                <td class="text-center">
                    <a class="btn btn-sm btn-outline-dark"
                       href="{{ route('admin.permissions.show', $inv->id) }}">
                        Lihat
                    </a>
                </td>

                <td class="text-center">
                    @if($permStatus === 'accepted')
                        <span class="badge bg-success">Diterima</span>
                    @elseif($permStatus === 'declined')
                        <span class="badge bg-danger">Ditolak</span>
                    @elseif($permStatus === 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @else
                        <span class="badge bg-secondary">Belum di review</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">Belum ada inovasi.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<style>
.panel{
    background:#fff;
    border:2px solid #061a4d;
    border-radius:18px;
    padding:18px;
}
</style>
@endsection
