<?php

namespace App\Http\Controllers;

use App\Models\InnovatorOfTheMonth;
use App\Models\Innovator;
use App\Models\Innovation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminInnovatorOfTheMonthController extends Controller
{
    public function edit()
    {
        $innovators = Innovator::with('faculty')
            ->orderBy('name')
            ->get();

        $innovations = Innovation::where('status', 'published')
            ->orderBy('title')
            ->get();

        $iotm = InnovatorOfTheMonth::first();

        return view('admin.innovator_of_the_month.edit', compact('innovators', 'innovations', 'iotm'));

    }


    public function update(Request $request)
    {
        $validated = $request->validate([
            'innovator_id' => ['required', 'exists:innovators,id'],
            'innovation_id'=> ['nullable', 'exists:innovations,id'],
            'description'  => ['nullable', 'string'],
            'month'        => ['required', 'integer', 'min:1', 'max:12'],
            'year'         => ['required', 'integer'],
            'photo'        => ['nullable', 'image'],
        ]);

        $iotm = InnovatorOfTheMonth::firstOrNew([
            'month' => $validated['month'],
            'year'  => $validated['year'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request
                ->file('photo')
                ->store('innovator_of_month', 'public');
        }

        $iotm->fill($validated);
        $iotm->save();

        return back()->with('success', 'Innovator of The Month berhasil disimpan');
    }



}
