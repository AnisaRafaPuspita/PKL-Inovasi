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
        <div class="chart-head">
            <div class="section-title m-0">Grafik Kunjungan Inovasi</div>

            <form method="GET" action="{{ route('admin.dashboard') }}" class="chart-filter">
                <select name="month" class="chart-select">
                    @foreach($months as $monthNumber => $monthLabel)
                        <option value="{{ $monthNumber }}" {{ (int) $selectedMonth === (int) $monthNumber ? 'selected' : '' }}>
                            {{ $monthLabel }}
                        </option>
                    @endforeach
                </select>

                <select name="year" class="chart-select">
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ (int) $selectedYear === (int) $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-navy">Terapkan</button>
            </form>
        </div>

        <div class="chart-box-lg">
            <canvas id="viewsChart"
                data-labels='@json($chartLabels)'
                data-values='@json($chartData)'
                data-month='{{ $months[$selectedMonth] ?? "" }}'
                data-year='{{ $selectedYear }}'></canvas>
        </div>
    </div>

    <div class="chart-grid-two mb-4">
        <div class="panel chart-panel-half">
            <div class="section-title mb-3">Grafik Jenis KI</div>
            <div class="chart-box-sm">
                <canvas id="kiTypeChart"
                    data-labels='@json($kiChartLabels)'
                    data-values='@json($kiChartData)'></canvas>
            </div>
        </div>

        <div class="panel chart-panel-half">
            <div class="section-title mb-3">Grafik Kategori Fakultas</div>
            <div class="chart-box-sm">
                <canvas id="facultyChart"
                    data-labels='@json($facultyChartLabels)'
                    data-values='@json($facultyChartData)'></canvas>
            </div>
        </div>
    </div>

    <style>
        .chart-head{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:16px;
            flex-wrap:wrap;
            margin-bottom:18px;
        }

        .chart-filter{
            display:flex;
            align-items:center;
            gap:10px;
            flex-wrap:wrap;
        }

        .chart-select{
            min-width:150px;
            height:42px;
            border:1.5px solid rgba(6,26,77,.18);
            border-radius:12px;
            padding:0 12px;
            font-weight:600;
            color:#0f172a;
            background:#fff;
            outline:none;
            transition:border-color .18s ease, box-shadow .18s ease;
        }

        .chart-select:focus{
            border-color:#061a4d;
            box-shadow:0 0 0 4px rgba(6,26,77,.08);
        }

        .chart-box-lg{
            height:360px;
        }

        .chart-grid-two{
            display:grid;
            grid-template-columns: 1fr 1fr;
            gap:20px;
        }

        .chart-panel-half{
            margin-bottom:0;
        }

        .chart-box-sm{
            height:360px;
        }

        @media (max-width: 992px){
            .chart-grid-two{
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px){
            .chart-filter{
                width:100%;
            }

            .chart-select,
            .chart-filter .btn{
                width:100%;
            }
        }
    </style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const viewsCanvas = document.getElementById('viewsChart');
    const kiCanvas = document.getElementById('kiTypeChart');
    const facultyCanvas = document.getElementById('facultyChart');

    if (typeof Chart === 'undefined') {
        console.error('Chart.js belum ke-load. Cek script CDN di layout.');
        return;
    }

    if (viewsCanvas) {
        const labels = JSON.parse(viewsCanvas.dataset.labels || '[]');
        const values = JSON.parse(viewsCanvas.dataset.values || '[]');
        const month = viewsCanvas.dataset.month || '';
        const year = viewsCanvas.dataset.year || '';

        new Chart(viewsCanvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kunjungan ' + month + ' ' + year,
                    data: values,
                    tension: 0.25,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 5,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    if (kiCanvas) {
        const kiLabels = JSON.parse(kiCanvas.dataset.labels || '[]');
        const kiValues = JSON.parse(kiCanvas.dataset.values || '[]');

        new Chart(kiCanvas, {
            type: 'bar',
            data: {
                labels: kiLabels,
                datasets: [{
                    label: 'Jumlah Inovasi',
                    data: kiValues,
                    borderWidth: 1,
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    if (facultyCanvas) {
        const facultyLabels = JSON.parse(facultyCanvas.dataset.labels || '[]');
        const facultyValues = JSON.parse(facultyCanvas.dataset.values || '[]');

        new Chart(facultyCanvas, {
            type: 'bar',
            data: {
                labels: facultyLabels,
                datasets: [{
                    label: 'Jumlah Innovator',
                    data: facultyValues,
                    borderWidth: 1,
                    borderRadius: 10
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    y: {
                        ticks: {
                            autoSkip: false
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush