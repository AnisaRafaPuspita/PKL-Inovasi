<?php

namespace App\Http\Controllers;

use App\Models\Innovator;
use App\Models\InnovatorOfTheMonth;

class InnovatorController extends Controller
{
    public function index()
    {
        $innovators = Innovator::with('faculty')->latest()->get();
        return view('pages.innovator-month.index', compact('innovators'));
    }

    public function show(Innovator $innovator)
    {
        $iom = InnovatorOfTheMonth::query()
            ->where('innovator_id', $innovator->id)
            ->latest()
            ->first();

        $featuredInnovation = $iom?->innovation
            ?? $innovator->innovations()
                ->where('status', 'published')
                ->latest()
                ->first();

        return view('pages.innovator-month.show', [
            'iom' => $iom,
            'featuredInnovation' => $featuredInnovation,
        ]);
    }


}
