<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InnovationRanking;


class RankingController extends Controller
{
    public function show(InnovationRanking $ranking)
    {
        $ranking->load('photos');

        return view('pages.rankings.show', compact('ranking'));
    }

    public function index()
    {
        $rankings = InnovationRanking::latest()->get();
        return view('rankings.index', compact('rankings'));
    }

}
