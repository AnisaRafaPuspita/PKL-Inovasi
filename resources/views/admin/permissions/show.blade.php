@extends('layouts.admin')
@section('title','Detail Permission')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">Permission Innovations</h1>
<p style="font-weight:700;color:#061a4d;">Detail</p>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@php
    $first = $innovation->innovators->first();
    $permStatus = optional($innovation->permission)->status;
    $primary = $innovation->primaryImage ?? null;
    $photos = $innovation->images ?? collect();
@endphp

<div class="panel">
    <div class="d-flex justify-content-between align-items-start gap-4">
        <div style="flex:1;">
            <h3 style="font-weight:900;color:#061a4d;margin-bottom:14px;">
                {{ $innovation->title }}
            </h3>

            <div style="font-weight:800;">Nama Innovator: <span style="font-weight:600;">{{ $first?->name ?? '-' }}</span></div>
            <div style="font-weight:800;">Fakultas: <span style="font-weight:600;">{{ $first?->faculty?->name ?? '-' }}</span></div>
            <div style="font-weight:800;">Kategori: <span style="font-weight:600;">{{ $innovation->category ?? '-' }}</span></div>
            <div style="font-weight:800;">Mitra: <span style="font-weight:600;">{{ $innovation->partner ?? '-' }}</span></div>
            <div style="font-weight:800;">Status HKI: <span style="font-weight:600;">{{ $innovation->hki_status ?? '-' }}</span></div>

            <div style="font-weight:800;">
                Video URL:
                <span style="font-weight:600;">
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
                <div style="font-weight:900;color:#061a4d;">Deskripsi</div>
                <div>{{ $innovation->description ?? '-' }}</div>
            </div>

            <div class="mt-3">
                <div style="font-weight:900;color:#061a4d;">Keunggulan</div>
                <div>{{ $innovation->advantages ?? '-' }}</div>
            </div>

            <div class="mt-3">
                <div style="font-weight:900;color:#061a4d;">Keberdampakan</div>
                <div>{{ $innovation->impact ?? '-' }}</div>
            </div>

            <div class="mt-4 d-flex gap-2 align-items-center flex-wrap">
                <a class="btn btn-outline-secondary" href="{{ route('admin.permissions.index') }}">Kembali</a>

                <form method="POST" action="{{ route('admin.permissions.accept', $innovation->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-success">Accept</button>
                </form>

                <form method="POST" action="{{ route('admin.permissions.decline', $innovation->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Decline</button>
                </form>

                <div class="ms-auto">
                    @if($permStatus === 'accepted')
                        <span class="badge bg-success">Accepted</span>
                    @elseif($permStatus === 'declined')
                        <span class="badge bg-danger">Declined</span>
                    @elseif($permStatus === 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @else
                        <span class="badge bg-secondary">Belum di review</span>
                    @endif
                </div>
            </div>
        </div>

        <div style="width:220px;">
            <div style="border:2px solid #061a4d;border-radius:18px;overflow:hidden;background:#fff;">
                @if($primary)
                    <img src="{{ asset('storage/'.$primary->image_path) }}" style="width:100%;height:220px;object-fit:cover;">
                @elseif($photos->count())
                    <img src="{{ asset('storage/'.$photos->first()->image_path) }}" style="width:100%;height:220px;object-fit:cover;">
                @else
                    <div style="height:220px;display:flex;align-items:center;justify-content:center;font-weight:800;">
                        No Photo
                    </div>
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
</style>
@endsection
