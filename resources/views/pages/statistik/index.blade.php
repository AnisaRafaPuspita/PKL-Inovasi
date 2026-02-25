@extends('layouts.app')

@section('title', 'Statistik')

@section('content')
<div class="max-w-6xl mx-auto py-16 space-y-16">

    <h1 class="text-3xl font-bold text-[#001349] text-center">
        Statistik Inovasi Tahun {{ $year }}
    </h1>

    <!-- DATA HOLDER (AMAN UNTUK BLADE + JS) -->
    <div id="statistik-data"
         data-monthly='@json($monthlyData)'
         data-faculty-labels='@json($facultyLabels)'
         data-faculty-totals='@json($facultyTotals)'
         data-ki-labels='@json($kiLabels)'
         data-ki-totals='@json($kiTotals)'>
    </div>

    <!-- Grafik Per Bulan -->
    <div class="bg-white p-8 rounded-2xl shadow">
        <h2 class="text-xl font-semibold mb-6 text-[#001349]">
            Grafik Inovasi per Bulan
        </h2>
        <canvas id="monthlyChart"></canvas>
    </div>

    <!-- Grafik Per Fakultas -->
    <div class="bg-white p-8 rounded-2xl shadow">
        <h2 class="text-xl font-semibold mb-6 text-[#001349]">
            Distribusi Inovasi per Fakultas
        </h2>
        <canvas id="facultyChart"></canvas>
    </div>

    <!-- Grafik KI -->
    <div class="bg-white p-8 rounded-2xl shadow">
        <h2 class="text-xl font-semibold mb-6 text-[#001349]">
            Distribusi Hak Kekayaan Intelektual
        </h2>
        <canvas id="kiChart"></canvas>
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

    // 📊 1. Grafik Per Bulan
    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Jumlah Inovasi',
                data: monthlyData,
                borderColor: '#001349',
                backgroundColor: 'rgba(0,19,73,0.15)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            }
        }
    });

    // 🏫 2. Grafik Fakultas
    new Chart(document.getElementById('facultyChart'), {
        type: 'bar',
        data: {
            labels: facultyLabels,
            datasets: [{
                label: 'Jumlah Inovasi',
                data: facultyTotals,
                backgroundColor: '#1A6ECE'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 📜 3. Grafik KI
    new Chart(document.getElementById('kiChart'), {
        type: 'pie',
        data: {
            labels: kiLabels,
            datasets: [{
                data: kiTotals,
                backgroundColor: [
                    '#001349',
                    '#1A6ECE',
                    '#6B7280',
                    '#F59E0B',
                    '#10B981'
                ]
            }]
        },
        options: {
            responsive: true
        }
    });

});
</script>
@endpush