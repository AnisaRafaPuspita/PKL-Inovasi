@extends('layouts.admin')
@section('title','Innovation Ranking')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">Innovation Ranking</h1>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-end mb-3">
  <a href="{{ route('admin.innovation_rankings.create') }}" class="btn btn-navy">
    + Tambah Ranking
  </a>
</div>

<div class="panel">
  <table class="table table-bordered align-middle mb-0">
    <thead>
      <tr style="background:#061a4d;color:#fff;">
        <th style="width:90px;">Rank</th>
        <th>Innovation</th>
        <th style="width:320px;">Achievement</th>
        <th style="width:140px;">Status</th>
        <th style="width:140px;">Image</th>
        <th style="width:180px;">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rankings as $r)
        <tr>
          <td class="text-center fw-bold">#{{ $r->rank }}</td>
          <td>{{ $r->innovation?->title ?? '-' }}</td>
          <td>{{ $r->achievement ?? '-' }}</td>
          <td class="text-center">
            @if($r->status === 'active')
              <span class="badge bg-success">active</span>
            @else
              <span class="badge bg-secondary">inactive</span>
            @endif
          </td>
          <td class="text-center">
            @if(!empty($r->image))
              <img src="{{ asset('storage/'.$r->image) }}" style="height:44px;border-radius:8px;">
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td class="text-center">
            <a class="btn btn-sm btn-outline-dark"
               href="{{ route('admin.innovation_rankings.edit', $r->id) }}">Edit</a>

            <form class="d-inline" method="POST"
                  action="{{ route('admin.innovation_rankings.destroy', $r->id) }}"
                  onsubmit="return confirm('Hapus ranking ini?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="text-center text-muted">Belum ada ranking.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
