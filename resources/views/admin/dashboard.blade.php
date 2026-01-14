@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
    <h1 class="mb-4" style="font-weight:900;color:#061a4d;">Welcome admin!</h1>

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-4">
            <div class="stat-card">
                <div>
                    <div class="stat-title">Total Innovations</div>
                    <div class="stat-value">{{ number_format($stats['total_innovations']) }}</div>
                </div>
                <div class="icon-badge">💡</div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="stat-card">
                <div>
                    <div class="stat-title">Total Innovators</div>
                    <div class="stat-value">{{ number_format($stats['total_innovators']) }}</div>
                </div>
                <div class="icon-badge">👥</div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="stat-card">
                <div>
                    <div class="stat-title">Total Visited</div>
                    <div class="stat-value">{{ number_format($stats['total_visited']) }}</div>
                </div>
                <div class="icon-badge">👁️</div>
            </div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="panel mb-4">
        <div class="section-title">Innovations Views Over Time</div>
        <div style="height:360px;">
            <canvas id="viewsChart"
                data-labels='@json($chartLabels)'
                data-values='@json($chartData)'></canvas>
        </div>
    </div>

    {{-- Innovator of the month --}}
    <div class="panel">
        <div class="section-title">Innovator of The Month</div>

        <div class="iom-wrap">
            <div class="iom-photo">
                @if(!empty($innovatorOfMonth?->photo))
                    <img src="{{ asset('storage/'.$innovatorOfMonth->photo) }}" alt="photo">
                @else
                    Photo
                @endif
            </div>

            <div class="d-flex justify-content-between align-items-end gap-3 flex-wrap">
                <div>
                    <p class="iom-text">Nama: {{ $innovatorOfMonth?->innovator_name ?? '-' }}</p>
                    <p class="iom-text">Fakultas: {{ $innovatorOfMonth?->faculty ?? '-' }}</p>

                    {{-- inovasi ditampilin dari relasi (kalau kamu bikin relasi innovation()) --}}
                    @if(!empty($innovatorOfMonth?->innovation?->title))
                        <p class="iom-text">Nama Inovasi: {{ $innovatorOfMonth->innovation->title }}</p>
                    @else
                        <p class="iom-text">Nama Inovasi: -</p>
                    @endif
                </div>

                <a href="{{ route('admin.innovator_of_month.edit') }}" class="btn btn-navy">Edit</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
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
                fill: false,
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
