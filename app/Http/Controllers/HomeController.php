<?php

namespace App\Http\Controllers;

use App\Models\Innovation;
use App\Models\InnovatorOfTheMonth;
use App\Models\InnovationRanking;
use App\Models\Faculty;





class HomeController extends Controller
{
    public function index()
    {
        // impact innovations (published & is_impact)
        $impactInnovations = Innovation::query()
            ->where('status', 'published')
            ->where('is_impact', true)
            ->latest()
            ->get();

        $innovations = Innovation::where('is_impact', false)->latest()->get();

        // most visited
        $mostVisited = Innovation::query()
            ->where('status', 'published')
            ->orderByDesc('views_count')
            ->take(3)
            ->get();

        // innovator of the month (latest record)
        $innovatorMonth = InnovatorOfTheMonth::query()
            ->with(['innovator.faculty'])
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->first();

        $rankings = InnovationRanking::with('innovation')
            ->where('status', 'active')
            ->orderBy('rank')
            ->get();

        $faculties = Faculty::orderBy('name')->get();

        return view('pages.home', compact('impactInnovations','innovations', 'mostVisited', 'innovatorMonth', 'rankings', 'faculties'));
    }

    public function about()
    {
        return view('pages.about');
    }
}
