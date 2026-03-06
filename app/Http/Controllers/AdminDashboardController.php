<?php

namespace App\Http\Controllers;

use App\Models\InnovatorOfTheMonth;
use App\Models\Innovation;
use App\Models\Innovator;
use App\Models\InnovationViewStat;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = (int) $request->query('month', now()->month);
        $selectedYear = (int) $request->query('year', now()->year);

        if ($selectedMonth < 1 || $selectedMonth > 12) {
            $selectedMonth = now()->month;
        }

        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

        $rows = InnovationViewStat::whereBetween('date', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ])
            ->orderBy('date')
            ->get(['date', 'views'])
            ->keyBy(function ($row) {
                return Carbon::parse($row->date)->day;
            });

        $chartLabels = [];
        $chartData = [];

        for ($day = 1; $day <= $endDate->day; $day++) {
            $chartLabels[] = $day;
            $chartData[] = (int) ($rows[$day]->views ?? 0);
        }

        $stats = [
            'total_innovations' => Innovation::count(),
            'total_innovators' => Innovator::count(),
            'total_visited' => (int) InnovationViewStat::sum('views'),
        ];

        $innovatorOfMonth = InnovatorOfTheMonth::with(['innovation', 'innovator.faculty'])
            ->where('is_active', 1)
            ->latest()
            ->first();

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $years = InnovationViewStat::selectRaw('YEAR(date) as year')
            ->whereNotNull('date')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        if ($years->isEmpty()) {
            $years = collect([now()->year]);
        }

        $kiMap = [
            'paten' => 'Paten',
            'hak_cipta' => 'Hak Cipta',
            'desain_industri' => 'Desain Industri',
            'merek' => 'Merek',
        ];

        $kiCounts = Innovation::query()
            ->selectRaw('ki_type, COUNT(*) as total')
            ->whereNotNull('ki_type')
            ->where('ki_type', '!=', '')
            ->groupBy('ki_type')
            ->pluck('total', 'ki_type');

        $kiChartLabels = [];
        $kiChartData = [];

        foreach ($kiMap as $key => $label) {
            $kiChartLabels[] = $label;
            $kiChartData[] = (int) ($kiCounts[$key] ?? 0);
        }

        $facultyCounts = Innovator::query()
            ->with('faculty')
            ->selectRaw('faculty_id, COUNT(*) as total')
            ->whereNotNull('faculty_id')
            ->groupBy('faculty_id')
            ->get();

        $facultyChartLabels = [];
        $facultyChartData = [];

        foreach ($facultyCounts as $item) {
            $facultyChartLabels[] = optional($item->faculty)->name ?? 'Tidak diketahui';
            $facultyChartData[] = (int) $item->total;
        }

        return view('admin.dashboard', compact(
            'stats',
            'chartLabels',
            'chartData',
            'innovatorOfMonth',
            'months',
            'years',
            'selectedMonth',
            'selectedYear',
            'kiChartLabels',
            'kiChartData',
            'facultyChartLabels',
            'facultyChartData'
        ));
    }
}