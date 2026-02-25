<?php

namespace App\Http\Controllers;

use App\Models\Innovation;
use App\Models\InnovatorOfTheMonth;
use App\Models\InnovationRanking;
use App\Models\Faculty;
use App\Models\Innovator;
use App\Models\HomePamflet;





class HomeController extends Controller
{
    public function index()
    {
        $impactInnovations = Innovation::query()
            ->where('status', 'published')
            ->whereHas('permission', fn ($q) => $q->where('status', 'accepted'))
            ->latest()
            ->get();



        // most visited
        $mostVisited = Innovation::query()
            ->where('status', 'published')
            ->orderByDesc('views_count')
            ->take(3)
            ->get();

        $featuredInnovators = InnovatorOfTheMonth::query()
            ->with(['innovator.faculty'])
            ->latest()
            ->get();


        $rankings = InnovationRanking::query()
            ->select('*')
            ->orderBy('rank')
            ->get();


        $faculties = Faculty::orderBy('name')->get();

        $innovators = Innovator::orderBy('name')->get(); 

        $homePamflet = HomePamflet::first() ?? HomePamflet::create();


        return view('pages.home', compact('impactInnovations', 'homePamflet', 'mostVisited', 'featuredInnovators', 'rankings', 'faculties', 'innovators'));
    }

    public function about()
    {
        return view('pages.about');
    }
}
