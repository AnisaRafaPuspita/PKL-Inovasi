<?php

namespace App\Http\Controllers;

use App\Models\InnovatorOfTheMonth;
use App\Models\Innovation;
use App\Models\Innovator;
use App\Models\InnovationViewStat;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $days = 14;

        $from = now()->subDays($days - 1)->toDateString();
        $to = now()->toDateString();

        $rows = InnovationViewStat::whereBetween('date', [$from, $to])
            ->orderBy('date')
            ->get(['date', 'views'])
            ->keyBy(function ($r) {
                return Carbon::parse($r->date)->toDateString();
            });

        $chartLabels = [];
        $chartData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $chartLabels[] = Carbon::parse($d)->format('M j');
            $chartData[] = (int) (($rows[$d]->views ?? 0));
        }

        $stats = [
            'total_innovations' => Innovation::count(),
            'total_innovators'  => Innovator::count(),
            'total_visited'     => (int) InnovationViewStat::sum('views'),
        ];

        $now = Carbon::now();

        $innovatorOfMonth = InnovatorOfTheMonth::with(['innovation', 'innovator.faculty'])
            ->where('month', $now->month)
            ->where('year', $now->year)
            ->first();


        return view('admin.dashboard', compact('stats','chartLabels','chartData','innovatorOfMonth'));
    }
}
