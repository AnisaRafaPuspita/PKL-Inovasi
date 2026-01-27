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

}
