<?php

namespace App\Http\Controllers;

use App\Models\Innovation;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index()
    {
        $year = now()->year;

        $innovationsPerMonth = Innovation::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $innovationsPerMonth[$i] ?? 0;
        }

        $facultyData = DB::table('innovations')
            ->join('innovation_innovator', 'innovations.id', '=', 'innovation_innovator.innovation_id')
            ->join('innovators', 'innovation_innovator.innovator_id', '=', 'innovators.id')
            ->join('faculties', 'innovators.faculty_id', '=', 'faculties.id')
            ->select('faculties.name', DB::raw('COUNT(DISTINCT innovations.id) as total'))
            ->groupBy('faculties.name')
            ->pluck('total', 'faculties.name');

        $facultyLabels = $facultyData->keys()->toArray();
        $facultyTotals = $facultyData->values()->toArray();

        foreach ($facultyData as $facultyId => $total) {
            $faculty = \App\Models\Faculty::find($facultyId);
            $facultyLabels[] = $faculty?->name ?? 'Unknown';
            $facultyTotals[] = $total;
        }

        $kiData = Innovation::where('status', 'published')
            ->whereHas('permission', function ($q) {
                $q->where('status', 'accepted');
            })
            ->whereNotNull('ki_type')
            ->select('ki_type', DB::raw('COUNT(*) as total'))
            ->groupBy('ki_type')
            ->pluck('total', 'ki_type');

        $kiLabels = $kiData->keys();
        $kiTotals = $kiData->values();

        

        return view('pages.statistik.index', [
            'monthlyData' => $monthlyData,
            'facultyLabels' => $facultyLabels,
            'facultyTotals' => $facultyTotals,
            'kiLabels' => $kiLabels,
            'kiTotals' => $kiTotals,
            'year' => $year
        ]);
    }
}