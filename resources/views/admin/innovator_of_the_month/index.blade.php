@extends('layouts.admin')
@section('title','Data Innovator')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">Data Innovator</h1>

@if(session('success'))
  <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-end mt-3">
  <a href="{{ route('admin.innovator_of_month.create') }}" class="btn btn-navy">
    Tambah Innovator
  </a>
</div>

<div class="panel mt-3">
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th style="width:90px;">Foto</th>
          <th>Nama</th>
          <th>Fakultas</th>
          <th>Deskripsi</th>
          <th>Inovasi Unggulan</th>
          <th style="width:200px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $row)
          <tr>
            <td>
              @if($row->photo)
                <img src="{{ asset('storage/'.$row->photo) }}" alt="Foto"
                     style="width:60px;height:60px;border-radius:12px;object-fit:cover;">
              @else
                <div style="width:60px;height:60px;border-radius:12px;background:#eef2ff;display:flex;align-items:center;justify-content:center;color:#6b7280;font-weight:700;">
                  N/A
                </div>
              @endif
            </td>
            <td style="font-weight:700;color:#061a4d;">{{ $row->innovator?->name ?? '-' }}</td>
            <td>{{ $row->innovator?->faculty?->name ?? '-' }}</td>
            <td>{{ \Illuminate\Support\Str::limit($row->description, 70) }}</td>
            <td>{{ $row->innovation?->title ?? '-' }}</td>
            <td class="d-flex gap-2">
              <a class="btn btn-navy" href="{{ route('admin.innovator_of_month.edit', $row->id) }}">
                Edit
              </a>

              <form method="POST" action="{{ route('admin.innovator_of_month.destroy', $row->id) }}"
                    onsubmit="return confirm('Yakin mau hapus data ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">Hapus</button>
              </form>
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
@endsection
