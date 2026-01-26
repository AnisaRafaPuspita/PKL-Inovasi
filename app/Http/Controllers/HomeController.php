<?php

namespace App\Http\Controllers;

use App\Models\Innovation;
use App\Models\InnovatorOfTheMonth;
use App\Models\InnovationRanking;
use App\Models\Faculty;
use App\Models\Innovator;





class HomeController extends Controller
{
    public function index()
    {
        // impact innovations (published & is_impact)
        $impactInnovations = Innovation::query()
            ->where('status', 'published')
            ->whereHas('permission', fn ($q) => $q->where('status', 'accepted'))
            ->where('is_impact', true)
            ->latest()
            ->get();


        $innovations = Innovation::query()
            ->where('status', 'published')
            ->whereHas('permission', function ($q) {
                $q->where('status', 'accepted');
            })
            ->where('is_impact', false)
            ->latest()
            ->get();


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

        $rankings = InnovationRanking::orderBy('rank')->get();

        $faculties = Faculty::orderBy('name')->get();

        $innovators = Innovator::orderBy('name')->get(); 

        return view('pages.home', compact('impactInnovations','innovations', 'mostVisited', 'innovatorMonth', 'rankings', 'faculties', 'innovators'));
    }

    public function about()
    {
        return view('pages.about');
    }
}
