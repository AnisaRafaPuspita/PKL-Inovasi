<?php

namespace App\Http\Controllers;

use App\Models\InnovatorOfTheMonth;
use App\Models\Innovation;
use App\Models\Innovator;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_innovations' => Innovation::count(),
            'total_innovators'  => Innovator::count(),
            'total_visited'     => 0,
        ];

        $chartLabels = ['Jan 1','Jan 8','Jan 16','Jan 24','Feb 1','Feb 8','Feb 16','Feb 24'];
        $chartData   = [300, 450, 600, 680, 620, 900, 940, 1000];

        $now = Carbon::now();

        $innovatorOfMonth = InnovatorOfTheMonth::with('innovation')
            ->where('month', $now->month)
            ->where('year', $now->year)
            ->first();

        return view('admin.dashboard', compact('stats','chartLabels','chartData','innovatorOfMonth'));
    }
}
