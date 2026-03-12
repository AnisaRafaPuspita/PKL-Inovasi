@extends('layouts.app')

@section('title', 'Statistik')

@section('content')
<div class="max-w-6xl mx-auto py-14 space-y-10">

    <h1 class="text-3xl font-bold text-[#001349] text-center">
        Statistik Inovasi Tahun {{ $year }}
    </h1>

    <!-- DATA HOLDER -->
    <div id="statistik-data"
         data-monthly='@json($monthlyData)'
         data-faculty-labels='@json($facultyLabels)'
         data-faculty-totals='@json($facultyTotals)'
         data-ki-labels='@json($kiLabels)'
         data-ki-totals='@json($kiTotals)'>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-r from-[#001349] to-[#1A6ECE] text-white rounded-2xl p-6 shadow">
            <div class="text-sm opacity-80">Total Inovasi</div>
            <div class="text-3xl font-bold mt-2">
                {{ is_array($monthlyData) ? array_sum($monthlyData) : $monthlyData->sum() }}
            </div>
        </div>

        <div class="bg-[#EAF2FF] rounded-2xl shadow p-6">
            <div class="text-sm text-[#001349]/70">
                Total Fakultas Terlibat
            </div>
            <div class="text-3xl font-bold text-[#001349] mt-2">
                {{ count($facultyLabels) }}
            </div>
        </div>

        <div class="bg-[#EAF2FF] rounded-2xl shadow p-6">
            <div class="text-sm text-gray-500">Total KI</div>
            <div class="text-3xl font-bold text-[#001349] mt-2">
                {{ is_array($kiTotals) ? array_sum($kiTotals) : $kiTotals->sum() }}
            </div>
        </div>
    </div>

    <!-- TOP GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Grafik Bulanan -->
        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold text-[#001349] mb-4">
                Grafik Inovasi per Bulan
            </h2>
            <canvas id="monthlyChart" height="200"></canvas>
        </div>

        <!-- Grafik KI -->
        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold text-[#001349] mb-4">
                Distribusi Hak Kekayaan Intelektual
            </h2>
            <div style="max-height:250px;">
                <canvas id="kiChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Grafik Fakultas -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-lg font-semibold text-[#001349] mb-6">
            Distribusi Inovasi per Fakultas
        </h2>
        <canvas id="facultyChart" height="110"></canvas>
    </div>

</div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const dataEl = document.getElementById("statistik-data");

    const monthlyData = JSON.parse(dataEl.dataset.monthly || "[]");
    const facultyLabels = JSON.parse(dataEl.dataset.facultyLabels || "[]");
    const facultyTotals = JSON.parse(dataEl.dataset.facultyTotals || "[]");
    const kiLabels = JSON.parse(dataEl.dataset.kiLabels || "[]");
    const kiTotals = JSON.parse(dataEl.dataset.kiTotals || "[]");

    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

    
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(26,110,206,0.35)');
    gradient.addColorStop(1, 'rgba(26,110,206,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                data: monthlyData,
                borderColor: '#1A6ECE',
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#001349',
                pointRadius: 4
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            responsive: true
        }
    });

    
    new Chart(document.getElementById('facultyChart'), {
        type: 'bar',
        data: {
            labels: facultyLabels,
            datasets: [{
                data: facultyTotals,
                backgroundColor: '#1A6ECE',
                borderRadius: 6
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            responsive: true
        }
    });

    
    new Chart(document.getElementById('kiChart'), {
        type: 'pie',
        data: {
            labels: kiLabels,
            datasets: [{
                data: kiTotals,
                backgroundColor: [
                    '#001349',
                    '#1A6ECE',
                    '#7FB3FF',
                    '#BFD9FF'
                ]
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });

});
</script>
@endpush