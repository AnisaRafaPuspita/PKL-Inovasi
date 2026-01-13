<?php

namespace App\Http\Controllers;

use App\Models\InnovatorOfTheMonth;
use App\Models\Innovation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminInnovatorOfTheMonthController extends Controller
{
    public function edit()
    {
        $now = Carbon::now();

        $iotm = InnovatorOfTheMonth::where('month', $now->month)
            ->where('year', $now->year)
            ->first();

        $innovations = Innovation::orderBy('title')->get(['id','title']);

        return view('admin.innovator_of_the_month.edit', compact('iotm','innovations','now'));
    }

    public function update(Request $request)
    {
        $now = \Carbon\Carbon::now();

        $data = $request->validate([
            'innovator_name' => 'required|string|max:255',
            'faculty'        => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'innovation_id'  => 'nullable|exists:innovations,id',
            'photo'          => 'nullable|image|max:2048',
        ]);

        $iotm = \App\Models\InnovatorOfTheMonth::firstOrNew([
            'month' => $now->month,
            'year'  => $now->year,
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')
                ->store('innovator_of_month', 'public');
        }

        $iotm->fill($data);
        $iotm->save();

        return back()->with('success', 'Berhasil disimpan');
    }

}
