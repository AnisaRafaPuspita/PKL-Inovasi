<?php

namespace App\Http\Controllers;

use App\Models\InnovatorOfTheMonth;
use App\Models\Innovation;

class InnovatorOfMonthController extends Controller
{
    public function show()
    {
        $iom = InnovatorOfTheMonth::query()
            ->with(['innovator.faculty'])
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->firstOrFail();

        // featured innovation: ambil inovasi terbaru dari inovator tsb (published)
        $featuredInnovation = Innovation::query()
            ->where('status', 'published')
            ->whereHas('innovators', fn($q) => $q->where('innovators.id', $iom->innovator_id))
            ->latest()
            ->first();

        return view('pages.innovator-month.show', compact('iom', 'featuredInnovation'));


    }
}
