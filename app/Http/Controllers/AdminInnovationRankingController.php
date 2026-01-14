<?php

namespace App\Http\Controllers;

use App\Models\Innovation;
use App\Models\InnovationRanking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminInnovationRankingController extends Controller
{
    public function index()
    {
        $rankings = InnovationRanking::with('innovation')->orderBy('rank')->get();
        return view('admin.rankings.index', compact('rankings'));
    }

    public function create()
    {
        $innovations = Innovation::orderBy('title')->get();

        return view('admin.rankings.form', [
            'mode' => 'create',
            'ranking' => new InnovationRanking(),
            'innovations' => $innovations,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rank' => 'required|integer|min:1|max:100',
            'innovation_id' => 'required|exists:innovations,id',
            'achievement' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rankings', 'public');
        }

        InnovationRanking::create($data);

        return redirect()
            ->route('admin.innovation_rankings.index')
            ->with('success', 'Ranking berhasil ditambahkan.');
    }

    public function edit(InnovationRanking $ranking)
    {
        $innovations = Innovation::orderBy('title')->get();

        return view('admin.rankings.form', [
            'mode' => 'edit',
            'ranking' => $ranking,
            'innovations' => $innovations,
        ]);
    }

    public function update(Request $request, InnovationRanking $ranking)
    {
        $data = $request->validate([
            'rank' => 'required|integer|min:1|max:100',
            'innovation_id' => 'required|exists:innovations,id',
            'achievement' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if (!empty($ranking->image)) {
                Storage::disk('public')->delete($ranking->image);
            }
            $data['image'] = $request->file('image')->store('rankings', 'public');
        }

        $ranking->update($data);

        return redirect()
            ->route('admin.innovation_rankings.index')
            ->with('success', 'Ranking berhasil diperbarui.');
    }

    public function destroy(InnovationRanking $ranking)
    {
        if (!empty($ranking->image)) {
            Storage::disk('public')->delete($ranking->image);
        }

        $ranking->delete();

        return redirect()
            ->route('admin.innovation_rankings.index')
            ->with('success', 'Ranking berhasil dihapus.');
    }
}
