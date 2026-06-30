@extends('layouts.admin')

@section('title', 'Pamflet Beranda')

@section('content')
    <h1 class="mb-4" style="font-weight:900;color:#061a4d;">Pamflet Beranda</h1>

    <div class="panel">
        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <div class="fw-bold mb-1">Gagal menyimpan:</div>
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM UPLOAD --}}
        <form action="{{ route('admin.pamflet.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                @foreach ([1,2,3] as $i)
                    @php $key = "pamflet_{$i}"; @endphp

                    <div class="col-12 col-md-4">
                        <div style="border:1px solid #e5e7eb;border-radius:12px;padding:14px;">
                            <div class="d-flex align-items-center justify-content-between" style="margin-bottom:10px;">
                                <div style="font-weight:800;color:#061a4d;">Pamflet {{ $i }}</div>

                                @if(!empty($homePamflet?->$key))
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="if(confirm('Hapus Pamflet {{ $i }}?')) document.getElementById('deletePamflet{{ $i }}').submit();"
                                    >
                                        Hapus
                                    </button>
                                @endif
                            </div>

                            <div style="width:100%;height:180px;border-radius:12px;border:1px dashed #cbd5e1;display:flex;align-items:center;justify-content:center;overflow:hidden;background:#f8fafc;">
                                @if(!empty($homePamflet?->$key))
                                    <img src="{{ Storage::disk('s3')->url($homePamflet->$key) }}" alt="Pamflet {{ $i }}"
                                         style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <span style="color:#94a3b8;">Belum ada gambar</span>
                                @endif
                            </div>

                            <div class="mt-2">
                                <input type="file" name="{{ $key }}" class="form-control">
                                @error($key)
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button type="submit" class="btn btn-navy">Simpan Pamflet</button>
            </div>
        </form>

        {{-- FORM DELETE (DI LUAR FORM UPLOAD) --}}
        @foreach ([1,2,3] as $i)
            <form id="deletePamflet{{ $i }}"
                  action="{{ route('admin.pamflet.delete', ['slot' => $i]) }}"
                  method="POST"
                  class="d-none">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </div>
@endsection