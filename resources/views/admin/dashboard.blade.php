@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
    <h1 class="mb-4" style="font-weight:900;color:#061a4d;">Selamat datang admin!</h1>

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-4">
            <div class="stat-card">
                <div>
                    <div class="stat-title">Total Inovasi</div>
                    <div class="stat-value">{{ number_format($stats['total_innovations']) }}</div>
                </div>
                <div style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;border:1px solid #0B2A6F;border-radius:8px;flex:0 0 32px;overflow:hidden;margin-left:auto;">
                    <img src="{{ asset('images/total-innovation.svg') }}" alt="Total Inovasi" style="width:16px;height:16px;display:block;object-fit:contain;">
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="stat-card">
                <div>
                    <div class="stat-title">Total Inovator</div>
                    <div class="stat-value">{{ number_format($stats['total_innovators']) }}</div>
                </div>
                <div style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;border:1px solid #0B2A6F;border-radius:8px;flex:0 0 32px;overflow:hidden;margin-left:auto;">
                    <img src="{{ asset('images/total-innovators.svg') }}" alt="Total Innovator" style="width:16px;height:16px;display:block;object-fit:contain;">
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="stat-card">
                <div>
                    <div class="stat-title">Total Kunjungan</div>
                    <div class="stat-value">{{ number_format($stats['total_visited']) }}</div>
                </div>
                <div style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;border:1px solid #0B2A6F;border-radius:8px;flex:0 0 32px;overflow:hidden;margin-left:auto;">
                    <img src="{{ asset('images/total-visited.svg') }}" alt="Total Kunjungan" style="width:16px;height:16px;display:block;object-fit:contain;">
                </div>
            </div>
        </div>
    </div>

    <div class="panel mb-4">
        <div class="section-title">Grafik Kunjungan Inovasi</div>
        <div style="height:360px;">
            <canvas id="viewsChart"
                data-labels='@json($chartLabels)'
                data-values='@json($chartData)'></canvas>
        </div>
    </div>

    <div class="panel">
        <div class="section-title">Pamflet Beranda</div>

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

        <form action="{{ route('admin.dashboard.home_pamflet.update') }}" method="POST" enctype="multipart/form-data">
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
                                        type="submit"
                                        name="delete_slot"
                                        value="{{ $i }}"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus Pamflet {{ $i }}?')"
                                    >
                                        Hapus
                                    </button>
                                @endif
                            </div>

                            <div style="width:100%;height:180px;border-radius:12px;border:1px dashed #cbd5e1;display:flex;align-items:center;justify-content:center;overflow:hidden;background:#f8fafc;">
                                @if(!empty($homePamflet?->$key))
                                    <img src="{{ asset('storage/'.$homePamflet->$key) }}" alt="Pamflet {{ $i }}"
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
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('viewsChart');
    if (!canvas) return;

    if (typeof Chart === 'undefined') {
        console.error('Chart.js belum ke-load. Cek script CDN di layout.');
        return;
    }

    const labels = JSON.parse(canvas.dataset.labels || '[]');
    const values = JSON.parse(canvas.dataset.values || '[]');

    if (!labels.length || !values.length) {
        console.error('Data chart kosong. Cek $chartLabels / $chartData dari controller.');
        return;
    }

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Views',
                data: values,
                tension: 0.25,
                borderWidth: 2,
                pointRadius: 4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
});
</script>
@endpush
